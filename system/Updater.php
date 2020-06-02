<?php


namespace System;


use App\Models\Database\AppDatabase;
use Monolog\Handler\StreamHandler;
use System\App\AppException;
use System\Exception\FileException;
use VisualAppeal\AutoUpdate;

class Updater
{
    /**
     * @var AutoUpdate
     */
    protected $updater;


    /**
     * @var null|self
     */
    //protected int $updateCheckInterval = (1);
    protected int $updateCheckInterval = (60 * 60 * 12);
    protected $logger;
    protected int $timeout = 120;
    protected bool $checkSSL = true;
    protected string $url;
    protected $currentVersion;
    protected string $tempFilePath;
    protected string $destFilePath;
    protected string $logFile = ROOTPATH . DIRECTORY_SEPARATOR . "writable" . DIRECTORY_SEPARATOR . "update.log";
    private $latestVersion;

    /**
     * Update constructor.
     * Defaults:
     * updateCheckInterval: 12hrs
     * checkSSL: true
     * Timeout (update check): 120s
     *
     * @param AutoUpdate $updater
     */
    public function __construct(string $url, string $tempFilePath, string $destFilePath, $currentVersion)
    {
        $this->logger = SystemLogger::get();
        $this->logger->info('Updater loaded');
        $this->logger->info(time());

        $this->tempFilePath = $tempFilePath;
        $this->destFilePath = $destFilePath;
        $this->url = $url;
        $this->currentVersion = $currentVersion;


    }

    public function update($simulation = true, $deleteDownload = false)
    {
        if ($this->updater === null) {
            $this->connectToUpdateServer();
        }
        if ($this->latestVersion === null) {
            $this->getLatestVersion();
        }
        $result = $this->updater->update(true, $deleteDownload);
        if ($result === true && !$simulation) {
            return $this->updater->update(false, $deleteDownload);
        } else {
            $this->logger->warning('Update simulation failed: ' . $result . '!<br>');
            if ($result === AutoUpdate::ERROR_DOWNLOAD_UPDATE) {
                $result = 'Could not download update.';
            } elseif ($result === AutoUpdate::ERROR_SIMULATE) {
                var_dump($this->updater->getSimulationResults());
                $this->logger->error($this->updater->getSimulationResults());
                $result = "Error in simulated install.";
            } elseif ($result === AutoUpdate::ERROR_DELETE_TEMP_UPDATE) {
                $result = "Could not delete zip update file.";
            } elseif ($result === AutoUpdate::ERROR_INSTALL) {
                $this->logger->error('Error while installing the update.');
                $result = "Error while installing the update.";

            } elseif ($result === AutoUpdate::ERROR_INSTALL_DIR) {
                $result = "Install directory does not exist or is not writable";
            } elseif ($result === AutoUpdate::ERROR_INVALID_ZIP) {
                $result = "Zip file could not be opened.";
            } elseif ($result === AutoUpdate::ERROR_VERSION_CHECK) {
                $result = "Could not check for last version.";
            }
        }

    }

    /**
     * @throws \Exception
     */
    public function connectToUpdateServer(): void
    {
        $this->logger->info("Connecting to update server");
        $this->updater = new AutoUpdate($this->tempFilePath, $this->destFilePath, $this->timeout);
        $this->updater->setCurrentVersion(Core::getVersion())
            ->setUpdateUrl($this->url)
            ->addLogHandler(new StreamHandler($this->logFile))
            ->setSslVerifyHost($this->checkSSL);


        $this->updater->onEachUpdateFinish(function () {
            $this->logger->info('Finished Update Component');
        });


        $this->updater->setOnAllUpdateFinishCallbacks(function () {
            $this->logger->info('Finished Update Component');
        });


        $this->logger->debug("Connected to " . $this->url);


    }

    /**
     * @return string
     */
    public function getLatestVersion()
    {
        if ($this->updater === null) {
            $this->connectToUpdateServer();
        }
        if ($this->latestVersion === null || $this->latestVersion === '') {
            $this->logger->info("Checking for a new version");
            try {

                if ($this->updater->checkUpdate()) {
                    AppDatabase::setLastUpdateCheck(time());
                    $this->latestVersion = $this->updater->getLatestVersion();
                    AppDatabase::setLatestAvailableVersion($this->latestVersion);
                    $this->updater->getVersionsToUpdate();
                    return $this->latestVersion;
                } else {
                    // No new update
                    $this->logger->info('Your application is up to date');
                    return false;
                }
            } catch
            (\Exception $ex) {
                $this->logger->error($ex);

            }
        }
        return $this->latestVersion;
    }

    /**
     * @return bool|null
     * @throws AppException
     * @throws \Exception
     */
    public function isUpdateAvailable()
    {
        $this->logger->info("isUpdateAvailable called");
        $this->connectToUpdateServer();


        if (time() - AppDatabase::getLastUpdateCheck() > $this->updateCheckInterval) {
            $this->latestVersion = $this->getLatestVersion();
        } else {
            $this->latestVersion = AppDatabase::getLatestAvailableVersion();
        }
        if ($this->latestVersion > $this->currentVersion) {
            return true;
        }
        return false;

    }

    public function checkForUpdate()
    {

    }


    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return Updater
     */
    public function setUrl(string $url): Updater
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return int
     */
    public function getCurrentVersion(): int
    {

        return $this->currentVersion;
    }

    /**
     * @param int $currentVersion
     *
     * @return Updater
     */
    public function setCurrentVersion(int $currentVersion): Updater
    {
        $this->currentVersion = $currentVersion;
        return $this;
    }

    /**
     * @return string
     */
    public function getTempFilePath(): string
    {
        return $this->tempFilePath;
    }

    /**
     * @param string $tempFilePath
     *
     * @return Updater
     */
    public function setTempFilePath(string $tempFilePath): Updater
    {
        $this->tempFilePath = $tempFilePath;
        return $this;
    }

    /**
     * @return string
     */
    public function getDestFilePath(): string
    {
        return $this->destFilePath;
    }

    /**
     * @param string $destFilePath
     *
     * @return Updater
     */
    public function setDestFilePath(string $destFilePath): Updater
    {
        $this->destFilePath = $destFilePath;
        return $this;
    }

    /**
     * @return string
     */
    public function getLogFile(): string
    {
        return $this->logFile;
    }

    /**
     * @param string $logFile
     *
     * @return Updater
     */
    public function setLogFile(string $logFile): Updater
    {
        $this->logFile = $logFile;
        return $this;
    }

    public function getLastCheckedState()
    {
        return $this->lastCheckedState;
    }

    /**
     * @return int
     */
    public function getUpdateCheckInterval(): int
    {
        return $this->updateCheckInterval;
    }

    /**
     * @param int $updateCheckInterval
     *
     * @return Updater
     */
    public function setUpdateCheckInterval(int $updateCheckInterval): Updater
    {
        $this->updateCheckInterval = $updateCheckInterval;
        return $this;
    }

    /**
     * @return DatabaseLogger|SystemLogger|null
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param DatabaseLogger|SystemLogger|null $logger
     *
     * @return Updater
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * @param int $timeout
     *
     * @return Updater
     */
    public
    function setTimeout(int $timeout): Updater
    {
        $this->timeout = $timeout;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCheckSSL(): bool
    {
        return $this->checkSSL;
    }

    /**
     * @param bool $checkSSL
     *
     * @return Updater
     */
    public function setCheckSSL(bool $checkSSL): Updater
    {
        $this->checkSSL = $checkSSL;
        return $this;
    }


}
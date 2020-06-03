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
    protected int $updateCheckInterval = (1);
    // protected int $updateCheckInterval = (60 * 60 * 12);
    protected $logger;
    protected int $timeout = 120;
    protected bool $checkSSL = true;
    protected string $jsonURL;
    protected $currentVersion;
    protected string $tempFilePath;
    protected string $destFilePath;
    //protected string $logFile = ROOTPATH . DIRECTORY_SEPARATOR . "writable" . DIRECTORY_SEPARATOR . "update.log";
    protected $latestVersion;
    protected $latestVersionURL;
    /**
     * @var mixed
     */
    protected $updatesAvailable;
    private string $downloadedFile;

    /**
     * Update constructor.
     * Defaults:
     * updateCheckInterval: 12hrs
     * checkSSL: true
     * Timeout (update check): 120s
     *
     * @param string $jsonURL
     * @param string $tempFilePath
     * @param string $destFilePath
     * @param $currentVersion
     */
    public function __construct(string $jsonURL, string $tempFilePath, string $destFilePath, $currentVersion)
    {
        $this->logger = SystemLogger::get();
        $this->logger->info('Updater loaded');
        $this->logger->info(time());

        $this->tempFilePath = $tempFilePath;
        $this->destFilePath = $destFilePath;
        $this->jsonURL = $jsonURL;
        $this->currentVersion = $currentVersion;
        $this->isUpdateAvailable();

    }

    /**
     * @return bool|null
     * @throws AppException
     * @throws \Exception
     */
    public
    function isUpdateAvailable()
    {
        $this->logger->info("isUpdateAvailable called");
        //$this->connectToUpdateServer();


        if (time() - AppDatabase::getLastUpdateCheck() > $this->updateCheckInterval) {
            $this->latestVersion = $this->getLatestVersionsFromURL();
        } else {
            $this->latestVersion = json_decode(AppDatabase::getLatestAvailableVersion())[0];
        }
        if ($this->latestVersion > $this->currentVersion) {
            return true;
        }
        return false;

    }

    /**
     * Connectes to the JSON URL set in the updater and determines the latest version
     *
     * @return string
     */
    public function getLatestVersionsFromURL()
    {

        /**
         * Check to make sure an update json url has been set
         */
        if ($this->jsonURL !== null && $this->jsonURL !== '') {
            /**
             * A Random number to send so we don't deal with caching
             */
            $sendGet = rand(1000000000, 9999999999);

            $this->logger->info("Checking for a new version at " . $this->jsonURL);
            /**
             * Get the JSON contents into a PHP array
             */
            $this->updatesAvailable = (array)(json_decode(file_get_contents($this->jsonURL . "?rand=" . $sendGet)));
            $this->logger->debug($this->updatesAvailable);
            /**
             * Sort the array from highest to loweest
             */
            $this->logger->debug(ksort($this->updatesAvailable));
            $this->updatesAvailable = array_reverse($this->updatesAvailable);
            $this->logger->debug($this->updatesAvailable);
            /**
             * Pull the latest version from the first key name
             */
            $this->latestVersion = array_key_first($this->updatesAvailable);
            $this->latestVersionURL = $this->updatesAvailable[$this->latestVersion];
            $this->logger->debug($this->latestVersion);
            $this->logger->debug($this->latestVersionURL);
            return $this->latestVersion;
        } else {
            /**
             * There was no JSON url set
             */
            throw new AppException('Update URL is not set');
        }
    }

    public
    function update($simulation = true, $deleteDownload = true)
    {
        if ($this->isUpdateAvailable()) {
            if ($this->run(true)) {

                if (!$simulation && $this->backupApp()) {
                    if ($this->run()) {
                        $this->logger->info("Update completed successfully");
                        $this->deleteRollback();
                    } else {
                        $this->rollbackUpdate();
                    }
                }
            } else {
                throw new AppException("Update Simulation Failed");
            }
            if ($deleteDownload) {
                $this->deleteDownloadedUpdate();
            }
            return true;
        } else {
            return "There is no update available";
        }


        if ($this->updater === null) {
            $this->connectToUpdateServer();
        }
        if ($this->latestVersion === null) {
            $this->getLatestVersionsFromURL();
        }
        $result = $this->updater->update(true, $deleteDownload);
        if ($result === true && !$simulation) {
            $result = $this->updater->update(false, $deleteDownload);
            var_dump($result);
            if ($result === true) {
                $this->logger->debug("Update completed successfully");
                return "Update completed successfully";
            } else {
                $this->logger->error("Update did not completed successfully");

                return false;
            }
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
            return $result;
        }

    }

    private function run($simulation = false)
    {
        if ($simulation) {
            $this->logger->info("Running update in simulation mode");
        } else {
            $this->logger->info("Running update");
        }
        $extractPath = $this->tempFilePath . DIRECTORY_SEPARATOR . "extract";
        $this->logger->debug($this->tempFilePath);
        $this->downloadedFile = $this->tempFilePath . DIRECTORY_SEPARATOR . "update.zip";
        File::overwriteFile($this->downloadedFile, fopen($this->latestVersionURL, 'r'));
        $zip = new \ZipArchive();
        if ($zip->open($this->downloadedFile)) {
            $zip->extractTo($extractPath);
            $dir = new\RecursiveDirectoryIterator ($extractPath);
            /**
             * @var \SplFileInfo $file
             */
            foreach (new \RecursiveIteratorIterator($dir) as $file) {

                //var_dump($file);
                $pathname = str_replace([$extractPath . DIRECTORY_SEPARATOR], "", $file->getPathname());
                $filename = basename($pathname);

                if ($filename !== "." && $filename !== "..") {
                    //var_dump($filename);
                    $pathname = str_replace("\\", "/", $pathname);
                    $liveFile = ROOTPATH . DIRECTORY_SEPARATOR . $pathname;
                    //var_dump($liveFile);
                    if (File::exists($liveFile)) {
                        $this->logger->debug("Will overwrite $liveFile");

                    } else {
                        $this->logger->debug("Will create $liveFile");

                    }
                    if (!$simulation) {
                        copy($file->getPathname(), $liveFile);
                        $this->logger->debug("Processed file: $pathname");
                    }
                    //var_dump($pathname);
                }

            }
        }
        File::removeDirectory($extractPath);
        return true;
    }

    private function backupApp()
    {
        /**
         * @todo Make backup app
         */
        return true;
    }

    private function deleteRollback()
    {
        return true;
    }

    private function rollbackUpdate()
    {
        /**
         * @todo Make rollback update from backup before update
         */
        $this->logger->error("Trying to roll back update");
        return true;
    }

    private function deleteDownloadedUpdate()
    {
        File::deleteFile($this->downloadedFile);
    }

    public function getLatestVersion()
    {
        if ($this->latestVersion === null) {
            $this->getLatestVersionsFromURL();
        }
        return $this->latestVersion;
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
     * @param bool $checkSSL
     *
     * @return Updater
     */
    public
    function setCheckSSL(bool $checkSSL): Updater
    {
        $this->checkSSL = $checkSSL;
        return $this;
    }

    /**
     *
     * Compares two versions (x.x.x) and returns true if $a
     * is higher
     *
     * @param $a
     * @param $b
     *
     * @return bool
     */
    protected
    function versionIsHigher($a, $b)
    {
        $a = explode(".", $a);
        $b = explode(".", $b);
        if ($a[0] > $b[0]) {
            return true;
        }
        if ($a[1] > $b[1]) {
            return true;
        }
        if ($a[2] > $b[2]) {
            return true;
        }
        return false;
    }


}
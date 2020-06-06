<?php


namespace System\Update;


use System\CoreException;
use System\File;
use System\SystemLogger;

class Updater
{

    /**
     * @var null|self
     */
    protected int $updateCheckInterval = (1);
    // protected int $updateCheckInterval = (60 * 60 * 12);
    protected $logger;
    protected int $timeout = 120;
    protected bool $checkSSL = true;
    protected string $jsonURL;
    /**
     * @var string
     */
    protected $currentVersion;
    protected string $tempFilePath;
    protected string $destFilePath;
    protected string $downloadedFile;
    protected array $excludeList;
    /**
     * @var AvailableUpdate
     */
    protected AvailableUpdate $latestUpdate;
    protected array $availableUpdates;
    /**
     * @var string The directory where the update is extracted to
     */
    protected string $extractPath;
    protected string $backupPath;
    protected int $updateFileCount;
    protected $progressFilePath;

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
        $this->extractPath = $this->tempFilePath . DIRECTORY_SEPARATOR . "extract";
        $this->backupPath = $this->tempFilePath . DIRECTORY_SEPARATOR . "backup";
        $this->progressFilePath = $this->tempFilePath . DIRECTORY_SEPARATOR . "update_progress.json";
        $this->jsonURL = $jsonURL;
        $this->currentVersion = $currentVersion;
        try {
            $this->isUpdateAvailable();
        } catch (CoreException $e) {
            $this->logger->warning($e);
        }
        File::deleteFile($this->progressFilePath);

    }

    /**
     * @return bool|null
     * @throws \Exception
     */
    public function isUpdateAvailable(): ?bool
    {
        $this->logger->info("isUpdateAvailable called");
        if ($this->latestUpdate->version > $this->currentVersion) {
            return true;
        }
        return false;

    }

    /**
     * Runs the update as configured.
     * The update always runs a simulation prior to attempting
     * actually overwriting files.
     *
     * Use update(false); to actually apply the update.
     *
     * Returns true on successful simulation/update
     * Returns false on failure
     *
     * @param bool $simulation     If true, will not overwrite files. Defaults to true.
     * @param bool $deleteDownload If true, will remove downloaded update file after update. Defaults to true.
     *
     * @return bool|string
     */
    public function update($simulation = true, $deleteDownload = false)
    {
        $this->logger->info("Running an update. Simulation Mode:$simulation Purge Downloaded Update:$deleteDownload");
        if ($this->isUpdateAvailable()) {

            if ($this->downloadUpdateFiles()) {
                //$this->updateFileCount = File::fileCount($this->extractPath);
                $this->logger->info("Total update files: $this->updateFileCount");

                if ($this->backupApp()) {
                    if ($this->run($simulation) && $this->runPostInstall($simulation)) {
                        $this->logger->info("Update completed successfully");
                        $this->deleteRollback();
                    } else {
                        if (!$simulation) {
                            $this->rollbackUpdate();
                        }
                    }

                }
                $this->logger->info("Running post-installation script");
                $this->deleteExtractedUpdateFiles();

            }
            if ($deleteDownload) {
                $this->deleteDownloadedUpdate();
            }
            if ($simulation) {
                $successMessage = 'Simulation completed without error!';
            } else {
                $successMessage = "Application was successfully updated to version {$this->latestUpdate->version}<br> Please refresh your browser at this point.";

            }
            return $successMessage;
        } else {
            $this->logger->info("There is no update available");
        }
        return false;


    }

    /**
     * @return bool|null
     */
    private function downloadUpdateFiles(): ?bool
    {

        $this->logger->debug($this->tempFilePath);
        /**
         * Set up  the downloaded update filename
         */
        $this->downloadedFile = $this->tempFilePath . DIRECTORY_SEPARATOR . "update.zip";
        /**
         * Download the update
         */
        File::createDirectory($this->tempFilePath);
        File::overwriteFile($this->downloadedFile, fopen($this->latestUpdate->downloadURL, 'r'));

        $zip = new \ZipArchive();
        /**
         * Check to make sure we can open the zip
         */
        if ($zip->open($this->downloadedFile)) {
            /**
             * Get the count of files in the zip
             */

            $this->updateFileCount = $this->latestUpdate->fileCount;
            /**
             * Extract the update to a temp extract directory
             */
            $this->logger->info("Extracting downloaded update");
            if ($zip->extractTo($this->extractPath)) {
                /**
                 * That's it the update has been downloaded
                 */
                return true;
            } else {
                $this->logger->error('Couldn\'t extract the zip');
            }
        } else {
            $this->logger->error("Couldn't open the zip file");
            return false;
        }

        return false;
    }

    /**
     * @return bool
     */
    protected function backupApp(): bool
    {
        return true;
        //$zip = new \ZipArchive($this->backupPath);
        $dir = new\RecursiveDirectoryIterator (ROOTPATH);
        /**
         * @var \SplFileInfo $file
         */
        foreach (new \RecursiveIteratorIterator($dir) as $file) {
            if (strpos($file->getPathname(), $this->tempFrilePath) === false) {
                //$zip->addFile();
                $this->logger->debug("Backing up file " . $file->getFilename());
                $destinationFilepath = ROOTPATH . str_replace(ROOTPATH, '', $file->getPathname());
                copy($file->getPathname(), $destinationFilepath);
            }
        }

        return true;
    }

    /**
     * The internal actual running of the update process outlined as:
     * Download file
     * Extract to tmp dir
     * Perform simulation
     * If not in simulation, then perform overwrites
     * Delete the temp directory
     *
     * @param bool $simulation
     *
     * @return bool
     */
    protected function run($simulation = false): bool
    {

        if ($simulation) {
            $this->logger->info("Running update in simulation mode");
            $progressTitle = 'Running Simulation';
            $progressMessage = 'Checking files...';
        } else {
            $this->logger->info("Running update");
            $progressTitle = "Applying Update";
            $progressMessage = 'Copying files...';

        }
        $dir = new\RecursiveDirectoryIterator ($this->extractPath);
        /**
         * @var \SplFileInfo $file
         */
        /**
         * Initialize some counters for progress tracking
         */
        $fileIndex = 1;
        $statusRefreshCounter = 0;
        /**
         * Set the starting progress
         */
        $this->refreshUpdateStatus($progressTitle, $progressMessage, ($fileIndex / $this->updateFileCount) * 100);
        /**
         * Loop recursively through the extract directory
         */
        foreach (new \RecursiveIteratorIterator($dir) as $file) {
            //var_dump($file);
            /**
             * Prepare relative file pathing by removing the extract path
             * from each file path
             */
            $pathname = str_replace([$this->extractPath . DIRECTORY_SEPARATOR], "", $file->getPathname());
            $filename = basename($pathname);
            /**
             * Skip those pesky dots
             */
            if ($filename !== "." && $filename !== "..") {
                /**
                 * Lets update the progress if we are at a 20% mark
                 */
                if ($statusRefreshCounter > ($this->updateFileCount / 5)) {
                    $this->refreshUpdateStatus($progressTitle, $progressMessage, ($fileIndex / $this->updateFileCount) * 100);
                    $statusRefreshCounter = 0;
                }
                /**
                 * Update them counters
                 */
                $statusRefreshCounter++;
                $fileIndex++;

                /**
                 * Prepare the destination filename, it could be a
                 * live file if it exists
                 */
                $pathname = str_replace("\\", "/", $pathname);
                $liveFile = ROOTPATH . DIRECTORY_SEPARATOR . $pathname;

                //var_dump($liveFile);
                /**
                 * Lets check to see if the destionation file would exist and
                 * if so we should make sure that is a file we should be overwriting.
                 *
                 * AKA Not on exclude list or identical to update file.
                 */
                if (File::exists($liveFile)) {
                    if ($this->shouldOverwrite($file->getPathname(), $liveFile)) {
                        $this->logger->debug("$liveFile selected for overwrite");
                        if (!$simulation) {
                            copy($file->getPathname(), $liveFile);
                            $this->logger->debug("file: $liveFile");
                        }
                    } else {
                        //$this->logger->debug("$liveFile skipped");

                    }

                } else {
                    /**
                     * Otherwise the update file does not yet exist on this live install
                     * so we can just copy the update file over
                     */
                    //$this->logger->debug("Will create $liveFile");
                    if (!$simulation) {
                        copy($file->getPathname(), $liveFile);
                        $this->logger->debug("Created file: $pathname");
                    }
                }

                //var_dump($pathname);
            }

        }
        $this->refreshUpdateStatus($progressTitle, 'Simulation Complete', 100);

        $this->logger->debug("Total Files Processed: " . $fileIndex);
        return true;
    }

    /**
     * Writes an update_progress.json file in
     * the configured tempFilePath to track the
     * progress of the update.
     *
     * @param string $title
     * @param string $message
     * @param int|null $progress
     */
    protected function refreshUpdateStatus(string $title, string $message, int $progress = null): void
    {
        $json = ["title" => $title, "message" => $message];
        if (!is_null($progress)) {
            $json["progress"] = $progress;
        }
        $this->logger->debug($json);
        File::overwriteFile($this->progressFilePath, json_encode($json));
    }

    protected function shouldOverwrite($sourceFile, $destFile)
    {
        /**
         * @todo Add check for excluded files and directories
         */
        if (File::getMD5($sourceFile) !== File::getMD5($destFile) && strpos($sourceFile, ".db") === false) {
            return true;

        }
        return false;
    }

    /**
     * @return bool
     */
    protected function runPostInstall($simulation = true): bool
    {
        $this->logger->info("Running post install script");

        if ($this->latestUpdate->postInstallScriptPath !== null && $this->latestUpdate->postInstallScriptPath !== '') {
            $this->logger - debug($this->latestUpdate->postInstallScriptPath);
            if (File::exists(ROOTPATH . DIRECTORY_SEPARATOR . $this->latestUpdate->postInstallScriptPath)) {
                $this->logger->debug("The post install script was found.");
                return true;
            }
        }
        return false;
    }

    /**
     * @return bool
     */
    protected function deleteRollback(): bool
    {
        $this->logger->info("Deleting update rollback");

        return true;
    }

    /**
     * @return bool
     */
    protected function rollbackUpdate(): bool
    {
        /**
         * @todo Make rollback update from backup before update
         */
        //$this->logger->error("Trying to roll back update");
        return true;
    }

    /**
     * @return bool
     */
    protected function deleteExtractedUpdateFiles(): bool
    {
        $this->logger->info("Deleting extracted update");

        if (File::removeDirectory($this->extractPath)) {
            return true;
        }
        $this->logger->warning("Unable to delete extracted update at $this->extractPath");
        return false;
    }

    /**
     * @return bool
     */
    protected function deleteDownloadedUpdate(): bool
    {
        $this->logger->info("Deleting downloaded update");
        if (File::deleteFile($this->downloadedFile)) {


            return true;
        }
        $this->logger->warning("Unable to delete downloaded update at $this->downloadedFile");

        return false;
    }

    /**
     * @return mixed
     */
    public function getLatestVersion()
    {
        if ($this->latestUpdate === null) {
            $this->getLatestUpdateFromURL();
        }
        return $this->latestUpdate->version;
    }

    /**
     * Connectes to the JSON URL set in the updater and determines the latest version
     *
     * @return string
     */
    public function getLatestUpdateFromURL()
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
            $updatesAvailable = (array)(json_decode(file_get_contents($this->jsonURL . "?rand=" . $sendGet)));
            $this->availableUpdates = $this->translateJSONArray($updatesAvailable);
            $this->logger->debug($this->availableUpdates);
            /**
             * Sort the array from highest to loweest
             */
            $this->logger->debug(usort($this->availableUpdates, function ($a, $b) {
                /* @var AvailableUpdate $a */
                /* @var AvailableUpdate $b */
                if ($a->version < $b->version) {
                    return true;
                }
                return false;
            }));

            $this->logger->debug($this->availableUpdates);
            /**
             * Pull the latest version from the first key name
             */
            $this->latestUpdate = $this->availableUpdates[0];

            $this->logger->debug($this->latestUpdate);
            return $this->latestUpdate;
        } else {
            /**
             * There was no JSON url set
             */
            throw new CoreException('Update URL is not set');
        }
    }

    /**
     * Given a plain array that represents a list of AvailableUpdate's
     * this method will return an array list of actual AvailableUpdate
     * objects
     *
     * @param array $updatesAvailable
     */
    protected function translateJSONArray(array $updatesAvailable): array
    {
        $this->logger->debug($updatesAvailable);
        $updates = [];
        foreach ($updatesAvailable as $update) {
            $update = (array)$update;
            $possibleUpdate = new AvailableUpdate();

            $properties = ['version', 'downloadURL'];

            foreach ($update as $property => $value) {
                if (property_exists(get_class($possibleUpdate), $property)) {
                    $possibleUpdate->$property = $value;
                }

            }
            $this->logger->debug($possibleUpdate);
            if ($possibleUpdate->passesChecks()) {
                $updates[] = $possibleUpdate;
            }
        }
        return $updates;
    }

    /**
     * @param int $timeout
     *
     * @return Updater
     */
    public function setTimeout(int $timeout): Updater
    {
        $this->timeout = $timeout;
        return $this;
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

    /**
     * @return AvailableUpdate
     */
    public function getLatestUpdate(): AvailableUpdate
    {
        return $this->latestUpdate;
    }

    /**
     * @param AvailableUpdate $latestUpdate
     */
    public function setLatestUpdate(AvailableUpdate $latestUpdate): void
    {
        $this->latestUpdate = $latestUpdate;
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
    protected function versionIsHigher($a, $b)
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
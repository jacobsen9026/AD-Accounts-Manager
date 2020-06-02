<?php


namespace app\controllers;


use App\App\AppUpdater;
use System\App\AppException;
use System\Post;
use VisualAppeal\AutoUpdate;

class Update extends Controller
{
    private $updater;

    public function indexPost()
    {
        $this->updater = new AppUpdater();
        $updateToken = $this->updater->getUpdateToken();
        if ($updateToken === Post::get('updateToken')) {


            /**
             * Move to Update () in updater
             */
            $result = $this->updater->update();
            if ($result === true) {
                return 'Update simulation successful<br>';
            } else {
                $this->logger->warning('Update simulation failed: ' . $result . '!<br>');
                if ($result === AutoUpdate::ERROR_DOWNLOAD_UPDATE) {
                    return 'Could not download update.';
                } elseif ($result === AutoUpdate::ERROR_SIMULATE) {
                    var_dump($this->updater->getSimulationResults());
                    $this->logger->error($this->updater->getSimulationResults());
                    return "Error in simulated install.";
                } elseif ($result === AutoUpdate::ERROR_DELETE_TEMP_UPDATE) {
                    return "Could not delete zip update file.";
                } elseif ($result === AutoUpdate::ERROR_INSTALL) {
                    $this->logger->error('Error while installing the update.');
                    return "Error while installing the update.";

                } elseif ($result === AutoUpdate::ERROR_INSTALL_DIR) {
                    return "Install directory does not exist or is not writable";
                } elseif ($result === AutoUpdate::ERROR_INVALID_ZIP) {
                    return "Zip file could not be opened.";
                } elseif ($result === AutoUpdate::ERROR_VERSION_CHECK) {
                    return "Could not check for last version.";
                }
            }
            /**
             * End move
             */


        } else {
            throw new AppException('Update tokens did not match');
        }

    }
}
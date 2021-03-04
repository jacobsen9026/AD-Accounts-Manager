<?php


namespace app\controllers\api\settings;


use App\App\App;
use App\Controllers\Api\APIController;
use App\Models\Database\AppDatabase;
use App\Models\Database\AuditDatabase;
use App\Models\Database\AuthDatabase;
use App\Models\Database\DomainDatabase;
use App\Models\Database\EmailDatabase;
use App\Models\Database\NotificationDatabase;
use App\Models\Database\PermissionMapDatabase;
use App\Models\Database\PrivilegeLevelDatabase;
use App\Models\Database\SchemaDatabase;
use App\Models\Database\UserDatabase;
use DateTime;
use System\File;
use System\Get;
use system\Header;
use System\Models\Post\UploadedFile;
use System\Post;

class Import extends APIController
{
    public function __construct(App $app)
    {
        parent::__construct($app);
        $date = new DateTime();

        $this->timePrefix = $date->format('Y-m-d');
    }


    public function fullPost()
    {
        $import = new UploadedFile(Post::getFile('backup_upload'));
        $import = json_decode($import->getTempFileContents());
        //var_dump($import);
        foreach ($import as $database => $row) {
            switch ($database) {
                case "AppDatabase":
                    echo "Detected AppDatabase";
                    break;
                case "AuditDatabase":
                    echo "Detected AuditDatabase";
                    break;
                case "AuthDatabase":
                    echo "Detected AuthDatabase";
                    break;
                case "DomainDatabase":
                    echo "Detected DomainDatabase";
                    break;
                case "EmailDatabase":
                    echo "Detected EmailDatabase";
                    break;
                case "NotificationDatabase":
                    echo "Detected NotificationDatabase";
                    break;
                case "PermissionMapDatabase":
                    echo "Detected PermissionMapDatabase";
                    break;
                case "PrivilegeLevelDatabase":
                    echo "Detected PrivilegeLevelDatabase";
                    break;
                case "SchemaDatabase":
                    echo "Detected SchemaDatabase";
                    break;
                case "UserDatabase":
                    echo "Detected UserDatabase";
                    break;
            }
        }

    }

}
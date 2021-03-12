<?php


namespace App\Controllers\Settings;


use App\App\App;
use App\Models\Database\AppDatabase;
use App\Models\Database\AuditDatabase;
use App\Models\Database\AuthDatabase;
use App\Models\Database\DomainDatabase;
use App\Models\Database\EmailDatabase;
use App\Models\Database\EmailTemplateDatabase;
use App\Models\Database\PermissionMapDatabase;
use App\Models\Database\PrivilegeLevelDatabase;
use App\Models\Database\SchemaDatabase;
use App\Models\Database\UserDatabase;
use DateTime;
use System\Models\Post\UploadedFile;
use System\Post;

class Import extends SettingsController
{
    public function __construct(App $app)
    {
        parent::__construct($app);
        $date = new DateTime();

        $this->timePrefix = $date->format('Y-m-d');
    }

    public function permissionsPost()
    {
        $import = new UploadedFile(Post::getFile('permissions_upload'));
        $import = json_decode($import->getTempFileContents(), true);

        foreach ($import as $database => $table) {
            switch ($database) {
                case "PermissionMapDatabase":
                    PermissionMapDatabase::import($table);
                    break;
                case "PrivilegeLevelDatabase":
                    PrivilegeLevelDatabase::import($table);
                    break;
            }
        }
        $this->redirect('/settings/domain/permissions');
    }

    public
    function fullPost()
    {
        $import = new UploadedFile(Post::getFile('backup_upload'));
        $import = json_decode($import->getTempFileContents(), true);

        $this->redirect('/settings/backup');
        foreach ($import as $database => $table) {
            switch ($database) {
                case "AppDatabase":
                    AppDatabase::import($table);
                    break;
                case "AuditDatabase":
                    AuditDatabase::import($table);
                    break;
                case "AuthDatabase":
                    AuthDatabase::import($table);
                    break;
                case "DomainDatabase":
                    DomainDatabase::import($table);
                    break;
                case "EmailDatabase":
                    EmailDatabase::import($table);
                    break;
                case "NotificationDatabase":
                    EmailTemplateDatabase::import($table);
                    break;
                case "PermissionMapDatabase":
                    PermissionMapDatabase::import($table);
                    break;
                case "PrivilegeLevelDatabase":
                    PrivilegeLevelDatabase::import($table);
                    break;
                case "SchemaDatabase":
                    SchemaDatabase::import($table);
                    break;
                case "UserDatabase":
                    UserDatabase::import($table);
                    break;
            }
        }

    }

}
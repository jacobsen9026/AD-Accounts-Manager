<?php


namespace app\controllers\api\settings;


use App\App\App;
use App\Controllers\Api\APIController;
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
use System\File;
use System\Get;
use system\Header;

class Export extends APIController
{
    public function __construct(App $app)
    {
        parent::__construct($app);
        $date = new DateTime();

        $this->timePrefix = $date->format('Y-m-d');
    }

    public function __destruct()
    {
        $this->removeTempFiles();
    }

    public function removeTempFiles()
    {
        File::delete(WRITEPATH . DIRECTORY_SEPARATOR . 'audit.csv');
    }

    public function index()
    {
        return $this->permissions();
    }

    public function permissions()
    {
        $export['PrivilegeLevelDatabase'] = PrivilegeLevelDatabase::get();
        $export['PermissionMapDatabase'] = PermissionMapDatabase::get();

        Header::sendFile(json_encode($export),
            Header::APPLICATION_JSON,
            false,
            $this->timePrefix . '_' . AppDatabase::getAppAbbreviation() . '_permissions.json');
    }

    public function auditGet($type = 'json')
    {
        $date = new DateTime();
        $this->timePrefix = $date->format('Y-m-d');
        if ($type == 'csv') {
            $fromTime = new DateTime(Get::get('from'));
            $toTime = new DateTime(Get::get('to'));
            $export['AuditDatabase'] = AuditDatabase::getBetween($fromTime->getTimestamp(), $toTime->getTimestamp());
            //var_dump(WRITEPATH . DIRECTORY_SEPARATOR . 'audit.csv');
            //var_dump($export);
            File::overwriteFile(WRITEPATH . DIRECTORY_SEPARATOR . 'audit.csv', 'test');
            $f = fopen(WRITEPATH . DIRECTORY_SEPARATOR . 'audit.csv', 'w');
            $firstRow = true;
            foreach ($export['AuditDatabase'] as $row) {
                if ($firstRow) {
                    foreach ($row as $column => $value) {
                        $headerRow[] = $column;
                    }
                    $firstRow = false;
                    fputcsv($f, $headerRow);
                }

                fputcsv($f, $row);
            }
            fclose($f);
            $csv = File::getContents(WRITEPATH . DIRECTORY_SEPARATOR . 'audit.csv');
            Header::sendFile($csv,
                Header::APPLICATION_CSV,
                false,
                $this->timePrefix . '_audit.csv');
        } else {
            $export['AuditDatabase'] = AuditDatabase::get();
            Header::sendFile(json_encode($export), Header::APPLICATION_JSON, false, $this->timePrefix . '_audit.json');

        }
    }

    public function full()
    {
        $export['AppDatabase'] = AppDatabase::get();
        $export['AuditDatabase'] = AuditDatabase::get();
        $export['AuthDatabase'] = AuthDatabase::get();
        $export['EmailDatabase'] = EmailDatabase::get();
        $export['DomainDatabase'] = DomainDatabase::get();
        $export['NotificationDatabase'] = EmailTemplateDatabase::get();
        $export['PermissionMapDatabase'] = PermissionMapDatabase::get();
        $export['PrivilegeLevelDatabase'] = PrivilegeLevelDatabase::get();
        $export['SchemaDatabase'] = SchemaDatabase::get();
        $export['UserDatabase'] = UserDatabase::get();
        Header::sendFile(json_encode($export),
            Header::APPLICATION_JSON,
            false,
            $this->timePrefix . '_' . AppDatabase::getAppAbbreviation() . '_full_backup.json');

    }

}
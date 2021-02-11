<?php


namespace App\Models\Database;


use System\File;

class SchemaDatabase extends DatabaseModel
{
    const TABLE_NAME = "Schema";

    /**
     * @return mixed
     */
    public static function getSchemaVersion()
    {
        return self::getDatabaseValue('Schema_Version', 1);
    }

    public static function isSchemaUpdateAvailable(){
        if(self::getNextSchemaVersion()!==null){
            return true;
        }
        return false;
    }

    public static function getNextSchemaVersion()
    {
        $currentVersion = self::getSchemaVersion();
        $currentVersionNumber = explode('.', $currentVersion);
        foreach(File::getFiles(APPPATH.DIRECTORY_SEPARATOR."database".DIRECTORY_SEPARATOR."updates")as $schemaUpdateFile){
            $availableVersion = str_replace("v","",pathinfo($schemaUpdateFile)['filename']);
            $availableVersionNumber = explode('.', $availableVersion);


                if($availableVersionNumber[0]>$currentVersionNumber[0]){
                    $nextVersion = $availableVersion;
                }elseif ($availableVersionNumber[1]>$currentVersionNumber[1]){
                    $nextVersion = $availableVersion;
                }elseif ($availableVersionNumber[2]>$currentVersionNumber[2]){
                    $nextVersion = $availableVersion;
                }

        }
        return $nextVersion;
    }
}
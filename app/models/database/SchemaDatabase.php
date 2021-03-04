<?php


namespace App\Models\Database;


use System\File;

class SchemaDatabase extends DatabaseModel
{
    const TABLE_NAME = "Schema";

    public static function isSchemaUpdateAvailable()
    {
        if (self::getNextSchemaVersion() !== null) {
            return true;
        }
        return false;
    }

    public static function getNextSchemaVersion()
    {
        $currentVersion = self::getSchemaVersion();
        $currentVersionNumber = explode('.', $currentVersion);
        $nextVersion = $currentVersion;
        $availableVersions = self::getAvailableVersions();
        foreach ($availableVersions as $availableVersion) {
            $availableVersionNumber = explode('.', $availableVersion);


            if ($availableVersionNumber[0] > $currentVersionNumber[0]) {
                $nextVersion = $availableVersion;
            } elseif ($availableVersionNumber[1] > $currentVersionNumber[1]) {
                $nextVersion = $availableVersion;
            } elseif ($availableVersionNumber[2] > $currentVersionNumber[2]) {
                $nextVersion = $availableVersion;
            }

        }
        return $nextVersion;
    }

    /**
     * @return mixed
     */
    public static function getSchemaVersion()
    {
        $schemaVersion = self::getDatabaseValue('Schema_Version', 1);
        if ($schemaVersion === false) {
            self::insertRow();
            self::getSchemaVersion();
        }
        return $schemaVersion;
    }

    public static function getAvailableVersions()
    {
        foreach (File::getFiles(APPPATH . DIRECTORY_SEPARATOR . "database" . DIRECTORY_SEPARATOR . "updates") as $schemaUpdateFile) {
            $availableVersions[] = str_replace("v", "", pathinfo($schemaUpdateFile)['filename']);
        }
        return $availableVersions;
    }
}
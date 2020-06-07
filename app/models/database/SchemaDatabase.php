<?php


namespace App\Models\Database;


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
}
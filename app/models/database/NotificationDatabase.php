<?php


namespace App\Models\Database;


class NotificationDatabase extends DatabaseModel
{
    const TABLE_NAME = "Notification";

    public static function get($id = null)
    {
        $response = parent::get($id);
        if (is_null($response)) {
            self::createTable();
            $response = parent::get($id);

        }
        if ($id !== null) {
            $response = $response[0];
        }
        return $response;
    }

    private static function createTable()
    {
        $query = new Query(self::TABLE_NAME, Query::CREATE);
        $query->addColumn('ID', 'INTEGER', null, true, true)
            ->addColumn('Name')
            ->addColumn('Subject')
            ->addColumn('Body');
        return $query->run();
    }

    public static function create($newNotificationName)
    {
        return self::insertRow(["Name" => $newNotificationName]);
    }

    public static function updateTemplate($id, $name, $subject, $body)
    {
        return self::updateRow($id, ["Name" => $name, "Subject" => $subject, "Body" => $body]);
    }

    public static function deleteTemplate($id)
    {
        return self::deleteRow($id);
    }


}
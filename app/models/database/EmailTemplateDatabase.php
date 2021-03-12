<?php


namespace App\Models\Database;


use app\models\view\EmailTemplateInterpreter;

class EmailTemplateDatabase extends DatabaseModel
{
    const TABLE_NAME = "EmailTemplate";


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
        if (!self::columnExists("CC")) {
            self::addColumn(self::TABLE_NAME, "CC");
        }
        if (!self::columnExists("BCC")) {
            self::addColumn(self::TABLE_NAME, "BCC");
        }
        return $response;
    }

    private static function createTable()
    {
        $query = new Query(self::TABLE_NAME, Query::CREATE);
        $query->addColumn('ID', 'INTEGER', null, true, true)
            ->addColumn('Name')
            ->addColumn('Subject')
            ->addColumn('Body')
            ->addColumn('CC')
            ->addColumn('BCC');
        return $query->run();
    }

    public static function create($newNotificationName)
    {

        return self::insertRow(["Name" => $newNotificationName]);
    }

    public static function updateTemplate($id, $name, $subject, $body, $cc, $bcc)
    {
        return self::updateRow($id,
            ["Name" => trim($name),
                "Subject" => trim($subject),
                "Body" => trim($body),
                "CC" => trim($cc),
                "BCC" => trim($bcc)]);
    }

    public static function deleteTemplate($id)
    {
        return self::deleteRow($id);
    }

    public static function getSubject(?int $id)
    {
        return self::getDatabaseValue("Subject", $id);
    }

    public static function getBody(?int $id)
    {
        return self::getDatabaseValue("Body", $id);

    }

    public static function getCC(?int $id)
    {
        return self::getDatabaseValue("CC", $id);

    }

    public static function getBCC(?int $id)
    {
        return self::getDatabaseValue("BCC", $id);

    }


}
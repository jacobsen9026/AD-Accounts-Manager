<?php


namespace App\App;


use System\Database;

class ConfigDatabase extends Database
{

    /** @var Database|null */
    public static $instance;

    public function __construct()
    {
        $dsn = "sqlite:" . APPCONFIGDBPATH;
        parent::__construct($dsn);
        $this->connect();
        self::$instance = $this;

    }

    public function connect()
    {
        /*
         * If the db file doesn't exist we want to seed it after we connect
         */
        if (!file_exists(APPCONFIGDBPATH)) {
            $seedDatabse = true;
        }
        $this->logger->info("connecting " . APPCONFIGDBPATH);
        parent::connect();
        /*
         * Seed if first connection
         */
        if (!empty($seedDatabse)) {
            $this->seedDatabase();
        }
        /**
         *  activate use of foreign key constraints
         */
        $this->enableForeignKeyConstraints();

    }

    private function seedDatabase()
    {
        $this->logger->debug("Seeding the configuration database.");
        /*
         * Load in DB Schema from file
         */
        $query = file_get_contents(APPPATH . DIRECTORY_SEPARATOR . "database" . DIRECTORY_SEPARATOR . "seeds" . DIRECTORY_SEPARATOR . "Config.sql");
        $this->db->exec($query);
        $this->db->exec($query);
        /*
         * Seed Tables
         */
        $query = 'INSERT INTO App DEFAULT VALUES';
        $this->db->exec($query);
    }


    /**
     *
     * @return self
     */
    public static function get()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }


}
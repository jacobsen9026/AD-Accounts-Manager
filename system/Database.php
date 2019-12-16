<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace system;

/**
 * Description of Database
 *
 * @author cjacobsen
 */
use SQLite3;
use PDO;

class Database extends Parser {
    /*
     * Database Scheme as Contansts
     */

    /** @var PDO Description */
    private $db;

    /** @var Database|null */
    public static $instance;

    /**
     *
     * @return Database
     */
    public static function get() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    function __construct() {
        self::$instance = $this;
        $this->connect();
    }

    public function connect() {
        /*
         * If the db file doesn't exist we want to seed it after we connect
         */
        if (!file_exists(DBPATH)) {
            $seedDatabse = true;
        }
        SystemLogger::get()->info("connecting " . DBPATH);
        $this->db = new \PDO("sqlite:" . DBPATH, null, null, array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ));
        /*
         * Seed if first connection
         */
        if (!empty($seedDatabse)) {
            $this->seedDatabase();
        }
        // activate use of foreign key constraints
        $this->db->exec('PRAGMA foreign_keys = ON;');
    }

    private function seedDatabase() {
        app\AppLogger::get()->debug("Seeding the configuration database.");
        /*
         * Load in DB Schema from file
         */
        $query = file_get_contents(APPPATH . DIRECTORY_SEPARATOR . "database" . DIRECTORY_SEPARATOR . "seeds" . DIRECTORY_SEPARATOR . "0-1-0.sql");
        $this->db->exec($query);
        /*
         * Seed Tables
         */
        $query = 'INSERT INTO App DEFAULT VALUES';
        $this->db->exec($query);
    }

    public function query($query) {
        /* @var $db PDO */
        app\AppLogger::get()->debug("Query: " . $query);
        try {
            $result = $this->db->query($query);
            /*
             * Check that the SQL statement completed successfully, log any errors
             */
            if ($this->db->errorCode()[0] != '00000') {
                app\AppLogger::get()->error($this->db->errorInfo());
                return false;
            }
            /*
             * Convert PDO response into a regular array
             */
            $return = false;
            if (isset($result) and $result != false) {
                foreach ($result as $row) {
                    //Add row response to return array
                    $return[] = $row;
                }
            }
            /*
             * Check if response conatined a signle field and extract that value
             */
            if (is_countable($return) and is_countable($return[0]) and sizeof($return) == 1 and sizeof($return[0]) == 1) {
                $return = array_values($return)[0];
                $key = array_key_first($return);
                $return = $return[$key];
            }

            //Return Array
            app\AppLogger::get()->debug("Response: " . var_export($return, true));
            return $return;
        } catch (Exception $ex) {
            app\AppLogger::get()->error($ex);
            return false;
        }
    }

    /**
     *
     * @return array
     */
    public function getAllTables() {
        /*
         * Generic get all tables function for SQLite3
         */
        $query = "SELECT name FROM sqlite_master WHERE type ='table' AND name NOT LIKE 'sqlite_%';";
        $result = $this->query($query);
        foreach ($result as $field => $tableName) {
            $tables[] = $tableName["name"];
        }
        return $tables;
    }

    /**
     *
     * @param string $table
     * @return array
     */
    public function getAllColumns($table) {
        /*
         * Generic get all columns function for SQLite3
         */

        $query = "PRAGMA table_info(" . $table . ");";
        $result = $this->query($query);
        foreach ($result as $field => $tableName) {
            $tables[] = $tableName["name"];
        }
        return $tables;
    }

    /**
     * getConstants
     *
     * creates an array of DATABASE_FIELD,Field
     * for all tables and columns in database
     *
     * @return array
     */
    public function getConstants() {
        $tables = $this->getAllTables();
        foreach ($tables as $table) {
            $columns = $this->getAllColumns($table);
            foreach ($columns as $column) {
                $constants[$table . '_' . $column] = $column;
            }
        }
        return $constants;
    }

}

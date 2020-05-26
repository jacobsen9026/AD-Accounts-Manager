<?php

/*
 * The MIT License
 *
 * Copyright 2020 cjacobsen.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace System;

/**
 * Description of Database
 *
 * This needs to be moved to App/Models
 *
 * This class is the bottom layer of database access, all queries
 * should be funneled into this class.
 *
 * @author cjacobsen
 * @todo   Generalize class
 */

use PDO;

class Database extends Parser
{
    /*
     * Database Scheme as Contansts
     */

    /** @var PDO Description */
    private $db;

    /** @var Database|null */
    public static $instance;

    /**
     *
     * @var DatabaseLogger
     */
    public static $logger;

    /**
     *
     * @return Database
     */
    public static function get()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    function __construct()
    {
        self::$instance = $this;
        self::$logger = DatabaseLogger::get();
        $this->connect();
    }

    public function connect()
    {
        /*
         * If the db file doesn't exist we want to seed it after we connect
         */
        if (!file_exists(APPCONFIGDBPATH)) {
            $seedDatabse = true;
        }
        self::$logger->info("connecting " . APPCONFIGDBPATH);
        /**
         * Connect to database, will create file if it doesn't already exist
         */
        $this->db = new \PDO("sqlite:" . APPCONFIGDBPATH, null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]);
        /*
         * Seed if first connection
         */
        if (!empty($seedDatabse)) {
            $this->seedDatabase();
        }
        /**
         *  activate use of foreign key constraints
         */
        $this->db->exec('PRAGMA foreign_keys = ON;');
    }

    private function seedDatabase()
    {
        self::$logger->debug("Seeding the configuration database.");
        /*
         * Load in DB Schema from file
         */
        $query = file_get_contents(APPPATH . DIRECTORY_SEPARATOR . "database" . DIRECTORY_SEPARATOR . "seeds" . DIRECTORY_SEPARATOR . "0-1-0.sql");
        $this->db->exec($query);
        /*
         * Seed the Grade Definitions Table
         */
        $query = file_get_contents(APPPATH . DIRECTORY_SEPARATOR . "database" . DIRECTORY_SEPARATOR . "seeds" . DIRECTORY_SEPARATOR . "GradeDefinitions.sql");
        $this->db->exec($query);
        /*
         * Seed Tables
         */
        $query = 'INSERT INTO App DEFAULT VALUES';
        $this->db->exec($query);
    }

    /**
     * Performs a query on the config database
     * Should be in the form of a standard SQL query
     *
     * @param string $query
     *
     * @return boolean
     */
    public function query($query)
    {

        //var_dump($query);
        /* @var $db PDO */

        self::$logger->debug("Query: " . $query);
        //var_dump($query);
        try {
            $result = $this->db->query($query);
            /*
             * Check that the SQL statement completed successfully, log any errors
             */
            if ($this->db->errorCode()[0] != '00000') {
                self::$logger->error($this->db->errorInfo());
                return false;
            }
            /*
             * Convert PDO response into a regular array
             */
            //var_dump($result);
            $return = false;
            if (isset($result) and $result != false) {
                foreach ($result as $row) {
                    //Add row response to return array
                    //var_dump($query);
                    //var_dump($row);
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
            self::$logger->debug("Response: " . var_export($return, true));

            //var_dump($return);
            return $return;
        } catch (Exception $ex) {
            self::$logger->error($ex);
            return false;
        }
    }

    /**
     * Returns all tables in the config database
     * as an array
     *
     * @return array A list of table names
     */
    public function getAllTables()
    {
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
     * Returns all columns for a table as an array
     *
     * @param string $table The table name
     *
     * @return array A list of column names
     */
    public function getAllColumns($table)
    {
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

}

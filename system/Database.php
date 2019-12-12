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

class Database {

    //put your code here
    public $databaseFile;

    /** @var SQLite3 Description */
    private $handle;

    function __construct() {
        $this->databaseFile = CONFIGPATH . DIRECTORY_SEPARATOR . "config.db";
        var_dump($this->databaseFile);


        // Set default timezone
        date_default_timezone_set('UTC');


        /*         * ************************************
         * Create databases and                *
         * open connections                    *
         * ************************************ */

        // Create (connect to) SQLite database in file
        $file_db = new PDO('sqlite:messaging.sqlite3');
        // Set errormode to exceptions
        $file_db->setAttribute(PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION);

        // Create new database in memory
        $memory_db = new PDO('sqlite::memory:');
        // Set errormode to exceptions
        $memory_db->setAttribute(PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION);


        /*         * ************************************
         * Create tables                       *
         * ************************************ */

        // Create table messages
        $file_db->exec("CREATE TABLE IF NOT EXISTS messages (
                    id INTEGER PRIMARY KEY,
                    title TEXT,
                    message TEXT,
                    time INTEGER)");

        // Create table messages with different time format
        $memory_db->exec("CREATE TABLE messages (
                      id INTEGER PRIMARY KEY,
                      title TEXT,
                      message TEXT,
                      time TEXT)");


        /*         * ************************************
         * Set initial data                    *
         * ************************************ */

        // Array with some test data to insert to database
        $messages = array(
            array('title' => 'Hello!',
                'message' => 'Just testing...',
                'time' => 1327301464),
            array('title' => 'Hello again!',
                'message' => 'More testing...',
                'time' => 1339428612),
            array('title' => 'Hi!',
                'message' => 'SQLite3 is cool...',
                'time' => 1327214268)
        );
    }

    public function initializeSchema() {
        $this->handle = new \SQLite3($this->databaseFile);
        var_dump($this->createTable("test"));
        var_dump($this->get("test"));
    }

    public function createDB($name) {
        return $this->handle->query("CREATE TABLE $tableName");
    }

    public function createTable($tableName) {
        return $this->handle->query("CREATE TABLE Product (p_id INTEGER PRIMARY KEY AUTOINCREMENT,p_name TEXT NOT NULL,price REAL,quantity INTEGER);");
    }

    public function dropTable($tableName) {

    }

    public function get($tableName) {

    }

}

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
        SystemLogger::get()->info("connecting " . DBPATH);
        $this->db = new \PDO("sqlite:" . DBPATH, null, null, array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ));
        // activate use of foreign key constraints
        $this->db->exec('PRAGMA foreign_keys = ON;');
    }

    public function query($query) {
        /* @var $db PDO */
        app\AppLogger::get()->info("Query: " . $query);
        try {
            $result = $this->db->query($query);
            if ($this->db->errorCode()[0] != '00000') {
                app\AppLogger::get()->error($this->db->errorInfo());
                return false;
            }
            //Convert PDO response into a regular array
            $return = false;
            if (isset($result) and $result != false) {
                foreach ($result as $row) {
                    //Add row response to return array
                    $return[] = $row;
                }
            }
            //Return Array
            app\AppLogger::get()->info("Response: " . var_export($return, true));
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
        $query = "PRAGMA table_info(" . $table . ");";
        $result = $this->query($query);
        foreach ($result as $field => $tableName) {
            $tables[] = $tableName["name"];
        }
        return $tables;
    }

    /**
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

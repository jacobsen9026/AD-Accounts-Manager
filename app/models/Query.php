<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;

/**
 * Description of Query
 *
 * @author cjacobsen
 */
use app\database\Schema;
use system\app\AppLogger;

class Query {

    //put your code here
    const SELECT = "SELECT";
    const UPDATE = "UPDATE";
    const CREATE = "CREATE";
    const DELETE = "DELETE";
    const INSERT = "INSERT";

    private $query;
    private $queryType;
    private $targetTable;
    private $targetColumns;
    private $targetWhere;
    private $targetJoin = '';
    private $targetInserts;
    private $targetSets;

    /**
     *
     * @param type $columns
     * @return string|array
     */
    private function preProcessColumns($columns) {
        if (is_array($columns)) {
            if (key_exists(Schema::COLUMN, $columns)) {
                return $columns[Schema::COLUMN];
            }
            foreach ($columns as $column) {
                if (is_array($column) and key_exists(Schema::COLUMN, $column)) {
                    $return[] = $column[Schema::COLUMN];
                }
            }
            if (isset($return)) {
                return $return;
            }
        }
        return $columns;
    }

    /**
     *
     * @param const $table
     * @param const $type
     * @param stirng $columns
     */
    function __construct($table, $type = self::SELECT, $columns = '*') {
        $this->targetTable = $table;
        $this->targetColumns = $this->preProcessColumns($columns);
        $this->queryType = $type;
    }

    /**
     *
     * @param type $whereArray
     * @return Query
     */
    public function where($column, $value) {
        if (!is_int($value) and is_string($value)) {
            $value = "'" . $value . "'";
        }
        //\system\app\AppLogger::get()->debug($column);
        if (is_array($column)) {
            $where = $column[Schema::TABLE] . '.' . $column[Schema::COLUMN] . ' = ' . $value;
        } elseif (is_string($column)) {
            $where = $column . ' = ' . $value;
        }
        $this->targetWhere[] = $where;
        return $this;
    }

    /**
     *
     * @param type $joinTable
     * @param type $srcMatchColumn
     * @param type $dstMatchColumn
     * @return Query
     */
    public function leftJoin($joinTable, $srcMatchColumn, $dstMatchColumn) {
        $this->targetJoin .= ' LEFT JOIN ' . $joinTable . ' ON '
                . $this->targetTable . '.' . $srcMatchColumn[Schema::COLUMN] . ' = '
                . $joinTable . '.' . $dstMatchColumn[Schema::COLUMN];
        //var_dump($this);
        return $this;
    }

    public function insert($Schema, $value) {
        $column = "'" . $Schema[Schema::COLUMN] . "'";
        //var_dump($column);
        $columnValue = "'" . $value . "'";
        //var_dump($columnValue);
        $this->targetInserts[] = [$column, $columnValue];
        return $this;
    }

    public function set($Schema, $value) {
        $column = "'" . $Schema . "'";
        //var_dump($column);
        var_dump($value);
        $this->targetSets[] = [$column, "'" . $value . "'"];
        return $this;
    }

    public function run() {

        switch ($this->queryType) {
            case self::SELECT:
                if (is_array($this->targetColumns)) {
                    $targetColumns = "";
                    $firstColumn = true;
                    foreach ($this->targetColumns as $column) {
                        if ($firstColumn) {
                            $firstColumn = false;
                            $targetColumns .= $column;
                        } else {

                            $targetColumns .= ', ' . $column;
                        }
                    }
                    $this->targetColumns = $targetColumns;
                }
                //var_dump($this->targetColumns);
                $this->query = $this->queryType .
                        ' ' .
                        $this->targetColumns .
                        ' FROM ' .
                        $this->targetTable;

                break;
            case self::INSERT:
                $this->prepareInsert();
                break;
            case self::UPDATE:
                $this->prepareUpdate();
                break;

            default:
                break;
        }

        if (!empty($this->targetJoin)) {
            $this->query .= $this->targetJoin;
        }
        if (!empty($this->targetWhere)) {
            $this->query .= ' WHERE';
            $first = true;
            foreach ($this->targetWhere as $where) {
                if ($first) {
                    $this->query .= ' ' . $where;
                    $first = false;
                    continue;
                }
                $this->query .= ' AND ' . $where;
            }
        }
        //var_dump($query);
        //echo '<br/><br/><br/><br/><br/><br/>';
        //var_dump($query);
        return \system\Database::get()->query($this->query);
    }

    private function prepareInsert() {
        $firstAction = true;

        var_dump($this->targetInserts);

        $this->query = $this->queryType . ' INTO ' . $this->targetTable . ' (';
        foreach ($this->targetInserts as $insert) {
            if (!$firstAction) {
                $this->query .= ', ';
            }
            $this->query .= $insert[0];
            $firstAction = false;
        }
        $firstAction = true;
        $this->query .= ') VALUES (';
        foreach ($this->targetInserts as $insert) {
            if (!$firstAction) {
                $this->query .= ', ';
            }
            $this->query .= $insert[1];

            $firstAction = false;
        }
        $this->query .= ')';

        var_dump($this->targetInserts);
        //exit;
    }

    private function prepareUpdate() {
        $firstAction = true;

        $this->query = $this->queryType . ' ' . $this->targetTable . ' SET ';
        foreach ($this->targetSets as $set) {
            if (!$firstAction) {
                $this->query .= ', ';
            }
            $this->query .= $set[0];
            $firstAction = false;
        }
        $firstAction = true;
        $this->query .= ' = ';
        foreach ($this->targetSets as $set) {
            if (!$firstAction) {
                $this->query .= ', ';
            }
            $this->query .= $set[1];

            $firstAction = false;
        }

        var_dump($this->targetSets);
        //exit;
    }

}

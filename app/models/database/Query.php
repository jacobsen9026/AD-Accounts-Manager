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

namespace App\Models\Database;

/**
 * Description of Query
 *
 * @author cjacobsen
 */

use app\database\Schema;
use System\App\AppLogger;

class Query
{

//put your code here
    const SELECT = "SELECT";
    const UPDATE = "UPDATE";
    const CREATE = "CREATE";
    const DELETE = "DELETE";
    const INSERT = "INSERT";
    const DESC = "DESC";
    const ASC = "ASC";
    const COUNT = "SELECT COUNT";

    private $query;
    private $queryType;
    private $targetTable;
    private $targetColumns;
    private $targetWhere;
    private $targetJoin = '';
    private $targetInserts;
    private $targetSets;
    private $sort;

    /**
     *
     * @param type $columns
     *
     * @return string|array
     */
    private function preProcessColumns($table, $columns)
    {
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
        return $table . '.' . $columns;
    }

    /**
     *
     * @param const $table    Target Table
     * @param const $type     If not supplied will be a SELECT
     * @param stirng $columns If not supplied will be '*'
     */
    function __construct($table, $type = self::SELECT, $columns = '*')
    {
        $this->targetTable = $table;
        $this->targetColumns = $this->preProcessColumns($table, $columns);
        $this->queryType = $type;
    }

    private function prepareSelect()
    {
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
    }

    private function prepareCount()
    {
        if (is_array($this->targetColumns)) {
            $this->targetColumns = $this->targetColumns[0];
        }
        $this->query = $this->queryType . ' (' . $this->targetColumns . ') FROM ' . $this->targetTable;

    }

    /**
     *
     * @param type $whereArray
     *
     * @return Query
     */
    public function where($column, $value, $like = false)
    {

        $operator = "=";
        $wildcard = '';
        if ($like) {
            $operator = "LIKE";
            $wildcard = '%';
        }
        $value = $wildcard . $value . $wildcard;
//\System\App\AppLogger::get()->debug($column);
        if (!is_int($value) and is_string($value)) {
            $value = "'" . $value . "'";
        }
        $where = $column . ' ' . $operator . ' ' . $value;


        $this->targetWhere[] = $where;

        return $this;
    }

    /**
     *
     * @param type $joinTable
     * @param type $srcMatchColumn
     * @param type $dstMatchColumn
     *
     * @return Query
     */
    public function leftJoin($joinTable, $srcMatchColumn, $dstMatchColumn)
    {
        $this->targetJoin .= ' LEFT JOIN ' . $joinTable . ' ON '
            . $this->targetTable . '.' . $srcMatchColumn . ' = '
            . $joinTable . '.' . $dstMatchColumn;
//var_dump($this);
        return $this;
    }

    public function insert($Schema, $value)
    {
        if (is_array($Schema)) {
            $column = "'" . $Schema[Schema::COLUMN] . "'";
        } else {
            $column = "'" . $Schema . "'";
        }
//var_dump($column);
        $columnValue = "'" . $value . "'";
//var_dump($columnValue);
        $this->targetInserts[] = [$column, $columnValue];
        return $this;
    }

    public function set($Schema, $value)
    {
        $column = "'" . $Schema . "'";
//var_dump($column);
//var_dump($value);
        $this->targetSets[] = [$column, "'" . $value . "'"];
        return $this;
    }

    public function run()
    {

        switch ($this->queryType) {
            case self::SELECT:
                $this->prepareSelect();

                break;
            case self::INSERT:
                $this->prepareInsert();
                break;
            case self::UPDATE:
                $this->prepareUpdate();
                break;

            case self::COUNT:
                $this->prepareCount();
                break;
            case self::DELETE:
                $this->prepareDelete();
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
        if (!empty($this->sort)) {
            //var_dump($this->sort);
            $this->query .= ' ORDER BY ';
            foreach ($this->sort as $sort) {
                foreach ($sort as $direction => $column) {
                    $this->query .= $column . ' ' . $direction;
                }
            }

            AppLogger::get()->warning($this->query);
        }
        //var_dump($this->query);
//echo '<br/><br/><br/><br/><br/><br/>';
        return \system\Database::get()->query($this->query . ';');
    }

    public function orWhere($column, $value)
    {
        if (!is_int($value) and is_string($value)) {
            $value = "'" . $value . "'";
        }
//\System\App\AppLogger::get()->debug($column);
        if (is_array($value)) {
            $where = '(';
            $first = true;
            foreach ($value as $v) {
                if (!is_int($v) and is_string($v)) {
                    $v = "'" . $v . "'";
                }
                if (!$first) {
                    $where .= ' OR ';
                }
                $where .= $column . ' = ' . $v;
                $first = false;
            }
        } elseif (is_string($column)) {
            $where = $column . ' = ' . $value;
        }
        $this->targetWhere[] = $where . ')';
        return $this;
    }

    private function prepareInsert()
    {
        $firstAction = true;

//var_dump($this->targetInserts);

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

//var_dump($this->targetInserts);
//exit;
    }

    private function prepareDelete()
    {
        $firstAction = true;

        $this->query = $this->queryType . ' FROM ' . $this->targetTable . ' ';

        $firstAction = true;
    }

    private function prepareUpdate()
    {
        $firstAction = true;

        $this->query = $this->queryType . ' ' . $this->targetTable . ' SET ';
        foreach ($this->targetSets as $set) {
            if (!$firstAction) {
                $this->query .= ', ';
            }
            $this->query .= $set[0] . '=' . $set[1];
            $firstAction = false;
        }
    }

    public function sort(string $direction, string $column)
    {
        $this->sort[] = [$direction => $column];
    }

}

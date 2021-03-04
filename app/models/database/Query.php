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


use PDOException;
use System\App\AppException;
use System\App\AppLogger;
use system\Database;
use System\DatabaseLogger;

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
    const SHOW = 'SHOW';
    const ALTER = 'ALTER';

    protected $query;
    protected $queryType;
    protected $targetTable;
    protected $targetColumns;
    protected $targetWhere;
    protected $targetJoin = '';
    protected $targetInserts;
    protected $targetSets;
    protected $sort;
    protected string $error = '';

    /**
     *
     * @param string $table Target Table
     * @param string $type If not supplied will be a SELECT
     * @param string $columns If not supplied will be '*'
     */
    function __construct($table, $type = self::SELECT, $columns = '*')
    {
        $this->targetTable = $table;
        $this->targetColumns = $this->preProcessColumns($table, $columns);
        $this->queryType = $type;
    }

    /**
     *
     * @param array|string $columns
     *
     * @return string|array
     */
    private function preProcessColumns($table, $columns)
    {
        return $table . '.' . $columns;
    }

    /**
     * @param $column
     * @param $value
     * @param string $operator
     * @return $this
     */
    public function where($column, $value, $operator = '=')
    {
        $operator = " " . trim($operator) . " ";

        if (!is_int($value) and is_string($value)) {
            $value = "'" . $value . "'";
        }
        $where = $column . $operator . $value;


        $this->targetWhere[] = $where;

        return $this;
    }

    /**
     *
     * @param string $joinTable
     * @param string $srcMatchColumn
     * @param string $dstMatchColumn
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

    /**
     * @param $column
     * @param $value
     * @return $this
     */
    public function insert($column, $value)
    {
        $columnName = "'" . $column . "'";
        $columnValue = "'" . $value . "'";
        $this->targetInserts[] = [$columnName, $columnValue];
        return $this;
    }

    /**
     * @param $Schema
     * @param $value
     * @return $this
     */
    public function set($Schema, $value)
    {
        $column = "'" . $Schema . "'";
        $this->targetSets[] = [$column, "'" . $value . "'"];
        return $this;
    }

    /**
     * Runs the current query as configured
     *
     * @return array|bool|null
     * @throws \System\CoreException
     */
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
            case self::ALTER:
                $this->prepareAlter();
                break;
            case self::CREATE:
                $this->prepareCreate();
                break;
            default:
                throw new AppException('Malformed query', AppException::MALFORMED_QUERY);
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
            $this->query .= ' ORDER BY ';
            foreach ($this->sort as $sort) {
                foreach ($sort as $direction => $column) {
                    $this->query .= $column . ' ' . $direction;
                }
            }

            AppLogger::get()->debug($this->query);
        }
        $return = null;
        try {

            Database::get()->prepare($this->query . ';');
        } catch (PDOException $ex) {
            if (strpos($ex->getMessage(), QueryError::UNRECOGNIZED_TOKEN) > 0) {
                $this->error = QueryError::UNRECOGNIZED_TOKEN;
                DatabaseLogger::get()->warning($ex->getMessage());
            }
        }
        try {
            $return = Database::get()->query($this->query . ';');
        } catch (PDOException $ex) {
            if (strpos($ex->getMessage(), QueryError::NO_SUCH_COLUMN) > 0) {
                $this->error = QueryError::NO_SUCH_COLUMN;
                DatabaseLogger::get()->warning($ex->getMessage());
            } elseif (strpos($ex->getMessage(), QueryError::NO_SUCH_COLUMN) > 0) {
                $this->error = QueryError::NO_SUCH_TABLE;
                DatabaseLogger::get()->warning($ex->getMessage());
            } elseif (strpos($ex->getMessage(), QueryError::UNRECOGNIZED_TOKEN) > 0) {
                $this->error = QueryError::UNRECOGNIZED_TOKEN;
                DatabaseLogger::get()->warning($ex->getMessage());
            } elseif (strpos($ex->getMessage(), QueryError::SYNTAX_ERROR) > 0) {
                $this->error = QueryError::SYNTAX_ERROR;
                DatabaseLogger::get()->warning($ex->getMessage());
            } else {
                DatabaseLogger::get()->error($ex->getMessage());
            }


        }

        return $return;

    }

    /**
     *
     */
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

    /**
     *
     */
    private function prepareInsert()
    {
        $firstAction = true;

//var_dump($this->targetInserts);

        $this->query = $this->queryType . ' INTO ' . $this->targetTable;
        if ($this->targetInserts !== null) {
            $this->query .= ' (';
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
        } else {
            $this->query .= ' DEFAULT VALUES';
        }

    }

    /**
     *
     */
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

    /**
     *
     */
    private function prepareCount()
    {
        if (is_array($this->targetColumns)) {
            $this->targetColumns = $this->targetColumns[0];
        }
        $this->query = $this->queryType . ' (' . $this->targetColumns . ') FROM ' . $this->targetTable;

    }

    /**
     *
     */
    private function prepareDelete()
    {
        $firstAction = true;

        $this->query = $this->queryType . ' FROM ' . $this->targetTable . ' ';

        $firstAction = true;
    }

    private function prepareAlter()
    {
        $this->query = 'ALTER TABLE ' . $this->targetTable . ' ADD COLUMN ' . explode('.', $this->targetColumns)[1] . ' STRING';
    }

    private function prepareCreate()
    {
        $this->query = "CREATE TABLE  IF NOT EXISTS " . $this->targetTable . " (";
        /** @var SchemaEntry $schemaColumn */
        $first = true;
        foreach ($this->targetColumns as $schemaColumn) {
            if (!$first) {
                $this->query .= ",";
            }
            $this->query .= "'" . $schemaColumn->getColumnName() . "' " . $schemaColumn->getDataType() . " ";
            if (!is_null($schemaColumn->getDefaultValue())) {
                $this->query .= "DEFAULT " . $schemaColumn->getDefaultValue() . " ";
            }
            if ($schemaColumn->isNotNull()) {
                $this->query .= " NOT NULL ";
            }
            if ($schemaColumn->isPrimaryKey()) {
                $this->query .= " PRIMARY KEY AUTOINCREMENT";
            }
            $first = false;

        }

        $this->query .= ")";
    }

    /**
     * @param string $column
     * @param array|string $value
     * @return $this
     */
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

    /**
     * @param string $direction
     * @param string $column
     */
    public function sort(string $direction, string $column)
    {
        $this->sort[] = [$direction => $column];
    }

    public function hadError()
    {
        if ($this->error == '') {
            return false;
        }
        return true;

    }

    public function getError()
    {
        return $this->error;
    }

    public function addColumn(string $columnName, string $dataType = 'STRING', $defaultValue = null, bool $notNull = false, bool $primaryKey = false)
    {
        if (is_string($this->targetColumns)) {
            $this->targetColumns = [];
        }
        $this->targetColumns[] = new SchemaEntry($columnName, $dataType, $defaultValue, $notNull, $primaryKey);
        return $this;
    }

}

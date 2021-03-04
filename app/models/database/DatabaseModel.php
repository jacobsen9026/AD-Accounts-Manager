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
 * Description of DatabaseModel
 *
 * @author cjacobsen
 */

use App\Models\Model;
use System\DatabaseLogger;

abstract class DatabaseModel extends Model implements DatabaseModelInterface
{

    public static $cache = [];

    /**
     * Returns the entire table or a specific row by id
     *
     * @return array
     */
    public static function get($id = null)
    {
        if ($id === null) {
            /**
             * getting entire table
             */
            if (!is_array(self::$cache) ||
                !key_exists(static::TABLE_NAME, self::$cache) ||
                !key_exists('ENTIRE_TABLE', self::$cache[static::TABLE_NAME]) ||
                is_null(self::$cache[static::TABLE_NAME]['ENTIRE_TABLE'])) {
                /**
                 * No cached entry found running new database call
                 */
                $query = new Query(static::TABLE_NAME);
                $response = $query->run();
                DatabaseLogger::get()->info('Storing entire table to cache for ' . static::TABLE_NAME);
                self::$cache[static::TABLE_NAME]['ENTIRE_TABLE'] = $response;
                return $response;
            } else {
                DatabaseLogger::get()->info('Retreiving entire table from cache for ' . static::TABLE_NAME . ': ' . self::$cache[static::TABLE_NAME]['ENTIRE_TABLE']);
                return self::$cache[static::TABLE_NAME]['ENTIRE_TABLE'];
            }
        } else {
            /**
             * getting specific row
             */
            if (!is_array(self::$cache) ||
                !key_exists(static::TABLE_NAME, self::$cache) ||
                !key_exists($id, self::$cache[static::TABLE_NAME]) ||
                is_null(self::$cache[static::TABLE_NAME][$id])) {
                /**
                 * No cached entry found running new database call
                 */
                $query = new Query(static::TABLE_NAME);
                $query->where('ID', $id);
                $response = $query->run();
                DatabaseLogger::get()->info('Storing entire row to cache for ' . static::TABLE_NAME . '.' . $id);
                self::$cache[static::TABLE_NAME][$id] = $response;
                return $response;
            } else {
                DatabaseLogger::get()->info('Retreiving entire row from cache for ' . static::TABLE_NAME . '.' . $id . ': ' . self::$cache[static::TABLE_NAME][$id]);
                return self::$cache[static::TABLE_NAME][$id];
            }
        }

    }


    /**
     * The calling object MUST have a TABLE_NAME constant
     * defined with the value of the name for the table
     * in the database.
     *
     * @param string $column
     * @param string $value
     *
     * @return string
     */
    protected static function updateDatabaseValue($column, $value)
    {
        $query = new Query(static::TABLE_NAME, Query::UPDATE, $column);
        $query->where("ID", 1)
            ->set($column, $value);
        DatabaseLogger::get()->info('Storing value to cache for ' . $column . ': ' . $value);
        self::$cache[static::TABLE_NAME][1][$column] = $value;
        $return = $query->run();
        if ($query->hadError()) {
            self::$cache[static::TABLE_NAME][1][$column] = null;
            if ($query->getError() == QueryError::NO_SUCH_COLUMN) {
                self::addColumn(static::TABLE_NAME, $column);
                $return = $query->run();

            }
        }
        return $return;
    }

    protected static function addColumn($table, string $column)
    {
        $query = new Query($table, Query::ALTER, $column);
        return $query->run();
    }

    /**
     * The calling object MUST have a TABLE_NAME constant
     * defined with the value of the name for the table
     * in the database.
     *
     *
     * @param string $column
     *
     * @return mixed
     */
    protected static function getDatabaseValue($column, $id = 1)
    {

        if (!is_array(self::$cache) ||
            !key_exists(static::TABLE_NAME, self::$cache) ||
            !key_exists($id, self::$cache[static::TABLE_NAME]) ||
            !is_array(self::$cache[static::TABLE_NAME][$id]) ||
            !key_exists($column, self::$cache[static::TABLE_NAME][$id]) ||
            is_null(self::$cache[static::TABLE_NAME][$id][$column])) {
            $query = new Query(static::TABLE_NAME, Query::SELECT, $column);
            $query->where("ID", $id);
            $response = $query->run();
            if (!$query->hadError()) {
                /*
                 * Query ran with no errors
                 */

                if (is_string($response) && strlen($response) < 1000) {
                    DatabaseLogger::get()->info('Storing value to cache for ' . $column . ': ' . $response);
                }
                self::$cache[static::TABLE_NAME][$id][$column] = $response;
                return $response;
            } else {
                /*
                 * Query ran into errors while running
                 */
                switch ($query->getError()) {
                    case QueryError::NO_SUCH_COLUMN:
                        /*
                         * The requested column did not exist
                         */
                        DatabaseLogger::get()->error(QueryError::NO_SUCH_COLUMN);
                        break;
                    case QueryError::NO_SUCH_TABLE:
                        DatabaseLogger::get()->error(QueryError::NO_SUCH_TABLE);
                        break;
                    default:
                        DatabaseLogger::get()->error('Other error');
                        break;
                }
            }
        } else {
            if (strlen(self::$cache[static::TABLE_NAME][$id][$column]) < 1000) {
                DatabaseLogger::get()->info('Retreiving value from cache for ' . $column . ': ' . self::$cache[static::TABLE_NAME][$id][$column]);
            } else {
                DatabaseLogger::get()->info('Retreiving value from cache for ' . $column);
            }
            return self::$cache[static::TABLE_NAME][$id][$column];
        }
    }

    protected static function insertRow($data = null, $id = null)
    {
        $query = new Query(static::TABLE_NAME, Query::INSERT);
        if ($data !== null && is_array($data) && !empty($data)) {
            foreach ($data as $column => $value) {
                $query->insert($column, $value);
            }
        }
        if ($id !== null) {


            $query->insert('ID', $id);
        } else {
            $id = 1;
        }

        $response = $query->run();
        self::$cache[static::TABLE_NAME][$id] = null;
        //DatabaseLogger::get()->error($response);

    }

    protected static function updateRow($id, $data)
    {
        if (is_array($data) && !empty($data)) {
            $query = new Query(static::TABLE_NAME, Query::UPDATE);
            foreach ($data as $key => $value) {
                $query->set($key, $value);
            }
            $query->where('ID', $id);
            $query->run();
        }
    }

    protected static function deleteRow($id)
    {
        $query = new Query(static::TABLE_NAME, Query::DELETE);
        $query->where('ID', $id);
        $query->run();

    }


}

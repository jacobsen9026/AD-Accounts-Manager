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

abstract class DatabaseModel extends Model implements DatabaseModelInterface {

    /**
     * The calling object MUST have a TABLE_NAME constant
     * defined with the value of the name for the table
     * in the database.
     *
     * @param type $column
     * @param type $value
     * @return type
     */
    protected static function updateDatabaseValue($column, $value) {
        $query = new Query(static::TABLE_NAME, Query::UPDATE, $column);
        $query->where("ID", 1)
                ->set($column, $value);
        return $query->run();
    }

    /**
     * The calling object MUST have a TABLE_NAME constant
     * defined with the value of the name for the table
     * in the database.
     *
     *
     * @param string $column
     * @return mixed
     */
    protected static function getDatabaseValue($column, $id = 1) {
        $query = new Query(static::TABLE_NAME, Query::SELECT, $column);
        $query->where("ID", $id);
        return $query->run();
    }

    /**
     * Returns the entire table
     * @return type
     */
    public static function get() {
        $query = new Query(static::TABLE_NAME);
        return $query->run();
    }

}

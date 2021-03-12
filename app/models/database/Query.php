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


use App\App\ConfigDatabase;
use System\Common\CommonQuery;
use System\Models\Database\SchemaEntry;

class Query extends CommonQuery
{
    /**
     * Query constructor.
     * @param $table
     * @param string $type
     * @param string $columns
     * @throws \System\App\AppException
     */
    public function __construct($table, $type = self::SELECT, $columns = '*')
    {
        parent::__construct($table, $type, $columns);
        $this->database = ConfigDatabase::get();
    }

    /**
     * @param string $columnName
     * @param string $dataType
     * @param null $defaultValue
     * @param bool $notNull
     * @param bool $primaryKey
     * @return $this
     */
    public function addColumn(string $columnName, string $dataType = 'STRING', $defaultValue = null, bool $notNull = false, bool $primaryKey = false)
    {
        if (is_string($this->targetColumns)) {
            $this->targetColumns = [];
        }
        $this->targetColumns[] = new SchemaEntry($columnName, $dataType, $defaultValue, $notNull, $primaryKey);
        return $this;
    }

}

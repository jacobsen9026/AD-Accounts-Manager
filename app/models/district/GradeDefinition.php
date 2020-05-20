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

namespace App\Models\District;

/**
 * Description of GradeDefinition
 *
 * @author cjacobsen
 * @deprecated
 */
use App\Models\Query;

class GradeDefinition {

    //put your code here

    const TABLE_NAME = 'GradeDefinition';

    public static function getDistrictID($schoolID) {
        return(\system\Database::get()->query('SELECT ' . Schema::SCHOOL_DISTRICT_ID[Schema::COLUMN] . ' From ' . self::TABLE_NAME . ' Where ID=' . $schoolID)[0][Schema::SCHOOL_DISTRICT_ID]);
    }

    public static function getDropdownArray() {
        $query = new Query(self::TABLE_NAME);
        $response = $query->run();
        var_dump($response);
    }

}

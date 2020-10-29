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

use App\Models\Audit\AuditEntry;
use App\Models\Database\Query;
use DateInterval;
use DateTime;

abstract class AuditDatabase extends DatabaseModel
{
    const TABLE_NAME = "Audit";


    /**
     * @param AuditEntry $newAudit
     */
    public static function addAudit(AuditEntry $newAudit)
    {
        $query = new Query(self::TABLE_NAME, Query::INSERT);
        $query->insert('Timestamp', $newAudit->getTimestamp())
            ->insert('Username', $newAudit->getUsername())
            ->insert('IP', $newAudit->getIp())
            ->insert('Action', $newAudit->getAction()->getType())
            ->insert('Description', $newAudit->getAction()->getDescription());
        $query->run();
    }

    public static function getLast24Hrs(){
        $now = new DateTime();
        $yesterday = new DateTime();
        $yesterday->sub(new DateInterval('P1D'));
        return self::getBetween($yesterday->getTimestamp(),$now->getTimestamp());
        $query = new Query(static::TABLE_NAME);
        $query->where('Timestamp',$yesterday->getTimestamp(),'>=');
        return $query->run();
}

public static function getBetween($from,$to){
        $query = new Query(static::TABLE_NAME);
        $query->where('Timestamp',$from,'>=')
        ->where('Timestamp',$to,'<=');
        return $query->run();
}

}
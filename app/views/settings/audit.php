<?php

/*
 * The MIT License
 *
 * Copyright 2019 cjacobsen.
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


use System\App\Forms\Form;
use System\App\Forms\FormButton;
use System\App\Forms\FormDate;

/**
 *  Form Button that opens a modal with a form in it
 */
//$audit = \App\Models\Database\AuditDatabase::getLast24Hrs();

?>
    <h5>
        Audit Log
    </h5>
    <small>Times are in UTC</small>
<?php
$form = new Form('/settings/audit', 'audit');

$fromTime = new FormDate('From Time', null, 'fromTime', $this->fromTime);
$toTime = new FormDate('To Time', null, 'toTime', $this->toTime);

$fromTime->auto();
$toTime->auto();


$submit = new FormButton('Search', 'small');
$submit->setType('submit');

$form->addElementToNewRow($fromTime)
    ->addElementToCurrentRow($toTime)
    ->addElementToCurrentRow($submit);
echo $form->print();
?>
    <div class="w-100">
        <div class="row">
            <strong class="col-3">
                Timestamp
            </strong>
            <strong class="col-1">
                Username
            </strong>
            <strong class="col-1">
                IP
            </strong>
            <strong class="col-2">
                Action
            </strong>
            <strong class="col-5">
                Description
            </strong>
        </div>
        <?php
        if (is_array($this->audit) && count($this->audit) > 0) {
            foreach ($this->audit as $entry) {
                $timestamp = new DateTime();
                $timestamp->setTimestamp($entry['Timestamp']);
                ?>
                <div class="row">
                    <div class="col-3">
                        <?= $timestamp->format('Y-m-d H:i:s') ?>
                    </div>
                    <div class="col-1">
                        <?= $entry['Username'] ?>

                    </div>
                    <div class="col-1">
                        <?= $entry['IP'] ?>

                    </div>
                    <div class="col-2">
                        <?= $entry['Action'] ?>

                    </div>
                    <div class="col-5">
                        <?= $entry['Description'] ?>

                    </div>
                </div>


                <?php
            }
        } else {
            echo "No results found.";
        }


        ?>

    </div>
<?php


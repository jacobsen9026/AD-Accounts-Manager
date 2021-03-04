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
use System\Lang;

/**
 *  Form Button that opens a modal with a form in it
 */
//$audit = \App\Models\Database\AuditDatabase::getLast24Hrs();

?>
    <h5>

        <?= Lang::get('Audit Log') ?>
    </h5>
    <small>
        <?= Lang::getHelp('Times are in UTC') ?></small>
<?php
$form = new Form('/settings/audit', 'audit');

$exportButton = new \System\App\Forms\FormButton('Export Audit to CSV');
$exportButton->addClientRequest('/api/settings/export/audit/csv?from=' . $this->fromTime . '&to=' . $this->toTime);

$fromTime = new FormDate(Lang::get('From'), null, 'fromTime', $this->fromTime);
$toTime = new FormDate(Lang::get('To'), null, 'toTime', $this->toTime);

//$fromTime->auto();
//$toTime->auto();


$submit = new FormButton(Lang::get('Search'), 'small');
$submit->setType('submit');

$form->addElementToNewRow($fromTime)
    ->addElementToCurrentRow($toTime)
    ->addElementToCurrentRow($submit)
    ->addElementToCurrentRow($exportButton);
echo $form->print();
?>
    <div class="auditLog w-100 bg-light border border-dark" style="min-width:800px;">
        <div class="row ">
            <strong class="col-3 text-center">
                <?= Lang::get('Timestamp') ?>
            </strong>
            <strong class="col-1 text-center">
                <?= Lang::get('Username') ?>
            </strong>
            <strong class="col-1 text-center">
                <?= Lang::get('IP') ?>
            </strong>
            <strong class="col-2 text-center">
                <?= Lang::get('Action') ?>
            </strong>
            <strong class="col-5 text-center">
                <?= Lang::get('Description') ?>
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
                        <?= $timestamp->format('Y-m-d h:i:s A') ?>
                    </div>
                    <div class="col-1">
                        <?= $entry['Username'] ?>

                    </div>
                    <div class="col-1 px-2">
                        <?= $entry['IP'] ?>

                    </div>
                    <div class="col-2 px-2">
                        <?= $entry['Action'] ?>

                    </div>
                    <div class="col-5" style="overflow-wrap: break-word;">
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


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

if (!function_exists('enablePHPErrors')) {

    function enablePHPErrors() {
        /*
          $bt = debug_backtrace(1);
          $caller = array_shift($bt);
          echo $caller['file'] . ":" . $caller["line"]; */
        //error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
    }

}
if (!function_exists('disablePHPErrors')) {

    function disablePHPErrors() {

        //error_reporting(0);
        ini_set('display_errors', FALSE);
        ini_set('display_startup_errors', FALSE);
    }

}
if (!function_exists('backTrace')) {

    function backTrace($startOffset = null) {
        $bt = debug_backtrace(1);
        //var_dump($bt);
        if ($startOffset == null) {
            $caller = $bt[3];
        } else {

            $caller = $bt[$startOffset];
        }

        //var_dump($caller);
        //$caller = array_shift($caller);
        return $caller;
    }

}
if (!function_exists('formTextInput')) {

    function formTextInput($label, $name, $value, $helpText = null, $placeholde = null) {
        ?>
        <div class="form-group p-3 mb-2">
            <label class="font-weight-bold" for="<?= $name; ?>"><?= $label; ?></label>
            <?php
            if (!empty($helpText)) {
                ?>
                <small id="<?= $name; ?>HelpBlock" class="form-text text-muted">
                    <?= $helpText; ?>
                </small>
                <?php
            }
            ?>
            <input class="form-control text-center" name="<?= $name; ?>" value="<?= $value; ?>" placeholder="<?= $placeholde; ?>"/>
        </div>
        <?php
    }

}

if (!function_exists('formBinaryInput')) {

    function formBinaryInput($label, $name, $state, $helpText = null, $helpFunction = null) {
        //var_dump(boolval($state));
        ?>

        <div class="form-group p-3 mb-2">

            <label class="font-weight-bold" for="<?= $name; ?>"><?= $label; ?></label>
            <?php
            if (!empty($helpText)) {
                ?>
                <small id="<?= $name; ?>HelpBlock" class="form-text text-muted">
                    <?php
                    if (!is_null($helpText)) {
                        echo $helpText;
                    }
                    //var_dump($helpFunction);
                    if (!is_null($helpFunction)) {
                        $helpFunction();
                    }
                    ?>
                </small>
                <?php
            }
            ?>
            <div class="row">
                <div class="col-md">

                    <input class="form-control-input" type="radio" name="<?= $name; ?>" value="0" <?php
                    if (!$state) {
                        echo "checked";
                    }
                    ?>/>
                    <label class="" for="<?= $name; ?>">False</label>
                </div>
                <div class="col-md">

                    <input class="form-control-input" type="radio" name="<?= $name; ?>" value="1" <?php
                    if ($state) {
                        echo "checked";
                    }
                    ?>/>
                    <label class="" for="<?= $name; ?>">True</label>
                </div>
            </div>
        </div>
        <?php
    }

}
if (!function_exists('formUpdateButton')) {

    function formUpdateButton() {
        ?>
        <div class="">
            <button class="btn btn-primary" type="submit">Update</button>
        </div>
        <?php
    }

}
if (!function_exists('formTextArea')) {

    function formTextArea($label, $name, $contents = null, $helpText = null, $placeholder = null) {
        //var_dump(boolval($state));
        ?>

        <div  class="row">
            <div class="col-md-5">
                <h3>
                    <?= $label; ?></h3>
                <?php
                if (!empty($helpText)) {
                    ?>
                    <small id="<?= $name; ?>HelpBlock" class="form-text text-muted">
                        <?= $helpText; ?>
                    </small>
                    <?php
                }
                ?>

            </div>
            <div class="col-md-7">


                <textarea placeholder="<?= $placeholder; ?>" class="container container-lg" name="<?= $name; ?>" rows="5" spellcheck="false"><?php
                    if (isset($contents)and is_countable($contents)) {
                        foreach ($contents as $line) {

                            echo $line . "\n";
                        }
                    } else {
                        system\app\AppLogger::get()->warning($label . ' has no contents recieved from the database');
                    }
                    ?></textarea>
            </div>
        </div>
        <?php
    }

}
if (!function_exists('formCheckBox')) {

    function formCheckBox($label = null, $name = null, $state = false, $helpText = null, $helpFunction = null) {
        //var_dump(boolval($state));
        ?>

        <div class = "row">
            <div class = "col-md-5">
                <h3>
                    <?= $label; ?>
                </h3>
                <small>
                    <?php
                    if (!is_null($helpText)) {
                        echo $helpText;
                    }
                    //var_dump($helpFunction);
                    if (!is_null($helpFunction)) {
                        $helpFunction();
                    }
                    ?>
                </small>
            </div>
            <div class="col-md-7">
                <input id="hidden_false_"type="text" name="<?= \app\database\Schema::APP_FORCE_HTTPS ?>" value="false"hidden/>
                <input type="checkbox" class="form-check-input" name="<?= \app\database\Schema::APP_FORCE_HTTPS ?>" value="true" <?php
                       if (\app\models\AppConfig::getForceHTTPS()) {
                           echo "checked";
                       }
                       ?>>
            </div>



        </div>

        <?php
    }

}
?>

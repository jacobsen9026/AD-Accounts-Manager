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

use app\models\view\PermissionMapPrinter;
use app\models\database\PrivilegeLevelDatabase;

/* @var $district District  */
/** @var District $district */
$district = $this->district;

//echo $this->view('layouts/setup_navbar');
?>

<div id="managePrivilegeLevels" class='shadow p-5 '>
    <?= $this->view('settings/district/permissions/privilegeLevels') ?>

</div>

<?php
if (PrivilegeLevelDatabase::get() !== false) {
    ?>
    <div class='shadow p-5 mt-5'>
        <h4>
            District Level Permissions
        </h4>

        <h5>
            Current Permissions
        </h5>
        <?php
        echo PermissionMapPrinter::printDistrictPermissions($district->getId());
        ?>
        <h5>
            Add New Permission
        </h5>
        <?= PermissionMapPrinter::printAddDistrictPermission($district->getId()); ?>
    </div>
    <div class='shadow p-5 mt-5'>
        <h4>
            OU Level Permissions
        </h4>


        <?= PermissionMapPrinter::printOUPermissionTree($district->getADBaseDN()); ?>

    </div>
    <?php
}
?>
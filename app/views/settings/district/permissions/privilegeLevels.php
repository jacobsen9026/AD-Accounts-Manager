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

$district = $this->district;

use App\Models\View\PermissionMapPrinter;

?>
<h4 class="mb-3">
    Manage Privilege Levels
</h4>

<div class="row p-2">
    <div class="col">
        Privilege levels can be assigned at the District or OU level with propagation. So a group with
        permission for a School OU will have that same permission for all sub-OU's, unless explicitly set at a lower
        level.
    </div>
</div>
<div class="row p-2 m-3">
    <div class="col">
        Permissions are different for users or groups, but they both are successive levels of access that inherit the
        previous level.
        A group with Change permission can also Read, but can not Add or Delete (for groups).
    </div>
</div>
<div class="row p-2 m-3">
    <div class="col">
        Privilege Levels with Super Admin enabled have permission to the whole directory as well as settings and setup
        pages.
    </div>
</div>
<div class="row">
    <div class="col">
        <?php echo PermissionMapPrinter::printPrivilegeLevels($district->getId()); ?>
    </div>
</div>
<div class="row">
    <div class="col" id="managePrivilegeLevelsContainer">
        <?php
        echo PermissionMapPrinter::printAddPrivilegeLevelForm($district->getId());
        ?>
    </div>

</div>


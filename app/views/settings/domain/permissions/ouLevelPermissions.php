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

use App\Api\AD;
use App\Models\Database\DomainDatabase;
use App\Models\Database\PermissionMapDatabase;
use App\Models\View\PermissionMapPrinter;
use App\Models\View\Javascript;
use System\App\Forms\Form;

/* @var $domain \App\Models\Domain\Domain */
$domain = $this->domain;


?>
<h4 class="mb-3">
    OU Level Permissions
</h4>

<script>

    $(document).on('click', '.ouPermissionsButton[data-target-ou]', function () {
        console.log("showing permissions for: " + $(this).data('target-ou'));
        let ou = $(this).data('target-ou');

        $("#ouPermissionsContainer").html('<div class="round-loader text-secondary"></div>');
        $.post('/api/settings/domain/permissions', {
            action: "showOULevelPermissions",
            ou: ou,
            csrfToken: "<?=Form::getCSRFToken()?>"
        }, function (data) {

            data = JSON.parse(data);

            $("#ouPermissionsContainer").html(data.output.ajax.html);
            //console.log(data.output.ajax.logs);
            <?=Javascript::$logInjectionScript?>

        });
        $(".ouPermissionsButton[data-target-ou]").removeClass("selected");
        $(this).addClass("selected");


        $(document).ajaxComplete(function (event, data) {
            data = JSON.parse(data.responseText);
            console.log(data);
            let navs = data.output.ajax.ouPermNav;
            navs.forEach(function (nav) {
                console.log(nav);
                console.log(nav.or);
                let ou = nav.ou;
                let count = nav.count;
                let subCount = nav.subCount;
                //console.log($('p[data-target-ou="' + ou + '"]'));
                $('p[data-target-ou="' + ou + '"]').removeClass('text-success');
                if (subCount > 0) {
                    $('p[data-target-ou="' + ou + '"]').addClass('text-success');
                }
                $('div[data-count-ou="' + ou + '"]').addClass('hidden');
                if (count > 0) {
                    $('div[data-count-ou="' + ou + '"]').removeClass('hidden');
                    $('div[data-count-ou="' + ou + '"]').children('p').html(count);
                }


            });
        });


    });


</script>
<div class="row ">
    <div class="col-sm-4 border">
        <div class="permissionCountBadge">
            <p>
                <?php
                echo PermissionMapDatabase::getSubOUPermissionsCount($domain->getAdBaseDN());
                ?>
            </p>
        </div>
        <h5>
            Organizational Units
        </h5>


        <div id="ouNavigationTree">
            <?php
            $ad = AD::get();
            $tree = $ad->getAllSubOUs(DomainDatabase::getAD_BaseDN(1));
            echo PermissionMapPrinter::printOUNavigationTree($tree);
            ?>
        </div>
    </div>
    <div class="col-sm-8 px-4 border" id="ouPermissionsContainer">

    </div>

</div>


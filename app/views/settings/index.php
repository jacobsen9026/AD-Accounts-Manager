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
?>
<script>
    //Highlight changed items on all forms
    $(document).ready(function () {

        $('input').keyup(function () {
            $(this).addClass('text-danger border-danger');

        });

        $('select').change(function () {
            console.log("wpsdafdsa");
            $(this).addClass('border-danger text-danger');

        });

    });
</script>
<h4 class="centered text-center">
    Settings
</h4>
<nav>
    <div class="nav nav-tabs nav-fill nav-justified" id="nav-tab" role="tablist">
        <a class="nav-item nav-link active" id="nav-app-tab" data-toggle="tab" href="#nav-app" role="tab" aria-controls="nav-app" aria-selected="true">Application</a>
        <a class="nav-item nav-link" id="nav-auth-tab" data-toggle="tab" href="#nav-auth" role="tab" aria-controls="nav-auth" aria-selected="false">Authentication</a>
        <a class="nav-item nav-link" id="nav-email-tab" data-toggle="tab" href="#nav-email" role="tab" aria-controls="nav-email" aria-selected="false">Email</a>
        <a class="nav-item nav-link" id="nav-notification-tab" data-toggle="tab" href="#nav-notification" role="tab" aria-controls="nav-notification" aria-selected="false">Notification</a>
    </div>
</nav>
<div class="tab-content pt-5" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-app" role="tabpanel" aria-labelledby="nav-home-tab"><?php echo $this->view('settings/application'); ?></div>
    <div class="tab-pane fade" id="nav-auth" role="tabpanel" aria-labelledby="nav-auth-tab"><?php echo $this->view('settings/authentication'); ?></div>
    <div class="tab-pane fade" id="nav-email" role="tabpanel" aria-labelledby="nav-email-tab"><?php echo $this->view('settings/email'); ?></div>
    <div class="tab-pane fade" id="nav-notification" role="tabpanel" aria-labelledby="nav-notification-tab"><?php echo $this->view('settings/notification'); ?></div>
</div>
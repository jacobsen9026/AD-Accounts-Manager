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


<form method="post" class ="table-hover">
    <div  class="row">
        <div class="col-5">
            <h3>
                Admin Usernames</h3>
            <small>Users in this list will not be able to<br/>be modified via the tools on this site.<br/></small>

        </div>
        <div class="col-7">
            <textarea placeholder="Enter list of usernames, one per line." class="container container-lg" name="adminUsernames" rows="5" spellcheck="false"><?php
                foreach ($appConfig["adminUsernames"] as $admin) {

                    echo $admin . "\n";
                }
                ?></textarea>
        </div>
    </div>


    <div  class="row">
        <div class="col-5">
            <h3>
                Homepage Message</h3>
            <small>Accepts HTML and inline style</small>

        </div>
        <div class="col-7">


            <textarea placeholder="Enter list of emails, one per line." class="container container-lg" name="homepageMessage" rows="5" spellcheck="false"><?php
                foreach ($appConfig["homepageMessage"] as $admin) {

                    echo $admin . "\n";
                }
                ?></textarea>
        </div>
    </div>



    <div  class="row">
        <div class="col-5">
            <h3>
                Redirect to HTTPS
            </h3>
            <small>
                <?php
                //echo  $appConfig["redirectHTTP"];
                //echo $_POST["redirectHTTPCheck"];

                if (isset($appConfig["redirectHTTP"])) {
                    if ($appConfig["redirectHTTP"]) {
                        if (strtolower(explode("=", $_SERVER['HTTPS_SERVER_ISSUER'])[1]) == strtolower($_SERVER['SERVER_NAME'])) {
                            echo "You are using a self-signed certificate.<br/>Understand the risks of allowing this<br/>to be published on the internet.";
                        }
                    } else {
                        echo "You are not using HTTPS.<br/>Understand the risks of allowing this<br/>to be published on the internet.";
                    }
                } else {
                    echo "You are not using HTTPS.<br/>Understand the risks of allowing this<br/>to be published on the internet.";
                }
                ?>
            </small>
        </div>
        <div class="col-7">
            <input type="text" name="redirectHTTP" hidden/>
            <input type="checkbox" class="form-check-input" name="redirectHTTPCheck" value="true" <?php
            //echo  $appConfig["redirectHTTP"];
            if (isset($appConfig["redirectHTTP"])) {
                if ($appConfig["redirectHTTP"]) {
                    echo "checked";
                }
            }
            ?>>
        </div>



    </div>




    <div  class="row">
        <div class="col-5">
            <h3>
                Session Timeout
            </h3>
        </div>
        <div class="col-7">
            <input  placeholder="Time in seconds (eg:1200)" type="text" style="width:25%" name="sessionTimeout" value="<?php echo $appConfig["sessionTimeout"]; ?>"> Seconds
        </div>


    </div>





    <div  class="row">
        <div class="col-5">
            <h3>
                Web App Name
            </h3>
        </div>
        <div class="col-7">
            <input placeholder="Enter Name" type="text" name="webAppName" value="<?php echo $appConfig["webAppName"]; ?>">
        </div>


    </div>

    <div  class="container pt-5"><button type="submit" class="mx-auth btn btn-primary mb-2">Save Settings</button></div>
</form>
<table id="loginPopupContainer">
    <tr>
        <th>
            School Accounts Manager
        </th>
    </tr>
    <tr>
        <td>
            <div style="width:100%;text-align:center;">
                <?php
                if(isset($_POST['badpass'])){

                ?>
                <script>var toast = new iqwerty.toast.Toast('Bad Username/Password');</script>


                <?php

                }
                ?>
                <span id="badPassMessage" style="display:none">Bad Username/Password</span>
                <?php
                if(isset($_POST['notauthorized'])){

                ?>
                <script>var toast = new iqwerty.toast.Toast('Not Authorized');</script>


                <?php

                }
                ?>
                <span id="notAuthorizedMessage" style="display:none">Not Authorized</span>
                <?php
                if(isset($_SESSION)){
                    if(isset($timedOut)){
                        if($timedOut=="true"){
                            $timedOut="";
                            session_unset();
                ?>

                <!--                    <span style="color:black;">Your session has timed out. Please log in again.</span><br/>//-->

                <?php
                        }
                    }
                }
                ?>

                Username
                <input name="intent" value="<?php echo $goto;?>" hidden>
            </div>
            <input <?php if (!isset($_COOKIE["username"])) { ?>autofocus<?php } ?> type="text" name="username" value="<?php if (isset($_COOKIE["username"])) { echo $_COOKIE["username"]; } ?>" autocomplete /><br/>
            Password
            <input id="passwordInput" <?php if (isset($_COOKIE["username"])) { ?>autofocus<?php } ?> type="password" name="password" autocomplete onkeypress="javascript:password_onkeypress(event);" /><br/>

            <br />
            <br /><div style="cursor:hand;" style="width:50%;height:3em;vertical-align:middle;display:inline">
            <text onclick="checkOnPage('rememberUsernameCheckbox')">Remember Username</text> 
            <input id="rememberUsernameCheckbox" style="cursor:hand;width:2em;vertical-align:middle;" type="checkbox" name="rememberUsername" <?php if (isset($_COOKIE["username"])) { echo 'checked=true'; }?>/>
            <?php //print_r($_COOKIE);?>
            </div><br/><br/>
            <button type="button" onclick="reauthenticateSession()">Login</button>
            <!--                <button type="submit" hidden>Login</button>//-->

        </td>
    </tr>

</table>
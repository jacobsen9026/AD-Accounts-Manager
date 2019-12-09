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

namespace system\app\auth;

/**
 * Description of LDAP
 *
 * @author cjacobsen
 */
abstract class LDAP {

    //put your code here
    public function authenticate($username, $password, $domain, $domainController) {
        $adServer = "adserver";
        $domainArray = explode(".", 'domain name');
        $distinguishedName = '';
        foreach ($domainArray as $domain) {
            $distinguishedName = strval($distinguishedName . "DC=" . $domain . ",");
        }
        $distinguishedName = substr($distinguishedName, 0, strlen($distinguishedName) - 1);

        $ldap = ldap_connect($adServer);
        $username = strtolower($username);


        $ldaprdn = 'netbios' . "\\" . $username;

        ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

        $bind = @ldap_bind($ldap, $ldaprdn, $password);

        if ($bind) {
            $filter = "(sAMAccountName=$username)";
            $result = ldap_search($ldap, $distinguishedName, $filter);
            //ldap_sort($ldap,$result,"sn");
            $info = ldap_get_entries($ldap, $result);

            $filterBasic = "(&(sAMAccountName=" . $username . ")(memberOf:1.2.840.113556.1.4.1941:=CN=" . $appConfig["userMappings"]["basic"] . ",OU=System Security Groups," . $distinguishedName . "))";
            $filterPower = "(&(sAMAccountName=" . $username . ")(memberOf:1.2.840.113556.1.4.1941:=CN=" . $appConfig["userMappings"]["power"] . ",OU=System Security Groups," . $distinguishedName . "))";
            $filterAdmin = "(&(sAMAccountName=" . $username . ")(memberOf:1.2.840.113556.1.4.1941:=CN=" . $appConfig["userMappings"]["admin"] . ",OU=System Security Groups," . $distinguishedName . "))";
            $filterTech = "(&(sAMAccountName=" . $username . ")(memberOf:1.2.840.113556.1.4.1941:=CN=" . $appConfig["userMappings"]["tech"] . ",OU=System Security Groups," . $distinguishedName . "))";
            $resultBasic = ldap_search($ldap, $distinguishedName, $filterBasic);
            $resultPower = ldap_search($ldap, $distinguishedName, $filterPower);
            $resultAdmin = ldap_search($ldap, $distinguishedName, $filterAdmin);
            $resultTech = ldap_search($ldap, $distinguishedName, $filterTech);
            $infoBasic = ldap_get_entries($ldap, $resultBasic);
            $infoPower = ldap_get_entries($ldap, $resultPower);
            $infoAdmin = ldap_get_entries($ldap, $resultAdmin);
            $infoTech = ldap_get_entries($ldap, $resultTech);

            //echo "<br/><br/><br/><br/>";
            $passed = false;

            for ($i = 0; $i < $infoBasic["count"]; $i++) {
                //print_r($infoBasic[$i])."<br/><br/><br/>";
                if ($username == strtolower($infoBasic[$i]["samaccountname"][0])) {
                    $passed = true;
                    $_SESSION["authenticated_basic"] = "true";
                    $_SESSION["authenticated_power"] = "false";
                    $_SESSION["authenticated_admin"] = "false";
                    $_SESSION["authenticated_tech"] = "false";

                    $_SESSION["userLastName"] = $infoBasic[$i]["sn"][0];
                    $_SESSION["userFirstName"] = $infoBasic[$i]["givenname"][0];
                }
            }
            for ($i = 0; $i < $infoPower["count"]; $i++) {
                //print_r($infoBasic[$i])."<br/><br/><br/>";
                if ($username == strtolower($infoPower[$i]["samaccountname"][0])) {
                    $passed = true;
                    $_SESSION["authenticated_basic"] = "true";
                    $_SESSION["authenticated_power"] = "true";
                    $_SESSION["authenticated_admin"] = "false";
                    $_SESSION["authenticated_tech"] = "false";
                    $_SESSION["userLastName"] = $infoPower[$i]["sn"][0];
                    $_SESSION["userFirstName"] = $infoPower[$i]["givenname"][0];
                }
            }
            for ($i = 0; $i < $infoAdmin["count"]; $i++) {
                //print_r($infoBasic[$i])."<br/><br/><br/>";
                if ($username == strtolower($infoAdmin[$i]["samaccountname"][0])) {
                    $passed = true;
                    $_SESSION["authenticated_basic"] = "true";
                    $_SESSION["authenticated_power"] = "true";
                    $_SESSION["authenticated_admin"] = "true";
                    $_SESSION["authenticated_tech"] = "false";
                    $_SESSION["userLastName"] = $infoAdmin[$i]["sn"][0];
                    $_SESSION["userFirstName"] = $infoAdmin[$i]["givenname"][0];
                }
            }
            for ($i = 0; $i < $infoTech["count"]; $i++) {
                //print_r($infoBasic[$i])."<br/><br/><br/>";
                if ($username == strtolower($infoTech[$i]["samaccountname"][0])) {
                    $passed = true;
                    $_SESSION["authenticated_basic"] = "true";
                    $_SESSION["authenticated_power"] = "true";
                    $_SESSION["authenticated_admin"] = "true";
                    $_SESSION["authenticated_tech"] = "true";
                    $_SESSION["userLastName"] = $infoTech[$i]["sn"][0];
                    $_SESSION["userFirstName"] = $infoTech[$i]["givenname"][0];
                }
            }
            //exit();

            if ($passed) {
                //echo "authenticated";
                echo "<div id='authenticated'></div>";
                ?>
                <script>reauthenticatedSession();</script>

                <?php
                //exit();
                file_put_contents("./logs/login.log", $date . "," . $time . "," . $username . "\r\n", FILE_APPEND);
                if (isset($_POST["rememberUsername"]) || isset($_POST["rememberMe"])) {
                    //echo "remember me";
                    //$_COOKIE["username"] = $username;
                    setcookie("username", $username, time() + (86400 * 3650));
                }
                if (isset($_POST["rememberMe"])) {
                    //echo "remember me";
                    setcookie("token", json_encode(Array(getSIDHash($username), hash("sha256", $_SESSION["authenticated_basic"]), hash("sha256", $_SESSION["authenticated_power"]), hash("sha256", $_SESSION["authenticated_admin"]), hash("sha256", $_SESSION["authenticated_tech"])), time() + (86400 * 3650)));
                    //echo getSIDHash($username);
                    //exit();
                }

                if ($_POST["intent"] != "") {
                    ?>
                    <script>
                        window.location = "/?goto=<?php echo $_POST['intent']; ?>";
                    </script>
                    <?php
                }
            }
        }
    }

}

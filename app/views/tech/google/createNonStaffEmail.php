<table class="responseContainer" id="container">

    <tr>
        <th>
            Non-Staff New User Creator
        </th>
    </tr>
    <tr>
        <td>
            <?php
            $username = $_POST["username"];

            if ($_POST["firstname"] != "") {
                if ($_POST["lastname"] != "") {
                    if ($_POST["username"] != "") {
                        if ($_POST["password"] != "") {
                            if (!doesGAUserExist($username)) {

                                if ($_POST["category"] == "apple") {
                                    //echo "Central";
                                    $ou = "/Apple ID";

                                }
                                if ($_POST["category"] == "other") {
                                    //echo "Stony Brook";
                                    $ou = "/Other";
                                }
                                if ($_POST["category"] == "system") {
                                    //echo "Whiton";
                                    $ou = "/System Accounts";
                                }
                                //echo $ou;
                                //echo "would run<br/>";
                                $cmd = "gam create user " . $username . " firstname \"" . $_POST["firstname"] . "\" lastname \"" . $_POST["lastname"] . "\" password " . $_POST["password"] . " changepassword off org \"" . $ou . "\"";
                                //echo $cmd;
                                $result = shell_exec($cmd);
                                echo $result;
                                $message = "An account by the name of $username@" . $appConfig["domainName"] . " was created with the password " . $_POST["password"] . "\n\n";
                                sendNotificationEmail("Non-Staff GMail Account Created", $message);
                            } else {
                                echo "An account with that name already exists.";
                            }
                        } else {
                            echo "Missing Password.";
                        }
                    } else {
                        echo "Missing Username.";
                    }
                } else {
                    echo "Missing Last Name.";
                }
            } else {
                echo "Missing First Name.";
            }


            ?>
            <br/><br/>

            <a href='/?goto=/tech/google/index.php'>
                <button type='button' value='Create Another'>Create Another</button>
            </a>
        </td>
    </tr>
</table>
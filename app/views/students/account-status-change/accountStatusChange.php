
<table class="responseContainer">

    <tr>
        <th>
            Student Account Status Manager
        </th>
    </tr>
    <tr>
        <td>
            <?php 
            if ($_POST["username"]!=""){
                $username = $_POST["username"];
                debug(isStudent($username));
                //exit();
                if(isStudent($username)){
                    if(notProtectedUsername($username)){
                        $username = $username;
                        if($_POST["action"]=="unlock"){
                            enableADUserAccount($username);
                            enableGAUserAccount($username);
                            $message=$username." has had their accounts unlocked.";
                            echo $message;
                            sendNotificationEmail("Student Account Unlocked",$message);
                        }elseif ($_POST["action"]=="lock"){
                            disableADUserAccount($username);
                            disableGAUserAccount($username);
                            $message=$username." has had their accounts locked.";
                            echo $message;
                            sendNotificationEmail("Student Account Locked",$message);
                        }

                        echo $result;
                    }else{
                        echo "That user is protected.";
                    }
                }else{
                    echo "This can only be used for students.";
                }


            }else{
                echo "Missing Username.";
            }

            ?>
            <br/><br/>


            <div class="centered">
                <a href='/?goto=/students/account-status-change/index.php'><button type='button' value='Back'>Back</button></a>
            </div>
        </td>
    </tr>
</table>

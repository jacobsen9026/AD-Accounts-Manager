
<table class="responseContainer">

    <tr>
        <th>
            Staff Account Management
        </th>
    </tr>
    <tr>
        <td>
            <?php 

            $username =$_POST["username"];
            if ($_POST["username"]!=""){
                if(!isStudent($username)){
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
                    echo "This can only be used for staff.";
                }
            }else{
                echo "Missing Username.";
            }

            ?>
            <br/><br/>

            <a href='/?goto=/staff/account-status-change/index.php'><button type='button' value='Back'>Back</button></a>
        </td>
    </tr>
</table>

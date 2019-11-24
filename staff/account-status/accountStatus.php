<table class="responseContainer">

    <tr>
        <th>
            Staff Account Status
        </th>
    </tr>
    <tr>
        <td>
            <div class="left">
                <?php 
                if ($_POST["username"]!=""){
                    $username = $_POST["username"];
                    if(notProtectedUsername($username)){


                        if(!doesADUserExist($username)){
                            echo "User not found in Active Directory.";
                            exit();
                        }
                        if(!doesGAUserExist($username)){
                            echo "User not found in Google.";
                            exit();
                        }
                        $GAUser = getGAUser($username);
                        $ADUser = getADUser($username);


                        // GA User Info
                        echo "<br/><div><strong>GOOGLE</strong>";
                        echo "<br/><strong>First Name:</strong> ".$GAUser["First Name"];
                        echo "<br/><strong>Last Name:</strong> ".$GAUser["Last Name"];
                        echo "<br/><strong>Enabled:</strong> ".$GAUser["Account Enabled"];
                        echo "<br/><strong>Last Login:</strong> ".$GAUser["Last login time"];
                        echo "<br/><strong>Groups:</strong><br/> ";
                        foreach ($GAUser["Groups"] as $group){
                            echo $group[0]."<br/>(".$group[1].")<br/>"; 
                        }
                        echo "</div><br/><br/>";





                        // AD User Info
                        echo "<br/><div><strong>".$appConfig["domainNetBIOS"]."</strong>";
                        echo "<br/><strong>First Name:</strong> ".$ADUser['GivenName'];
                        echo "<br/><strong>Last Name:</strong> ".$ADUser['Surname'];
                        echo "<br/><strong>Enabled:</strong> ".$ADUser['Enabled'];
                        echo "<br/><strong>Locked Out:</strong> ".$ADUser['LockedOut'];
                        if($ADUser['HomeDirectory'] == null or $ADUser['HomeDirectory'] == ''){
                            echo "<br/><strong>H Drive Path:</strong> None";
                        }else{
                            echo "<br/><strong>H Drive Path:</strong> ".$ADUser['HomeDirectory'];
                        }

                        echo "<br/><strong>Groups:</strong><br/>";
                        foreach ($ADUser['Groups'] as $group){
                            echo $group."<br/>";
                        }
                        echo ("</div>");
                    }else{
                        echo "That user is protected.";
                    }
                }else{
                    echo "Missing Username.";
                }
                ?>
                <br/><br/>
            </div>
            <div class="centered">
                <a href='/?goto=/staff/account-status/index.php'><button type='' value='Back'>Back</button></a>
            </div>
        </td>
    </tr>
</table>

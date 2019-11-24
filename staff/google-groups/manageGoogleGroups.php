<table class="responseContainer" id="container">

    <tr>
        <th>
            Staff Google Groups Manager
        </th>
    </tr>
    <tr>
        <td sytle="text-align:center;font-size:1em;">
            <?php
            debug($_POST);
            if ($_POST["username"]!=""){
                $username=$_POST["username"];
                if(notProtectedUsername($username)){
                    if($_POST["action"]=="add"){
                        foreach($appConfig["staffEmailGroups"] as $group){
                            if ($_POST[$group]==$group){
                                $output=$output.addGAUserToGroup($username,$group);
                            }
                        }
                        debug($result);




                        echo "<strong>".$username." was added to the following groups:</strong>";
                        echo $output;
                    }else{

                        foreach($appConfig["staffEmailGroups"] as $group){
                            if ($_POST[$group]==$group){
                                debug($group."<br/>");
                                $result = $result.removeGAUserFromGroup($username,$group);
                            }
                        }

                        echo $result;
                    }
                }else{
                    echo "That user is protected.";
                }
            }else{
                echo "Missing Username.";
            }
            ?>
            <br/><br/>

            <a href='/?goto=/staff/google-groups/index.php'><button type='' value='Back'>Back</button></a>
        </td>
    </tr>
</table>

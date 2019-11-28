<table class="responseContainer" id="container">

    <tr>
        <th>
            Parent Google Groups Manager
        </th>
    </tr>
    <tr>
        <td sytle="text-align:center;font-size:1em;">
            <?php 
            $email=$_POST["email"];
            if ($email!=""){
                if($_POST["action"]=="add"){
                    addGAUserToGroup($email,$group);
                    echo "Added ".$email." to the requested groups.";

                }else{

                    removeGAUserFromGroup($email,$group);
                    echo "Removed ".$_POST["email"]." from the requested groups.";

                }

            }else{
                echo "Missing email address.";
            }
            ?>
            <br/><br/>
            <a href='/?goto=/parents/google-groups/manage/index.php'><button type='button' value='Back'>Back</button></a>
        </td>
    </tr>
</table>

<table class="responseContainer" id="container">

    <tr>
        <th>
            Staff Google Group Status
        </th>
    </tr>
    <tr>
        <td sytle="text-align:center;font-size:1em;">
            <?php 
            if ($_POST["username"]!=""){
                if(notProtectedUsername($_POST["username"])){
                    $username = $_POST["username"];
                    printGAStaffGroupsEditable($username);
                }else{
                    echo "That user is protected.";
                }
            }else{
                echo "Missing Username.";
            }
            ?>
            <br/><br/>
            <a href='/?goto=/staff/google-groups/index.php'><button type='button' value='Back'>Back</button></a>
        </td>
    </tr>
</table>

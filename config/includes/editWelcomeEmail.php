<?php

if(isset($_POST["welcomeEmailHTML"])){
    $dateTime=date("Y-m-d_h-i-s");
    copy($_SERVER['DOCUMENT_ROOT']."/config/staffemail.html" ,$_SERVER['DOCUMENT_ROOT']."/config/backup/".$dateTime."_staffemail.html");
   file_put_contents($_SERVER['DOCUMENT_ROOT']."/config/staffemail.html",trim($_POST["welcomeEmailHTML"])) ;

    saveConfig();
}
?>
 <div style="width:90%" class="settingsContainer">
        <form action="<?php echo $pageURL."#ap_input";?>" method="post">
            <table style="width:95%" class="settingsList">
                <tr>
                    <th>
                       Edit Welcome Email
                    </th>

                </tr>
                <tr>

                    <td>
                        <textarea style="width:95%;height:30em" type="text" name="welcomeEmailHTML" value=""><?php echo file_get_contents("./config/staffemail.html");?></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php

                        if(isset($_POST["welcomeEmailHTML"])){
                            echo"<div class='alert'>Welcome Email Updated Succefully!</div>";
                        }
                        ?>
                    </td>
                </tr>

            </table>
            <br/>
            <button id="ap_input" type="submit"  value="Update Welcome Email">Update Welcome Email</button><br/>

        </form>
    </div>



 <iframe style="width:50%;border:groove;min-height:450px;" src="./config/staffemail.html?a=<?php echo rand(0,9999999999);?>">
                Browser not compatible.
            </iframe>
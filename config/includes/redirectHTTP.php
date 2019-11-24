<?php
if(isset($_POST["redirectHTTP"])){
    print_r ($_POST["redirectHTTP"]);
    if($_POST['redirectHTTPCheck']==true){
        $appConfig["redirectHTTP"]=true;
    }else{
        $appConfig["redirectHTTP"]=false;
    }
    //$appConfig["redirectHTTP"] = IsChecked($_POST["redirectHTTP"]) ;

    saveConfig();
}
?>
<div class="shortSettingsContainer">
    <form action="<?php echo $pageURL."#rh_input";?>" method="post">
        <table  class="settingsList">
            <tr>
                <th>
                    Redirect to HTTPS
                </th>

            </tr>
            <tr>

                <td><?php //echo  $appConfig["redirectHTTP"];
                    //echo $_POST["redirectHTTPCheck"];

                    if(isset($appConfig["redirectHTTP"])){
                        if ($appConfig["redirectHTTP"]){
                            if (strtolower(explode("=",$_SERVER['HTTPS_SERVER_ISSUER'])[1])==strtolower($_SERVER['SERVER_NAME'])){
                                echo "You are using a self-signed certificate.<br/>Understand the risks of allowing this<br/>to be published on the internet.";
                               
                            }
                        }else{
                            echo "You are not using HTTPS.<br/>Understand the risks of allowing this<br/>to be published on the internet.";
                        } 
                    }else{
                        echo "You are not using HTTPS.<br/>Understand the risks of allowing this<br/>to be published on the internet.";
                    }  
                    ?>
                    <input type="text" name="redirectHTTP" hidden/>
                    <input type="checkbox"  name="redirectHTTPCheck" value="true" <?php //echo  $appConfig["redirectHTTP"];
                           if(isset($appConfig["redirectHTTP"])){
                               if($appConfig["redirectHTTP"]){
                                   echo "checked";
                               }
                           }
                           ?>>
                </td>
            </tr>
            <tr>
                <td>
                    <?php

                    if(isset($_POST["redirectHTTP"])){
                        echo"<div class='alert'>Redirection Updated Succefully!</div>";
                    }
                    ?>
                </td>
            </tr>

        </table>
        <br/>
        <button id="rh_input" type="submit"  value="Update Redirection">Update Redirection</button><br/>

    </form>
</div>

<table class="responseContainer">
    <tr>
        <th>
            Google Drive Manager
        </th>
    </tr>
    <tr>
        <td>
            <br/><br/>
            <?php
            if(isset($_POST["targetURL"])){
                if(isset($_POST["username"])){
                    if($_POST["targetURL"]!=""){
                        if($_POST["username"]!=""){
                            $username = $_POST["username"];
                            $target = $_POST["targetURL"];
                            if (strpos($target,".google.com/")!=false){
                                if(strpos($target,"/d/")!=false){
                                    //echo strpos($target,"/d/");
                                    $rightTarget = substr($target,strpos($target,"/d/")+3);
                                    //echo $rightTarget;
                                    if(strpos($rightTarget,"/")!=false){
                                        $rightTarget=substr($rightTarget,0,strlen($rightTarget)-(strlen($rightTarget)-strpos($rightTarget,"/")));

                                    }
                                    //echo $rightTarget;
                                    if(addTechDrivePermission($username,$rightTarget,$target)){
            ?>
            <script>

                window.open('<?php echo $target;?>','winname','directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=400,height=350');

            </script>
            Check your popup-blocker or use button below<br/><br/>
            <a target="_blank" href="<?php echo $target;?>">
                <button type="button">Open File</button>
            </a>
            <br/>	<br/>
            <form action="/?goto=/tech/google-drive/removeTechPermission.php" method="post">
                <input name="username" value="<?php echo $username;?>" hidden />
                <input name="targetURL" value="<?php echo $target;?>" hidden />
                <button onclick="showMessege('Removing technology staff access to file please wait...')" type="submit">Remove Permissions</button>

            </form>

            <?php
                                    }else{
                                        echo "There was an error.";

                                    }

                                }else{
                                    echo "That doesn't look like the right link.";
                                }

                            }else{
                                echo "That doesn't look like a Google Drive link!";
                            }
                        }else{
                            echo "Missing username.";
                        }
                    }else{
                        echo "Missing the URL."	;

                    }
                }
            }
            ?>
            <br/>
            <a href="/?goto=/tech/google-drive/index.php">
                <button type="button">Back</button>
            </a>
            <br/><br/>
        </td>
    </tr>
</table>

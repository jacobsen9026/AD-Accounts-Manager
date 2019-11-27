
<table class="responseContainer">

    <tr>
        <th>
            New User Creator
        </th>
    </tr>
    <tr>
        <td>
            <?php 

            if ($_POST["firstname"]!=""){
                if ($_POST["lastname"]!=""){
                    if ($_POST["middlename"]==""){
                        $_POST["middlename"]="NULL";
                    }


                    $yog=getYOGFromGrade($_POST["grade"]);
                    echo "YOG=".$yog;

                    $random2letters = substr(str_shuffle(str_repeat($x='abcdefghijklmnopqrstuvwxyz', ceil(2/strlen($x)) )),1,2);
                    $randomnumber1 = substr(str_shuffle(str_repeat($x='0123456789', ceil(1/strlen($x)) )),1,1);
                    $randomnumber2 = substr(str_shuffle(str_repeat($x='0123456789', ceil(1/strlen($x)) )),1,1);
                    $randomnumber3 = substr(str_shuffle(str_repeat($x='0123456789', ceil(1/strlen($x)) )),1,1);
                    $randomnumber4 = substr(str_shuffle(str_repeat($x='0123456789', ceil(1/strlen($x)) )),1,1);
                    $randomnumber5 = substr(str_shuffle(str_repeat($x='0123456789', ceil(1/strlen($x)) )),1,1);
                    $randomnumber6 = substr(str_shuffle(str_repeat($x='0123456789', ceil(1/strlen($x)) )),1,1);
                    switch ($_POST["grade"]) 
                    { 

                        case "8":
                            $gamOU = "/Schools/BCMS/Students/".$yog;
                            $accountPassword = $random2letters.$randomnumber1.$randomnumber2.$randomnumber3.$randomnumber4.$randomnumber5.$randomnumber6;
                            $forcePasswordChange=false;
                            $gamGroup = "allgrade".$grade."studentsatct@".$appConfig["domainName"];
                            break; 

                        case "7":
                            $gamOU = "/Schools/BCMS/Students/".$yog;
                            $accountPassword = $random2letters.$randomnumber1.$randomnumber2.$randomnumber3.$randomnumber4.$randomnumber5.$randomnumber6;
                            $forcePasswordChange=false;
                            $gamGroup = "allgrade".$grade."studentsatct@".$appConfig["domainName"];
                            break; 

                        case "6":
                            $gamOU = "/Schools/BCMS/Students/".$yog;
                            $accountPassword = $random2letters.$randomnumber1.$randomnumber2.$randomnumber3.$randomnumber4.$randomnumber5.$randomnumber6;
                            $forcePasswordChange=false;
                            $gamGroup = "allgrade".$grade."studentsatct@".$appConfig["domainName"];
                            break; 

                        case "5":
                            $gamOU = "/Schools/SBS/Students/".$yog;
                            $accountPassword = $random2letters.$randomnumber1.$randomnumber2.$randomnumber3.$randomnumber4.$randomnumber5.$randomnumber6;
                            $gamGroup = "allgrade".$grade."studentsatsb@".$appConfig["domainName"];
                            break; 

                        case "4":
                            $gamOU = "/Schools/SBS/Students/".$yog;
                            $accountPassword = $random2letters.$randomnumber1.$randomnumber2.$randomnumber3.$randomnumber4.$randomnumber5.$randomnumber6;
                            $gamGroup = "allgrade".$grade."studentsatsb@".$appConfig["domainName"];
                            break; 

                        case "3":
                            $gamOU = "/Schools/WES/Students/".$yog;
                            $accountPassword = $random2letters.$randomnumber1.$randomnumber2."1234";
                            $gamGroup = "allgrade".$grade."studentsatwt@".$appConfig["domainName"];
                            break;

                        case "2":
                            $gamOU = "/Schools/WES/Students/".$yog;
                            $accountPassword = $random2letters.$randomnumber1.$randomnumber2."1234";
                            $gamGroup = "allgrade".$grade."studentsatwt@".$appConfig["domainName"];
                            break;

                        case "1":
                            $gamOU = "/Schools/WES/Students/".$yog;
                            $accountPassword = $random2letters.$randomnumber1.$randomnumber2."1234";
                            $gamGroup = "allgrade".$grade."studentsatwt@".$appConfig["domainName"];
                            break; 

                        case "K":

                            $gamOU = "/Schools/WES/Students/".$yog;
                            $accountPassword = $random2letters.$randomnumber1.$randomnumber2."1234";
                            $gamGroup = "allkindergartenstudentsatwt@".$appConfig["domainName"];

                            break; 

                        case "PK4":

                            $gamOU = "/Schools/WES/Students/".$yog;
                            $accountPassword = $random2letters.$randomnumber1.$randomnumber2."1234";
                            $gamGroup = "allpre-kindergartenstudentsatwt@".$appConfig["domainName"];
                            break; 

                        case "PK3":

                            $gamOU = "/Schools/WES/Students/".$yog;
                            $accountPassword = $random2letters.$randomnumber1.$randomnumber2."1234";
                            $gamGroup = "allpre-kindergartenstudentsatwt@".$appConfig["domainName"];

                            break; 


                        default:
                            break;
                    }

                    if($_POST["password"]!=""){
                        $accountPassword=$_POST["password"];

                    }
                    $username=substr($yog,2).substr($_POST["firstname"],0,1).$_POST["lastname"];
                    if(strlen($username)>20){
                        $username=substr($username,0,20);
                    }
                    //echo "would run<br/>";
                    if($_POST["create_google"]=="yes" and $_POST["create_ad"]=="yes"){
                        $cmd = "powershell.exe .\students\create-user\CreateUsers.ps1 '".$yog."' '".$_POST["firstname"]."' '".$_POST["middlename"]."' '".$_POST["lastname"]."' '".$_POST["grade"]."' '".$accountPassword."'";
                        echo $cmd;
                        $result = shell_exec($cmd);
                    }elseif($_POST["create_google"]=="yes"){


                        $goCode=false;
                        $result = doesGAUserExist($username);
                        if($result != false){
                            if(strlen($username)>19){
                                $username=substr($username,0,19);
                            }
                            $username=$username."1";
                            //echo $username." checking";
                            $result = doesGAUserExist($username);
                            if($result != false){
                                $username=substr($username,0,strlen($username)-1)."2";
                                //echo $username." checking";
                                $result = doesGAUserExist($username);
                                if($result != false){

                                }else{
                                    $goCode=true;
                                }
                            }else{
                                $goCode=true;
                            }
                        }else{
                            $goCode=true;
                        }
                        if($goCode){
                            if($forcePasswordChange){
                                $cmd = "gam create user ".$username." firstname ".$_POST["firstname"]." lastname ".$_POST["lastname"]." password ".$accountPassword." changepassword on org ".$gamOU;
                            }else{
                                $cmd = "gam create user ".$username." firstname ".$_POST["firstname"]." lastname ".$_POST["lastname"]." password ".$accountPassword." changepassword off org ".$gamOU;
                            }
                            //echo $cmd."<br />";
                            echo $cmd;
                            $result = shell_exec($cmd);
                            echo $result;
                            $cmd = "gam update group ".$gamGroup." add member ".$username;
                            $result2 = shell_exec($cmd);
                            echo $cmd;
                            echo "<br/><br/>Student Credentials Copy to Genesis";
                            echo "<br/><div id='username'>";
                            echo $username;
                            echo "</div><div id='password'>";
                            echo $accountPassword;
                            echo "</div>";
                        }else{
                            echo "Did not make account";
                        }



                    }elseif ( $_POST["create_ad"]=="yes"){
                        $result=createADUserAccount($_POST["firstname"],$_POST["lastname"],$_POST["grade"],$yog,$accountPassword,$forcePasswordChange);

                        echo $appConfig['domainNetBIOS']." only account creation not available yet.";
                    }else{
                        echo "You didn't select a system to add the account to.";

                    }

                    echo $result;
                }else{
                    echo "Missing Last Name.";
                }
            }else{
                echo "Missing First Name.";
            }


            ?>
            <br/><br/>
            <div class="centered">
                <button onclick="CopyToClipboard('username')" type="button" value="Copy Username">Copy Username</button>
            </div>
            <br />
            <div class="centered">
                <button onclick="CopyToClipboard('password')" type="button" value="Copy Password">Copy Password</button>
            </div>
            <br />
            <div class="centered">
                <a href='/?goto=/students/create-user/index.php'><button type='button' value='Create Another'>Create Another</button></a>
            </div>
        </td>
    </tr>
</table>

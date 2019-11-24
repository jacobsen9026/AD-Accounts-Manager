<table class="responseContainer">

    <tr>
        <th>
            Student Google Group Manager
        </th>
    </tr>
    <tr>
        <td sytle="text-align:center;font-size:1em;">
            <?php 
            debug ($_POST);
            if ($_POST["username"]!=""){
                //echo sizeof($_POST["username"]);
                //echo "<br/>".$_POST["username"]."<br/>";

                if(sizeof($_POST["username"])<2 and $_POST["username"]!="Array"){
                    //	echo "single";
                    if(is_array($_POST["username"])){


                        $usernames[0]=$_POST["username"][0];
                        $usernames[1]="";
                        //echo $usernames[0];
                        //echo $usernames[1];
                    }else{
                        $usernames=explode(",",$_POST["username"]);
                    }
                }else{
                    //	echo "multiple";
                    $usernames=$_POST["username"];





                }
                foreach($usernames as $username){
                    //echo $username;
                    //$username=$_POST["username"];
                    if($username!=""){
                        if(substr($username,0,2)>21 and substr($username,0,2)<99){
                            if($_POST["grade"]!="" or $_POST["group"]!=""){
                                switch($_POST["grade"]){

                                    case "8":
                                        $group=$appConfig["studentEmailGroups"]["8"];

                                        break;
                                    case "7":
                                        $group=$appConfig["studentEmailGroups"]["7"];
                                        break;
                                    case "6":
                                        $group=$appConfig["studentEmailGroups"]["6"];
                                        break;
                                    case "5":
                                        $group=$appConfig["studentEmailGroups"]["5"];
                                        break;
                                    case "4":
                                        $group=$appConfig["studentEmailGroups"]["4"];
                                        break;
                                    case "3":
                                        $group=$appConfig["studentEmailGroups"]["3"];
                                        break;
                                    case "2":
                                        $group=$appConfig["studentEmailGroups"]["2"];
                                        break;
                                    case "1":
                                        $group=$appConfig["studentEmailGroups"]["1"];
                                        break;
                                    case "K":
                                        $group=$appConfig["studentEmailGroups"]["K"];
                                        break;
                                    case "PK4":
                                        $group=$appConfig["studentEmailGroups"]["PK4"];
                                        break;
                                    case "PK3":
                                        $group=$appConfig["studentEmailGroups"]["PK3"];
                                        break;



                                }
                                if($_POST["group"]!=""){
                                    $group=$_POST["group"];
                                }			



                                if($_POST["action"]=="add"){


                                    $result = addStudentToGoogleGroup($username,$group);
                                    echo $result;
                                    printGAUserGroupsEditable($username);

                                }else{

                                    $result = removeStudentFromGoogleGroup($username,$group);
                                    echo $result;
                                    printGAUserGroupsEditable($username);

                                }
                            }else{
                                echo "No group selected";
                            }
                        }else{
                            echo "This can only be used for students.";
                        }
                    }

                }
            }else{
                echo "Missing Username.";
            }
            ?>
            <br/><br/>

            <a href='/?goto=/students/google-groups/index.php'><button type='button' value='Back'>Back</button></a>
        </td>
    </tr>
</table>


 <div style="width:90%" class="settingsContainer">
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


            </table>
            <br/>
          
        
    </div>



 <iframe style="width:50%;border:groove;min-height:450px;" src="./config/staffemail.html?a=<?php echo rand(0,9999999999);?>">
                Browser not compatible.
            </iframe>
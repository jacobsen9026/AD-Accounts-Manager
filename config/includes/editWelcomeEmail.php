
<div style="width:90%" class="settingsContainer">
    <table style="width:95%" class="settingsList">
        <tr>
            <th>
                Edit Welcome Email
            </th>

        </tr>
        <tr>

            <td>
                <textarea id="staffEmailEditor" onkeypress="updatePreview(document.getElementById('staffEmailEditor').value);" style="width:95%;height:30em" type="text" name="welcomeEmailHTML" value=""><?php
                    if(file_exists("./config/staffemail.html")){
                        echo file_get_contents("./config/staffemail.html");
                    }else{
                        echo file_get_contents("./config/staffemail.html.example");
                    }

                    ?></textarea>
            </td>
        </tr>


    </table>
    <br/>


</div>

Live Email Preview
<?php
if(file_exists("./config/staffemail.html")){
?>
<iframe src="./config/staffemail.html?a=<?php echo rand(0,9999999999);?>">
    Browser not compatible.
</iframe>
<?php
                                           }else{

?>
<iframe id="staffEmailPreview">
    Browser not compatible.
</iframe>
<script>
    function updatePreview(contents){
        var doc = document.getElementById('staffEmailPreview').contentWindow.document;
        doc.open();
        doc.write(contents);
        doc.close();
    }
    updatePreview(document.getElementById('staffEmailEditor').value);
</script>

<?php
}
?>
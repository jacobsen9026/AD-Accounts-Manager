
<div class="settingsContainer">
    <div class="settingsList">
        <div style="width:100%;">
            <h3>
                Edit Welcome Email
            </h3>
<textarea id="staffEmailEditor" onkeypress="setInterval(function(){updatePreview(document.getElementById('staffEmailEditor').value);},100)" style="width:95%;height:30em" type="text" name="welcomeEmailHTML" value=""><?php
                    if(file_exists("./config/staffemail.html")){
                        echo file_get_contents("./config/staffemail.html");
                    }else{
                        echo file_get_contents("./config/staffemail.html.example");
                    }

                    ?></textarea>
        </div>
        


    </div>


</div>
<br/><br/>
<script>
    function updatePreview(contents){
        var doc = document.getElementById('staffEmailPreview').contentWindow.document;
        doc.open();
        doc.write(contents);
        doc.close();
		
    }
	</script>
<button type="button" onclick='document.getElementById("staffEmailPreview").style.display = "block";'>Show Live Welcome Email Preview</button><br/><br/>

<?php
if(file_exists("./config/staffemail.html")){
?>
<iframe id="staffEmailPreview" src="./config/staffemail.html?a=<?php echo rand(0,9999999999);?>" style="display:none">
    Browser not compatible.
</iframe>
<?php
                                           }else{

?>
<iframe id="staffEmailPreview" style="display:none">
    Browser not compatible.
</iframe>
<script>
    updatePreview(document.getElementById('staffEmailEditor').value);
</script>

<?php
}
?>
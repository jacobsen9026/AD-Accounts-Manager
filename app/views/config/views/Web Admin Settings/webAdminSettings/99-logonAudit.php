<?php

if(file_exists("./app/logs/login.log")){

$auditContents= file_get_contents("./app/logs/login.log");
$auditEntries=explode("\n",$auditContents);

?>
<br/><br/>
<button type="button" onclick='document.getElementById("logonAuditPopup").style.display = "block";'>Show Logon Audit</button><br/><br/>
<div  id="logonAuditPopup" style="overflow-y: scroll;
                                  overflow-x: hidden;
                                  height: 450px;
                                  min-width: 50%;
                                  max-width: 50%;
                                  margin-left: auto;
                                  margin-right: auto;
                                  border:groove;
                                  margin-top:15px;
								  display:none">
    <strong>Logon Audit</strong><br/>
    <div>

        <table style="width:100%">

            <?php
            foreach($auditEntries as $entry){
                echo "<tr>";
                //         echo $entry;
                $values=explode(",",$entry);
                foreach ($values as $value){
                    echo "<td>";
                    echo $value;
                    echo "</td>";

                }
                echo "</tr>";
            }


            ?>
        </table>
    </div>
</div>
<?php
}
?>
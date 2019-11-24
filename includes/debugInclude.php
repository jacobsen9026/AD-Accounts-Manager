<div id="debugIncludeContainer" class="debugIncludeContainer">
    <div id="debugIncludeMessageContainer">

        <div><strong>Debug Include</strong></div>

                <div  id="debugIncludeText" class="debugIncludeText"><?php echo debugArray(get_included_files());?></div>

                <br/>
                <button onclick='document.getElementById("debugIncludeContainer").style="visibility:hidden";'>
                    Close Console
                </button>

    </div>


</div>




<?php





?>
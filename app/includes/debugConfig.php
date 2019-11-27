<div id="debugConfigContainer" class="debugConfigContainer">
    <div id="debugConfigMessageContainer">

        <div><strong>Debug Config</strong></div>

        <div  id="debugConfigText" class="debugConfigText">
			<?php echo debugArray($appConfig);?>
		</div>

        <br/>
        <button onclick='document.getElementById("debugConfigContainer").style="visibility:hidden";'>
            Close Console
        </button>

    </div>


</div>




<?php





?>
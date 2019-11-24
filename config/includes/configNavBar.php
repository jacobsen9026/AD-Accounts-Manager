<table class="configNavContainer">
    <tr>
        <div class="configNavButton">
            <a href="/?goto=/config/index.php&config=webApplicationSettings">
                <button <?php if($config=='webApplicationSettings' or $config==""){echo 'class="currentPageButtonHighlight"';}?> >Web Application Settings</button>
            </a>
        </div>
        <div class="configNavButton">
            <a href="/?goto=/config/index.php&config=webAdminSettings">
                <button <?php
                        if(isset($config)){
                            if($config=='webAdminSettings'){
                                echo 'class="currentPageButtonHighlight"';
                            }
                        }
                        ?> >Web Admin Settings</button>
            </a>
        </div>
        <div class="configNavButton">
            <a href="/?goto=/config/index.php&config=districtSettings">
                <button <?php
                        if(isset($config)){
                            if($config=='districtSettings'){
                                echo 'class="currentPageButtonHighlight"';
                            }
                        }
                        ?> >District Settings</button>
            </a>
        </div>
        <div class="configNavButton">
            <a href="/?goto=/config/index.php&config=googleSettings">
                <button <?php
                        if(isset($config)){
                            if($config=='googleSettings'){
                                echo 'class="currentPageButtonHighlight"';
                            }
                        }
                        ?> >Google Settings</button>
            </a>
        </div>
        <div class="configNavButton">
            <a href="/?goto=/config/index.php&config=adSettings">
                <button <?php
                        if(isset($config)){
                            if($config=='adSettings'){
                                echo 'class="currentPageButtonHighlight"';
                            }
                        }
                        ?> >Active Directory</button>
            </a>
        </div>
        <div class="configNavButton">
            <a href="/?goto=/config/index.php&config=emailSettings">
                <button <?php
                        if(isset($config)){
                            if($config=='emailSettings'){
                                echo 'class="currentPageButtonHighlight"';
                            }
                        }
                        ?> >Email Settings</button>
            </a>
        </div>


    </tr>
</table>
<br/><br/>
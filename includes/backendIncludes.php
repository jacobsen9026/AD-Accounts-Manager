<?php
global $appConfig;

//Include PHP Functions
include($_SERVER['DOCUMENT_ROOT']."/utils/googleFunctions.php");
//include($_SERVER['DOCUMENT_ROOT']."/config/email-groups/gradeMappings.php");
//include($_SERVER['DOCUMENT_ROOT']."/config/siteVariables.php");
//include($_SERVER['DOCUMENT_ROOT']."/config/userMappings.php");
//include($_SERVER['DOCUMENT_ROOT']."/config/email-groups/parentEmailGroups.php");
//require($_SERVER['DOCUMENT_ROOT']."/config/email-groups/staffEmailGroups.php");
//include($_SERVER['DOCUMENT_ROOT']."/config/email-groups/studentEmailGroups.php");
//include($_SERVER['DOCUMENT_ROOT']."/config/adminUsernames.php");
include($_SERVER['DOCUMENT_ROOT']."/utils/adFunctions.php");
include($_SERVER['DOCUMENT_ROOT']."/utils/pageFunctions.php");
include($_SERVER['DOCUMENT_ROOT']."/utils/logicFunctions.php");
include($_SERVER['DOCUMENT_ROOT']."/utils/fileFunctions.php");
loadConfig();
?>
<?php
$cmd = "powershell.exe -Command 'Set-ExecutionPolicy -Scope LocalMachine -ExecutionPolicy Unrestricted -Force'";
echo shell_exec($cmd);
?>
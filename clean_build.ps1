$Answer = Read-Host -Prompt 'Are you sure you want to clean the build of dynamic content? (Y/N)'
if($Answer -eq 'Y' -or $Answer -eq 'y'){
$dir=$PSScriptRoot;
$blackhole = MKDIR $dir\temporary_pit
$blackhole = ROBOCOPY $dir\writable $dir\temporary_pit /s /XF .gitkeep /MOV 
$blackhole = remove-item  $dir'\temporary_pit' -Recurse -Force
Write-Host "Finished"
}
pause
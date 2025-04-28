@echo off
echo This will clean MySQL registry entries. Press any key to continue...
pause

echo Removing MySQL registry entries...
reg delete "HKLM\SYSTEM\CurrentControlSet\Services\MySQL" /f
reg delete "HKLM\SYSTEM\CurrentControlSet\Services\Eventlog\Application\MySQL" /f
reg delete "HKLM\SOFTWARE\MySQL" /f
reg delete "HKCU\Software\MySQL" /f

echo MySQL registry entries have been removed.
echo Please restart your computer.
pause 
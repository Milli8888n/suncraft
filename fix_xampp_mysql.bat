@echo off
echo Fixing XAMPP MySQL configuration...

echo Adding skip-service option to my.ini...
echo. >> "C:\xampp\mysql\bin\my.ini"
echo # Disable MySQL service >> "C:\xampp\mysql\bin\my.ini" 
echo skip-service >> "C:\xampp\mysql\bin\my.ini"

echo Configuration updated.
echo Please restart XAMPP Control Panel.
pause 
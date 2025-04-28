@echo off
echo Starting MySQL in standalone mode...
cd /d C:\xampp\mysql\bin
start /b mysqld.exe --defaults-file="C:\xampp\mysql\bin\my.ini" --standalone
echo MySQL started successfully! 
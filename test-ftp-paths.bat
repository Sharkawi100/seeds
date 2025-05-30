@echo off
echo ========================================
echo Testing FTP Connection and Paths
echo ========================================

"C:\Program Files (x86)\WinSCP\WinSCP.com" ^
  /command ^
    "open ftp://roots_sync%%40iseraj.com:Eron_910138@ftp.iseraj.com/" ^
    "pwd" ^
    "ls" ^
    "exit"

echo ========================================
pause
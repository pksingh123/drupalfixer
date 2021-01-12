@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../fileeye/mimemap/bin/fileeye-mimemap
php "%BIN_TARGET%" %*

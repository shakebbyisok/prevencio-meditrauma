@echo off
setlocal enabledelayedexpansion
chcp 65001 >nul

echo ============================================================
echo   ACTUALIZAR CONFIGURACION PARA POSTGRESQL
echo ============================================================

cd /d C:\Users\Administrador\Desktop\Prevencio\prevencio-meditrauma\current

echo.
echo [1/4] Actualizando DATABASE_URL en .env...

REM Buscar y reemplazar la linea DATABASE_URL
powershell -Command "(Get-Content .env -Raw) -replace 'DATABASE_URL=.*', 'DATABASE_URL=pgsql://prevencion:prevencion123@127.0.0.1:5432/prevencion' | Set-Content .env -NoNewline"

echo       OK - DATABASE_URL actualizado a PostgreSQL

echo.
echo [2/4] Restaurando Logger.php original...
cd ..
git checkout HEAD -- current/src/Logger.php
echo       OK - Logger.php restaurado

echo.
echo [3/4] Limpiando cache...
cd current
rmdir /s /q var\cache 2>nul
mkdir var\cache 2>nul
php bin/console cache:clear --env=prod --no-debug
php bin/console cache:warmup --env=prod --no-debug
echo       OK - Cache limpiado

echo.
echo [4/4] Reiniciando IIS...
iisreset
echo       OK - IIS reiniciado

echo.
echo ============================================================
echo   CONFIGURACION COMPLETADA
echo ============================================================
echo.
echo La aplicacion ahora usa PostgreSQL.
echo Prueba acceder a: http://localhost/index.php/login
echo Usuario: admin
echo Password: admin6291
echo.
pause


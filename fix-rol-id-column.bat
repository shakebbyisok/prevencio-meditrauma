@echo off
setlocal enabledelayedexpansion
chcp 65001 >nul 2>&1
echo.
echo ============================================================
echo   AGREGAR COLUMNAS FALTANTES A LA TABLA fos_user
echo ============================================================
echo.

REM Verificar que Docker y MySQL estén corriendo
docker ps | findstr "prevencio_mysql" >nul 2>&1
if !errorlevel! neq 0 (
    echo [ERROR] MySQL Docker container 'prevencio_mysql' no está corriendo.
    echo         Asegúrate de que Docker Desktop esté iniciado y el contenedor esté activo.
    pause
    exit /b 1
)

echo [INFO] Verificando y agregando columnas faltantes...
echo.

REM Función para verificar y agregar columna
set COLUMNS_ADDED=0

REM Verificar y agregar rol_id
docker exec prevencio_mysql mysql -u root -proot123 -N -e "SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='prevencion' AND TABLE_NAME='fos_user' AND COLUMN_NAME='rol_id';" 2>nul | findstr /C:"0" >nul
if !errorlevel! equ 0 (
    echo [INFO] Agregando columna: rol_id...
    docker exec prevencio_mysql mysql -u root -proot123 -e "USE prevencion; ALTER TABLE fos_user ADD COLUMN rol_id INT NOT NULL DEFAULT 1 AFTER id;" 2>nul
    if !errorlevel! equ 0 (
        echo [OK] Columna rol_id agregada.
        set /a COLUMNS_ADDED+=1
    )
)

REM Verificar y agregar password_mail
docker exec prevencio_mysql mysql -u root -proot123 -N -e "SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='prevencion' AND TABLE_NAME='fos_user' AND COLUMN_NAME='password_mail';" 2>nul | findstr /C:"0" >nul
if !errorlevel! equ 0 (
    echo [INFO] Agregando columna: password_mail...
    docker exec prevencio_mysql mysql -u root -proot123 -e "USE prevencion; ALTER TABLE fos_user ADD COLUMN password_mail VARCHAR(255) NULL AFTER password_changed_at;" 2>nul
    if !errorlevel! equ 0 (
        echo [OK] Columna password_mail agregada.
        set /a COLUMNS_ADDED+=1
    )
)

REM Verificar y agregar host_mail
docker exec prevencio_mysql mysql -u root -proot123 -N -e "SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='prevencion' AND TABLE_NAME='fos_user' AND COLUMN_NAME='host_mail';" 2>nul | findstr /C:"0" >nul
if !errorlevel! equ 0 (
    echo [INFO] Agregando columna: host_mail...
    docker exec prevencio_mysql mysql -u root -proot123 -e "USE prevencion; ALTER TABLE fos_user ADD COLUMN host_mail VARCHAR(255) NULL AFTER password_mail;" 2>nul
    if !errorlevel! equ 0 (
        echo [OK] Columna host_mail agregada.
        set /a COLUMNS_ADDED+=1
    )
)

REM Verificar y agregar puerto_mail
docker exec prevencio_mysql mysql -u root -proot123 -N -e "SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='prevencion' AND TABLE_NAME='fos_user' AND COLUMN_NAME='puerto_mail';" 2>nul | findstr /C:"0" >nul
if !errorlevel! equ 0 (
    echo [INFO] Agregando columna: puerto_mail...
    docker exec prevencio_mysql mysql -u root -proot123 -e "USE prevencion; ALTER TABLE fos_user ADD COLUMN puerto_mail VARCHAR(255) NULL AFTER host_mail;" 2>nul
    if !errorlevel! equ 0 (
        echo [OK] Columna puerto_mail agregada.
        set /a COLUMNS_ADDED+=1
    )
)

REM Verificar y agregar encriptacion_mail
docker exec prevencio_mysql mysql -u root -proot123 -N -e "SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='prevencion' AND TABLE_NAME='fos_user' AND COLUMN_NAME='encriptacion_mail';" 2>nul | findstr /C:"0" >nul
if !errorlevel! equ 0 (
    echo [INFO] Agregando columna: encriptacion_mail...
    docker exec prevencio_mysql mysql -u root -proot123 -e "USE prevencion; ALTER TABLE fos_user ADD COLUMN encriptacion_mail VARCHAR(255) NULL AFTER puerto_mail;" 2>nul
    if !errorlevel! equ 0 (
        echo [OK] Columna encriptacion_mail agregada.
        set /a COLUMNS_ADDED+=1
    )
)

REM Verificar y agregar mail
docker exec prevencio_mysql mysql -u root -proot123 -N -e "SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='prevencion' AND TABLE_NAME='fos_user' AND COLUMN_NAME='mail';" 2>nul | findstr /C:"0" >nul
if !errorlevel! equ 0 (
    echo [INFO] Agregando columna: mail...
    docker exec prevencio_mysql mysql -u root -proot123 -e "USE prevencion; ALTER TABLE fos_user ADD COLUMN mail VARCHAR(255) NULL AFTER encriptacion_mail;" 2>nul
    if !errorlevel! equ 0 (
        echo [OK] Columna mail agregada.
        set /a COLUMNS_ADDED+=1
    )
)

REM Verificar y agregar user_mail
docker exec prevencio_mysql mysql -u root -proot123 -N -e "SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='prevencion' AND TABLE_NAME='fos_user' AND COLUMN_NAME='user_mail';" 2>nul | findstr /C:"0" >nul
if !errorlevel! equ 0 (
    echo [INFO] Agregando columna: user_mail...
    docker exec prevencio_mysql mysql -u root -proot123 -e "USE prevencion; ALTER TABLE fos_user ADD COLUMN user_mail VARCHAR(255) NULL AFTER mail;" 2>nul
    if !errorlevel! equ 0 (
        echo [OK] Columna user_mail agregada.
        set /a COLUMNS_ADDED+=1
    )
)

echo.
if !COLUMNS_ADDED! gtr 0 (
    echo [OK] Se agregaron !COLUMNS_ADDED! columnas nuevas.
) else (
    echo [INFO] Todas las columnas ya existen.
)

echo.
echo ============================================================
echo   COLUMNAS CONFIGURADAS
echo ============================================================
echo.
echo   Las columnas faltantes han sido agregadas a la tabla fos_user.
echo   Ahora puedes ejecutar test-password.bat para verificar.
echo.
pause


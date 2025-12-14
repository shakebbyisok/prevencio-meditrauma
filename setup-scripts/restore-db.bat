@echo off
setlocal enabledelayedexpansion
chcp 65001 >nul 2>&1
echo.
echo ============================================================
echo   RESTAURACION DE BASE DE DATOS - MySQL
echo ============================================================
echo.

cd /d "%~dp0"

REM Verificar Docker
docker ps >nul 2>&1
if !errorlevel! neq 0 (
    echo [ERROR] Docker no esta corriendo
    pause
    exit /b 1
)

REM Verificar que MySQL esta corriendo
docker exec prevencio_mysql mysqladmin ping -h localhost -u root -proot123 >nul 2>&1
if !errorlevel! neq 0 (
    echo [INFO] Iniciando contenedores MySQL...
    docker-compose up -d >nul 2>&1
    echo       Esperando 30 segundos...
    timeout /t 30 /nobreak >nul
)

REM Buscar carpeta BBDDs
set BBDD_PATH=..\BBDDs
if not exist "%BBDD_PATH%" set BBDD_PATH=BBDDs
if not exist "%BBDD_PATH%" (
    echo [ERROR] No se encontro la carpeta BBDDs
    echo         Esperada en: ..\BBDDs o BBDDs
    pause
    exit /b 1
)

echo [INFO] Carpeta BBDDs encontrada: %BBDD_PATH%
echo.

REM Buscar y restaurar el dump principal de prevencion
echo [1/3] Buscando dump de prevencion...
set PREVENCION_DUMP=
for /d %%D in ("%BBDD_PATH%\dump-prevencion*") do (
    for %%F in ("%%D\*.sql") do (
        set PREVENCION_DUMP=%%F
    )
)
if not exist "%BBDD_PATH%\meditruamadb_2025-11-06.sql\meditruamadb_2025-11-06.sql" goto :check_dump
set PREVENCION_DUMP=%BBDD_PATH%\meditruamadb_2025-11-06.sql\meditruamadb_2025-11-06.sql

:check_dump
if not defined PREVENCION_DUMP (
    echo [WARN] No se encontro dump de prevencion
) else (
    echo       Encontrado: %PREVENCION_DUMP%
    echo       Copiando al contenedor...
    docker cp "%PREVENCION_DUMP%" prevencio_mysql:/tmp/dump.sql >nul 2>&1
    echo       Restaurando base de datos prevencion...
    docker exec prevencio_mysql mysql -u root -proot123 -e "CREATE DATABASE IF NOT EXISTS prevencion;" >nul 2>&1
    docker exec prevencio_mysql sh -c "mysql -u root -proot123 --binary-mode --force prevencion < /tmp/dump.sql 2>/dev/null"
    docker exec prevencio_mysql rm -f /tmp/dump.sql >nul 2>&1
    
    REM Verificar tablas
    for /f "tokens=*" %%T in ('docker exec prevencio_mysql mysql -u root -proot123 -N -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '\''prevencion'\'';" 2^>nul') do set TABLE_COUNT=%%T
    echo       OK - %TABLE_COUNT% tablas restauradas
)

echo.
echo [2/3] Buscando dump de stats_meditrauma...
set STATS_DUMP=
for /d %%D in ("%BBDD_PATH%\dump-stats*") do (
    for %%F in ("%%D\*.sql") do (
        set STATS_DUMP=%%F
    )
)
if not defined STATS_DUMP (
    echo [WARN] No se encontro dump de stats_meditrauma
) else (
    echo       Encontrado: %STATS_DUMP%
    docker cp "%STATS_DUMP%" prevencio_mysql_stats:/tmp/dump.sql >nul 2>&1
    docker exec prevencio_mysql_stats mysql -u root -proot123 -e "CREATE DATABASE IF NOT EXISTS stats_meditrauma;" >nul 2>&1
    docker exec prevencio_mysql_stats sh -c "mysql -u root -proot123 --binary-mode --force stats_meditrauma < /tmp/dump.sql 2>/dev/null"
    docker exec prevencio_mysql_stats rm -f /tmp/dump.sql >nul 2>&1
    echo       OK - Restaurado
)

echo.
echo [3/3] Buscando dump de openqueue...
set QUEUE_DUMP=
for /d %%D in ("%BBDD_PATH%\dump-openqueue*") do (
    for %%F in ("%%D\*.sql") do (
        set QUEUE_DUMP=%%F
    )
)
if not defined QUEUE_DUMP (
    echo [WARN] No se encontro dump de openqueue
) else (
    echo       Encontrado: %QUEUE_DUMP%
    docker cp "%QUEUE_DUMP%" prevencio_mysql_queue:/tmp/dump.sql >nul 2>&1
    docker exec prevencio_mysql_queue mysql -u root -proot123 -e "CREATE DATABASE IF NOT EXISTS openqueue;" >nul 2>&1
    docker exec prevencio_mysql_queue sh -c "mysql -u root -proot123 --binary-mode --force openqueue < /tmp/dump.sql 2>/dev/null"
    docker exec prevencio_mysql_queue rm -f /tmp/dump.sql >nul 2>&1
    echo       OK - Restaurado
)

echo.
echo [4/4] Verificando y agregando columnas faltantes en fos_user...
docker exec prevencio_mysql mysql -u root -proot123 -N -e "SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='prevencion' AND TABLE_NAME='fos_user' AND COLUMN_NAME='rol_id';" 2>nul | findstr /C:"0" >nul
if !errorlevel! equ 0 (
    echo       Agregando columna rol_id...
    docker exec prevencio_mysql mysql -u root -proot123 -e "USE prevencion; ALTER TABLE fos_user ADD COLUMN rol_id INT NOT NULL DEFAULT 1 AFTER id;" 2>nul
)
docker exec prevencio_mysql mysql -u root -proot123 -N -e "SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='prevencion' AND TABLE_NAME='fos_user' AND COLUMN_NAME='password_mail';" 2>nul | findstr /C:"0" >nul
if !errorlevel! equ 0 (
    echo       Agregando columnas relacionadas con mail...
    docker exec prevencio_mysql mysql -u root -proot123 -e "USE prevencion; ALTER TABLE fos_user ADD COLUMN password_mail VARCHAR(255) NULL, ADD COLUMN host_mail VARCHAR(255) NULL, ADD COLUMN puerto_mail VARCHAR(255) NULL, ADD COLUMN encriptacion_mail VARCHAR(255) NULL, ADD COLUMN mail VARCHAR(255) NULL, ADD COLUMN user_mail VARCHAR(255) NULL;" 2>nul
)
echo       OK - Columnas verificadas

echo.
echo ============================================================
echo   RESTAURACION COMPLETADA
echo ============================================================
echo.
echo   Base de datos restaurada y columnas verificadas.
echo   Si faltan tablas, ejecuta: create-missing-tables.bat
echo.
pause

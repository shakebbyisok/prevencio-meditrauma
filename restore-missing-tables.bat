@echo off
setlocal enabledelayedexpansion
chcp 65001 >nul 2>&1
echo.
echo ============================================================
echo   RESTAURAR TABLAS FALTANTES
echo ============================================================
echo.

cd /d "%~dp0"

REM Verificar Docker
docker ps | findstr "prevencio_mysql" >nul 2>&1
if !errorlevel! neq 0 (
    echo [ERROR] MySQL Docker container no esta corriendo
    pause
    exit /b 1
)

REM Buscar carpeta BBDDs
set BBDD_PATH=..\BBDDs
if not exist "%BBDD_PATH%" set BBDD_PATH=BBDDs
if not exist "%BBDD_PATH%" (
    echo [ERROR] No se encontro la carpeta BBDDs
    pause
    exit /b 1
)

echo [INFO] Buscando archivo SQL...
set SQL_FILE=%BBDD_PATH%\meditruamadb_2025-11-06.sql\meditruamadb_2025-11-06.sql
if not exist "%SQL_FILE%" (
    echo [ERROR] No se encontro el archivo SQL: %SQL_FILE%
    pause
    exit /b 1
)

echo [INFO] Archivo encontrado: %SQL_FILE%
echo [INFO] Verificando si la tabla 'revision' existe en la base de datos...
docker exec prevencio_mysql mysql -u root -proot123 -N -e "USE prevencion; SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = 'prevencion' AND table_name = 'revision';" 2>nul | findstr /C:"1" >nul
if !errorlevel! equ 0 (
    echo [INFO] La tabla 'revision' ya existe en la base de datos.
    echo        No es necesario restaurar.
    pause
    exit /b 0
)

echo [WARN] La tabla 'revision' NO existe en la base de datos.
echo [INFO] Intentando restaurar desde el dump SQL...
echo        Esto puede tardar varios minutos...

REM Copiar archivo al contenedor
echo [1/3] Copiando archivo SQL al contenedor...
docker cp "%SQL_FILE%" prevencio_mysql:/tmp/dump.sql
if !errorlevel! neq 0 (
    echo [ERROR] Error copiando archivo al contenedor
    pause
    exit /b 1
)

REM Restaurar solo las tablas faltantes (usando --force para ignorar errores de tablas existentes)
echo [2/3] Restaurando base de datos (esto puede tardar varios minutos)...
docker exec prevencio_mysql sh -c "mysql -u root -proot123 --binary-mode --force prevencion < /tmp/dump.sql 2>&1 | grep -v 'already exists' | grep -v 'Duplicate' | head -20"

REM Limpiar archivo temporal
echo [3/3] Limpiando archivos temporales...
docker exec prevencio_mysql rm -f /tmp/dump.sql >nul 2>&1

REM Verificar si la tabla revision existe ahora
echo.
echo [INFO] Verificando si la tabla 'revision' fue restaurada...
docker exec prevencio_mysql mysql -u root -proot123 -N -e "USE prevencion; SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = 'prevencion' AND table_name = 'revision';" 2>nul | findstr /C:"1" >nul
if !errorlevel! equ 0 (
    echo [OK] La tabla 'revision' fue restaurada correctamente.
) else (
    echo [ERROR] La tabla 'revision' aun no existe.
    echo         Puede que no este en el dump SQL o hubo un error durante la restauración.
    echo         Verifica el archivo SQL manualmente.
)

REM Contar tablas totales
for /f "tokens=*" %%T in ('docker exec prevencio_mysql mysql -u root -proot123 -N -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '\''prevencion'\'';" 2^>nul') do set TABLE_COUNT=%%T
echo [INFO] Total de tablas en la base de datos: !TABLE_COUNT!

echo.
echo ============================================================
echo   RESTAURACION COMPLETADA
echo ============================================================
echo.
pause


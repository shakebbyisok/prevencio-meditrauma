@echo off
setlocal enabledelayedexpansion
chcp 65001 >nul 2>&1
echo.
echo ============================================================
echo   CREAR TABLAS FALTANTES USANDO DOCTRINE
echo ============================================================
echo.

cd /d "%~dp0\..\current"

REM Verificar que Docker y MySQL estén corriendo
docker ps | findstr "prevencio_mysql" >nul 2>&1
if !errorlevel! neq 0 (
    echo [ERROR] MySQL Docker container no esta corriendo
    echo         Ejecuta primero: setup-scripts\deploy.bat
    pause
    exit /b 1
)

echo [INFO] Usando Doctrine para crear tablas faltantes...
echo       Esto creara las tablas definidas en las entidades PHP
echo.

REM Verificar si existe bin/console
if not exist "bin\console" (
    echo [ERROR] No se encuentra bin\console
    echo         Asegurate de estar en el directorio correcto
    pause
    exit /b 1
)

REM Ejecutar doctrine:schema:update --force
echo [1/2] Actualizando esquema de base de datos...
php bin/console doctrine:schema:update --force --env=prod
if !errorlevel! neq 0 (
    echo [ERROR] Error ejecutando doctrine:schema:update
    pause
    exit /b 1
)

REM Limpiar cache
echo [2/2] Limpiando cache...
if exist "var\cache\prod" (
    rmdir /s /q "var\cache\prod" >nul 2>&1
)
mkdir "var\cache\prod" >nul 2>&1

REM Verificar si la tabla revision existe ahora
docker exec prevencio_mysql mysql -u root -proot123 -N -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = 'prevencion' AND table_name = 'revision';" 2>nul | findstr /C:"1" >nul
if !errorlevel! equ 0 (
    echo [OK] La tabla 'revision' fue creada correctamente.
) else (
    echo [WARN] La tabla 'revision' aun no existe.
    echo         Puede que haya un error en la definicion de la entidad.
)

REM Contar tablas totales
for /f "tokens=*" %%T in ('docker exec prevencio_mysql mysql -u root -proot123 -N -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '\''prevencion'\'';" 2^>nul') do set TABLE_COUNT=%%T
echo [INFO] Total de tablas en la base de datos: !TABLE_COUNT!

echo.
echo ============================================================
echo   TABLAS CREADAS
echo ============================================================
echo.
echo   Las tablas faltantes han sido creadas usando Doctrine.
echo   Ahora puedes intentar iniciar sesion nuevamente.
echo.
pause


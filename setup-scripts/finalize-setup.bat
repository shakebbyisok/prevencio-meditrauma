@echo off
setlocal enabledelayedexpansion
chcp 65001 >nul 2>&1
echo.
echo ============================================================
echo   FINALIZAR SETUP - CREAR TABLAS FALTANTES
echo ============================================================
echo.
echo   Este script crea las tablas faltantes usando Doctrine
echo   y deja la aplicacion lista para usar.
echo.
echo ============================================================
echo.

cd /d "%~dp0\.."

REM Verificar que Docker y MySQL estén corriendo
docker ps | findstr "prevencio_mysql" >nul 2>&1
if !errorlevel! neq 0 (
    echo [ERROR] MySQL Docker container no esta corriendo
    echo         Ejecuta primero: setup-scripts\deploy.bat
    pause
    exit /b 1
)

echo [1/4] Verificando estado actual de la base de datos...
for /f "tokens=*" %%T in ('docker exec prevencio_mysql mysql -u root -proot123 -N -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '\''prevencion'\'';" 2^>nul') do set TABLE_COUNT=%%T
echo       Tablas actuales: !TABLE_COUNT!

docker exec prevencio_mysql mysql -u root -proot123 -N -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = 'prevencion' AND table_name = 'revision';" 2>nul | findstr /C:"1" >nul
if !errorlevel! equ 0 (
    echo       Tabla 'revision' existe: SI
    set REVISION_EXISTS=1
) else (
    echo       Tabla 'revision' existe: NO
    set REVISION_EXISTS=0
)

echo.
echo [2/5] Eliminando foreign keys problemáticas temporalmente...
REM Eliminar foreign keys que pueden causar conflictos con Doctrine
REM Intentar eliminar las foreign keys (ignorar errores si no existen)
docker exec prevencio_mysql mysql -u root -proot123 -e "USE prevencion; ALTER TABLE fos_user DROP FOREIGN KEY fk_user_centro;" >nul 2>&1
docker exec prevencio_mysql mysql -u root -proot123 -e "USE prevencion; ALTER TABLE fos_user DROP FOREIGN KEY fk_user_servicio;" >nul 2>&1
echo       OK - Foreign keys eliminadas temporalmente (si existían)

echo.
echo [3/5] Creando tablas faltantes usando Doctrine...
cd current

if not exist "bin\console" (
    echo [ERROR] No se encuentra bin\console
    echo         Asegurate de estar en el directorio correcto
    pause
    exit /b 1
)

REM Ejecutar doctrine:schema:update --force
echo       Ejecutando doctrine:schema:update --force...
php bin/console doctrine:schema:update --force --env=prod --no-interaction
if !errorlevel! neq 0 (
    echo [ERROR] Error ejecutando doctrine:schema:update
    echo         Verifica los logs para mas detalles
    pause
    exit /b 1
)

echo       OK - Esquema actualizado

echo.
echo [4/5] Verificando tablas creadas...
for /f "tokens=*" %%T in ('docker exec prevencio_mysql mysql -u root -proot123 -N -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '\''prevencion'\'';" 2^>nul') do set NEW_TABLE_COUNT=%%T
echo       Tablas totales ahora: !NEW_TABLE_COUNT!

docker exec prevencio_mysql mysql -u root -proot123 -N -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = 'prevencion' AND table_name = 'revision';" 2>nul | findstr /C:"1" >nul
if !errorlevel! equ 0 (
    echo       Tabla 'revision' creada: SI
) else (
    echo [WARN] Tabla 'revision' aun no existe
    echo         Puede que haya un error en la definicion de la entidad
)

echo.
echo [5/5] Limpiando cache de Symfony...
if exist "var\cache\prod" (
    rmdir /s /q "var\cache\prod" >nul 2>&1
)
mkdir "var\cache\prod" >nul 2>&1

REM Configurar permisos
icacls "var" /grant "IIS_IUSRS:(OI)(CI)F" /T /Q >nul 2>&1
icacls "var" /grant "IUSR:(OI)(CI)F" /T /Q >nul 2>&1

echo       OK - Cache limpiado y permisos configurados

cd ..

echo.
echo ============================================================
echo   SETUP FINALIZADO
echo ============================================================
echo.
echo   Estado:
echo   - Tablas en base de datos: !NEW_TABLE_COUNT!
echo   - Tabla 'revision': 
if !REVISION_EXISTS! equ 0 (
    docker exec prevencio_mysql mysql -u root -proot123 -N -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = 'prevencion' AND table_name = 'revision';" 2>nul | findstr /C:"1" >nul
    if !errorlevel! equ 0 (
        echo     CREADA
    ) else (
        echo     NO CREADA (verificar entidad Revision.php)
    )
) else (
    echo     YA EXISTIA
)
echo   - Cache limpiado: SI
echo.
echo   La aplicacion deberia estar lista para usar.
echo   Si faltan tablas, verifica las entidades en current\src\Entity\
echo.
echo   Para crear el usuario admin:
echo   setup-scripts\create-admin-user.bat
echo.
echo ============================================================
pause


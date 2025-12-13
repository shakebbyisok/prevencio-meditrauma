@echo off
setlocal enabledelayedexpansion
chcp 65001 >nul 2>&1
echo.
echo ============================================================
echo   AGREGAR COLUMNA rol_id A LA TABLA fos_user
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

echo [INFO] Verificando si la columna rol_id existe...
docker exec prevencio_mysql mysql -u root -proot123 -N -e "SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='prevencion' AND TABLE_NAME='fos_user' AND COLUMN_NAME='rol_id';" 2>nul | findstr /C:"0" >nul
if !errorlevel! equ 0 (
    echo [INFO] La columna rol_id NO existe. Agregándola...
    
    REM Agregar la columna rol_id con valor por defecto 1
    docker exec prevencio_mysql mysql -u root -proot123 -e "USE prevencion; ALTER TABLE fos_user ADD COLUMN rol_id INT NOT NULL DEFAULT 1 AFTER id;" 2>nul
    
    if !errorlevel! equ 0 (
        echo [OK] Columna rol_id agregada correctamente.
        
        REM Actualizar todos los usuarios existentes para que tengan rol_id = 1
        docker exec prevencio_mysql mysql -u root -proot123 -e "USE prevencion; UPDATE fos_user SET rol_id = 1 WHERE rol_id IS NULL OR rol_id = 0;" 2>nul
        
        echo [OK] Valores de rol_id actualizados.
    ) else (
        echo [ERROR] Error al agregar la columna rol_id.
        echo         Verificando si ya existe...
        docker exec prevencio_mysql mysql -u root -proot123 -e "USE prevencion; DESCRIBE fos_user;" 2>nul | findstr /C:"rol_id"
        pause
        exit /b 1
    )
) else (
    echo [INFO] La columna rol_id ya existe.
    docker exec prevencio_mysql mysql -u root -proot123 -e "USE prevencion; DESCRIBE fos_user;" 2>nul | findstr /C:"rol_id"
)

echo.
echo ============================================================
echo   COLUMNA rol_id CONFIGURADA
echo ============================================================
echo.
echo   La columna rol_id ha sido agregada a la tabla fos_user.
echo   Ahora puedes ejecutar test-password.bat para verificar.
echo.
pause


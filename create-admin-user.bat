@echo off
setlocal enabledelayedexpansion
chcp 65001 >nul 2>&1
echo.
echo ============================================================
echo   CREAR USUARIO ADMIN
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
    echo [ERROR] MySQL no esta corriendo
    echo         Ejecuta primero: deploy.bat
    pause
    exit /b 1
)

echo [INFO] Creando usuario admin directamente en MySQL...
echo       Usuario: admin
echo       Contraseña: admin6291
echo.

REM Generar hash de contraseña usando PHP (bcrypt)
echo       Generando hash de contraseña...
for /f "tokens=*" %%H in ('php -r "echo password_hash('admin6291', PASSWORD_BCRYPT);" 2^>nul') do set PASSWORD_HASH=%%H

if not defined PASSWORD_HASH (
    echo [ERROR] No se pudo generar el hash de contraseña
    echo         Verifica que PHP este instalado
    pause
    exit /b 1
)

REM Verificar si el usuario ya existe
docker exec prevencio_mysql mysql -u root -proot123 -N -e "SELECT COUNT(*) FROM prevencion.fos_user WHERE username='admin';" 2>nul | findstr /C:"0" >nul
if !errorlevel! equ 0 (
    echo       Usuario no existe, creando...
    REM Insertar usuario con todos los campos requeridos
    docker exec prevencio_mysql mysql -u root -proot123 -e "USE prevencion; INSERT INTO fos_user (username, username_canonical, email, email_canonical, enabled, salt, password, locked, expired, credentials_expired, roles, created_at, updated_at, centro_id, servicio_id) VALUES ('admin', 'admin', 'admin@prevencio.local', 'admin@prevencio.local', 1, NULL, '%PASSWORD_HASH%', 0, 0, 0, 'a:1:{i:0;s:16:\"ROLE_SUPER_ADMIN\";}', NOW(), NOW(), 1, 1);" 2>nul
    if !errorlevel! equ 0 (
        echo       OK - Usuario creado
        set USER_CREATED=1
    ) else (
        echo [ERROR] Error creando usuario
        echo         Ejecutando comando con salida detallada...
        docker exec prevencio_mysql mysql -u root -proot123 -e "USE prevencion; INSERT INTO fos_user (username, username_canonical, email, email_canonical, enabled, salt, password, locked, expired, credentials_expired, roles, created_at, updated_at, centro_id, servicio_id) VALUES ('admin', 'admin', 'admin@prevencio.local', 'admin@prevencio.local', 1, NULL, '%PASSWORD_HASH%', 0, 0, 0, 'a:1:{i:0;s:16:\"ROLE_SUPER_ADMIN\";}', NOW(), NOW(), 1, 1);"
        pause
        exit /b 1
    )
) else (
    echo       Usuario ya existe, actualizando contraseña...
    docker exec prevencio_mysql mysql -u root -proot123 -e "USE prevencion; UPDATE fos_user SET password='%PASSWORD_HASH%', enabled=1, locked=0, expired=0, credentials_expired=0, roles='a:1:{i:0;s:16:\"ROLE_SUPER_ADMIN\";}' WHERE username='admin';" 2>nul
    if !errorlevel! equ 0 (
        echo       OK - Contraseña actualizada
        set USER_CREATED=1
    ) else (
        echo [ERROR] Error actualizando usuario
        pause
        exit /b 1
    )
)

if defined USER_CREATED (
    echo.
    echo ============================================================
    echo   USUARIO ADMIN CREADO
    echo ============================================================
    echo.
    echo   Usuario: admin
    echo   Contraseña: admin6291
    echo.
    echo   Puedes iniciar sesion en: http://localhost/index.php/login
    echo.
    echo ============================================================
)

pause


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

echo [INFO] Creando usuario admin usando Symfony...
echo       Usuario: admin
echo       Contraseña: admin6291
echo.

REM Ejecutar script PHP usando Symfony
cd current
php ../create-admin-user.php
if !errorlevel! equ 0 (
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
) else (
    echo [ERROR] Error creando usuario
    echo         Verifica que PHP y Composer esten instalados
)
cd ..

pause


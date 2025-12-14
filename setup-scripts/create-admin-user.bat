@echo off
setlocal enabledelayedexpansion
chcp 65001 >nul 2>&1
echo.
echo ============================================================
echo   CREAR USUARIO ADMIN
echo ============================================================
echo.

cd /d "%~dp0\.."

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
    echo         Ejecuta primero: setup-scripts\deploy.bat
    pause
    exit /b 1
)

REM Verificar que PHP esta instalado
php --version >nul 2>&1
if !errorlevel! neq 0 (
    echo [ERROR] PHP no esta instalado o no esta en el PATH
    pause
    exit /b 1
)

REM Verificar que existe el script PHP
if not exist "create-admin-user.php" (
    echo [ERROR] No se encuentra create-admin-user.php
    pause
    exit /b 1
)

echo [INFO] Creando usuario admin usando Symfony encoder...
echo       Usuario: admin
echo       Contraseña: admin6291
echo.

REM Ejecutar script PHP que usa el encoder de Symfony
php setup-scripts\create-admin-user.php
if !errorlevel! neq 0 (
    echo.
    echo [ERROR] Error ejecutando el script PHP
    echo         Verifica que Symfony este correctamente instalado
    pause
    exit /b 1
)

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

pause


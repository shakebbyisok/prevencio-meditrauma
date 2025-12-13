@echo off
setlocal enabledelayedexpansion
echo ========================================
echo Instalador de FastCGI y Configurador de PHP
echo ========================================
echo.
echo Este script instalará FastCGI y configurará PHP en IIS
echo Requiere permisos de Administrador
echo.

REM Verificar permisos de administrador
net session >nul 2>&1
if !errorlevel! neq 0 (
    echo ✗ ERROR: Este script requiere permisos de Administrador
    echo.
    echo Haz clic derecho en este archivo y selecciona "Ejecutar como administrador"
    pause
    exit /b 1
)

set APPCMD=C:\Windows\system32\inetsrv\appcmd.exe
set PHP_DIR=C:\php

echo [1/4] Instalando FastCGI Module...
dism /online /enable-feature /featurename:IIS-CGI /all /norestart
if !errorlevel! equ 0 (
    echo ✓ FastCGI Module instalado
) else (
    echo ⚠ FastCGI puede requerir reinicio o ya está instalado
)

echo.
echo [2/4] Verificando PHP...
if not exist "%PHP_DIR%\php-cgi.exe" (
    echo ✗ ERROR: php-cgi.exe no encontrado en %PHP_DIR%
    echo   Verifica que PHP esté instalado correctamente
    pause
    exit /b 1
)
echo ✓ PHP encontrado en %PHP_DIR%

echo.
echo [3/4] Configurando FastCGI para PHP...
"%APPCMD%" set config -section:system.webServer/fastCgi /+"[fullPath='%PHP_DIR%\php-cgi.exe',monitorChangesTo='php.ini',activityTimeout='600',requestTimeout='600',instanceMaxRequests='10000']" /commit:apphost
if !errorlevel! equ 0 (
    echo ✓ FastCGI configurado para PHP
) else (
    echo ⚠ FastCGI puede que ya esté configurado
)

echo.
echo [4/4] Configurando handler de PHP en el sitio...
set SITE_NAME=PrevencioMeditrauma

REM Eliminar handler existente si existe
"%APPCMD%" set config "%SITE_NAME%" /section:system.webServer/handlers /-"[name='PHP_via_FastCGI']" >nul 2>&1

REM Agregar handler correcto
"%APPCMD%" set config "%SITE_NAME%" /section:system.webServer/handlers /+[name='PHP_via_FastCGI',path='*.php',verb='*',modules='FastCgiModule',scriptProcessor='%PHP_DIR%\php-cgi.exe',resourceType='Either']
if !errorlevel! equ 0 (
    echo ✓ Handler de PHP configurado correctamente
) else (
    echo ✗ Error configurando handler
    echo   Intenta configurarlo manualmente desde IIS Manager
)

echo.
echo Reiniciando IIS...
iisreset /noforce
if !errorlevel! equ 0 (
    echo ✓ IIS reiniciado
) else (
    echo ⚠ Error reiniciando IIS, reinicia manualmente desde IIS Manager
)

echo.
echo ========================================
echo ✓ CONFIGURACIÓN COMPLETADA
echo ========================================
echo.
echo Prueba acceder a: http://localhost/index.php
echo.
pause


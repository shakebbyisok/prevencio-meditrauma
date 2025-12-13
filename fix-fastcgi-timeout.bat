@echo off
setlocal enabledelayedexpansion
chcp 65001 >nul 2>&1
echo.
echo ============================================================
echo   CONFIGURAR TIMEOUT DE FASTCGI
echo ============================================================
echo.

REM Verificar permisos de administrador
net session >nul 2>&1
if !errorlevel! neq 0 (
    echo [ERROR] Este script requiere permisos de Administrador
    echo         Haz clic derecho y selecciona "Ejecutar como administrador"
    pause
    exit /b 1
)

echo [INFO] Configurando timeout de FastCGI en IIS...
echo       Aumentando timeout a 300 segundos (5 minutos)...

REM Aumentar timeout de actividad de FastCGI
%windir%\system32\inetsrv\appcmd.exe set config -section:system.webServer/fastCgi /[fullPath='C:\php\php-cgi.exe'].activityTimeout:300 /commit:apphost >nul 2>&1

REM Aumentar timeout de solicitud de FastCGI
%windir%\system32\inetsrv\appcmd.exe set config -section:system.webServer/fastCgi /[fullPath='C:\php\php-cgi.exe'].requestTimeout:300 /commit:apphost >nul 2>&1

REM Verificar configuración de PHP
where php >nul 2>&1
if !errorlevel! equ 0 (
    for /f "tokens=*" %%p in ('where php') do set PHP_PATH=%%p
    echo       PHP encontrado en: !PHP_PATH!
    
    REM Extraer directorio de PHP
    for %%f in ("!PHP_PATH!") do set PHP_DIR=%%~dpf
    set PHP_CGI=!PHP_DIR!php-cgi.exe
    
    if exist "!PHP_CGI!" (
        echo       Configurando timeout para: !PHP_CGI!
        %windir%\system32\inetsrv\appcmd.exe set config -section:system.webServer/fastCgi /+"[fullPath='!PHP_CGI!'].activityTimeout:300" /commit:apphost >nul 2>&1
        %windir%\system32\inetsrv\appcmd.exe set config -section:system.webServer/fastCgi /+"[fullPath='!PHP_CGI!'].requestTimeout:300" /commit:apphost >nul 2>&1
    )
)

REM Reiniciar IIS
echo [INFO] Reiniciando IIS...
iisreset /restart >nul 2>&1

echo.
echo ============================================================
echo   CONFIGURACION COMPLETADA
echo ============================================================
echo.
echo   Timeout de FastCGI configurado a 300 segundos.
echo   IIS ha sido reiniciado.
echo.
echo   Intenta iniciar sesion nuevamente.
echo.
pause


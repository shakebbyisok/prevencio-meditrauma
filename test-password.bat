@echo off
setlocal enabledelayedexpansion
chcp 65001 >nul 2>&1
echo.
echo ============================================================
echo   VERIFICAR CONTRASEÑA DEL USUARIO ADMIN
echo ============================================================
echo.

cd /d "%~dp0"

echo [INFO] Verificando contraseña del usuario admin...
echo       Usuario: admin
echo       Contraseña a verificar: admin6291
echo.

REM Ejecutar script PHP para verificar la contraseña
php test-password.php

if !errorlevel! equ 0 (
    echo.
    echo ============================================================
    echo   VERIFICACION COMPLETADA
    echo ============================================================
    echo.
    echo   La contraseña del usuario admin es correcta.
    echo   Si aun no puedes iniciar sesion, el problema puede ser:
    echo   - Timeout de FastCGI (ya configurado)
    echo   - Cache corrupto (ejecuta clear-cache.bat)
    echo   - Problema con la sesion de IIS
    echo.
) else (
    echo.
    echo ============================================================
    echo   ERROR EN LA VERIFICACION
    echo ============================================================
    echo.
    echo   La contraseña no coincide o hay un error.
    echo   Ejecuta create-admin-user.bat para recrear el usuario.
    echo.
)

pause


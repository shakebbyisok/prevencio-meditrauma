@echo off
setlocal enabledelayedexpansion
echo ========================================
echo Instalador Manual de Requisitos
echo ========================================
echo.
echo Este script configurará PHP y Composer si ya los tienes descargados
echo O te ayudará a instalarlos manualmente
echo.

set PHP_DIR=C:\php

REM Verificar si PHP ya está instalado
if exist "!PHP_DIR!\php.exe" (
    echo ✓ PHP encontrado en !PHP_DIR!
    echo   Versión:
    "!PHP_DIR!\php.exe" -v
    echo.
    goto :configure_composer
)

echo PHP no encontrado en !PHP_DIR!
echo.
echo OPCIONES:
echo.
echo 1. Si ya descargaste PHP:
echo    - Extrae el ZIP en: !PHP_DIR!
echo    - Luego ejecuta este script de nuevo
echo.
echo 2. Descargar PHP manualmente:
echo    - Abre tu navegador y ve a:
echo      https://windows.php.net/downloads/releases/php-7.4.33-Win32-vc15-x64.zip
echo    - Descarga el archivo
echo    - Extrae todo el contenido en: !PHP_DIR!
echo    - Ejecuta este script de nuevo
echo.
echo 3. Usar PHP desde otra ubicación:
echo    ¿Tienes PHP instalado en otra carpeta? (S/N)
set /p HAS_PHP=
if /i "!HAS_PHP!"=="S" (
    echo   Ingresa la ruta completa donde está php.exe:
    set /p PHP_DIR=
    if not exist "!PHP_DIR!\php.exe" (
        echo   ✗ No se encontró php.exe en esa ubicación
        pause
        exit /b 1
    )
) else (
    echo   Por favor descarga PHP primero usando la opción 2
    pause
    exit /b 0
)

:configure_composer
echo.
echo Configurando PHP...
set PHP_INI=!PHP_DIR!\php.ini
if not exist "!PHP_INI!" (
    if exist "!PHP_DIR!\php.ini-development" (
        copy "!PHP_DIR!\php.ini-development" "!PHP_INI!" >nul
        echo   ✓ php.ini creado
    ) else if exist "!PHP_DIR!\php.ini-production" (
        copy "!PHP_DIR!\php.ini-production" "!PHP_INI!" >nul
        echo   ✓ php.ini creado
    )
)

REM Habilitar extensiones
if exist "!PHP_INI!" (
    echo   Habilitando extensiones necesarias...
    powershell -Command "$content = Get-Content '!PHP_INI!'; $extensions = @('mysqli', 'pdo_mysql', 'mbstring', 'openssl', 'curl', 'fileinfo', 'gd2', 'intl', 'zip'); foreach ($ext in $extensions) { $content = $content -replace (';extension=' + $ext), ('extension=' + $ext) }; $content | Set-Content '!PHP_INI!'; Write-Host '✓ Extensiones habilitadas'"
)

REM Agregar al PATH
echo   Agregando PHP al PATH...
setx PATH "%PATH%;!PHP_DIR!" /M >nul 2>&1
if !errorlevel! equ 0 (
    echo   ✓ PHP agregado al PATH
) else (
    echo   ⚠ No se pudo agregar al PATH (ejecuta como Administrador)
)

REM Instalar Composer
echo.
echo Instalando Composer...
if not exist "!PHP_DIR!\composer.bat" (
    echo   Descargando instalador de Composer...
    set COMPOSER_SETUP=%TEMP%\composer-setup.php
    powershell -Command "Write-Host 'Descargando...' -ForegroundColor Yellow; Invoke-WebRequest -Uri 'https://getcomposer.org/installer' -OutFile '!COMPOSER_SETUP!' -UseBasicParsing -TimeoutSec 30"
    if !errorlevel! equ 0 (
        echo   Instalando...
        "!PHP_DIR!\php.exe" "!COMPOSER_SETUP!" --install-dir="!PHP_DIR!" --filename=composer
        del "!COMPOSER_SETUP!" >nul 2>&1
        if exist "!PHP_DIR!\composer.bat" (
            echo   ✓ Composer instalado
        ) else (
            echo   ✗ Error instalando Composer
        )
    ) else (
        echo   ✗ Error descargando Composer
        echo   Descarga manualmente desde: https://getcomposer.org/download/
    )
) else (
    echo   ✓ Composer ya está instalado
)

echo.
echo ========================================
echo ✓ CONFIGURACIÓN COMPLETADA
echo ========================================
echo.
echo IMPORTANTE: Cierra y vuelve a abrir la ventana de comandos
echo Luego ejecuta: deploy-ready.bat
echo.
pause


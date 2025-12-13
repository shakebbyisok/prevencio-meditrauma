@echo off
setlocal enabledelayedexpansion
echo ========================================
echo Instalador de Requisitos (PHP + Composer)
echo ========================================
echo.
echo Este script instalará:
echo - PHP 7.4 (compatible con Symfony 4.4)
echo - Composer
echo - Extensiones PHP necesarias
echo.

REM Verificar si ya están instalados
where php >nul 2>&1
if !errorlevel! equ 0 (
    echo PHP ya está instalado:
    php -v
    echo.
    set PHP_INSTALLED=1
) else (
    set PHP_INSTALLED=0
)

where composer >nul 2>&1
if !errorlevel! equ 0 (
    echo Composer ya está instalado:
    composer --version
    echo.
    set COMPOSER_INSTALLED=1
) else (
    set COMPOSER_INSTALLED=0
)

if !PHP_INSTALLED! equ 1 if !COMPOSER_INSTALLED! equ 1 (
    echo ✓ PHP y Composer ya están instalados
    echo ¿Deseas reinstalarlos? (S/N)
    set /p REINSTALL=
    if /i not "!REINSTALL!"=="S" (
        echo Instalación cancelada
        pause
        exit /b 0
    )
)

echo.
echo [1/4] Descargando PHP...
set PHP_DIR=C:\php
set PHP_ZIP=%TEMP%\php.zip
set PHP_URL=https://windows.php.net/downloads/releases/php-7.4.33-Win32-vc15-x64.zip

if not exist "!PHP_DIR!" (
    echo   Descargando PHP 7.4.33...
    echo   Esto puede tardar unos minutos...
    powershell -Command "Invoke-WebRequest -Uri '!PHP_URL!' -OutFile '!PHP_ZIP!'"
    if !errorlevel! neq 0 (
        echo   ✗ Error descargando PHP
        echo   Descarga manualmente desde: !PHP_URL!
        echo   Y extráelo en: !PHP_DIR!
        pause
        exit /b 1
    )
    
    echo   Extrayendo PHP...
    if not exist "!PHP_DIR!" mkdir "!PHP_DIR!"
    powershell -Command "Expand-Archive -Path '!PHP_ZIP!' -DestinationPath '!PHP_DIR!' -Force"
    del "!PHP_ZIP!" >nul 2>&1
    
    echo   ✓ PHP extraído en !PHP_DIR!
) else (
    echo   ✓ PHP ya está en !PHP_DIR!
)

REM Configurar php.ini
echo.
echo [2/4] Configurando PHP...
set PHP_INI=!PHP_DIR!\php.ini
if not exist "!PHP_INI!" (
    if exist "!PHP_DIR!\php.ini-development" (
        copy "!PHP_DIR!\php.ini-development" "!PHP_INI!" >nul
        echo   ✓ php.ini creado desde desarrollo
    ) else if exist "!PHP_DIR!\php.ini-production" (
        copy "!PHP_DIR!\php.ini-production" "!PHP_INI!" >nul
        echo   ✓ php.ini creado desde producción
    )
)

REM Habilitar extensiones necesarias
if exist "!PHP_INI!" (
    echo   Habilitando extensiones necesarias...
    powershell -Command "(Get-Content '!PHP_INI!') -replace ';extension=mysqli', 'extension=mysqli' | Set-Content '!PHP_INI!'"
    powershell -Command "(Get-Content '!PHP_INI!') -replace ';extension=pdo_mysql', 'extension=pdo_mysql' | Set-Content '!PHP_INI!'"
    powershell -Command "(Get-Content '!PHP_INI!') -replace ';extension=mbstring', 'extension=mbstring' | Set-Content '!PHP_INI!'"
    powershell -Command "(Get-Content '!PHP_INI!') -replace ';extension=openssl', 'extension=openssl' | Set-Content '!PHP_INI!'"
    powershell -Command "(Get-Content '!PHP_INI!') -replace ';extension=curl', 'extension=curl' | Set-Content '!PHP_INI!'"
    powershell -Command "(Get-Content '!PHP_INI!') -replace ';extension=fileinfo', 'extension=fileinfo' | Set-Content '!PHP_INI!'"
    powershell -Command "(Get-Content '!PHP_INI!') -replace ';extension=gd2', 'extension=gd2' | Set-Content '!PHP_INI!'"
    powershell -Command "(Get-Content '!PHP_INI!') -replace ';extension=intl', 'extension=intl' | Set-Content '!PHP_INI!'"
    powershell -Command "(Get-Content '!PHP_INI!') -replace ';extension=zip', 'extension=zip' | Set-Content '!PHP_INI!'"
    echo   ✓ Extensiones habilitadas
)

REM Agregar PHP al PATH
echo.
echo [3/4] Agregando PHP al PATH del sistema...
setx PATH "%PATH%;!PHP_DIR!" /M >nul 2>&1
if !errorlevel! equ 0 (
    echo   ✓ PHP agregado al PATH del sistema
    echo   ⚠ IMPORTANTE: Cierra y vuelve a abrir la ventana de comandos para que el PATH se actualice
) else (
    echo   ⚠ No se pudo agregar al PATH del sistema (requiere permisos de administrador)
    echo   Agrega manualmente: !PHP_DIR!
    echo   O ejecuta este script como Administrador
)

REM Instalar Composer
echo.
echo [4/4] Instalando Composer...
set COMPOSER_SETUP=%TEMP%\composer-setup.php
set COMPOSER_INSTALLER=https://getcomposer.org/installer

if not exist "!PHP_DIR!\composer.bat" (
    echo   Descargando instalador de Composer...
    powershell -Command "Invoke-WebRequest -Uri '!COMPOSER_INSTALLER!' -OutFile '!COMPOSER_SETUP!'"
    if !errorlevel! neq 0 (
        echo   ✗ Error descargando Composer
        pause
        exit /b 1
    )
    
    echo   Instalando Composer...
    "!PHP_DIR!\php.exe" "!COMPOSER_SETUP!" --install-dir="!PHP_DIR!" --filename=composer
    del "!COMPOSER_SETUP!" >nul 2>&1
    
    if exist "!PHP_DIR!\composer.bat" (
        echo   ✓ Composer instalado
    ) else (
        echo   ✗ Error instalando Composer
        pause
        exit /b 1
    )
) else (
    echo   ✓ Composer ya está instalado
)

echo.
echo ========================================
echo ✓ INSTALACIÓN COMPLETADA
echo ========================================
echo.
echo IMPORTANTE:
echo 1. Cierra esta ventana y abre una nueva para que el PATH se actualice
echo 2. Verifica la instalación ejecutando:
echo    php -v
echo    composer --version
echo.
echo 3. Luego ejecuta deploy-ready.bat de nuevo
echo.
pause


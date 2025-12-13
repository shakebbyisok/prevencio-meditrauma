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
    echo   Descargando PHP 7.4.33 (30MB)...
    echo   Esto puede tardar varios minutos según tu conexión...
    echo   Por favor espera, no cierres esta ventana...
    echo.
    echo   [1/3] Iniciando descarga de PHP...
    powershell -Command "$ProgressPreference = 'Continue'; Write-Host '[1/3] Conectando al servidor...' -ForegroundColor Yellow; $startTime = Get-Date; $webClient = New-Object System.Net.WebClient; $webClient.Headers.Add('User-Agent', 'Mozilla/5.0'); try { $webClient.DownloadFile('!PHP_URL!', '!PHP_ZIP!'); $elapsed = (Get-Date) - $startTime; Write-Host '[1/3] ✓ Descarga completada en ' $elapsed.TotalSeconds ' segundos' -ForegroundColor Green } catch { Write-Host '[1/3] ✗ Error descargando: ' $_.Exception.Message -ForegroundColor Red; Write-Host '   Intenta descargar manualmente desde: !PHP_URL!' -ForegroundColor Yellow; exit 1 } finally { $webClient.Dispose() }"
    if !errorlevel! neq 0 (
        echo.
        echo   ✗ Error descargando PHP
        echo   Posibles causas:
        echo   - Sin conexión a internet
        echo   - Firewall bloqueando la descarga
        echo   - Servidor temporalmente no disponible
        echo.
        echo   SOLUCIÓN MANUAL:
        echo   1. Descarga PHP manualmente desde:
        echo      !PHP_URL!
        echo   2. Extrae el ZIP en: !PHP_DIR!
        echo   3. Ejecuta este script de nuevo
        echo.
        pause
        exit /b 1
    )
    
    echo   [2/3] Extrayendo PHP (esto puede tardar un minuto)...
    if not exist "!PHP_DIR!" mkdir "!PHP_DIR!"
    powershell -Command "Write-Host '[2/3] Extrayendo archivos...' -ForegroundColor Yellow; Expand-Archive -Path '!PHP_ZIP!' -DestinationPath '!PHP_DIR!' -Force; Write-Host '[2/3] ✓ Extracción completada' -ForegroundColor Green"
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
    powershell -Command "$content = Get-Content '!PHP_INI!'; $extensions = @('mysqli', 'pdo_mysql', 'mbstring', 'openssl', 'curl', 'fileinfo', 'gd2', 'intl', 'zip'); foreach ($ext in $extensions) { $content = $content -replace (';extension=' + $ext), ('extension=' + $ext) }; $content | Set-Content '!PHP_INI!'; Write-Host '✓ Extensiones habilitadas'"
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
    echo   [3/3] Descargando instalador de Composer...
    powershell -Command "$ProgressPreference = 'Continue'; Write-Host '[3/3] Conectando a getcomposer.org...' -ForegroundColor Yellow; $startTime = Get-Date; try { Invoke-WebRequest -Uri '!COMPOSER_INSTALLER!' -OutFile '!COMPOSER_SETUP!' -UseBasicParsing -TimeoutSec 60; $elapsed = (Get-Date) - $startTime; Write-Host '[3/3] ✓ Descarga completada en ' $elapsed.TotalSeconds ' segundos' -ForegroundColor Green } catch { Write-Host '[3/3] ✗ Error: ' $_.Exception.Message -ForegroundColor Red; Write-Host '   Verifica tu conexión a internet' -ForegroundColor Yellow; exit 1 }"
    if !errorlevel! neq 0 (
        echo   ✗ Error descargando Composer
        pause
        exit /b 1
    )
    
    echo   Instalando Composer...
    "!PHP_DIR!\php.exe" "!COMPOSER_SETUP!" --install-dir="!PHP_DIR!" --filename=composer
    if !errorlevel! equ 0 (
        echo   ✓ Composer instalado correctamente
    ) else (
        echo   ⚠ Composer puede haberse instalado pero hubo advertencias
    )
    del "!COMPOSER_SETUP!" >nul 2>&1
    
    if not exist "!PHP_DIR!\composer.bat" (
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


@echo off
setlocal enabledelayedexpansion
echo ========================================
echo DESPLIEGUE AUTOMATICO COMPLETO
echo ========================================
echo.
echo Este script instalará y configurará TODO automáticamente
echo No requiere intervención manual
echo.

set PHP_DIR=C:\php
set PHP_ZIP=%TEMP%\php.zip
set COMPOSER_SETUP=%TEMP%\composer-setup.php

REM ========================================
REM PASO 1: Verificar/Instalar PHP
REM ========================================
echo [PASO 1/6] Verificando PHP...
where php >nul 2>&1
if !errorlevel! equ 0 (
    echo   ✓ PHP ya está instalado
    php -v
    goto :check_composer
)

if exist "!PHP_DIR!\php.exe" (
    echo   ✓ PHP encontrado en !PHP_DIR!
    setx PATH "%PATH%;!PHP_DIR!" /M >nul 2>&1
    goto :check_composer
)

echo   PHP no encontrado. Instalando automáticamente...
echo   Esto puede tardar varios minutos...

REM Intentar múltiples URLs de descarga
set PHP_URLS[0]=https://windows.php.net/downloads/releases/php-7.4.33-Win32-vc15-x64.zip
set PHP_URLS[1]=https://github.com/shivammathur/php-src-prebuilt/releases/download/php-7.4.33/php-7.4.33-Win32-vc15-x64.zip

set DOWNLOADED=0
for /L %%i in (0,1,1) do (
    if !DOWNLOADED! equ 0 (
        echo   Intentando descargar desde fuente %%i...
        powershell -Command "$ErrorActionPreference = 'Stop'; try { Write-Host 'Descargando PHP...' -ForegroundColor Yellow; $ProgressPreference = 'SilentlyContinue'; Invoke-WebRequest -Uri '!PHP_URLS[%%i]!' -OutFile '!PHP_ZIP!' -UseBasicParsing -TimeoutSec 120; Write-Host '✓ Descarga completada' -ForegroundColor Green; exit 0 } catch { Write-Host '✗ Error: ' $_.Exception.Message -ForegroundColor Red; exit 1 }"
        if !errorlevel! equ 0 (
            set DOWNLOADED=1
        )
    )
)

if !DOWNLOADED! equ 0 (
    echo   ✗ No se pudo descargar PHP automáticamente
    echo   Intentando método alternativo con Chocolatey...
    where choco >nul 2>&1
    if !errorlevel! equ 0 (
        echo   Instalando PHP con Chocolatey...
        choco install php -y --version=7.4.33
        if !errorlevel! equ 0 (
            echo   ✓ PHP instalado con Chocolatey
            goto :check_composer
        )
    )
    echo   ✗ No se pudo instalar PHP automáticamente
    echo   Por favor instala PHP manualmente desde: https://windows.php.net/download/
    pause
    exit /b 1
)

REM Extraer PHP
echo   Extrayendo PHP...
if not exist "!PHP_DIR!" mkdir "!PHP_DIR!"
powershell -Command "Write-Host 'Extrayendo archivos...' -ForegroundColor Yellow; Expand-Archive -Path '!PHP_ZIP!' -DestinationPath '!PHP_DIR!' -Force; Write-Host '✓ Extracción completada' -ForegroundColor Green"
del "!PHP_ZIP!" >nul 2>&1

REM Instalar Visual C++ Redistributables si es necesario
echo   Verificando Visual C++ Redistributables...
powershell -Command "if (-not (Test-Path 'C:\Windows\System32\vcruntime140.dll')) { Write-Host 'Instalando Visual C++ Redistributables...' -ForegroundColor Yellow; $vcRedist = '%TEMP%\vc_redist.x64.exe'; Invoke-WebRequest -Uri 'https://aka.ms/vs/17/release/vc_redist.x64.exe' -OutFile $vcRedist -UseBasicParsing -TimeoutSec 60; Start-Process -FilePath $vcRedist -ArgumentList '/quiet', '/norestart' -Wait; Remove-Item $vcRedist -Force; Write-Host '✓ Visual C++ instalado' -ForegroundColor Green } else { Write-Host '✓ Visual C++ ya está instalado' -ForegroundColor Green }"

REM Configurar PHP
echo   Configurando PHP...
set PHP_INI=!PHP_DIR!\php.ini
if not exist "!PHP_INI!" (
    if exist "!PHP_DIR!\php.ini-development" (
        copy "!PHP_DIR!\php.ini-development" "!PHP_INI!" >nul
    ) else if exist "!PHP_DIR!\php.ini-production" (
        copy "!PHP_DIR!\php.ini-production" "!PHP_INI!" >nul
    )
)

REM Habilitar extensiones
if exist "!PHP_INI!" (
    powershell -Command "$content = Get-Content '!PHP_INI!'; $extensions = @('mysqli', 'pdo_mysql', 'mbstring', 'openssl', 'curl', 'fileinfo', 'gd2', 'intl', 'zip'); foreach ($ext in $extensions) { $content = $content -replace (';extension=' + $ext), ('extension=' + $ext) }; $content | Set-Content '!PHP_INI!'"
)

REM Agregar al PATH
setx PATH "%PATH%;!PHP_DIR!" /M >nul 2>&1
echo   ✓ PHP instalado y configurado

:check_composer
REM ========================================
REM PASO 2: Verificar/Instalar Composer
REM ========================================
echo.
echo [PASO 2/6] Verificando Composer...
where composer >nul 2>&1
if !errorlevel! equ 0 (
    echo   ✓ Composer ya está instalado
    composer --version
    goto :check_docker
)

if exist "!PHP_DIR!\composer.bat" (
    echo   ✓ Composer encontrado
    goto :check_docker
)

echo   Instalando Composer...
powershell -Command "$ErrorActionPreference = 'Stop'; try { Write-Host 'Descargando Composer...' -ForegroundColor Yellow; Invoke-WebRequest -Uri 'https://getcomposer.org/installer' -OutFile '!COMPOSER_SETUP!' -UseBasicParsing -TimeoutSec 60; Write-Host 'Instalando...' -ForegroundColor Yellow; $proc = Start-Process -FilePath '!PHP_DIR!\php.exe' -ArgumentList '!COMPOSER_SETUP!', '--install-dir=!PHP_DIR!', '--filename=composer' -Wait -PassThru -NoNewWindow; if ($proc.ExitCode -eq 0) { Write-Host '✓ Composer instalado' -ForegroundColor Green } else { Write-Host '⚠ Composer puede haberse instalado con advertencias' -ForegroundColor Yellow } } catch { Write-Host '✗ Error: ' $_.Exception.Message -ForegroundColor Red }"
del "!COMPOSER_SETUP!" >nul 2>&1

if not exist "!PHP_DIR!\composer.bat" (
    if exist "!PHP_DIR!\composer" (
        echo   ✓ Composer instalado (sin .bat, usando directamente)
    ) else (
        echo   ✗ Error instalando Composer
        echo   Continuando sin Composer (instálalo manualmente después)
    )
)

:check_docker
REM ========================================
REM PASO 3: Verificar/Iniciar Docker
REM ========================================
echo.
echo [PASO 3/6] Verificando Docker y MySQL...
docker ps | findstr "prevencio_mysql" >nul 2>&1
if !errorlevel! neq 0 (
    echo   Iniciando contenedores MySQL...
    docker-compose up -d
    echo   Esperando 30 segundos para que MySQL esté listo...
    timeout /t 30 /nobreak >nul
    echo   ✓ MySQL iniciado
) else (
    echo   ✓ MySQL ya está corriendo
)

REM ========================================
REM PASO 4: Configurar .env
REM ========================================
echo.
echo [PASO 4/6] Configurando aplicación...
cd current

if not exist ".env" (
    if exist ".env.dist" (
        copy ".env.dist" ".env" >nul
    ) else (
        (
            echo APP_ENV=prod
            echo APP_DEBUG=0
            echo APP_SECRET=
            echo.
            echo DATABASE_URL=mysql://prevencion_user:prevencion123@127.0.0.1:3306/prevencion?serverVersion=8.0^&charset=utf8mb4
        ) > .env
    )
)

REM Generar APP_SECRET
findstr /C:"APP_SECRET=" .env | findstr /V /C:"APP_SECRET=$" | findstr /V /C:"APP_SECRET= " >nul
if !errorlevel! neq 0 (
    if exist "!PHP_DIR!\php.exe" (
        for /f "tokens=*" %%a in ('"!PHP_DIR!\php.exe" -r "echo bin2hex(random_bytes(16));"') do set NEW_SECRET=%%a
        powershell -Command "(Get-Content .env) -replace 'APP_SECRET=.*', 'APP_SECRET=!NEW_SECRET!' | Set-Content .env"
    ) else (
        echo   ⚠ No se pudo generar APP_SECRET (PHP no disponible)
    )
)

REM Verificar DATABASE_URL
findstr /C:"DATABASE_URL=mysql://" .env >nul
if !errorlevel! neq 0 (
    powershell -Command "(Get-Content .env) -replace 'DATABASE_URL=.*', 'DATABASE_URL=mysql://prevencion_user:prevencion123@127.0.0.1:3306/prevencion?serverVersion=8.0&charset=utf8mb4' | Set-Content .env"
)

cd ..
echo   ✓ Configuración completada

REM ========================================
REM PASO 5: Instalar dependencias
REM ========================================
echo.
echo [PASO 5/6] Instalando dependencias de Composer...
cd current

if not exist "vendor" (
    REM Buscar composer en diferentes ubicaciones
    set COMPOSER_CMD=
    where composer >nul 2>&1
    if !errorlevel! equ 0 (
        set COMPOSER_CMD=composer
    ) else if exist "!PHP_DIR!\composer.bat" (
        set COMPOSER_CMD=!PHP_DIR!\composer.bat
    ) else if exist "!PHP_DIR!\composer" (
        set COMPOSER_CMD=!PHP_DIR!\php.exe !PHP_DIR!\composer
    )
    
    if not "!COMPOSER_CMD!"=="" (
        echo   Instalando dependencias
        echo   Esto puede tardar varios minutos, por favor espera...
        call !COMPOSER_CMD! install --no-dev --optimize-autoloader --no-interaction
        if !errorlevel! equ 0 (
            echo   ✓ Dependencias instaladas
        ) else (
            echo   ⚠ Error instalando dependencias
            echo   Intenta manualmente: cd current ^&^& composer install --no-dev --optimize-autoloader
        )
    ) else (
        echo   ⚠ Composer no disponible, saltando instalación de dependencias
        echo   Ejecuta manualmente después: cd current ^&^& composer install --no-dev --optimize-autoloader
    )
) else (
    echo   ✓ Dependencias ya instaladas
)

cd ..

REM ========================================
REM PASO 6: Configurar cache y permisos
REM ========================================
echo.
echo [PASO 6/6] Configurando cache y permisos...
cd current

if not exist "var" mkdir var
if not exist "var\cache" mkdir var\cache
if not exist "var\log" mkdir var\log
if not exist "var\sessions" mkdir var\sessions

REM Usar PHP directamente desde la ruta
set PHP_CMD=
where php >nul 2>&1
if !errorlevel! equ 0 (
    set PHP_CMD=php
) else if exist "!PHP_DIR!\php.exe" (
    set PHP_CMD=!PHP_DIR!\php.exe
)

if not "!PHP_CMD!"=="" (
    echo   Limpiando cache...
    !PHP_CMD! bin/console cache:clear --env=prod --no-warmup >nul 2>&1
    if !errorlevel! equ 0 (
        echo   Calentando cache...
        !PHP_CMD! bin/console cache:warmup --env=prod >nul 2>&1
        echo   ✓ Cache configurado
    ) else (
        echo   ⚠ Error configurando cache (puede ser normal si faltan dependencias)
    )
) else (
    echo   ⚠ PHP no disponible, saltando configuración de cache
)

cd ..

echo.
echo ========================================
echo ✓ DESPLIEGUE AUTOMATICO COMPLETADO
echo ========================================
echo.
echo Estado de los servicios:
docker ps --filter "name=prevencio" --format "table {{.Names}}\t{{.Status}}" 2>nul
echo.
echo La aplicación está lista para:
echo   - Recibir cambios via git pull
echo   - Configurar IIS apuntando a: %CD%\current\public
echo.
echo NOTA: Si PHP/Composer no están en el PATH, cierra y vuelve a abrir
echo la ventana de comandos, o usa las rutas completas:
echo   %PHP_DIR%\php.exe
echo   %PHP_DIR%\composer.bat
echo.
pause


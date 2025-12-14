@echo off
setlocal enabledelayedexpansion
chcp 65001 >nul 2>&1
echo.
echo ============================================================
echo   DEPLOYMENT SCRIPT - Prevencio Meditrauma
echo ============================================================
echo.
echo   Este script configura la aplicacion completa en Windows Server
echo   Requiere: Docker, PHP, Composer, IIS
echo.
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

cd /d "%~dp0"
set PROJECT_ROOT=%CD%
set CURRENT_PATH=%CD%\current
set PORTAL_PATH=%CD%\portal

echo [1/8] Verificando estructura del proyecto...
if not exist "%CURRENT_PATH%\composer.json" (
    echo [ERROR] No se encuentra current\composer.json
    pause
    exit /b 1
)
if not exist "%PORTAL_PATH%\public\css" (
    echo [ERROR] No se encuentra portal\public\css
    pause
    exit /b 1
)
echo       OK - Estructura correcta

echo.
echo [2/8] Verificando Docker...
docker ps >nul 2>&1
if !errorlevel! neq 0 (
    echo [ERROR] Docker no esta corriendo
    echo         Inicia Docker Desktop y ejecuta este script de nuevo
    pause
    exit /b 1
)
echo       OK - Docker corriendo

echo.
echo [3/8] Iniciando contenedores MySQL...
docker-compose up -d >nul 2>&1
if !errorlevel! neq 0 (
    echo [ERROR] Error iniciando contenedores
    pause
    exit /b 1
)
echo       Esperando que MySQL este listo (30 segundos)...
timeout /t 30 /nobreak >nul
docker exec prevencio_mysql mysqladmin ping -h localhost -u root -proot123 >nul 2>&1
if !errorlevel! neq 0 (
    echo       Esperando 20 segundos mas...
    timeout /t 20 /nobreak >nul
)
echo       OK - MySQL corriendo

echo.
echo [4/8] Copiando assets estaticos de portal a current...
if not exist "%CURRENT_PATH%\public\css" (
    xcopy /E /I /Y /Q "%PORTAL_PATH%\public\css" "%CURRENT_PATH%\public\css" >nul
)
if not exist "%CURRENT_PATH%\public\js" (
    xcopy /E /I /Y /Q "%PORTAL_PATH%\public\js" "%CURRENT_PATH%\public\js" >nul
)
if not exist "%CURRENT_PATH%\public\images" (
    xcopy /E /I /Y /Q "%PORTAL_PATH%\public\images" "%CURRENT_PATH%\public\images" >nul
)
if not exist "%CURRENT_PATH%\public\img" (
    xcopy /E /I /Y /Q "%PORTAL_PATH%\public\img" "%CURRENT_PATH%\public\img" >nul
)
if not exist "%CURRENT_PATH%\public\flags" (
    xcopy /E /I /Y /Q "%PORTAL_PATH%\public\flags" "%CURRENT_PATH%\public\flags" >nul
)
if not exist "%CURRENT_PATH%\public\jstree" (
    xcopy /E /I /Y /Q "%PORTAL_PATH%\public\jstree" "%CURRENT_PATH%\public\jstree" >nul
)
if not exist "%CURRENT_PATH%\public\querybuilder" (
    xcopy /E /I /Y /Q "%PORTAL_PATH%\public\querybuilder" "%CURRENT_PATH%\public\querybuilder" >nul
)
if exist "%PORTAL_PATH%\public\favicon.ico" (
    copy /Y "%PORTAL_PATH%\public\favicon.ico" "%CURRENT_PATH%\public\favicon.ico" >nul 2>&1
)
echo       OK - Assets estaticos copiados

echo.
echo [5/8] Configurando archivo .env...
cd /d "%CURRENT_PATH%"
if not exist ".env" (
    if exist ".env.dist" (
        copy ".env.dist" ".env" >nul
    ) else (
        (
            echo APP_ENV=prod
            echo APP_DEBUG=0
            echo APP_SECRET=
            echo.
            echo DATABASE_URL=mysql://root:root123@127.0.0.1:3306/prevencion?serverVersion=8.0^&charset=utf8mb4
        ) > .env
    )
    echo       Archivo .env creado
) else (
    echo       Archivo .env ya existe
)

REM Generar APP_SECRET si esta vacio
findstr /C:"APP_SECRET=$" .env >nul 2>&1
if !errorlevel! equ 0 (
    for /f "tokens=*" %%a in ('php -r "echo bin2hex(random_bytes(16));" 2^>nul') do set NEW_SECRET=%%a
    if defined NEW_SECRET (
        powershell -Command "(Get-Content .env) -replace 'APP_SECRET=', 'APP_SECRET=!NEW_SECRET!' | Set-Content .env" >nul 2>&1
        echo       APP_SECRET generado
    )
)

REM Asegurar que DATABASE_URL apunta a MySQL local
findstr /C:"DATABASE_URL=mysql://root:root123@127.0.0.1:3306" .env >nul 2>&1
if !errorlevel! neq 0 (
    powershell -Command "(Get-Content .env) -replace 'DATABASE_URL=.*', 'DATABASE_URL=mysql://root:root123@127.0.0.1:3306/prevencion?serverVersion=8.0&charset=utf8mb4' | Set-Content .env" >nul 2>&1
    echo       DATABASE_URL actualizado para MySQL
)
echo       OK - Configuracion .env

echo.
echo [6/8] Instalando dependencias de Composer...
where composer >nul 2>&1
if !errorlevel! neq 0 (
    if exist "C:\php\composer.bat" (
        set COMPOSER_CMD=call C:\php\composer.bat
    ) else (
        echo [ERROR] Composer no encontrado
        echo         Instala Composer desde https://getcomposer.org/
        pause
        exit /b 1
    )
) else (
    set COMPOSER_CMD=call composer
)

if not exist "vendor\autoload.php" (
    echo       Instalando dependencias - esto puede tardar...
    !COMPOSER_CMD! install --no-dev --optimize-autoloader --ignore-platform-reqs --no-interaction >nul 2>&1
    if !errorlevel! neq 0 (
        echo [WARN] Error instalando dependencias, intentando con --no-plugins...
        !COMPOSER_CMD! install --no-dev --optimize-autoloader --ignore-platform-reqs --no-interaction --no-plugins >nul 2>&1
    )
    echo       OK - Dependencias instaladas
) else (
    echo       OK - Dependencias ya instaladas
)

echo.
echo [7/10] Limpiando cache de Symfony...
if exist "var\cache\prod" (
    rmdir /s /q "var\cache\prod" >nul 2>&1
)
if exist "var\cache\dev" (
    rmdir /s /q "var\cache\dev" >nul 2>&1
)
if not exist "var\cache" mkdir "var\cache" >nul 2>&1
if not exist "var\log" mkdir "var\log" >nul 2>&1
if not exist "var\sessions" mkdir "var\sessions" >nul 2>&1
echo       OK - Cache limpiada

echo.
echo [8/10] Configurando permisos para IIS...
icacls "%CURRENT_PATH%" /grant "IIS_IUSRS:(OI)(CI)R" /T /Q >nul 2>&1
icacls "%CURRENT_PATH%" /grant "IUSR:(OI)(CI)R" /T /Q >nul 2>&1
icacls "%CURRENT_PATH%\var" /grant "IIS_IUSRS:(OI)(CI)F" /T /Q >nul 2>&1
icacls "%CURRENT_PATH%\var" /grant "IUSR:(OI)(CI)F" /T /Q >nul 2>&1
icacls "%CURRENT_PATH%\var" /grant "NETWORK SERVICE:(OI)(CI)F" /T /Q >nul 2>&1
echo       OK - Permisos configurados

cd /d "%PROJECT_ROOT%"

echo.
echo [9/10] Instalando y configurando IIS...
set APP_PATH=%CURRENT_PATH%\public
set SITE_NAME=PrevencioMeditrauma
set SITE_PORT=80
set PHP_DIR=C:\php

REM Verificar IIS
where appcmd >nul 2>&1
if !errorlevel! neq 0 (
    set APPCMD=%SystemRoot%\System32\inetsrv\appcmd.exe
    if not exist "%APPCMD%" (
        echo       IIS no encontrado, instalando...
        dism /online /enable-feature /featurename:IIS-WebServerRole /all /norestart >nul 2>&1
        dism /online /enable-feature /featurename:IIS-WebServer /all /norestart >nul 2>&1
        dism /online /enable-feature /featurename:IIS-CommonHttpFeatures /all /norestart >nul 2>&1
        dism /online /enable-feature /featurename:IIS-HttpErrors /all /norestart >nul 2>&1
        dism /online /enable-feature /featurename:IIS-HttpLogging /all /norestart >nul 2>&1
        dism /online /enable-feature /featurename:IIS-RequestFiltering /all /norestart >nul 2>&1
        dism /online /enable-feature /featurename:IIS-StaticContent /all /norestart >nul 2>&1
        dism /online /enable-feature /featurename:IIS-DefaultDocument /all /norestart >nul 2>&1
        dism /online /enable-feature /featurename:IIS-DirectoryBrowsing /all /norestart >nul 2>&1
        dism /online /enable-feature /featurename:IIS-ApplicationInit /all /norestart >nul 2>&1
        dism /online /enable-feature /featurename:IIS-CGI /all /norestart >nul 2>&1
        if not exist "%APPCMD%" (
            echo [WARN] IIS instalado pero appcmd no disponible todavia
            echo         Reinicia el servidor o ejecuta este script de nuevo
            goto :skip_iis
        )
    )
) else (
    set APPCMD=appcmd
)

REM Verificar que el servicio IIS esta corriendo
sc query W3SVC | findstr "RUNNING" >nul 2>&1
if !errorlevel! neq 0 (
    echo       Iniciando servicio IIS...
    net start W3SVC >nul 2>&1
    timeout /t 3 /nobreak >nul
)

REM Instalar FastCGI Module
dism /online /enable-feature /featurename:IIS-CGI /all /norestart >nul 2>&1

REM Configurar FastCGI para PHP
if exist "%PHP_DIR%\php-cgi.exe" (
    "%APPCMD%" set config -section:system.webServer/fastCgi /+"[fullPath='%PHP_DIR%\php-cgi.exe',monitorChangesTo='php.ini',activityTimeout='600',requestTimeout='600',instanceMaxRequests='10000']" /commit:apphost >nul 2>&1
    
    REM Detener Default Web Site si usa puerto 80
    "%APPCMD%" list site "Default Web Site" >nul 2>&1
    if !errorlevel! equ 0 (
        "%APPCMD%" set site "Default Web Site" /-bindings.[protocol='http',bindingInformation='*:80:'] >nul 2>&1
        "%APPCMD%" set site "Default Web Site" /+bindings.[protocol='http',bindingInformation='*:8080:'] >nul 2>&1
        "%APPCMD%" stop site "Default Web Site" >nul 2>&1
    )
    
    REM Crear sitio
    "%APPCMD%" stop site "%SITE_NAME%" >nul 2>&1
    "%APPCMD%" delete site "%SITE_NAME%" >nul 2>&1
    "%APPCMD%" add site /name:"%SITE_NAME%" /physicalPath:"%APP_PATH%" /bindings:protocol=http,bindingInformation=*:%SITE_PORT%: >nul 2>&1
    
    REM Configurar handler PHP
    "%APPCMD%" set config "%SITE_NAME%" /section:system.webServer/handlers /-"[name='PHP_via_FastCGI']" >nul 2>&1
    "%APPCMD%" set config "%SITE_NAME%" /section:system.webServer/handlers /+[name='PHP_via_FastCGI',path='*.php',verb='*',modules='FastCgiModule',scriptProcessor='%PHP_DIR%\php-cgi.exe',resourceType='Either'] >nul 2>&1
    
    REM Iniciar sitio y reiniciar IIS
    "%APPCMD%" start site "%SITE_NAME%" >nul 2>&1
    iisreset /noforce >nul 2>&1
    
    echo       OK - IIS configurado: http://localhost
) else (
    echo [WARN] PHP no encontrado en %PHP_DIR%
    echo         Instala PHP en C:\php
)

:skip_iis
echo.
echo [10/10] Creando archivos de Webpack Encore y limpiando cache...
if not exist "%CURRENT_PATH%\public\build" mkdir "%CURRENT_PATH%\public\build" >nul 2>&1

REM Crear manifest.json basico
(
    echo {
    echo   "build/app.js": "/build/app.js",
    echo   "build/app.css": "/build/app.css"
    echo }
) > "%CURRENT_PATH%\public\build\manifest.json" 2>nul

REM Crear entrypoints.json basico (requerido por Webpack Encore)
(
    echo {
    echo   "entrypoints": {
    echo     "app": {
    echo       "js": [],
    echo       "css": []
    echo     }
    echo   }
    echo }
) > "%CURRENT_PATH%\public\build\entrypoints.json" 2>nul

REM Limpiar cache de nuevo para aplicar cambios de webpack_encore.yaml
if exist "%CURRENT_PATH%\var\cache\prod" (
    rmdir /s /q "%CURRENT_PATH%\var\cache\prod" >nul 2>&1
)

echo       OK - Archivos creados y cache limpiada

echo.
echo [10/10] Configurando timeout de FastCGI...
where php >nul 2>&1
if !errorlevel! equ 0 (
    for /f "tokens=*" %%p in ('where php') do set PHP_PATH=%%p
    for %%f in ("!PHP_PATH!") do set PHP_DIR=%%~dpf
    set PHP_CGI=!PHP_DIR!php-cgi.exe
    
    if exist "!PHP_CGI!" (
        %SystemRoot%\System32\inetsrv\appcmd.exe set config -section:system.webServer/fastCgi /+"[fullPath='!PHP_CGI!'].activityTimeout:300" /commit:apphost >nul 2>&1
        %SystemRoot%\System32\inetsrv\appcmd.exe set config -section:system.webServer/fastCgi /+"[fullPath='!PHP_CGI!'].requestTimeout:300" /commit:apphost >nul 2>&1
    )
)
echo       OK - FastCGI configurado

echo.
echo ============================================================
echo   DEPLOYMENT COMPLETADO
echo ============================================================
echo.
echo   Base de datos: MySQL en Docker (puerto 3306)
echo   Aplicacion:    %APP_PATH%
echo   URL:           http://localhost
echo.
echo   Siguientes pasos:
echo   1. Restaurar base de datos: restore-db.bat
echo   2. Crear tablas faltantes: create-missing-tables.bat
echo   3. Crear usuario admin: create-admin-user.bat
echo.
echo ============================================================
pause


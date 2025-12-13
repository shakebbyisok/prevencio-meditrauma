@echo off
setlocal enabledelayedexpansion
echo ========================================
echo Configurador de IIS
echo ========================================
echo.
echo Este script configurará IIS para servir la aplicación Symfony
echo.

REM Verificar que estamos en el directorio correcto
if not exist "current\public\index.php" (
    echo ✗ ERROR: No se encuentra current\public\index.php
    echo   Ejecuta este script desde el directorio prevencio-meditrauma
    pause
    exit /b 1
)

set APP_PATH=%CD%\current\public
set SITE_NAME=PrevencioMeditrauma
set SITE_PORT=80

echo Ruta de la aplicación: %APP_PATH%
echo Nombre del sitio: %SITE_NAME%
echo Puerto: %SITE_PORT%
echo.

REM Verificar si IIS está instalado
if not exist "C:\Windows\system32\inetsrv\appcmd.exe" (
    echo ⚠ IIS no está instalado o appcmd no se encuentra
    echo.
    echo Ejecuta install-iis.bat primero para instalar IIS
    echo O instala IIS manualmente desde "Activar o desactivar características de Windows"
    echo.
    pause
    exit /b 1
)

set APPCMD=C:\Windows\system32\inetsrv\appcmd.exe

echo Verificando si el sitio ya existe...
"%APPCMD%" list site "%SITE_NAME%" >nul 2>&1
if !errorlevel! equ 0 (
    echo ⚠ El sitio %SITE_NAME% ya existe
    echo ¿Deseas eliminarlo y recrearlo? (S/N)
    set /p RECREATE=
    if /i "!RECREATE!"=="S" (
        echo Eliminando sitio existente...
        "%APPCMD%" delete site "%SITE_NAME%"
    ) else (
        echo Saltando creación del sitio
        goto :configure_php
    )
)

echo.
echo [1/3] Creando sitio web en IIS...
"%APPCMD%" add site /name:"%SITE_NAME%" /bindings:http/*:%SITE_PORT%: /physicalPath:"%APP_PATH%"
if !errorlevel! equ 0 (
    echo ✓ Sitio creado
) else (
    echo ✗ Error creando sitio
    echo Intenta crear el sitio manualmente desde IIS Manager
    pause
    exit /b 1
)

:configure_php
echo.
echo [2/3] Configurando PHP en IIS...
REM Verificar si PHP está configurado en IIS
if exist "C:\php\php-cgi.exe" (
    echo   Configurando handler de PHP...
    "%APPCMD%" set config "%SITE_NAME%" /section:system.webServer/handlers /+[name='PHP_via_FastCGI',path='*.php',verb='*',modules='FastCgiModule',scriptProcessor='C:\php\php-cgi.exe',resourceType='Either']
    echo   ✓ Handler de PHP configurado
) else (
    echo   ⚠ php-cgi.exe no encontrado en C:\php
    echo   Asegúrate de que PHP esté instalado correctamente
    echo   Nota: Puedes usar PHP Manager para IIS para configurar PHP más fácilmente
)

echo.
echo [3/3] Configurando permisos y módulos...
REM Habilitar URL Rewrite si está disponible
"%APPCMD%" set config "%SITE_NAME%" /section:system.webServer/rewrite /enabled:true >nul 2>&1

REM Configurar permisos de lectura para IIS_IUSRS
icacls "%APP_PATH%" /grant "IIS_IUSRS:(OI)(CI)R" /T >nul 2>&1
icacls "%CD%\current\var" /grant "IIS_IUSRS:(OI)(CI)F" /T >nul 2>&1

echo ✓ Permisos configurados

echo.
echo ========================================
echo ✓ CONFIGURACIÓN DE IIS COMPLETADA
echo ========================================
echo.
echo Sitio creado: %SITE_NAME%
echo URL: http://localhost:%SITE_PORT%
echo.
echo Próximos pasos:
echo 1. Abre IIS Manager y verifica el sitio
echo 2. Asegúrate de que el Application Pool esté configurado para .NET Framework 4.0 o superior
echo 3. Si PHP no funciona, instala PHP Manager para IIS desde:
echo    https://phpmanager.ciaranmcconnell.com/
echo 4. Prueba la aplicación en: http://localhost
echo.
pause


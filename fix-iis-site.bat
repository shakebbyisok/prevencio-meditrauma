@echo off
setlocal enabledelayedexpansion
echo ========================================
echo Verificador y Corrector de Sitio IIS
echo ========================================
echo.

set APPCMD=C:\Windows\system32\inetsrv\appcmd.exe
set SITE_NAME=PrevencioMeditrauma

echo Verificando estado del sitio %SITE_NAME%...
"%APPCMD%" list site "%SITE_NAME%"

echo.
echo Estado del sitio:
"%APPCMD%" list site "%SITE_NAME%" /state

echo.
echo [1/3] Iniciando sitio si está detenido...
"%APPCMD%" start site "%SITE_NAME%"
if !errorlevel! equ 0 (
    echo ✓ Sitio iniciado
) else (
    echo ⚠ El sitio puede que ya esté iniciado o hubo un error
)

echo.
echo [2/3] Verificando bindings...
"%APPCMD%" list site "%SITE_NAME%" /config | findstr "binding"

echo.
echo [3/3] Configurando como sitio por defecto...
REM Detener el sitio por defecto
"%APPCMD%" stop site "Default Web Site" >nul 2>&1
echo ✓ Sitio por defecto detenido

echo.
echo Verificando que el sitio esté funcionando...
"%APPCMD%" list site "%SITE_NAME%" /state

echo.
echo ========================================
echo ✓ CONFIGURACIÓN COMPLETADA
echo ========================================
echo.
echo El sitio %SITE_NAME% debería estar disponible en:
echo   http://localhost
echo.
echo Si aún ves la página de bienvenida de IIS:
echo 1. Limpia la caché del navegador (Ctrl+F5)
echo 2. Verifica en IIS Manager que el sitio esté "Started"
echo 3. Asegúrate de que el puerto 80 esté asignado correctamente
echo.
pause


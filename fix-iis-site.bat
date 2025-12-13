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
echo [1/3] Resolviendo conflicto de puerto 80...
REM Detener y cambiar binding del sitio por defecto
"%APPCMD%" stop site "Default Web Site" >nul 2>&1
"%APPCMD%" set site "Default Web Site" /-bindings.[protocol='http',bindingInformation='*:80:'] >nul 2>&1
"%APPCMD%" set site "Default Web Site" /+bindings.[protocol='http',bindingInformation='*:8080:'] >nul 2>&1
echo ✓ Puerto 80 liberado

echo.
echo Iniciando sitio %SITE_NAME%...
"%APPCMD%" start site "%SITE_NAME%"
if !errorlevel! equ 0 (
    echo ✓ Sitio iniciado correctamente
) else (
    echo ⚠ Error al iniciar el sitio, verificando...
    "%APPCMD%" list site "%SITE_NAME%"
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
"%APPCMD%" list site "%SITE_NAME%"
echo.
echo Verificando que el servicio IIS esté corriendo...
net start W3SVC >nul 2>&1
if !errorlevel! equ 0 (
    echo ✓ Servicio IIS iniciado
) else (
    echo ⚠ El servicio IIS puede no estar corriendo
)

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


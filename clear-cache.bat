@echo off
setlocal enabledelayedexpansion
chcp 65001 >nul 2>&1
echo.
echo ============================================================
echo   LIMPIAR CACHE DE SYMFONY
echo ============================================================
echo.

cd /d "%~dp0\current"

REM Detener IIS temporalmente para liberar archivos bloqueados
echo [INFO] Deteniendo sitio IIS temporalmente...
%windir%\system32\inetsrv\appcmd.exe stop site "PrevencioMeditrauma" >nul 2>&1

REM Limpiar cache de Symfony
echo [INFO] Limpiando cache de Symfony...
if exist "var\cache\prod" (
    echo       Eliminando var\cache\prod...
    rmdir /s /q "var\cache\prod" >nul 2>&1
)
if exist "var\cache\dev" (
    echo       Eliminando var\cache\dev...
    rmdir /s /q "var\cache\dev" >nul 2>&1
)
if exist "var\cache" (
    echo       Eliminando var\cache...
    rmdir /s /q "var\cache" >nul 2>&1
)

REM Crear directorio de cache con permisos correctos
echo [INFO] Creando directorio de cache...
mkdir "var\cache" >nul 2>&1
mkdir "var\cache\prod" >nul 2>&1

REM Otorgar permisos completos a IIS_IUSRS e IUSR
echo [INFO] Configurando permisos...
icacls "var" /grant "IIS_IUSRS:(OI)(CI)F" /T >nul 2>&1
icacls "var" /grant "IUSR:(OI)(CI)F" /T >nul 2>&1
icacls "var" /grant "Users:(OI)(CI)F" /T >nul 2>&1

REM Reiniciar sitio IIS
echo [INFO] Reiniciando sitio IIS...
%windir%\system32\inetsrv\appcmd.exe start site "PrevencioMeditrauma" >nul 2>&1

echo.
echo ============================================================
echo   CACHE LIMPIADO
echo ============================================================
echo.
echo   El cache de Symfony ha sido limpiado completamente.
echo   Los permisos han sido configurados correctamente.
echo.
echo   Intenta iniciar sesion nuevamente.
echo.
pause


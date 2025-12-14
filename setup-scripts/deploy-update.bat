@echo off
setlocal enabledelayedexpansion
chcp 65001 >nul

echo ============================================================
echo   DEPLOY UPDATE - Actualizar aplicacion
echo ============================================================

cd /d C:\Users\Administrador\Desktop\Prevencio\prevencio-meditrauma

echo.
echo [1/3] Obteniendo ultimos cambios de Git...
git pull
if %ERRORLEVEL% neq 0 (
    echo [ERROR] Error haciendo git pull
    pause
    exit /b 1
)

echo.
echo [2/3] Limpiando cache de Symfony...
cd current
rmdir /s /q var\cache 2>nul
php bin/console cache:clear --env=prod --no-debug
php bin/console cache:warmup --env=prod --no-debug

echo.
echo [3/3] Reiniciando IIS...
iisreset

echo.
echo ============================================================
echo   DEPLOY COMPLETADO
echo ============================================================
echo.
echo Los cambios estan activos en: http://localhost/
echo.
pause


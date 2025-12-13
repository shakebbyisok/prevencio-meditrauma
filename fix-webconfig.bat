@echo off
echo ========================================
echo Corrector de web.config
echo ========================================
echo.
echo El error 500.19 indica que URL Rewrite Module no está instalado
echo.
echo Opciones:
echo 1. Usar web.config simplificado (sin rewrite rules)
echo 2. Instalar URL Rewrite Module
echo.
echo ¿Qué deseas hacer? (1 o 2)
set /p OPTION=

if "%OPTION%"=="1" (
    echo.
    echo Creando web.config simplificado...
    copy "current\public\web.config" "current\public\web.config.backup" >nul
    copy "web.config.simple" "current\public\web.config" >nul
    echo ✓ web.config simplificado creado
    echo.
    echo NOTA: Las URLs amigables no funcionarán hasta instalar URL Rewrite Module
    echo Pero la aplicación debería funcionar accediendo directamente a index.php
    echo.
) else if "%OPTION%"=="2" (
    echo.
    echo Instalando URL Rewrite Module...
    echo Descargando desde Microsoft...
    set URL_REWRITE=%TEMP%\rewrite_amd64.msi
    powershell -Command "$ProgressPreference = 'SilentlyContinue'; Write-Host 'Descargando URL Rewrite Module...' -ForegroundColor Yellow; Invoke-WebRequest -Uri 'https://www.iis.net/downloads/file.aspx?id=17866' -OutFile '%URL_REWRITE%' -UseBasicParsing -TimeoutSec 120"
    
    if exist "%URL_REWRITE%" (
        echo Instalando...
        msiexec /i "%URL_REWRITE%" /quiet /norestart
        del "%URL_REWRITE%" >nul 2>&1
        echo ✓ URL Rewrite Module instalado
        echo   Puede requerir reinicio de IIS o del servidor
    ) else (
        echo ✗ Error descargando URL Rewrite Module
        echo   Descarga manualmente desde: https://www.iis.net/downloads/microsoft/url-rewrite
    )
) else (
    echo Opción no válida
    pause
    exit /b 1
)

echo.
echo Prueba acceder a: http://localhost
echo O directamente a: http://localhost/index.php
echo.
pause


@echo off
setlocal enabledelayedexpansion
echo ========================================
echo Instalador de IIS (Internet Information Services)
echo ========================================
echo.
echo Este script instalará IIS y las características necesarias
echo Requiere permisos de Administrador
echo.

REM Verificar permisos de administrador
net session >nul 2>&1
if !errorlevel! neq 0 (
    echo ✗ ERROR: Este script requiere permisos de Administrador
    echo.
    echo Haz clic derecho en este archivo y selecciona "Ejecutar como administrador"
    pause
    exit /b 1
)

echo Verificando si IIS ya está instalado...
if exist "C:\Windows\system32\inetsrv\appcmd.exe" (
    echo ✓ IIS ya está instalado
    goto :configure_site
)
echo   IIS no encontrado, procediendo con la instalación...

echo.
echo [1/3] Instalando IIS y características necesarias...
echo Esto puede tardar varios minutos...
echo.

REM Instalar IIS con características necesarias
dism /online /enable-feature /featurename:IIS-WebServerRole /all /norestart
dism /online /enable-feature /featurename:IIS-WebServer /all /norestart
dism /online /enable-feature /featurename:IIS-CommonHttpFeatures /all /norestart
dism /online /enable-feature /featurename:IIS-HttpErrors /all /norestart
dism /online /enable-feature /featurename:IIS-HttpLogging /all /norestart
dism /online /enable-feature /featurename:IIS-RequestFiltering /all /norestart
dism /online /enable-feature /featurename:IIS-StaticContent /all /norestart
dism /online /enable-feature /featurename:IIS-DefaultDocument /all /norestart
dism /online /enable-feature /featurename:IIS-DirectoryBrowsing /all /norestart
dism /online /enable-feature /featurename:IIS-ASPNET45 /all /norestart
dism /online /enable-feature /featurename:IIS-NetFxExtensibility45 /all /norestart
dism /online /enable-feature /featurename:IIS-ISAPIExtensibility /all /norestart
dism /online /enable-feature /featurename:IIS-ISAPIFilter /all /norestart
dism /online /enable-feature /featurename:IIS-ASPNET45 /all /norestart

echo.
echo [2/3] Instalando URL Rewrite Module...
echo Descargando URL Rewrite...
set URL_REWRITE=%TEMP%\rewrite_amd64.msi
set URL_REWRITE_URL=https://www.iis.net/downloads/file.aspx?id=17866

powershell -Command "$ProgressPreference = 'SilentlyContinue'; Write-Host 'Descargando URL Rewrite Module...' -ForegroundColor Yellow; Invoke-WebRequest -Uri '%URL_REWRITE_URL%' -OutFile '%URL_REWRITE%' -UseBasicParsing -TimeoutSec 120"
if exist "%URL_REWRITE%" (
    echo Instalando URL Rewrite Module...
    msiexec /i "%URL_REWRITE%" /quiet /norestart
    del "%URL_REWRITE%" >nul 2>&1
    echo ✓ URL Rewrite Module instalado
) else (
    echo ⚠ No se pudo descargar URL Rewrite Module
    echo   Puedes instalarlo manualmente después desde:
    echo   https://www.iis.net/downloads/microsoft/url-rewrite
)

echo.
echo [3/3] Iniciando servicio IIS...
net start W3SVC >nul 2>&1
if !errorlevel! equ 0 (
    echo ✓ Servicio IIS iniciado
) else (
    echo ⚠ El servicio IIS puede requerir reinicio del sistema
)

:configure_site
echo.
echo ========================================
echo ✓ IIS INSTALADO
echo ========================================
echo.
echo Próximos pasos:
echo 1. Si se solicitó reinicio, reinicia el servidor
echo 2. Ejecuta configure-iis.bat para configurar el sitio web
echo.
echo O configura manualmente:
echo - Abre IIS Manager
echo - Crea un nuevo sitio web apuntando a: %CD%\current\public
echo.
pause


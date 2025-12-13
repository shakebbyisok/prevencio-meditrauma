@echo off
echo ========================================
echo Instalador de Visual C++ Redistributables
echo ========================================
echo.
echo Este script instalará los Visual C++ Redistributables necesarios para PHP
echo.

set VCREDIST=%TEMP%\vc_redist.x64.exe
set VCREDIST_URL=https://aka.ms/vs/17/release/vc_redist.x64.exe

echo Verificando si Visual C++ ya está instalado...
reg query "HKEY_LOCAL_MACHINE\SOFTWARE\Microsoft\VisualStudio\14.0\VC\Runtimes\x64" >nul 2>&1
if !errorlevel! equ 0 (
    echo ✓ Visual C++ Redistributables ya están instalados
    pause
    exit /b 0
)

echo Descargando Visual C++ Redistributables 2015-2022...
echo Esto puede tardar un minuto...
powershell -Command "$ProgressPreference = 'Continue'; Write-Host 'Descargando...' -ForegroundColor Yellow; Invoke-WebRequest -Uri '%VCREDIST_URL%' -OutFile '%VCREDIST%' -UseBasicParsing -TimeoutSec 120; Write-Host '✓ Descarga completada' -ForegroundColor Green"

if not exist "%VCREDIST%" (
    echo ✗ Error descargando Visual C++ Redistributables
    echo.
    echo Descarga manualmente desde:
    echo %VCREDIST_URL%
    echo.
    echo Y ejecuta el instalador
    pause
    exit /b 1
)

echo Instalando Visual C++ Redistributables...
echo Por favor espera, esto puede tardar un minuto...
"%VCREDIST%" /install /quiet /norestart

if !errorlevel! equ 0 (
    echo ✓ Visual C++ Redistributables instalados correctamente
    echo.
    echo IMPORTANTE: Puede que necesites reiniciar el sistema para que los cambios
    echo tengan efecto completo. Si PHP sigue sin funcionar, reinicia el servidor.
) else (
    echo ⚠ La instalación puede requerir reinicio del sistema
)

del "%VCREDIST%" >nul 2>&1

echo.
echo Verificando que PHP funcione...
if exist "C:\php\php.exe" (
    C:\php\php.exe -v >nul 2>&1
    if !errorlevel! equ 0 (
        echo ✓ PHP funciona correctamente
        C:\php\php.exe -v
    ) else (
        echo ✗ PHP aún no funciona, puede requerir reinicio
        echo   Ejecuta: C:\php\php.exe -v para verificar manualmente
    )
) else (
    echo ⚠ PHP no encontrado en C:\php
)

echo.
pause


@echo off
setlocal enabledelayedexpansion
echo ========================================
echo Configurador de Permisos IIS
echo ========================================
echo.
echo Este script configurará los permisos necesarios para IIS
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

set APP_PATH=%CD%\current\public
set VAR_PATH=%CD%\current\var
set CURRENT_PATH=%CD%\current

echo Configurando permisos para:
echo   %CURRENT_PATH% (carpeta completa de la aplicación)
echo   %VAR_PATH% (carpeta de escritura)
echo.

echo [1/4] Configurando permisos de lectura para toda la aplicación...
REM Dar permisos de lectura a toda la carpeta current
icacls "%CURRENT_PATH%" /grant "IIS_IUSRS:(OI)(CI)R" /T >nul 2>&1
icacls "%CURRENT_PATH%" /grant "IUSR:(OI)(CI)R" /T >nul 2>&1
icacls "%CURRENT_PATH%" /grant "Users:(OI)(CI)R" /T >nul 2>&1
icacls "%CURRENT_PATH%" /grant "NETWORK SERVICE:(OI)(CI)R" /T >nul 2>&1
echo ✓ Permisos de lectura configurados para toda la aplicación

echo.
echo [2/4] Configurando permisos de escritura para var...
if exist "%VAR_PATH%" (
    icacls "%VAR_PATH%" /grant "IIS_IUSRS:(OI)(CI)F" /T >nul 2>&1
    icacls "%VAR_PATH%" /grant "IUSR:(OI)(CI)F" /T >nul 2>&1
    icacls "%VAR_PATH%" /grant "Users:(OI)(CI)F" /T >nul 2>&1
    echo ✓ Permisos de escritura configurados para var
) else (
    echo ⚠ Carpeta var no existe, creándola...
    mkdir "%VAR_PATH%" >nul 2>&1
    mkdir "%VAR_PATH%\cache" >nul 2>&1
    mkdir "%VAR_PATH%\log" >nul 2>&1
    mkdir "%VAR_PATH%\sessions" >nul 2>&1
    icacls "%VAR_PATH%" /grant "IIS_IUSRS:(OI)(CI)F" /T >nul 2>&1
    icacls "%VAR_PATH%" /grant "IUSR:(OI)(CI)F" /T >nul 2>&1
    icacls "%VAR_PATH%" /grant "Users:(OI)(CI)F" /T >nul 2>&1
    echo ✓ Carpeta var creada con permisos
)

echo.
echo [3/4] Configurando permisos para vendor y cache...
REM Dar permisos de lectura a vendor si existe
if exist "%CURRENT_PATH%\vendor" (
    icacls "%CURRENT_PATH%\vendor" /grant "IIS_IUSRS:(OI)(CI)R" /T >nul 2>&1
    icacls "%CURRENT_PATH%\vendor" /grant "IUSR:(OI)(CI)R" /T >nul 2>&1
)

REM Dar permisos de lectura a config
if exist "%CURRENT_PATH%\config" (
    icacls "%CURRENT_PATH%\config" /grant "IIS_IUSRS:(OI)(CI)R" /T >nul 2>&1
    icacls "%CURRENT_PATH%\config" /grant "IUSR:(OI)(CI)R" /T >nul 2>&1
    icacls "%CURRENT_PATH%\config" /grant "NETWORK SERVICE:(OI)(CI)R" /T >nul 2>&1
)
echo ✓ Permisos para vendor y config configurados

echo.
echo [4/4] Configurando permisos adicionales...
REM Dar permisos al Application Pool (puede variar según la configuración)
icacls "%CURRENT_PATH%" /grant "IIS AppPool\PrevencioMeditrauma:(OI)(CI)R" /T >nul 2>&1
icacls "%VAR_PATH%" /grant "IIS AppPool\PrevencioMeditrauma:(OI)(CI)F" /T >nul 2>&1

echo ✓ Permisos adicionales configurados

echo.
echo ========================================
echo ✓ PERMISOS CONFIGURADOS
echo ========================================
echo.
echo Prueba acceder a: http://localhost
echo.
pause


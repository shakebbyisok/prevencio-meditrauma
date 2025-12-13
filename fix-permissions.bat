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

echo Configurando permisos para:
echo   %APP_PATH%
echo   %VAR_PATH%
echo.

echo [1/3] Configurando permisos de lectura para IIS_IUSRS...
icacls "%APP_PATH%" /grant "IIS_IUSRS:(OI)(CI)R" /T >nul 2>&1
icacls "%APP_PATH%" /grant "IUSR:(OI)(CI)R" /T >nul 2>&1
icacls "%APP_PATH%" /grant "Users:(OI)(CI)R" /T >nul 2>&1
echo ✓ Permisos de lectura configurados

echo.
echo [2/3] Configurando permisos de escritura para var...
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
echo [3/3] Configurando permisos adicionales...
REM Dar permisos al Application Pool (puede variar según la configuración)
icacls "%APP_PATH%" /grant "IIS AppPool\PrevencioMeditrauma:(OI)(CI)R" /T >nul 2>&1
icacls "%VAR_PATH%" /grant "IIS AppPool\PrevencioMeditrauma:(OI)(CI)F" /T >nul 2>&1

REM También dar permisos a NETWORK SERVICE por si acaso
icacls "%APP_PATH%" /grant "NETWORK SERVICE:(OI)(CI)R" /T >nul 2>&1
icacls "%VAR_PATH%" /grant "NETWORK SERVICE:(OI)(CI)F" /T >nul 2>&1

echo ✓ Permisos adicionales configurados

echo.
echo ========================================
echo ✓ PERMISOS CONFIGURADOS
echo ========================================
echo.
echo Prueba acceder a: http://localhost
echo.
pause


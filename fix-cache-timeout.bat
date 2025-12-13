@echo off
setlocal enabledelayedexpansion
echo ========================================
echo Solucionador de Cache y Timeout
echo ========================================
echo.

REM Verificar permisos de administrador
net session >nul 2>&1
if !errorlevel! neq 0 (
    echo ⚠ Algunas operaciones requieren permisos de Administrador
)

set CURRENT_PATH=%CD%\current
set CACHE_PATH=%CURRENT_PATH%\var\cache
set LOG_PATH=%CURRENT_PATH%\var\log

echo [1/3] Configurando permisos de escritura para cache...
icacls "%CACHE_PATH%" /grant "IIS_IUSRS:(OI)(CI)F" /T >nul 2>&1
icacls "%CACHE_PATH%" /grant "IUSR:(OI)(CI)F" /T >nul 2>&1
icacls "%CACHE_PATH%" /grant "NETWORK SERVICE:(OI)(CI)F" /T >nul 2>&1
icacls "%CACHE_PATH%" /grant "Users:(OI)(CI)F" /T >nul 2>&1
icacls "%CACHE_PATH%" /grant "IIS AppPool\PrevencioMeditrauma:(OI)(CI)F" /T >nul 2>&1
echo ✓ Permisos configurados

echo.
echo [2/3] Configurando timeout de PHP...
set PHP_INI=C:\php\php.ini
if exist "%PHP_INI%" (
    REM Aumentar max_execution_time a 300 segundos (5 minutos)
    powershell -Command "(Get-Content '%PHP_INI%') -replace 'max_execution_time\s*=\s*\d+', 'max_execution_time = 300' | Set-Content '%PHP_INI%'"
    REM Aumentar memory_limit si es necesario
    powershell -Command "(Get-Content '%PHP_INI%') -replace 'memory_limit\s*=\s*[^\r\n]+', 'memory_limit = 512M' | Set-Content '%PHP_INI%'"
    echo ✓ php.ini actualizado (max_execution_time=300, memory_limit=512M)
) else (
    echo ⚠ php.ini no encontrado en C:\php
)

echo.
echo [3/3] Limpiando caché antigua...
if exist "%CACHE_PATH%\prod" (
    rmdir /s /q "%CACHE_PATH%\prod" >nul 2>&1
    echo ✓ Caché de producción eliminada
)
if exist "%CACHE_PATH%\dev" (
    rmdir /s /q "%CACHE_PATH%\dev" >nul 2>&1
    echo ✓ Caché de desarrollo eliminada
)

echo.
echo ========================================
echo ✓ CONFIGURACIÓN COMPLETADA
echo ========================================
echo.
echo IMPORTANTE: Reinicia IIS para aplicar los cambios de php.ini
echo   Ejecuta: iisreset
echo.
echo Luego prueba acceder a: http://localhost/index.php
echo.
pause


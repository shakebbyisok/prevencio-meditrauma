@echo off
setlocal enabledelayedexpansion
echo ========================================
echo DESPLIEGUE COMPLETO AUTOMATIZADO
echo ========================================
echo.
echo Este script ejecutará:
echo 1. Instalación de PHP y Composer (si es necesario)
echo 2. Configuración completa de la aplicación
echo.
echo ¿Deseas continuar? (S/N)
set /p CONTINUE=
if /i not "!CONTINUE!"=="S" (
    echo Cancelado
    exit /b 0
)

echo.
echo ========================================
echo PASO 1: Instalando requisitos (PHP + Composer)
echo ========================================
echo.

REM Verificar si PHP está instalado
where php >nul 2>&1
if !errorlevel! neq 0 (
    echo PHP no está instalado. Ejecutando instalador...
    call install-requirements.bat
    if !errorlevel! neq 0 (
        echo ✗ Error en la instalación de requisitos
        pause
        exit /b 1
    )
    echo.
    echo ⚠ IMPORTANTE: Cierra esta ventana y ejecuta deploy-full.bat de nuevo
    echo    para que PHP y Composer estén disponibles en el PATH
    pause
    exit /b 0
) else (
    echo ✓ PHP ya está instalado
    php -v
)

REM Verificar Composer
where composer >nul 2>&1
if !errorlevel! neq 0 (
    echo Composer no está instalado. Ejecutando instalador...
    call install-requirements.bat
    if !errorlevel! neq 0 (
        echo ✗ Error en la instalación de requisitos
        pause
        exit /b 1
    )
    echo.
    echo ⚠ IMPORTANTE: Cierra esta ventana y ejecuta deploy-full.bat de nuevo
    echo    para que Composer esté disponible en el PATH
    pause
    exit /b 0
) else (
    echo ✓ Composer ya está instalado
    composer --version
)

echo.
echo ========================================
echo PASO 2: Configurando aplicación
echo ========================================
echo.

call deploy-ready.bat

echo.
echo ========================================
echo ✓ DESPLIEGUE COMPLETO FINALIZADO
echo ========================================
echo.
echo Próximos pasos manuales:
echo 1. Configurar IIS apuntando a: %CD%\current\public
echo 2. Verificar que PHP esté configurado en IIS
echo 3. Probar la aplicación en el navegador
echo.
pause


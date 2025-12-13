@echo off
setlocal enabledelayedexpansion
echo ========================================
echo Compilador de Assets usando Docker
echo ========================================
echo.

REM Verificar que Docker está corriendo
docker ps >nul 2>&1
if !errorlevel! neq 0 (
    echo ✗ ERROR: Docker no está corriendo o no está instalado
    echo   Por favor inicia Docker Desktop
    pause
    exit /b 1
)
echo ✓ Docker está corriendo

REM Verificar que estamos en el directorio correcto
cd /d "%~dp0current"
if not exist "package.json" (
    echo ✗ ERROR: No se encuentra package.json en la carpeta current
    echo   Ejecuta este script desde el directorio prevencio-meditrauma
    pause
    exit /b 1
)

set PROJECT_PATH=%CD%
set CONTAINER_NAME=node-build-assets

echo.
echo [1/3] Descargando imagen de Node.js (si no existe)...
docker pull node:20-alpine >nul 2>&1
if !errorlevel! equ 0 (
    echo ✓ Imagen de Node.js lista
) else (
    echo ⚠ No se pudo descargar la imagen, intentando continuar...
)

echo.
echo [2/3] Instalando dependencias de npm...
echo   Esto puede tardar varios minutos...
docker run --rm -v "%PROJECT_PATH%:/app" -w /app node:20-alpine sh -c "npm install"
if !errorlevel! neq 0 (
    echo ✗ ERROR: Error instalando dependencias
    pause
    exit /b 1
)
echo ✓ Dependencias instaladas

echo.
echo [3/3] Compilando assets para producción...
docker run --rm -v "%PROJECT_PATH%:/app" -w /app node:20-alpine sh -c "npm run build"
if !errorlevel! neq 0 (
    echo ⚠ Error compilando para producción, intentando modo desarrollo...
    docker run --rm -v "%PROJECT_PATH%:/app" -w /app node:20-alpine sh -c "npm run dev"
    if !errorlevel! neq 0 (
        echo ✗ ERROR: Error compilando assets
        pause
        exit /b 1
    )
)
echo ✓ Assets compilados

echo.
echo [4/4] Verificando archivos compilados...
if exist "public\build\manifest.json" (
    echo ✓ manifest.json existe
    for %%F in (public\build\*.js public\build\*.css) do (
        echo ✓ Encontrado: %%~nxF
    )
) else (
    echo ⚠ manifest.json no encontrado, pero la compilación puede haber funcionado
)

echo.
echo ========================================
echo ✓ PROCESO COMPLETADO
echo ========================================
echo.
echo Assets compilados usando Docker.
echo Refresca el navegador para ver los estilos aplicados.
echo.
pause


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
echo [1/3] Descargando imagen de Node.js v16 (máxima compatibilidad)...
docker pull node:16-bullseye-slim >nul 2>&1
if !errorlevel! equ 0 (
    echo ✓ Imagen de Node.js v16 lista
) else (
    echo ⚠ No se pudo descargar la imagen, intentando continuar...
)

echo.
echo [2/3] Instalando dependencias de npm...
echo   Esto puede tardar varios minutos...
echo   Nota: Usando npm ci para instalar versiones exactas del lock file...
docker run --rm -v "%PROJECT_PATH%:/app" -w /app node:16-bullseye-slim sh -c "apt-get update -qq && apt-get install -y -qq python3 make g++ >/dev/null 2>&1 && (npm ci || npm install --legacy-peer-deps)"
if !errorlevel! neq 0 (
    echo ⚠ Error con npm ci, intentando con npm install normal...
    docker run --rm -v "%PROJECT_PATH%:/app" -w /app node:16-bullseye-slim sh -c "apt-get update -qq && apt-get install -y -qq python3 make g++ >/dev/null 2>&1 && npm install --legacy-peer-deps"
    if !errorlevel! neq 0 (
        echo ✗ ERROR: Error instalando dependencias
        echo   Verifica que Docker tenga suficiente memoria y espacio en disco
        pause
        exit /b 1
    )
)
echo ✓ Dependencias instaladas

echo.
echo [3/3] Compilando assets para producción...
echo   Usando Node.js v16 (compatible con webpack 4 y babel antiguo)...
docker run --rm -v "%PROJECT_PATH%:/app" -w /app node:16-bullseye-slim sh -c "npm run build"
if !errorlevel! neq 0 (
    echo ⚠ Error compilando para producción, intentando modo desarrollo...
    docker run --rm -v "%PROJECT_PATH%:/app" -w /app node:16-bullseye-slim sh -c "npm run dev"
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


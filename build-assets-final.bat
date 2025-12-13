@echo off
setlocal enabledelayedexpansion
echo ========================================
echo Compilador de Assets DEFINITIVO
echo ========================================
echo.
echo Este script usa Node.js 12 para maxima compatibilidad
echo con las dependencias antiguas del proyecto.
echo.

REM Verificar que Docker esta corriendo
docker ps >nul 2>&1
if !errorlevel! neq 0 (
    echo X ERROR: Docker no esta corriendo o no esta instalado
    echo   Por favor inicia Docker Desktop
    pause
    exit /b 1
)
echo OK Docker esta corriendo

REM Verificar que estamos en el directorio correcto
cd /d "%~dp0current"
if not exist "package.json" (
    echo X ERROR: No se encuentra package.json en la carpeta current
    echo   Ejecuta este script desde el directorio prevencio-meditrauma
    pause
    exit /b 1
)

set PROJECT_PATH=%CD%

echo.
echo [1/4] Descargando imagen de Node.js v12 (maxima compatibilidad)...
docker pull node:12-buster-slim >nul 2>&1
if !errorlevel! equ 0 (
    echo OK Imagen de Node.js v12 lista
) else (
    echo WARN No se pudo descargar la imagen, intentando continuar...
)

echo.
echo [2/4] Limpiando e instalando dependencias...
echo   Esto puede tardar varios minutos...

REM Limpiar node_modules completamente y reinstalar
docker run --rm -v "%PROJECT_PATH%:/app" -w /app node:12-buster-slim sh -c "rm -rf node_modules package-lock.json 2>/dev/null; npm install"
if !errorlevel! neq 0 (
    echo WARN Primer intento fallo, intentando con --legacy-peer-deps...
    docker run --rm -v "%PROJECT_PATH%:/app" -w /app node:12-buster-slim sh -c "rm -rf node_modules 2>/dev/null; npm install --legacy-peer-deps"
    if !errorlevel! neq 0 (
        echo X ERROR: No se pudieron instalar las dependencias
        pause
        exit /b 1
    )
)
echo OK Dependencias instaladas

echo.
echo [3/4] Compilando assets para produccion...
echo   Esto puede tardar varios minutos...

docker run --rm -v "%PROJECT_PATH%:/app" -w /app node:12-buster-slim sh -c "npm run build"
if !errorlevel! equ 0 (
    echo OK Assets compilados para produccion
    goto :verify
)

echo.
echo WARN Error compilando para produccion, intentando modo desarrollo...
docker run --rm -v "%PROJECT_PATH%:/app" -w /app node:12-buster-slim sh -c "npm run dev"
if !errorlevel! equ 0 (
    echo OK Assets compilados en modo desarrollo
    goto :verify
)

echo.
echo X ERROR: Error compilando assets
echo   Verifica los logs anteriores para mas detalles
pause
exit /b 1

:verify
echo.
echo [4/4] Verificando archivos compilados...

if exist "public\build\manifest.json" (
    echo OK manifest.json existe
) else (
    echo X manifest.json no encontrado
)

for %%F in (public\build\*.js) do (
    echo OK JS: %%~nxF
)

for %%F in (public\build\*.css) do (
    echo OK CSS: %%~nxF
)

echo.
echo ========================================
echo COMPLETADO - Assets compilados
echo ========================================
echo.
echo Refresca el navegador (Ctrl+F5) para ver los estilos aplicados.
echo URL: http://localhost/index.php
echo.
pause


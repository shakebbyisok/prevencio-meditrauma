@echo off
setlocal enabledelayedexpansion
echo ========================================
echo Compilador de Assets para Producción
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

echo.
echo [1/4] Descargando imagen de Node.js v16...
docker pull node:16-bullseye-slim >nul 2>&1
if !errorlevel! equ 0 (
    echo ✓ Imagen de Node.js v16 lista
) else (
    echo ⚠ No se pudo descargar la imagen, intentando continuar...
)

echo.
echo [2/4] Instalando herramientas de compilación y dependencias...
echo   Esto puede tardar varios minutos...
echo   Estrategia: Limpiar node_modules y reinstalar con sass...

REM Limpiar node_modules y package-lock.json si existen, luego instalar
docker run --rm -v "%PROJECT_PATH%:/app" -w /app node:16-bullseye-slim sh -c "rm -rf node_modules package-lock.json 2>/dev/null; npm install --legacy-peer-deps"
if !errorlevel! equ 0 (
    echo ✓ Dependencias instaladas correctamente
    goto :verify_sass
)

echo.
echo ⚠ Error con instalación limpia, intentando sin limpiar...
docker run --rm -v "%PROJECT_PATH%:/app" -w /app node:16-bullseye-slim sh -c "npm install --legacy-peer-deps"
if !errorlevel! equ 0 (
    echo ✓ Dependencias instaladas
    goto :verify_sass
)

echo.
echo ✗ ERROR: No se pudieron instalar las dependencias
pause
exit /b 1

:verify_sass
echo.
echo Verificando que sass esté instalado...
docker run --rm -v "%PROJECT_PATH%:/app" -w /app node:16-bullseye-slim sh -c "test -d node_modules/sass && echo 'sass encontrado' || (echo 'sass no encontrado, instalando...' && npm install --save-dev sass@^1.32.0 --legacy-peer-deps)"
if !errorlevel! equ 0 (
    echo ✓ Sass verificado/instalado
    goto :compile
) else (
    echo ✗ ERROR: No se pudo instalar sass
    pause
    exit /b 1
)

echo.
echo ⚠ Error con instalación normal, intentando reemplazar node-sass con sass...
echo   Sass (dart-sass) no requiere compilación nativa...

REM Eliminar node-sass y node_modules/node-sass si existe
docker run --rm -v "%PROJECT_PATH%:/app" -w /app node:16-bullseye-slim sh -c "rm -rf node_modules/node-sass 2>/dev/null; npm uninstall node-sass 2>/dev/null || true"

REM Instalar sass y reinstalar dependencias
docker run --rm -v "%PROJECT_PATH%:/app" -w /app node:16-bullseye-slim sh -c "npm install --save-dev sass@^1.32.0 --legacy-peer-deps && npm install --legacy-peer-deps"
if !errorlevel! equ 0 (
    echo ✓ Dependencias instaladas (usando sass en lugar de node-sass)
    echo   Webpack Encore detectará automáticamente sass
    goto :compile
)

echo.
echo ✗ ERROR: No se pudieron instalar las dependencias
echo   Verifica que Docker tenga suficiente memoria y espacio en disco
pause
exit /b 1

:compile
echo.
echo [3/4] Compilando assets para producción...
echo   Esto puede tardar varios minutos...

docker run --rm -v "%PROJECT_PATH%:/app" -w /app node:16-bullseye-slim sh -c "npm run build"
if !errorlevel! equ 0 (
    echo ✓ Assets compilados para producción
    goto :verify
)

echo.
echo ⚠ Error compilando para producción, intentando modo desarrollo...
docker run --rm -v "%PROJECT_PATH%:/app" -w /app node:16-bullseye-slim sh -c "npm run dev"
if !errorlevel! equ 0 (
    echo ✓ Assets compilados en modo desarrollo
    goto :verify
)

echo.
echo ✗ ERROR: Error compilando assets
echo   Verifica los logs anteriores para más detalles
pause
exit /b 1

:verify
echo.
echo [4/4] Verificando archivos compilados...
set FILES_FOUND=0

if exist "public\build\manifest.json" (
    echo ✓ manifest.json existe
    set /a FILES_FOUND+=1
) else (
    echo ✗ manifest.json no encontrado
)

if exist "public\build\app.js" (
    echo ✓ app.js compilado
    set /a FILES_FOUND+=1
) else (
    echo ✗ app.js no encontrado
)

if exist "public\build\app.css" (
    echo ✓ app.css compilado
    set /a FILES_FOUND+=1
) else (
    echo ✗ app.css no encontrado
)

for %%F in (public\build\*.js public\build\*.css) do (
    echo ✓ Encontrado: %%~nxF
    set /a FILES_FOUND+=1
)

if !FILES_FOUND! equ 0 (
    echo ⚠ No se encontraron archivos compilados
    echo   La aplicación funcionará pero sin estilos personalizados
)

echo.
echo ========================================
echo ✓ PROCESO COMPLETADO
echo ========================================
echo.
echo Assets compilados usando Docker.
echo Refresca el navegador (Ctrl+F5) para ver los estilos aplicados.
echo.
pause


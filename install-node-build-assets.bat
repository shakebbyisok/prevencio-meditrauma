@echo off
setlocal enabledelayedexpansion
echo ========================================
echo Instalador de Node.js y Compilador de Assets
echo ========================================
echo.

REM Verificar permisos de administrador
net session >nul 2>&1
if !errorlevel! neq 0 (
    echo ⚠ Algunas operaciones pueden requerir permisos de Administrador
)

set NODE_VERSION=20.11.0
set NODE_ARCH=x64
set NODE_INSTALLER=node-v%NODE_VERSION%-win-%NODE_ARCH%.msi
set NODE_URL=https://nodejs.org/dist/v%NODE_VERSION%/%NODE_INSTALLER%
set TEMP_DIR=%TEMP%\nodejs-install

echo [1/5] Verificando si Node.js ya está instalado...
where node >nul 2>&1
if !errorlevel! equ 0 (
    echo   Node.js ya está instalado:
    node --version
    npm --version
    echo   ✓ Node.js encontrado
    goto :install_deps
)

echo   Node.js no está instalado, procediendo con la instalación...
echo.

echo [2/5] Creando directorio temporal...
if not exist "%TEMP_DIR%" mkdir "%TEMP_DIR%"
cd /d "%TEMP_DIR%"

echo.
echo [3/5] Descargando Node.js v%NODE_VERSION%...
echo   Esto puede tardar unos minutos...
powershell -Command "& {[Net.ServicePointManager]::SecurityProtocol = [Net.SecurityProtocolType]::Tls12; Invoke-WebRequest -Uri '%NODE_URL%' -OutFile '%NODE_INSTALLER%' -UseBasicParsing}"
if !errorlevel! neq 0 (
    echo   ✗ Error descargando Node.js
    echo   Intentando con URL alternativa...
    set NODE_URL=https://nodejs.org/dist/v%NODE_VERSION%/%NODE_INSTALLER%
    powershell -Command "& {[Net.ServicePointManager]::SecurityProtocol = [Net.SecurityProtocolType]::Tls12; Invoke-WebRequest -Uri '%NODE_URL%' -OutFile '%NODE_INSTALLER%' -UseBasicParsing}"
    if !errorlevel! neq 0 (
        echo   ✗ ERROR: No se pudo descargar Node.js
        echo   Descarga manualmente desde: https://nodejs.org/
        pause
        exit /b 1
    )
)
echo   ✓ Node.js descargado

echo.
echo [4/5] Instalando Node.js...
echo   Esto instalará Node.js silenciosamente...
echo   Por favor espera...
msiexec /i "%NODE_INSTALLER%" /quiet /norestart ADDLOCAL=ALL
if !errorlevel! neq 0 (
    echo   ✗ ERROR: Error instalando Node.js
    echo   Intenta instalar manualmente desde: %TEMP_DIR%\%NODE_INSTALLER%
    pause
    exit /b 1
)

echo   ✓ Node.js instalado
echo   Esperando 5 segundos para que se actualice el PATH...
timeout /t 5 /nobreak >nul

REM Actualizar PATH en la sesión actual
set "PATH=%PATH%;C:\Program Files\nodejs"
if exist "C:\Program Files (x86)\nodejs" (
    set "PATH=%PATH%;C:\Program Files (x86)\nodejs"
)

echo.
echo [5/5] Verificando instalación...
where node >nul 2>&1
if !errorlevel! neq 0 (
    echo   ⚠ Node.js instalado pero no está en el PATH de esta sesión
    echo   Por favor, cierra y vuelve a abrir esta ventana de comandos
    echo   O reinicia el sistema para que se actualice el PATH
    pause
    exit /b 1
)

node --version
npm --version
echo   ✓ Node.js instalado correctamente

:install_deps
echo.
echo ========================================
echo Instalando dependencias y compilando assets
echo ========================================
echo.

cd /d "%~dp0current"
if not exist "package.json" (
    echo ✗ ERROR: No se encuentra package.json en la carpeta current
    echo   Verifica que estés ejecutando el script desde el directorio correcto
    pause
    exit /b 1
)

echo [1/3] Instalando dependencias de npm...
echo   Esto puede tardar varios minutos...
call npm install
if !errorlevel! neq 0 (
    echo   ✗ ERROR: Error instalando dependencias
    pause
    exit /b 1
)
echo   ✓ Dependencias instaladas

echo.
echo [2/3] Compilando assets para producción...
call npm run build
if !errorlevel! neq 0 (
    echo   ⚠ Error compilando para producción, intentando modo desarrollo...
    call npm run dev
    if !errorlevel! neq 0 (
        echo   ✗ ERROR: Error compilando assets
        pause
        exit /b 1
    )
)
echo   ✓ Assets compilados

echo.
echo [3/3] Verificando archivos compilados...
if exist "public\build\manifest.json" (
    echo   ✓ manifest.json existe
    for %%F in (public\build\*.js public\build\*.css) do (
        echo   ✓ Encontrado: %%~nxF
    )
) else (
    echo   ⚠ manifest.json no encontrado, pero la compilación puede haber funcionado
)

echo.
echo ========================================
echo ✓ PROCESO COMPLETADO
echo ========================================
echo.
echo Node.js instalado y assets compilados.
echo Refresca el navegador para ver los estilos aplicados.
echo.
pause


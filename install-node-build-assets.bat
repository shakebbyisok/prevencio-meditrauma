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

set NODE_VERSION=20.17.0
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
echo [3/5] Intentando instalar Node.js con Chocolatey...
where choco >nul 2>&1
if !errorlevel! equ 0 (
    echo   Chocolatey encontrado, instalando Node.js...
    choco install nodejs-lts -y --no-progress
    if !errorlevel! equ 0 (
        echo   ✓ Node.js instalado con Chocolatey
        goto :verify_install
    )
)

echo   Chocolatey no disponible, intentando descargar Node.js...
echo   Obteniendo la última versión LTS disponible...
powershell -Command "& {[Net.ServicePointManager]::SecurityProtocol = [Net.SecurityProtocolType]::Tls12; $ProgressPreference = 'SilentlyContinue'; try { $response = Invoke-RestMethod -Uri 'https://nodejs.org/dist/index.json' -UseBasicParsing; $ltsVersion = ($response | Where-Object { $_.lts -ne $null } | Select-Object -First 1).version; $ltsVersion = $ltsVersion -replace 'v', ''; $majorVersion = $ltsVersion.Split('.')[0]; $minorVersion = $ltsVersion.Split('.')[1]; $patchVersion = $ltsVersion.Split('.')[2]; $nodeInstaller = \"node-v$ltsVersion-win-x64.msi\"; $nodeUrl = \"https://nodejs.org/dist/v$ltsVersion/$nodeInstaller\"; Write-Host \"Found LTS version: v$ltsVersion\"; Write-Host \"Downloading from: $nodeUrl\"; try { $webClient = New-Object System.Net.WebClient; $webClient.DownloadFile($nodeUrl, '%TEMP_DIR%\' + $nodeInstaller); Write-Host 'Download successful'; $env:NODE_INSTALLER = $nodeInstaller; $env:NODE_VERSION = $ltsVersion } catch { Write-Host 'Download failed, trying direct LTS link...'; $webClient.DownloadFile('https://nodejs.org/dist/latest-v20.x/node-v20.11.0-win-x64.msi', '%TEMP_DIR%\node-v20.11.0-win-x64.msi'); Write-Host 'Download successful (fallback)'; $env:NODE_INSTALLER = 'node-v20.11.0-win-x64.msi'; $env:NODE_VERSION = '20.11.0' } } catch { Write-Host 'Could not determine LTS version'; exit 1 } }"
if !errorlevel! neq 0 (
    echo   ✗ ERROR: No se pudo descargar Node.js automáticamente
    echo.
    echo   ========================================
    echo   INSTALACIÓN MANUAL REQUERIDA
    echo   ========================================
    echo   Por favor instala Node.js manualmente:
    echo   1. Ve a: https://nodejs.org/
    echo   2. Descarga la versión LTS (v20.x) para Windows x64
    echo   3. Instálalo
    echo   4. Cierra y vuelve a abrir esta ventana
    echo   5. Ejecuta este script de nuevo
    echo.
    echo   O instala con Chocolatey (si lo tienes):
    echo   choco install nodejs-lts -y
    echo.
    pause
    exit /b 1
)

REM Usar el nombre del instalador que PowerShell determinó
if defined NODE_INSTALLER (
    set NODE_INSTALLER=%NODE_INSTALLER%
) else (
    REM Fallback a una versión conocida
    set NODE_INSTALLER=node-v20.11.0-win-x64.msi
)

REM Verificar que el archivo se descargó correctamente (solo si no se usó Chocolatey)
if not exist "%TEMP_DIR%\%NODE_INSTALLER%" (
    if not exist "C:\Program Files\nodejs\node.exe" (
        echo   ✗ ERROR: El archivo no se descargó correctamente
        echo   Por favor descarga Node.js manualmente desde: https://nodejs.org/
        pause
        exit /b 1
    )
) else (
    echo   ✓ Node.js descargado correctamente
    echo.
    echo [4/5] Instalando Node.js...
    echo   Esto instalará Node.js silenciosamente...
    echo   Por favor espera...
    msiexec /i "%TEMP_DIR%\%NODE_INSTALLER%" /quiet /norestart ADDLOCAL=ALL
    if !errorlevel! neq 0 (
        echo   ✗ ERROR: Error instalando Node.js
        echo   Intenta instalar manualmente ejecutando:
        echo   %TEMP_DIR%\%NODE_INSTALLER%
        pause
        exit /b 1
    )
    echo   ✓ Node.js instalado
    echo   Esperando 5 segundos para que se actualice el PATH...
    timeout /t 5 /nobreak >nul
)

REM Actualizar PATH en la sesión actual
set "PATH=%PATH%;C:\Program Files\nodejs"
if exist "C:\Program Files (x86)\nodejs" (
    set "PATH=%PATH%;C:\Program Files (x86)\nodejs"
)

:verify_install
echo.
echo [5/5] Verificando instalación...
where node >nul 2>&1
if !errorlevel! neq 0 (
    echo   ⚠ Node.js instalado pero no está en el PATH de esta sesión
    echo   Por favor, cierra y vuelve a abrir esta ventana de comandos
    echo   O reinicia el sistema para que se actualice el PATH
    echo   Luego ejecuta solo la parte de compilación:
    echo   cd current ^&^& npm install ^&^& npm run build
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


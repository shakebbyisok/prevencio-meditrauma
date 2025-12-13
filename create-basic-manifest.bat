@echo off
setlocal enabledelayedexpansion
echo ========================================
echo Creando manifest.json básico
echo ========================================
echo.

set PROJECT_PATH=%CD%\current

if not exist "%PROJECT_PATH%\public\build" (
    mkdir "%PROJECT_PATH%\public\build"
    echo ✓ Carpeta build creada
)

REM Crear un manifest.json básico que permita que la aplicación funcione
(
    echo {
    echo   "build/app.js": "/build/app.js",
    echo   "build/app.css": "/build/app.css"
    echo }
) > "%PROJECT_PATH%\public\build\manifest.json"

REM Crear archivos CSS y JS básicos vacíos si no existen
if not exist "%PROJECT_PATH%\public\build\app.js" (
    echo // Assets compilados placeholder > "%PROJECT_PATH%\public\build\app.js"
)
if not exist "%PROJECT_PATH%\public\build\app.css" (
    echo /* Assets compilados placeholder */ > "%PROJECT_PATH%\public\build\app.css"
)

echo ✓ manifest.json creado
echo ✓ Archivos placeholder creados
echo.
echo NOTA: La aplicación funcionará pero sin estilos personalizados.
echo Para compilar los assets correctamente, necesitas resolver
echo los problemas de compatibilidad de Babel/Webpack.
echo.
pause


@echo off
setlocal enabledelayedexpansion
echo ========================================
echo Script de Despliegue Completo
echo ========================================
echo.

REM Verificar que estamos en el directorio correcto
if not exist "current\composer.json" (
    echo ✗ ERROR: No se encuentra current\composer.json
    echo   Ejecuta este script desde el directorio prevencio-meditrauma
    pause
    exit /b 1
)

REM Paso 1: Verificar/Iniciar Docker y MySQL
echo [1/7] Verificando Docker y MySQL...
docker ps | findstr "prevencio_mysql" >nul 2>&1
if !errorlevel! neq 0 (
    echo   Iniciando contenedores MySQL...
    docker-compose up -d
    echo   Esperando 30 segundos para que MySQL esté listo...
    timeout /t 30 /nobreak >nul
    docker exec prevencio_mysql mysqladmin ping -h localhost -u root -proot123 >nul 2>&1
    if !errorlevel! neq 0 (
        echo   ⚠ MySQL aún no está listo, esperando más...
        timeout /t 20 /nobreak >nul
    )
    echo   ✓ MySQL iniciado
) else (
    echo   ✓ MySQL ya está corriendo
)
echo.

REM Paso 2: Verificar bases de datos restauradas
echo [2/7] Verificando bases de datos...
docker exec prevencio_mysql mysql -u root -proot123 -e "USE prevencion;" >nul 2>&1
if !errorlevel! equ 0 (
    echo   ✓ Base de datos prevencion accesible
    echo   ℹ Si necesitas restaurar datos, ejecuta restore-db.bat manualmente
) else (
    echo   ⚠ No se puede acceder a la base de datos prevencion
    echo   Verifica que MySQL esté corriendo correctamente
)
echo.

REM Paso 3: Configurar archivo .env
echo [3/7] Configurando archivo .env...
cd current
if not exist ".env" (
    echo   Creando archivo .env desde .env.dist...
    if exist ".env.dist" (
        copy ".env.dist" ".env" >nul
        echo   ✓ Archivo .env creado
    ) else (
        echo   ⚠ No se encontró .env.dist, creando .env básico...
        (
            echo APP_ENV=prod
            echo APP_DEBUG=0
            echo APP_SECRET=
            echo.
            echo DATABASE_URL=mysql://prevencion_user:prevencion123@127.0.0.1:3306/prevencion?serverVersion=8.0^&charset=utf8mb4
        ) > .env
        echo   ✓ Archivo .env creado con configuración básica
    )
) else (
    echo   ✓ Archivo .env ya existe
)

REM Generar APP_SECRET si no existe o está vacío
findstr /C:"APP_SECRET=" .env | findstr /V /C:"APP_SECRET=$" | findstr /V /C:"APP_SECRET= " >nul
if !errorlevel! neq 0 (
    echo   Generando APP_SECRET...
    for /f "tokens=*" %%a in ('php -r "echo bin2hex(random_bytes(16));"') do set NEW_SECRET=%%a
    powershell -Command "(Get-Content .env) -replace 'APP_SECRET=.*', 'APP_SECRET=!NEW_SECRET!' | Set-Content .env"
    echo   ✓ APP_SECRET generado
)

REM Verificar que DATABASE_URL esté configurado para MySQL
findstr /C:"DATABASE_URL=mysql://" .env >nul
if !errorlevel! neq 0 (
    echo   ⚠ DATABASE_URL no está configurado para MySQL
    echo   Actualizando DATABASE_URL...
    powershell -Command "(Get-Content .env) -replace 'DATABASE_URL=.*', 'DATABASE_URL=mysql://prevencion_user:prevencion123@127.0.0.1:3306/prevencion?serverVersion=8.0&charset=utf8mb4' | Set-Content .env"
    echo   ✓ DATABASE_URL actualizado
)
cd ..
echo.

REM Paso 4: Instalar dependencias de Composer
echo [4/7] Verificando dependencias de Composer...
set COMPOSER_CMD=
where composer >nul 2>&1
if !errorlevel! equ 0 (
    set COMPOSER_CMD=composer
) else if exist "current\composer.phar" (
    echo   Encontrado composer.phar local
    set COMPOSER_CMD=php current\composer.phar
) else (
    echo   ⚠ Composer no está instalado o no está en el PATH
    echo   Instala Composer desde https://getcomposer.org/download/
    echo   Luego ejecuta: cd current ^&^& composer install --no-dev --optimize-autoloader
    set COMPOSER_CMD=
)

if not "!COMPOSER_CMD!"=="" (
    cd current
    if not exist "vendor" (
        echo   Instalando dependencias de Composer (esto puede tardar varios minutos)
        !COMPOSER_CMD! install --no-dev --optimize-autoloader --no-interaction
        if !errorlevel! equ 0 (
            echo   ✓ Dependencias instaladas
        ) else (
            echo   ✗ Error instalando dependencias
            echo   Intenta ejecutar manualmente: cd current ^&^& composer install --no-dev --optimize-autoloader
        )
    ) else (
        echo   ✓ Dependencias ya instaladas
    )
    cd ..
) else (
    echo   ⚠ Saltando instalación de dependencias (Composer no disponible)
)
echo.

REM Paso 5: Configurar permisos y cache
echo [5/7] Configurando permisos y cache...
cd current

REM Crear directorios necesarios
if not exist "var" mkdir var
if not exist "var\cache" mkdir var\cache
if not exist "var\log" mkdir var\log
if not exist "var\sessions" mkdir var\sessions

REM Limpiar cache
echo   Limpiando cache...
php bin/console cache:clear --env=prod --no-warmup >nul 2>&1
if !errorlevel! equ 0 (
    echo   ✓ Cache limpiado
) else (
    echo   ⚠ No se pudo limpiar cache (puede ser normal si es la primera vez)
)

REM Warmup cache
echo   Calentando cache...
php bin/console cache:warmup --env=prod >nul 2>&1
if !errorlevel! equ 0 (
    echo   ✓ Cache calentado
) else (
    echo   ⚠ No se pudo calentar cache
)
cd ..
echo.

REM Paso 6: Verificar conexión a base de datos
echo [6/7] Verificando conexión a base de datos...
cd current
php bin/console doctrine:schema:validate --env=prod >nul 2>&1
if !errorlevel! equ 0 (
    echo   ✓ Conexión a base de datos OK
) else (
    echo   ⚠ Problemas con la conexión a base de datos (puede ser normal)
    echo   Verifica el archivo .env si hay problemas
)
cd ..
echo.

REM Paso 7: Resumen final
echo [7/7] Resumen final...
echo.
echo ========================================
echo ✓ DESPLIEGUE COMPLETADO
echo ========================================
echo.
echo Estado de los servicios:
docker ps --filter "name=prevencio" --format "table {{.Names}}\t{{.Status}}\t{{.Ports}}"
echo.
echo Próximos pasos:
echo 1. Configurar IIS apuntando a: %CD%\current\public
echo 2. Asegúrate de que PHP esté instalado y configurado en IIS
echo 3. Verifica que el puerto 3306 esté accesible para la aplicación
echo.
echo Credenciales MySQL:
echo   Host: localhost:3306
echo   Usuario: prevencion_user
echo   Password: prevencion123
echo   Base de datos: prevencion
echo.
echo La aplicación está lista para recibir cambios via git pull
echo.
pause


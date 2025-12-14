@echo off
setlocal enabledelayedexpansion
chcp 65001 >nul

echo ============================================================
echo   RESTAURAR BASE DE DATOS POSTGRESQL
echo ============================================================

cd /d C:\Users\Administrador\Desktop\Prevencio\prevencio-meditrauma

echo.
echo [1/5] Deteniendo contenedores MySQL antiguos...
docker stop prevencio_mysql prevencio_mysql_stats prevencio_mysql_queue 2>nul
docker rm prevencio_mysql prevencio_mysql_stats prevencio_mysql_queue 2>nul
echo       OK - Contenedores MySQL detenidos

echo.
echo [2/5] Iniciando contenedores PostgreSQL...
docker-compose up -d
timeout /t 15 /nobreak >nul
echo       OK - PostgreSQL iniciado

echo.
echo [3/5] Verificando conexion a PostgreSQL...
docker exec prevencio_postgres pg_isready -U prevencion -d prevencion
if %ERRORLEVEL% neq 0 (
    echo       Esperando a que PostgreSQL este listo...
    timeout /t 10 /nobreak >nul
)

echo.
echo [4/5] Restaurando base de datos principal (esto puede tardar varios minutos)...
echo       Archivo: BBDDs\dump-prevencion-202511120956\dump-prevencion-202511120956.sql
echo       Tamano: ~725MB - Por favor espera...

docker cp "C:\Users\Administrador\Desktop\Prevencio\BBDDs\dump-prevencion-202511120956\dump-prevencion-202511120956.sql" prevencio_postgres:/tmp/dump.sql
if %ERRORLEVEL% neq 0 (
    echo [ERROR] No se pudo copiar el archivo de dump
    pause
    exit /b 1
)

docker exec prevencio_postgres psql -U prevencion -d prevencion -f /tmp/dump.sql
if %ERRORLEVEL% neq 0 (
    echo [WARNING] Algunos errores durante la restauracion (puede ser normal)
) else (
    echo       OK - Base de datos restaurada
)

echo.
echo [5/5] Verificando tablas...
docker exec prevencio_postgres psql -U prevencion -d prevencion -c "SELECT COUNT(*) as total_tablas FROM information_schema.tables WHERE table_schema = 'public';"

echo.
echo ============================================================
echo   RESTAURACION COMPLETADA
echo ============================================================
echo.
echo Ahora ejecuta: setup-scripts\update-env-postgres.bat
echo.
pause


@echo off
echo Restaurando bases de datos en Docker...
echo.

REM Esperar a que PostgreSQL esté listo
timeout /t 10

REM Buscar archivos .sql en BBDDs o db (compatibilidad)
set BBDD_PATH=BBDDs
if not exist "%BBDD_PATH%" set BBDD_PATH=db

REM Restaurar base de datos principal
echo Restaurando prevencion...
if exist "%BBDD_PATH%\dump-prevencion-202511120956.sql" (
    docker exec -i prevencio_postgres psql -U postgres -d prevencion < "%BBDD_PATH%\dump-prevencion-202511120956.sql"
) else if exist "%BBDD_PATH%\dump-prevencion-202511120956\*.sql" (
    for %%f in ("%BBDD_PATH%\dump-prevencion-202511120956\*.sql") do (
        echo Restaurando %%f...
        docker exec -i prevencio_postgres psql -U postgres -d prevencion < "%%f"
    )
) else (
    echo ✗ No se encontró dump-prevencion-202511120956.sql
)

if %errorlevel% equ 0 (
    echo ✓ Base de datos prevencion restaurada correctamente
) else (
    echo ✗ Error restaurando prevencion
)

REM Restaurar stats (opcional)
if exist "%BBDD_PATH%\dump-stats_meditrauma-202511121025.sql" (
    echo Restaurando stats_meditrauma...
    docker exec -i prevencio_postgres_stats psql -U postgres -d stats_meditrauma < "%BBDD_PATH%\dump-stats_meditrauma-202511121025.sql"
    if %errorlevel% equ 0 (
        echo ✓ Base de datos stats_meditrauma restaurada correctamente
    )
) else if exist "%BBDD_PATH%\dump-stats_meditrauma-202511121025\*.sql" (
    for %%f in ("%BBDD_PATH%\dump-stats_meditrauma-202511121025\*.sql") do (
        echo Restaurando %%f...
        docker exec -i prevencio_postgres_stats psql -U postgres -d stats_meditrauma < "%%f"
    )
)

REM Restaurar queue (opcional)
if exist "%BBDD_PATH%\dump-openqueue-202511121025.sql" (
    echo Restaurando openqueue...
    docker exec -i prevencio_postgres_queue psql -U postgres -d openqueue < "%BBDD_PATH%\dump-openqueue-202511121025.sql"
    if %errorlevel% equ 0 (
        echo ✓ Base de datos openqueue restaurada correctamente
    )
) else if exist "%BBDD_PATH%\dump-openqueue-202511121025\*.sql" (
    for %%f in ("%BBDD_PATH%\dump-openqueue-202511121025\*.sql") do (
        echo Restaurando %%f...
        docker exec -i prevencio_postgres_queue psql -U postgres -d openqueue < "%%f"
    )
)

echo.
echo ¡Restauración completada!
pause

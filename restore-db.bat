@echo off
setlocal enabledelayedexpansion
echo Restaurando bases de datos en Docker...
echo.

REM Esperar a que PostgreSQL esté listo
timeout /t 10

REM Buscar archivos .sql en BBDDs o db (compatibilidad)
REM Buscar en directorio actual primero, luego en directorio padre
set BBDD_PATH=BBDDs
if not exist "%BBDD_PATH%" set BBDD_PATH=db
if not exist "%BBDD_PATH%" set BBDD_PATH=..\BBDDs
if not exist "%BBDD_PATH%" set BBDD_PATH=..\db

echo Buscando archivos SQL en: %BBDD_PATH%
if not exist "%BBDD_PATH%" (
    echo ✗ ERROR: No se encontró la carpeta BBDDs o db
    echo.
    echo Buscado en:
    echo   .\BBDDs
    echo   .\db
    echo   ..\BBDDs
    echo   ..\db
    echo.
    echo Por favor, crea la carpeta BBDDs y coloca los archivos SQL dentro.
    echo Estructura esperada:
    echo   BBDDs\dump-prevencion-202511120956\*.sql
    echo   BBDDs\dump-stats_meditrauma-202511121025\*.sql
    echo   BBDDs\dump-openqueue-202511121025\*.sql
    pause
    exit /b 1
)

echo ✓ Carpeta encontrada: %BBDD_PATH%
echo.

REM Restaurar base de datos principal
echo Restaurando prevencion...
set FOUND=0

if exist "%BBDD_PATH%\dump-prevencion-202511120956.sql" (
    echo   Encontrado: %BBDD_PATH%\dump-prevencion-202511120956.sql
    docker exec -i prevencio_postgres psql -U postgres -d prevencion < "%BBDD_PATH%\dump-prevencion-202511120956.sql"
    set FOUND=1
) else if exist "%BBDD_PATH%\dump-prevencion-202511120956" (
    echo   Buscando en carpeta: %BBDD_PATH%\dump-prevencion-202511120956
    echo   Contenido de la carpeta:
    dir /b "%BBDD_PATH%\dump-prevencion-202511120956" 2>nul
    echo.
    for %%f in ("%BBDD_PATH%\dump-prevencion-202511120956\*.sql") do (
        echo   Encontrado: %%f
        docker exec -i prevencio_postgres psql -U postgres -d prevencion < "%%f"
        if !errorlevel! equ 0 (
            set FOUND=1
        )
    )
)

if %FOUND% equ 0 (
    echo ✗ No se encontró dump-prevencion-202511120956.sql
    echo   Buscado en: %BBDD_PATH%\dump-prevencion-202511120956.sql
    echo   Buscado en: %BBDD_PATH%\dump-prevencion-202511120956\*.sql
    if exist "%BBDD_PATH%\dump-prevencion-202511120956" (
        echo   La carpeta existe pero no contiene archivos .sql
    )
) else (
    echo ✓ Base de datos prevencion restaurada correctamente
)

REM Restaurar stats (opcional)
echo.
echo Restaurando stats_meditrauma...
set FOUND_STATS=0

if exist "%BBDD_PATH%\dump-stats_meditrauma-202511121025.sql" (
    echo   Encontrado: %BBDD_PATH%\dump-stats_meditrauma-202511121025.sql
    docker exec -i prevencio_postgres_stats psql -U postgres -d stats_meditrauma < "%BBDD_PATH%\dump-stats_meditrauma-202511121025.sql"
    set FOUND_STATS=1
    if %errorlevel% equ 0 (
        echo ✓ Base de datos stats_meditrauma restaurada correctamente
    )
) else if exist "%BBDD_PATH%\dump-stats_meditrauma-202511121025" (
    echo   Buscando en carpeta: %BBDD_PATH%\dump-stats_meditrauma-202511121025
    echo   Contenido de la carpeta:
    dir /b "%BBDD_PATH%\dump-stats_meditrauma-202511121025" 2>nul
    echo.
    for %%f in ("%BBDD_PATH%\dump-stats_meditrauma-202511121025\*.sql") do (
        echo   Encontrado: %%f
        docker exec -i prevencio_postgres_stats psql -U postgres -d stats_meditrauma < "%%f"
        if !errorlevel! equ 0 (
            set FOUND_STATS=1
        )
    )
)

if %FOUND_STATS% equ 0 (
    echo   ⚠ No se encontró dump-stats_meditrauma-202511121025.sql (opcional)
)

REM Restaurar queue (opcional)
echo.
echo Restaurando openqueue...
set FOUND_QUEUE=0

if exist "%BBDD_PATH%\dump-openqueue-202511121025.sql" (
    echo   Encontrado: %BBDD_PATH%\dump-openqueue-202511121025.sql
    docker exec -i prevencio_postgres_queue psql -U postgres -d openqueue < "%BBDD_PATH%\dump-openqueue-202511121025.sql"
    set FOUND_QUEUE=1
    if %errorlevel% equ 0 (
        echo ✓ Base de datos openqueue restaurada correctamente
    )
) else if exist "%BBDD_PATH%\dump-openqueue-202511121025" (
    echo   Buscando en carpeta: %BBDD_PATH%\dump-openqueue-202511121025
    echo   Contenido de la carpeta:
    dir /b "%BBDD_PATH%\dump-openqueue-202511121025" 2>nul
    echo.
    for %%f in ("%BBDD_PATH%\dump-openqueue-202511121025\*.sql") do (
        echo   Encontrado: %%f
        docker exec -i prevencio_postgres_queue psql -U postgres -d openqueue < "%%f"
        if !errorlevel! equ 0 (
            set FOUND_QUEUE=1
        )
    )
)

if %FOUND_QUEUE% equ 0 (
    echo   ⚠ No se encontró dump-openqueue-202511121025.sql (opcional)
)

echo.
echo ¡Restauración completada!
pause

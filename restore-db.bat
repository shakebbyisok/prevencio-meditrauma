@echo off
setlocal enabledelayedexpansion
echo Restaurando bases de datos MySQL en Docker...
echo.

REM Esperar a que MySQL esté listo
echo Esperando a que MySQL esté listo...
timeout /t 15
echo Verificando conexión a MySQL...
docker exec prevencio_mysql mysqladmin ping -h localhost -u root -proot123 >nul 2>&1
if !errorlevel! neq 0 (
    echo ⚠ MySQL aún no está listo, esperando más tiempo...
    timeout /t 10
)

REM Buscar archivos .sql en BBDDs o db (compatibilidad)
REM Buscar primero en directorio padre (donde normalmente están), luego en actual
set BBDD_PATH=..\BBDDs
if not exist "%BBDD_PATH%" set BBDD_PATH=..\db
if not exist "%BBDD_PATH%" set BBDD_PATH=BBDDs
if not exist "%BBDD_PATH%" set BBDD_PATH=db

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
set "FULL_PATH=%CD%\%BBDD_PATH%"
if "%BBDD_PATH:~0,3%"=="..\" (
    pushd "%BBDD_PATH%" >nul 2>&1
    set "FULL_PATH=%CD%"
    popd >nul 2>&1
)
echo   Ruta completa: %FULL_PATH%
echo   Verificando contenido...
set HAS_CONTENT=0
if exist "%BBDD_PATH%\dump-prevencion-*" set HAS_CONTENT=1
if exist "%BBDD_PATH%\dump-stats_*" set HAS_CONTENT=1
if exist "%BBDD_PATH%\dump-openqueue-*" set HAS_CONTENT=1
if %HAS_CONTENT% equ 0 (
    echo   ⚠ ADVERTENCIA: La carpeta parece estar vacía o no tiene las subcarpetas esperadas
    echo   Contenido encontrado:
    dir /b "%BBDD_PATH%" 2>nul | findstr /v "^$" || echo     (vacía)
)
echo.

REM Restaurar base de datos principal
echo Restaurando prevencion...
set FOUND=0

if exist "%BBDD_PATH%\dump-prevencion-202511120956.sql" (
    echo   Encontrado: %BBDD_PATH%\dump-prevencion-202511120956.sql
    echo   Restaurando (esto puede tardar varios minutos...)
    docker exec -i prevencio_mysql mysql -u root -proot123 prevencion < "%BBDD_PATH%\dump-prevencion-202511120956.sql"
    set FOUND=1
) else if exist "%BBDD_PATH%\dump-prevencion-202511120956" (
    echo   Buscando en carpeta: %BBDD_PATH%\dump-prevencion-202511120956
    echo   Contenido de la carpeta:
    dir /b "%BBDD_PATH%\dump-prevencion-202511120956" 2>nul | findstr /v "^$" || echo     (vacía)
    echo   Archivos .sql encontrados:
    pushd "%BBDD_PATH%\dump-prevencion-202511120956" >nul 2>&1
    if !errorlevel! equ 0 (
        set SQL_COUNT=0
        for %%f in (*.sql) do (
            echo     - %%f
            set /a SQL_COUNT+=1
        )
        if !SQL_COUNT! equ 0 (
            echo     (ninguno)
        ) else (
            echo   Restaurando archivos...
            for %%f in (*.sql) do (
            echo     Restaurando: %%f (esto puede tardar varios minutos...)
            docker exec -i prevencio_mysql mysql -u root -proot123 prevencion < "%%f"
                if !errorlevel! equ 0 (
                    set FOUND=1
                    echo     ✓ Restaurado correctamente
                ) else (
                    echo     ✗ Error al restaurar (ver errores arriba)
                )
            )
        )
        popd >nul 2>&1
    ) else (
        echo   ⚠ No se pudo acceder a la carpeta, intentando con ruta completa...
        for %%f in ("%BBDD_PATH%\dump-prevencion-202511120956\*.sql") do (
            echo   Encontrado: %%f
            echo   Restaurando: %%f (esto puede tardar varios minutos...)
            docker exec -i prevencio_mysql mysql -u root -proot123 prevencion < "%%f"
            if !errorlevel! equ 0 (
                set FOUND=1
                echo   ✓ Restaurado correctamente
            ) else (
                echo   ✗ Error al restaurar (ver errores arriba)
            )
        )
    )
    echo.
)

if %FOUND% equ 0 (
    echo ✗ No se encontró dump-prevencion-202511120956.sql
    echo   Buscado en: %BBDD_PATH%\dump-prevencion-202511120956.sql
    echo   Buscado en: %BBDD_PATH%\dump-prevencion-202511120956\*.sql
    if exist "%BBDD_PATH%\dump-prevencion-202511120956" (
        echo   ⚠ La carpeta existe pero no contiene archivos .sql
        echo   Verifica que los archivos .sql estén dentro de la carpeta
    ) else (
        echo   ⚠ La carpeta dump-prevencion-202511120956 no existe
        echo   Verifica la estructura en: %BBDD_PATH%
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
    docker exec -i prevencio_mysql_stats mysql -u root -proot123 stats_meditrauma < "%BBDD_PATH%\dump-stats_meditrauma-202511121025.sql"
    set FOUND_STATS=1
    if !errorlevel! equ 0 (
        echo ✓ Base de datos stats_meditrauma restaurada correctamente
    )
) else if exist "%BBDD_PATH%\dump-stats_meditrauma-202511121025" (
    echo   Buscando en carpeta: %BBDD_PATH%\dump-stats_meditrauma-202511121025
    pushd "%BBDD_PATH%\dump-stats_meditrauma-202511121025" >nul 2>&1
    if !errorlevel! equ 0 (
        for %%f in (*.sql) do (
            echo   Encontrado: %%f
            docker exec -i prevencio_mysql_stats mysql -u root -proot123 stats_meditrauma < "%%f"
            if !errorlevel! equ 0 (
                set FOUND_STATS=1
                echo   ✓ Restaurado correctamente
            )
        )
        popd >nul 2>&1
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
    docker exec -i prevencio_mysql_queue mysql -u root -proot123 openqueue < "%BBDD_PATH%\dump-openqueue-202511121025.sql"
    set FOUND_QUEUE=1
    if !errorlevel! equ 0 (
        echo ✓ Base de datos openqueue restaurada correctamente
    )
) else if exist "%BBDD_PATH%\dump-openqueue-202511121025" (
    echo   Buscando en carpeta: %BBDD_PATH%\dump-openqueue-202511121025
    pushd "%BBDD_PATH%\dump-openqueue-202511121025" >nul 2>&1
    if !errorlevel! equ 0 (
        for %%f in (*.sql) do (
            echo   Encontrado: %%f
            docker exec -i prevencio_mysql_queue mysql -u root -proot123 openqueue < "%%f"
            if !errorlevel! equ 0 (
                set FOUND_QUEUE=1
                echo   ✓ Restaurado correctamente
            )
        )
        popd >nul 2>&1
    )
)

if %FOUND_QUEUE% equ 0 (
    echo   ⚠ No se encontró dump-openqueue-202511121025.sql (opcional)
)

echo.
echo ¡Restauración completada!
pause

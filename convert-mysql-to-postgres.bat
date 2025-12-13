@echo off
echo ========================================
echo Convertidor MySQL a PostgreSQL
echo ========================================
echo.
echo Este script convertirá los dumps de MySQL a formato PostgreSQL
echo Requiere: sed (incluido en Git Bash) o PowerShell
echo.

set BBDD_PATH=..\BBDDs
if not exist "%BBDD_PATH%" set BBDD_PATH=BBDDs

echo Buscando dumps en: %BBDD_PATH%
echo.

REM Verificar si existe sed (Git Bash)
where sed >nul 2>&1
if %errorlevel% equ 0 (
    echo Usando sed para conversión...
    goto :convert_with_sed
) else (
    echo sed no encontrado, usando PowerShell...
    goto :convert_with_powershell
)

:convert_with_sed
echo.
echo Convirtiendo dump-prevencion...
if exist "%BBDD_PATH%\dump-prevencion-202511120956\dump-prevencion-202511120956.sql" (
    echo   Convirtiendo: dump-prevencion-202511120956.sql
    sed -e "s/`//g" -e "s/LOCK TABLES.*;//g" -e "s/UNLOCK TABLES;//g" -e "s/ENGINE=InnoDB//g" -e "s/DEFAULT CHARSET=.*//g" "%BBDD_PATH%\dump-prevencion-202511120956\dump-prevencion-202511120956.sql" > "%BBDD_PATH%\dump-prevencion-202511120956\dump-prevencion-postgres.sql"
    echo   ✓ Convertido a: dump-prevencion-postgres.sql
)
goto :end

:convert_with_powershell
echo.
echo IMPORTANTE: La conversión automática de MySQL a PostgreSQL es compleja.
echo.
echo Opciones:
echo 1. Usar herramienta externa como 'mysql2pgsql' o 'pgloader'
echo 2. Cambiar docker-compose.yml para usar MySQL en lugar de PostgreSQL
echo 3. Obtener dumps originales en formato PostgreSQL
echo.
echo Para una conversión manual básica, puedes usar:
echo.
echo PowerShell -Command "(Get-Content '%BBDD_PATH%\dump-prevencion-202511120956\dump-prevencion-202511120956.sql') -replace '`', '' -replace 'LOCK TABLES.*;', '' -replace 'UNLOCK TABLES;', '' | Set-Content '%BBDD_PATH%\dump-prevencion-202511120956\dump-prevencion-postgres.sql'"
echo.
goto :end

:end
echo.
echo ========================================
echo NOTA: La conversión completa requiere ajustes manuales adicionales:
echo - Tipos de datos (INT -> INTEGER, etc.)
echo - Auto-increment (AUTO_INCREMENT -> SERIAL)
echo - Comillas dobles para identificadores
echo - Funciones específicas de MySQL
echo ========================================
pause


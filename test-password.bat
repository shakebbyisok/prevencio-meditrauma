@echo off
setlocal enabledelayedexpansion
chcp 65001 >nul 2>&1
echo.
echo ============================================================
echo   VERIFICAR CONTRASEÑA DEL USUARIO ADMIN
echo ============================================================
echo.

cd /d "%~dp0\current"

echo [INFO] Verificando contraseña del usuario admin...
echo       Usuario: admin
echo       Contraseña a verificar: admin6291
echo.

REM Ejecutar script PHP para verificar la contraseña
php -r "require 'vendor/autoload.php'; use Symfony\Component\Dotenv\Dotenv; if (file_exists('.env')) { \$dotenv = new Dotenv(); \$dotenv->load('.env'); } \$kernel = new \App\Kernel(\$_ENV['APP_ENV'] ?? 'prod', false); \$kernel->boot(); \$container = \$kernel->getContainer(); \$userManager = \$container->get('fos_user.user_manager'); \$user = \$userManager->findUserByUsername('admin'); if (!\$user) { echo '[ERROR] Usuario admin no encontrado\n'; exit(1); } \$passwordEncoder = \$container->get('security.password_encoder'); \$isValid = \$passwordEncoder->isPasswordValid(\$user, 'admin6291'); if (\$isValid) { echo '[OK] La contraseña es VALIDA\n'; echo 'Usuario: ' . \$user->getUsername() . '\n'; echo 'Email: ' . \$user->getEmail() . '\n'; echo 'Enabled: ' . (\$user->isEnabled() ? 'SI' : 'NO') . '\n'; echo 'Roles: ' . implode(', ', \$user->getRoles()) . '\n'; } else { echo '[ERROR] La contraseña es INVALIDA\n'; echo 'Hash almacenado: ' . \$user->getPassword() . '\n'; exit(1); }"

if !errorlevel! equ 0 (
    echo.
    echo ============================================================
    echo   VERIFICACION COMPLETADA
    echo ============================================================
    echo.
    echo   La contraseña del usuario admin es correcta.
    echo   Si aun no puedes iniciar sesion, el problema puede ser:
    echo   - Timeout de FastCGI (ejecuta fix-fastcgi-timeout.bat)
    echo   - Cache corrupto (ejecuta clear-cache.bat)
    echo   - Problema con la sesion de IIS
    echo.
) else (
    echo.
    echo ============================================================
    echo   ERROR EN LA VERIFICACION
    echo ============================================================
    echo.
    echo   La contraseña no coincide o hay un error.
    echo   Ejecuta create-admin-user.bat para recrear el usuario.
    echo.
)

pause


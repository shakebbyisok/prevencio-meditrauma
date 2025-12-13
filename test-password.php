<?php
// Script para verificar la contraseña del usuario admin
require __DIR__.'/current/vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;

// Cargar variables de entorno
if (file_exists(__DIR__.'/current/.env')) {
    $dotenv = new Dotenv();
    $dotenv->load(__DIR__.'/current/.env');
}

$kernel = new \App\Kernel($_ENV['APP_ENV'] ?? 'prod', false);
$kernel->boot();
$container = $kernel->getContainer();

echo "[INFO] Verificando contraseña del usuario admin...\n";
echo "       Usuario: admin\n";
echo "       Contraseña a verificar: admin6291\n\n";

try {
    $userManager = $container->get('fos_user.user_manager');
    $user = $userManager->findUserByUsername('admin');
    
    if (!$user) {
        echo "[ERROR] Usuario admin no encontrado\n";
        exit(1);
    }
    
    echo "[INFO] Usuario encontrado:\n";
    echo "       ID: " . $user->getId() . "\n";
    echo "       Username: " . $user->getUsername() . "\n";
    echo "       Email: " . $user->getEmail() . "\n";
    echo "       Enabled: " . ($user->isEnabled() ? 'SI' : 'NO') . "\n";
    
    // Verificar métodos disponibles antes de llamarlos
    if (method_exists($user, 'isLocked')) {
        echo "       Locked: " . ($user->isLocked() ? 'SI' : 'NO') . "\n";
    }
    if (method_exists($user, 'isExpired')) {
        echo "       Expired: " . ($user->isExpired() ? 'SI' : 'NO') . "\n";
    }
    if (method_exists($user, 'isCredentialsExpired')) {
        echo "       Credentials Expired: " . ($user->isCredentialsExpired() ? 'SI' : 'NO') . "\n";
    }
    
    echo "       Roles: " . implode(', ', $user->getRoles()) . "\n";
    echo "       Password Hash: " . substr($user->getPassword(), 0, 30) . "...\n\n";
    
    $passwordEncoder = $container->get('security.password_encoder');
    $isValid = $passwordEncoder->isPasswordValid($user, 'admin6291');
    
    if ($isValid) {
        echo "[OK] La contraseña es VALIDA\n";
        echo "\n";
        echo "============================================================\n";
        echo "  VERIFICACION COMPLETADA\n";
        echo "============================================================\n";
        echo "\n";
        echo "  La contraseña del usuario admin es correcta.\n";
        echo "  Si aun no puedes iniciar sesion, el problema puede ser:\n";
        echo "  - Timeout de FastCGI (ya configurado)\n";
        echo "  - Cache corrupto (ejecuta clear-cache.bat)\n";
        echo "  - Problema con la sesion de IIS\n";
        echo "\n";
    } else {
        echo "[ERROR] La contraseña es INVALIDA\n";
        echo "\n";
        echo "============================================================\n";
        echo "  ERROR EN LA VERIFICACION\n";
        echo "============================================================\n";
        echo "\n";
        echo "  La contraseña no coincide.\n";
        echo "  Ejecuta create-admin-user.bat para recrear el usuario.\n";
        echo "\n";
        exit(1);
    }
} catch (\Exception $e) {
    echo "[ERROR] Excepcion: " . $e->getMessage() . "\n";
    echo "       Trace: " . $e->getTraceAsString() . "\n";
    exit(1);
}


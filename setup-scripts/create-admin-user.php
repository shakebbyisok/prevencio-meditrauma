<?php
// Script para crear usuario admin usando FOSUserBundle UserManager
// Ejecutar: php create-admin-user.php

require __DIR__.'/../current/vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;

// Cargar variables de entorno
if (file_exists(__DIR__.'/../current/.env')) {
    $dotenv = new Dotenv();
    $dotenv->load(__DIR__.'/../current/.env');
}

$kernel = new \App\Kernel($_ENV['APP_ENV'] ?? 'prod', false);
$kernel->boot();
$container = $kernel->getContainer();

// Obtener el UserManager de FOSUserBundle
$userManager = $container->get('fos_user.user_manager');
$em = $container->get('doctrine.orm.entity_manager');
$connection = $em->getConnection();

// Verificar si el usuario existe usando SQL directo
$stmt = $connection->prepare("SELECT id FROM fos_user WHERE username = :username");
$stmt->execute(['username' => 'admin']);
$result = $stmt->fetch();

$userExists = ($result && isset($result['id']));

if ($userExists) {
    echo "Usuario 'admin' ya existe. Actualizando contraseña...\n";
    // Cargar usando findOneBy con solo username para evitar problema con rol_id
    try {
        $user = $em->getRepository(\App\Entity\User::class)->findOneBy(['username' => 'admin']);
    } catch (\Exception $e) {
        // Si falla por rol_id, usar SQL directo para actualizar
        echo "  Usando método alternativo (SQL directo)...\n";
        $passwordEncoder = $container->get('security.password_encoder');
        $tempUser = new \App\Entity\User();
        $tempUser->setUsername('admin');
        $encodedPassword = $passwordEncoder->encodePassword($tempUser, 'admin6291');
        
        $stmt = $connection->prepare("
            UPDATE fos_user 
            SET password = :password,
                salt = '',
                enabled = 1,
                locked = 0,
                expired = 0,
                credentials_expired = 0,
                roles = :roles,
                updated_at = NOW()
            WHERE username = :username
        ");
        $stmt->execute([
            'password' => $encodedPassword,
            'roles' => 'a:1:{i:0;s:16:"ROLE_SUPER_ADMIN";}',
            'username' => 'admin'
        ]);
        echo "✓ Contraseña actualizada usando SQL directo\n";
        exit(0);
    }
    
    if (!$user) {
        echo "[ERROR] No se pudo cargar el usuario existente\n";
        exit(1);
    }
} else {
    echo "Creando usuario 'admin'...\n";
    $user = $userManager->createUser();
    $user->setUsername('admin');
    $user->setEmail('admin@prevencio.local');
    $user->setEnabled(true);
    $user->setSuperAdmin(true);
    $user->setRoles(['ROLE_SUPER_ADMIN']);
    $user->setLocale('es');
}

// Establecer la contraseña usando setPlainPassword - FOSUserBundle manejará el hash
$user->setPlainPassword('admin6291');

// Actualizar campos canónicos y contraseña usando UserManager
$userManager->updateCanonicalFields($user);
$userManager->updatePassword($user);

// Guardar usando EntityManager
$em->persist($user);
$em->flush();

// Establecer centro_id y servicio_id usando SQL directo si las columnas existen
try {
    // Verificar si las columnas existen
    $columns = $connection->executeQuery("SHOW COLUMNS FROM fos_user LIKE 'centro_id'")->fetchAll();
    $hasCentroId = !empty($columns);
    
    $columns = $connection->executeQuery("SHOW COLUMNS FROM fos_user LIKE 'servicio_id'")->fetchAll();
    $hasServicioId = !empty($columns);
    
    if ($hasCentroId || $hasServicioId) {
        $updates = [];
        if ($hasCentroId) {
            $updates[] = "centro_id = 1";
        }
        if ($hasServicioId) {
            $updates[] = "servicio_id = 1";
        }
        if (!empty($updates)) {
            $sql = "UPDATE fos_user SET " . implode(", ", $updates) . " WHERE username = :username";
            $stmt = $connection->prepare($sql);
            $stmt->execute(['username' => 'admin']);
        }
    }
} catch (\Exception $e) {
    // Ignorar si las columnas no existen
    // echo "  [INFO] Columnas centro_id/servicio_id no disponibles: " . $e->getMessage() . "\n";
}

echo "✓ Usuario 'admin' creado/actualizado correctamente\n";
echo "\n";
echo "  Usuario: admin\n";
echo "  Contraseña: admin6291\n";
echo "  Puedes iniciar sesión en: http://localhost/index.php/login\n";
echo "\n";

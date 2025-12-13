<?php
// Script para crear usuario admin usando Symfony encoder
// Ejecutar: php create-admin-user.php

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

// Obtener el encoder de contraseñas de Symfony
$passwordEncoder = $container->get('security.password_encoder');

// Crear un usuario temporal para generar el hash con el encoder correcto
$tempUser = new \App\Entity\User();
$tempUser->setUsername('admin');
$encodedPassword = $passwordEncoder->encodePassword($tempUser, 'admin6291');

// Obtener la conexión de Doctrine para ejecutar SQL directo
$em = $container->get('doctrine.orm.entity_manager');
$connection = $em->getConnection();

// Verificar si el usuario ya existe usando SQL directo (evita problema con rol_id)
$stmt = $connection->prepare("SELECT COUNT(*) as count FROM fos_user WHERE username = :username");
$stmt->execute(['username' => 'admin']);
$result = $stmt->fetch();
$userExists = $result['count'] > 0;

// Generar salt aleatorio
$salt = bin2hex(random_bytes(32));

if ($userExists) {
    echo "Usuario 'admin' ya existe. Actualizando contraseña...\n";
    // Actualizar contraseña y campos necesarios
    $stmt = $connection->prepare("
        UPDATE fos_user 
        SET password = :password,
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
    echo "✓ Contraseña actualizada correctamente\n";
} else {
    echo "Creando usuario 'admin'...\n";
    // Insertar nuevo usuario con todos los campos requeridos
    $stmt = $connection->prepare("
        INSERT INTO fos_user (
            username, username_canonical, email, email_canonical,
            enabled, salt, password, locked, expired, credentials_expired,
            roles, created_at, updated_at, centro_id, servicio_id
        ) VALUES (
            :username, :username_canonical, :email, :email_canonical,
            1, :salt, :password, 0, 0, 0,
            :roles, NOW(), NOW(), 1, 1
        )
    ");
    $stmt->execute([
        'username' => 'admin',
        'username_canonical' => 'admin',
        'email' => 'admin@prevencio.local',
        'email_canonical' => 'admin@prevencio.local',
        'salt' => $salt,
        'password' => $encodedPassword,
        'roles' => 'a:1:{i:0;s:16:"ROLE_SUPER_ADMIN";}'
    ]);
    echo "✓ Usuario creado correctamente\n";
}

echo "\n";
echo "  Usuario: admin\n";
echo "  Contraseña: admin6291\n";
echo "  Puedes iniciar sesión en: http://localhost/index.php/login\n";
echo "\n";

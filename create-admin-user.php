<?php
// Script para crear usuario admin usando Symfony
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

// Obtener el entity manager
$em = $container->get('doctrine.orm.entity_manager');
$userRepo = $em->getRepository(\App\Entity\User::class);

// Verificar si el usuario ya existe
$existingUser = $userRepo->findOneBy(['username' => 'admin']);

if ($existingUser) {
    echo "Usuario 'admin' ya existe. Actualizando contraseña...\n";
    $user = $existingUser;
} else {
    echo "Creando usuario 'admin'...\n";
    $user = new \App\Entity\User();
    $user->setUsername('admin');
    $user->setUsernameCanonical('admin');
    $user->setEmail('admin@prevencio.local');
    $user->setEmailCanonical('admin@prevencio.local');
    $user->setEnabled(true);
    $user->setSuperAdmin(true);
    $user->setRoles(['ROLE_SUPER_ADMIN']);
    $user->setLocale('es');
}

// Establecer contraseña usando el encoder de FOSUserBundle
// FOSUserBundle usa bcrypt según security.yaml
$passwordEncoder = $container->get('security.password_encoder');
$encodedPassword = $passwordEncoder->encodePassword($user, 'admin6291');
$user->setPassword($encodedPassword);
$user->setPlainPassword(null); // Limpiar plain password

// Guardar
$em->persist($user);
$em->flush();

echo "✓ Usuario 'admin' creado/actualizado correctamente\n";
echo "  Usuario: admin\n";
echo "  Contraseña: admin6291\n";
echo "\n";

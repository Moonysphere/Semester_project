<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Entities\users;
use App\Repositories\UserRepository;

$adminEmail = 'admin@lorebase.com';
$adminUsername = 'admin';
$adminPassword = '12345678';

$userRepo = new UserRepository();

if ($userRepo->isEmail($adminEmail)) {
    echo "❌ L'admin existe déjà.\n";
    exit(1);
}

$admin = new users();
$admin->email = $adminEmail;
$admin->username = $adminUsername;
$admin->password = password_hash($adminPassword, PASSWORD_BCRYPT);
$admin->firstname = 'Admin';
$admin->lastname = 'Lorebase';
$admin->role = 'admin';

$success = $userRepo->register($admin);

if ($success) {
    echo "✅ Admin créé avec succès !\n";
    echo "   Email: $adminEmail\n";
    echo "   Username: $adminUsername\n";
    echo "   Password: $adminPassword\n";
    echo "\n⚠️  N'oublie pas de changer le mot de passe !\n";
} else {
    echo "❌ Erreur lors de la création de l'admin.\n";
    exit(1);
}

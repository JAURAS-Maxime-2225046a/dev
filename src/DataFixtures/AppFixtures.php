<?php

namespace App\DataFixtures;

use App\Entity\ApiLanguage;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Tableau des rôles possibles
        $roles = ['ROLE_USER', 'ROLE_ADMIN'];

        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setEmail($faker->unique()->email);

            // Choisir un rôle aléatoire parmi 'ROLE_USER' et 'ROLE_ADMIN'
            $role = $roles[array_rand($roles)];
            $user->setRoles([$role]);

            // Hasher le mot de passe
            $hashedPassword = $this->passwordHasher->hashPassword($user, 'password123');
            $user->setPassword($hashedPassword);

            // Persist l'utilisateur dans la base de données
            $manager->persist($user);
        }

        $languages = [
            ['code' => 'fr', 'name' => 'Français', 'isActive' => true],
            ['code' => 'en', 'name' => 'English', 'isActive' => false],
            ['code' => 'es', 'name' => 'Español', 'isActive' => false],
            ['code' => 'de', 'name' => 'Deutsch', 'isActive' => false],
        ];

        foreach ($languages as $data) {
            $language = new ApiLanguage();
            $language->setCode($data['code']);
            $language->setName($data['name']);
            $language->setIsActive($data['isActive']);

            $manager->persist($language);
        }

        $manager->flush();
    }
}
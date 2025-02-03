<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\UserAPI;
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

        // Création des UserAccount avec name et email
        for ($i = 0; $i < 10; $i++) {
            $userAccount = new UserAPI();
            $userAccount->setName('name'.$i);
            $userAccount->setEmail('email'.$i.'@gmail.com');

            // Persist l'UserAccount dans la base de données
            $manager->persist($userAccount);
        }

        // Sauvegarde tous les utilisateurs et UserAccount dans la base de données
        $manager->flush();
    }
}
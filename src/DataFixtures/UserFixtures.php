<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        $users = [
            [
                'email' => 'admin@bibliotheque.test',
                'password' => 'admin123',
                'roles' => ['ROLE_ADMIN'],
            ],
            [
                'email' => 'alice@bibliotheque.test',
                'password' => 'password',
                'roles' => [],
            ],
            [
                'email' => 'bob@bibliotheque.test',
                'password' => 'password',
                'roles' => [],
            ],
            [
                'email' => 'charlie@bibliotheque.test',
                'password' => 'password',
                'roles' => [],
            ],
            [
                'email' => 'david@bibliotheque.test',
                'password' => 'password',
                'roles' => [],
            ],
            [
                'email' => 'emma@bibliotheque.test',
                'password' => 'password',
                'roles' => [],
            ],
            [
                'email' => 'frank@bibliotheque.test',
                'password' => 'password',
                'roles' => [],
            ],
            [
                'email' => 'grace@bibliotheque.test',
                'password' => 'password',
                'roles' => [],
            ],
            [
                'email' => 'henry@bibliotheque.test',
                'password' => 'password',
                'roles' => [],
            ],
            [
                'email' => 'isabelle@bibliotheque.test',
                'password' => 'password',
                'roles' => [],
            ],
        ];

        foreach ($users as $data) {
            $user = new User();
            $user->setEmail($data['email']);
            $user->setRoles($data['roles']);

            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $data['password']
            );

            $user->setPassword($hashedPassword);

            $manager->persist($user);
        }

        $manager->flush();
    }
}

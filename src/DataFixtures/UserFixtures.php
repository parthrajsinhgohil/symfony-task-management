<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $names = [
            'John Doe', 'Jane Smith', 'Alice Johnson', 'Bob Brown',
            'Charlie Black', 'Diana Prince', 'Ethan Hunt', 'Fiona Apple',
            'George Washington', 'Hannah Montana', 'Ivy League', 'Jack Daniels',
            'Karen Gillan', 'Larry Page', 'Mona Lisa'
        ];

        foreach ($names as $name) {
            $user = new User();
            $user->setName($name);
            $user->setEmail(strtolower(str_replace(' ', '.', $name)) . '@example.com');
            $user->setCreatedAt(new DateTimeImmutable());
            $user->setUpdatedAt(new \DateTime());

            $manager->persist($user);
        }
        $manager->flush();
    }
}

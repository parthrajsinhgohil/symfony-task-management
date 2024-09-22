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
        $user1 = new User();
        $user1->setName('John Doe');
        $user1->setEmail('john@example.com');
        $user1->setCreatedAt(new \DateTimeImmutable());
        $user1->setUpdatedAt(new \DateTime());

        $user2 = new User();
        $user2->setName('Jane Smith');
        $user2->setEmail('jane@example.com');
        $user2->setCreatedAt(new \DateTimeImmutable());
        $user2->setUpdatedAt(new \DateTime());

        $manager->persist($user1);
        $manager->persist($user2);

        $manager->flush();
    }
}

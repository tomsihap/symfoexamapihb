<?php

namespace App\DataFixtures;

use App\Entity\Job;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class JobFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $job1 = new Job();
        $job1->setTitle("Cuisinier");
        $manager->persist($job1);

        $job2 = new Job();
        $job2->setTitle("Serveur");
        $manager->persist($job2);

        $job3 = new Job();
        $job3->setTitle("Commis de cuisine");
        $manager->persist($job3);

        $job4 = new Job();
        $job4->setTitle("Chef de rang");
        $manager->persist($job4);

        $job5 = new Job();
        $job5->setTitle("Manager");
        $manager->persist($job5);

        $manager->flush();
    }
}

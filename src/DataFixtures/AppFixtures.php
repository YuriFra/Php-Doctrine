<?php

namespace App\DataFixtures;

use App\Entity\Student;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager) : void
    {
        for ($i = 0; $i < 20; $i++) {
            $teacher = new Student();
            $teacher->setFirstName('first '.$i);
            $teacher->setFirstName('last '.$i);
            $teacher->setEmail('email '.$i);
            $teacher->setAddress('address '.$i);
            $manager->persist($teacher);
        }
        $manager->flush();
    }
}

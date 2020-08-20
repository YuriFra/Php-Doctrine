<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Student;
use App\Entity\Teacher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager) : void
    {
        $address = new Address('Meir', '10', '2000', 'Antwerp');
        $teacher = new Teacher();
        $teacher->setFirstName('Koen');
        $teacher->setLastName('Eelen');
        $teacher->setEmail('Koen@Becode.org');
        $teacher->setAddress($address);

        $studentAddress = new Address('Baan', '3', '2600', 'Antwerp');
        $student = new Student();
        $student->setFirstName('Yuri');
        $student->setLastName('Franken');
        $student->setEmail('Yuri@Becode.org');
        $student->setAddress($studentAddress);
        $student->setTeacher($teacher);

        $manager->persist($teacher);
        $manager->persist($student);
        $manager->flush();
    }
}

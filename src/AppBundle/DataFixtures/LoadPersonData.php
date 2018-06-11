<?php

namespace AppBundle\DataFixtures;


use AppBundle\Entity\Person;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadPersonData extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $person1 = new Person();
        $person1->setFirstName('Miro');
        $person1->setLastName('Las');
        $person1->setDateOfBirth(new \DateTime('1991-12-01'));

        $manager->persist($person1);
        $manager->flush();
    }
}
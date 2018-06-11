<?php

namespace AppBundle\DataFixtures;


use AppBundle\Entity\Movie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadMovieData extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $movie1 = new Movie();
        $movie1->setTitle('Green Mile');
        $movie1->setYear(1999);
        $movie1->setTime(189);
        $movie1->setDescription('Jus a movie description');

        $manager->persist($movie1);
        $manager->flush();
    }
}
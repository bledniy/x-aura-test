<?php

namespace App\DataFixtures;

use App\Entity\Resume;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ResumeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $resume = new Resume();

            $resume->setJobTitle($faker->colorName);
            $resume->setContent($faker->address);
            $resume->setFile(null);
            $resume->setCreatedAt($faker->dateTimeThisYear);
            $resume->setUpdatedAt($faker->dateTimeThisYear);

            $manager->persist($resume);
        }

        $manager->flush();
    }
}

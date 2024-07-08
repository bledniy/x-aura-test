<?php

namespace App\DataFixtures;

use App\ConstantBag\FeedbackStatusBag;
use App\Entity\Feedback;
use App\Entity\Resume;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class FeedbackFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $resumeRepository = $manager->getRepository(Resume::class);
        $resumes = $resumeRepository->findAll();

        foreach ($resumes as $resume) {
            for ($i = 0; $i < 5; $i++) {
                $feedback = new Feedback();

                $feedback->setResume($resume);
                $feedback->setIsPositive($faker->boolean());
                $feedback->setRecipient($faker->email);
                $feedback->setCreatedAt($faker->dateTimeThisYear);

                $manager->persist($feedback);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ResumeFixtures::class,
        ];
    }
}

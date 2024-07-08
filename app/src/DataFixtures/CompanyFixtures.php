<?php

namespace App\DataFixtures;

use App\Entity\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CompanyFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $companyNames = ['Company A', 'Company B', 'Company C'];

        foreach ($companyNames as $name) {
            $company = new Company();

            $company->setTitle($name);
            $company->setUrl('http://www.' . strtolower(str_replace(' ', '', $name)) . '.com');
            $company->setAddress('123 ' . $name . ' St.');
            $company->setPhone('123-456-7890');

            $manager->persist($company);
        }

        $manager->flush();
    }
}

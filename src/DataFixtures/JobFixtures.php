<?php

namespace App\DataFixtures;

use App\Entity\Job;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class JobFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $jobs = [
            "Data scientist",
            "Statisticien",
            "Médecin",
            "Mécanicien",
            "Electricien",
            "Bibliothécaire",
            "Artiste",
            "Déssinateur",
            "Mouleur",
            "Marin",
            "Grutier",
            "Développeur",
            "Marketing",
            "Professeur",
            "Instituteur",

        ];
        for ($i=0; $i < count($jobs); $i++) 
        { 
            $job = new Job();
            $job->setDesignation($jobs[$i]);            
            $manager->persist($job);
        }
        $manager->flush();
    }
}

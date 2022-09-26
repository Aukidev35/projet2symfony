<?php

namespace App\DataFixtures;

use App\Entity\Profil;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProfilFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $profil = new Profil();
        $profil->setRs('Facebook');
        $profil->setUrl('www.Facebook.com');

        $profil1 = new Profil();
        $profil1->setRs('Twitter');
        $profil1->setUrl('www.Twitter.com');

        $profil2 = new Profil();
        $profil2->setRs('Instagram');
        $profil2->setUrl('www.Instagram.com');

        $profil3 = new Profil();
        $profil3->setRs('Linkedin');
        $profil3->setUrl('www.Linkedin.com');

        $profil4 = new Profil();
        $profil4->setRs('GitHub');
        $profil4->setUrl('www.GitHub.com');

        $manager->persist($profil);
        $manager->persist($profil1);
        $manager->persist($profil2);
        $manager->persist($profil3);
        $manager->persist($profil4);

        $manager->flush();
    }
}

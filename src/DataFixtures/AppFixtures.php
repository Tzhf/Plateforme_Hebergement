<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Dispositif;
use App\Entity\Gestionnaire;
use App\Entity\Logement;
use App\Entity\Location;
use App\Entity\Occupant;
use App\Entity\Ville;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        for($i=0;$i<=4;$i++) {
            $dispositif = new Dispositif();
            $dispositif->setNom($faker->company());
            $manager->persist($dispositif);

            $ville = new Ville();
            $ville->setNom($faker->city());
            $manager->persist($ville);

            
            for($j=0;$j<=10;$j++) {
                $gestionnaire = new Gestionnaire();
                $gestionnaire->setUsername($faker->company());
                $gestionnaire->setEmail($faker->email());
                $gestionnaire->setPassword($faker->password());
                $gestionnaire->setVille($ville);
                $gestionnaire->setDispositif($dispositif);

                $manager->persist($gestionnaire);
                           
                for($k=0;$k<=10;$k++) {
                    $logement = new Logement();
                    $logement->setGestionnaire($gestionnaire);
                    $logement->setNumRue(rand(1, 500));
                    $logement->setNomRue($faker->streetName());
                    $logement->setVille($ville);
                    $logement->setNumBat(chr(rand(65, 70)));
                    $logement->setNumLgmt(rand(1, 90));
                    $logement->setEtage(rand(0, 10));
                    $logement->setTypologie('T'.rand(1,5));
                    $logement->setDescription('Super ce logement');
                    $logement->setCapacite(rand(1, 5));
                    
                    $manager->persist($logement);

                    $occupant = new Occupant();
                    $occupant->setNom($faker->firstName());
                    $occupant->setPrenom($faker->LastName());
                    
                    $manager->persist($occupant);

                    $location = new Location();
                    $location->setLogement($logement);
                    $location->setOccupant($occupant);
                    $location->setIsActive(1);
                    $location->setcreatedAt(new \DateTime());
                    
                    $manager->persist($location);
                }
            }
        }
        $manager->flush();
    }
}

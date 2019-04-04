<?php

namespace App\DataFixtures;

use App\Entity\Defibrillator;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture {

    private $_passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder) {
        $this->_passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager) {

        $faker = Factory::create('en_US');

        // Generate random users
//        for ($i = 1; $i <= 30; ++$i) {
//            $user = new User();
//            $user->setEmail($faker->email);
//            $user->setPassword(
//                $this->_passwordEncoder->encodePassword($user,'1234')
//            );
//            $user->setRoles(['ROLE_USER']);
//            $manager->persist($user);
//
//            $manager->flush();
//        }

        // Load defibrillators from OpenData files
        $jsonData = json_decode(
            file_get_contents(
                "https://data.toulouse-metropole.fr/explore/dataset/defibrillateurs/download/?format=json&timezone=Europe/Berlin"
            ),
            true
        );

        foreach ($jsonData as $data) {
            $defibrillator = new Defibrillator();
            $defibrillator->setLongitude($data['fields']['geo_point_2d'][1]);
            $defibrillator->setLatitude($data['fields']['geo_point_2d'][0]);

            $implantation = isset($data['fields']['implantation']) ? $data['fields']['implantation'] : '';

            $note = $data['fields']['nom_site'] ."<br />".
                $implantation .
                $data['fields']['adresse'] ."<br />".
                $data['fields']['accessibilite'];

            $defibrillator->setNote($note);

            $manager->persist($defibrillator);
        }

        $manager->flush();
    }
}

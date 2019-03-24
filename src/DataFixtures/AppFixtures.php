<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture {

    private $_passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder) {
        $this->_passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager) {

        $faker = \Faker\Factory::create('en_US');

        for ($i = 1; $i <= 30; ++$i) {
            $user = new User();
            $user->setEmail($faker->email);
            $user->setPassword(
                $this->_passwordEncoder->encodePassword($user,'1234')
            );
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);



            $manager->flush();
        }
    }
}

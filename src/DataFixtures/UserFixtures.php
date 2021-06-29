<?php
namespace App\DataFixtures;
use App\Entity\User ;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class UserFixtures extends Fixture


{  

	private  $passwordEncoder;

             public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder =$passwordEncoder;
    }



    public function load(ObjectManager $manager)
    {
         $product = new  User();

         $pass = $this->passwordEncoder->encodePassword($product, '12344456789');

    $product->setPassword($pass);
        $product->setEmail('halimsa@gmail.com');

         $manager->persist($product);

        $manager->flush();
    }
}

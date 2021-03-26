<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Image;
use App\Entity\Annonces;
use App\Entity\Commentaires;
use App\Entity\Role;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create("FR-fr");
        $adminRole = new Role;
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);
       
        $userAdmin = new User();
        $hash = $this->encoder->encodePassword($userAdmin, 'password');
        $userAdmin->setUsername('Muna')
                ->setLastName('Sakho')
                ->setDescription('Bla Bla Bla')
                ->setEmail('mainmouna.sakho@gmail.com')
                ->setAvatar("Mainmouna.jpg")
                ->setPassword($hash)
                ->addRole($adminRole)
             ;
            $manager->persist($userAdmin);

        $users = []; //declarer un tableau vide
        for($k = 1; $k <10 ; $k++)
        {
            $user = new User;
            $hash = $this->encoder->encodePassword($user, 'password');
            $username = $faker->userName();
            $email = $faker->email();
            $last_name = $faker->lastName();
            $description = $faker->sentence(12);
            $url = "https://randomuser.me/api/portraits/women/";
            $avatarId = mt_rand(1, 99);
            $avatar = $url.$avatarId.".jpg";           
             
            $user->setUsername($username)
                 ->setEmail($email)
                 ->setPassword($hash)
                 ->setLastName($last_name)
                 ->setAvatar($avatar)
                 ->setDescription($description)
                 ;
             $manager->persist($user);
             $users[] = $user; //chaque user viens s'ajouter au tableau users

        }

        for($i = 1; $i <= 20; $i++)
        {
        $user_id = $users[mt_rand(1, count($users)-1)];
        $titre = $faker->sentence(5);
        $adress = $faker->streetAddress();
        $description = $faker->sentence(12);
        $image = $faker->imageUrl(300, 350, 'city');
        $image = str_replace("https","http",$image);
        
        $annonce = new Annonces();
        $annonce->setTitre($titre)
                ->setAddress($adress)
                ->setPrix(mt_rand(200000, 500000))
                ->setDescription($description)
                ->setCoverImage($image)
                ->setChambre(mt_rand(2, 7))
                ->setAuteur($user_id)
        ;
        $manager->persist($annonce);

        for($j = 1; $j <4 ; $j++)
            {
            $legende = $faker->sentence(12);
            $url = $faker->imageUrl(300, 350, 'city');
            $url = str_replace("https","http",$url);  
            $images = new Image();
            $images->setUrl($url)
                    ->setLegende($legende)
                    ->setAnnonce($annonce)
            ;
            $manager->persist($images);
           } 
       
        for ($e=0;$e<3;$e++){

            $comment = new Commentaires();
            $comment->setContent($faker->sentence())
                    ->setAnnonceId($annonce);
            $manager->persist($comment);
        }
        }
        $manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use Faker;
use DateTime;
use App\Entity\User;
use App\Entity\Promo;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\MainColor;
use Doctrine\DBAL\Connection;
use App\Entity\SecondaryColor;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\Provider\MugOverflowProvider;

class AppFixtures extends Fixture
{
    private $connection;

    public function __construct(Connection $connection)
    {
        // Get connection of mysql BDD
        $this->connection = $connection;
    }

    /**
     * Set AI to 1 in BDD table and Truncate
     */
    private function truncate()
    {

        $this->connection->executeQuery('SET foreign_key_checks = 0');

        $this->connection->executeQuery('TRUNCATE TABLE category');
        $this->connection->executeQuery('TRUNCATE TABLE main_color');
        $this->connection->executeQuery('TRUNCATE TABLE product');
        $this->connection->executeQuery('TRUNCATE TABLE product_category');
        $this->connection->executeQuery('TRUNCATE TABLE product_user');
        $this->connection->executeQuery('TRUNCATE TABLE promo');
        $this->connection->executeQuery('TRUNCATE TABLE secondary_color');
        $this->connection->executeQuery('TRUNCATE TABLE user');
        // etc.
    }

    public function load(ObjectManager $manager): void
    {
        // Truncate manualy
        $this->truncate();

        // use the factory to create a Faker\Generator instance
        $faker = Faker\Factory::create('fr_FR');

        // to get everytime the same data
        $faker->seed(2021);

        // Our own data provider
        $MugOverflowProvider = new MugOverflowProvider();

        // We set Faker with out date provider
        $faker->addProvider($MugOverflowProvider);

        // Create 3 users :
        // superadmin
        $superAdmin = new User();
        $superAdmin->setEmail('superadmin@superadmin.com');
        $superAdmin->setRole(['ROLE_SUPERADMIN']);
        $superAdmin->setPassword('$2y$13$/XcKyU1CpuiamaCJkTiz7OddqrPuelpyrRK.WsGTkHh9kFIn0hu8y');
        $superAdmin->setFirstname('PrenomSuperAdmin');
        $superAdmin->setLastname('PrenomSuperAdmin');
        $superAdmin->setStatus(['STAFF']);
        $manager->persist($superAdmin);

        // admin
        $admin = new User();
        $admin->setEmail('admin@admin.com');
        $admin->setRole(['ROLE_ADMIN']);
        $admin->setPassword('$2y$13$BxiBaD6.LMpM8abdO/40few.yL/WKVpT2i7XcgL2vA6eSl1CKiopS');
        $admin->setFirstname('PrenomAdmin');
        $admin->setLastname('PrenomAdmin');
        $admin->setStatus(['STAFF']);
        $manager->persist($admin);

        // user
        $user = new User();
        $user->setEmail('user@user.com');
        $user->setRole(['ROLE_USER']);
        $user->setPassword('$2y$13$8ScmTQEkvTam.GE3.X4ol..Ayj6YkJ6Z.iQKsKaiD0aK6Y5ZFLt6O');
        $user->setFirstname('PrenomUser');
        $user->setLastname('NomUser');
        $user->setStatus(['STUDENT']);
        $manager->persist($user);

        $userList = [$superAdmin, $admin, $user];

        // Promo

        // Promo data array
        $promosList = [];

        for ($i = 1; $i <= 4; $i++) {

            // New promo
            $promo = new Promo();
            $promo->setName($faker->unique()->promoTitle());
            $promo->addUser($userList[mt_rand(0,2)]);

            // Add promo to the array
            $promosList[] = $promo;

            $manager->persist($promo);
        }

        // We add the users to a promo
        for ($i=0; $i < count($userList); $i++) { 
            $promosList[mt_rand(0,3)]->addUser($userList[mt_rand(0,2)]);
        }

        // Catégory

        // Category data array
        $categoriesList = [];

        for ($i = 1; $i <= 6; $i++) {

            // New Category
            $category = new Category();
            $category->setName($faker->unique()->productCategory());

            // Add the category to the array
            $categoriesList[] = $category;

            $manager->persist($category);
        }  


        // MainColor
        // Set a main color
        $mainColor = new MainColor();
        $mainColor->setMainColorName('White');
        $mainColor->setMainHexa('#FFFFFF');
        $mainColor->setStatus(1);

        $manager->persist($mainColor);

        // SecondaryColor
        // Set a secondary color
        $secondaryColor = new SecondaryColor();
        $secondaryColor->setSecondaryColorName('Black');
        $secondaryColor->setSecondaryHexa('#000000');
        $secondaryColor->setStatus(2);

        $manager->persist($secondaryColor);

        // Product
        for ($i=1; $i < 10; $i++) { 
            // Create new product
            $product = new Product();
            $product->setName($MugOverflowProvider->mugTitle(mt_rand(0, 9)));
            $product->addUser($userList[mt_rand(0,2)]);
            $product->setMainColor($mainColor);
            $product->setSecondaryColor($secondaryColor);
            $product->setCreatedAt(new DateTime());
            $product->setStatus(mt_rand(0,1));
            // Set random paragraph
            $product->setDescription($faker->paragraph(2));
            // Set images
            $product->setLogo('https://s3-eu-west-1.amazonaws.com/tpd/logos/595cae450000ff0005a600d6/0x0.png');
            $product->setImage('https://picsum.photos/id/'.mt_rand(1, 100).'/300/300');
            $product->setMockupBack('https://cdn.habitat.fr/thumbnails/product/1319/1319267/box/850/850/40/F4F4F4/mug-8cm-en-porcelaine-blanche_1319267.jpg');
            $product->setMockupFront('https://ih1.redbubble.net/image.1791840745.7490/ur,mug_lifestyle,tall_portrait,750x1000.jpg');
            
            // We add random number category by product
            for ($g = 1; $g <= mt_rand(1, 3); $g++) {
                $randomCategories = $categoriesList[mt_rand(0, count($categoriesList) - 1)];
                $product->addCategory($randomCategories);
                
            }

            $manager->persist($product);

        }

        $manager->flush();

    }


}

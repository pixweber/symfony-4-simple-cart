<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;

class ProductFixtures extends Fixture {

    public function load(ObjectManager $objectManager):void {

        $faker = FakerFactory::create('en_US');

        for ($i = 0; $i < 100; $i++) {
            $product = new Product();
            $product->setName($faker->text(30));
            $product->setImage($faker->imageUrl());
            $product->setPrice($faker->randomFloat(2, 100, 5000));
            $product->setCreatedAt(\DateTime::createFromFormat('Y-m-d H:i:s', Date('Y-m-d H:i:s')));
            $product->setUpdatedAt(\DateTime::createFromFormat('Y-m-d H:i:s', Date('Y-m-d H:i:s')));

            $objectManager->persist($product);
        }

        $objectManager->flush();
    }

}

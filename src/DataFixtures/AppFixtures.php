<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // create categories
        $category1 = new Category();
        $category1->setName('Category 1');
        $manager->persist($category1);

        $category2 = new Category();
        $category2->setName('Category 2');
        $manager->persist($category2);

        // create products
        $product1 = new Product();
        $product1->setTitle('Product 1');
        $product1->setPrice(10.99);
        $product1->setQty(5);
        $product1->setImg('https://via.placeholder.com/150');
        $product1->setCategory($category1);
        $product1->setRating(4);
        $manager->persist($product1);

        $product2 = new Product();
        $product2->setTitle('Product 2');
        $product2->setPrice(20.49);
        $product2->setQty(10);
        $product2->setImg('https://via.placeholder.com/150');
        $product2->setCategory($category2);
        $product2->setRating(3);
        $manager->persist($product2);

        $product3 = new Product();
        $product3->setTitle('Product 3');
        $product3->setPrice(30.99);
        $product3->setQty(3);
        $product3->setImg('https://via.placeholder.com/150');
        $product3->setCategory($category1);
        $product3->setRating(5);
        $manager->persist($product3);

        $manager->flush();
        // create 10 orders
        for ($i = 1; $i <= 10; $i++) {
            $order = new Order();
            $order->setCreatedAt(new \DateTimeImmutable());
            $order->setAdresse('Address ' . $i);

            // create random number of order items (between 1 and 4)
            $numItems = rand(1, 4);
            for ($j = 1; $j <= $numItems; $j++) {
                $orderItem = new OrderItem();

                // get random product
                $product = $manager->getRepository(Product::class)->find(1);

                $orderItem->setProd($product);
                $orderItem->setQty(rand(1, 5));

                $order->addOrderItem($orderItem);
                $manager->persist($orderItem);
            }

            $manager->persist($order);
        }

        $manager->flush();
    }
}

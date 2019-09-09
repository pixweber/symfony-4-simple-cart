# Symfony 4 Simple Cart

A very simple ecommerce cart using Symfony 4

## Instructions

You can install this project with Git and Composer, please follow these steps

Clone the repository

`git clone https://github.com/pixweber/symfony-4-simple-cart.git symfony-4-simple-cart`

Install all dependencies and configure parameters

`composer install` 

Create a new database 

`php bin/console doctrine:database:create`

Create database schema

`php bin/console doctrine:schema:update --dump-sql`

Change database credentials to yours in .env

`DATABASE_URL=mysql://master:master@127.0.0.1:3306/symfony_cart_dev`

## Demo

https://symfony-4-simple-cart.herokuapp.com

## Support

If you need help or encounter some problem, please submit a new issue. I will be more than happy to help.

Pixweber
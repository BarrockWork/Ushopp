# Ushopp

# Introduction
Site E-commerce de ventes de produits mangas

# Pré-requis
Pour lancer le projet vous aurez besoin de la configuration suivante :
* [Composer](https://getcomposer.org/)
* [MariaDB](https://mariadb.com/kb/en/where-to-download-mariadb/#the-latest-packages) >= v10.2
* [Php](https://www.php.net/manual/fr/install.php) >= v7.2.5
* [Nodejs >= v14](https://nodejs.org/en/download/) + [Yarn >= v1.22 (préférence)](https://yarnpkg.com/getting-started/install) ou Npm >= v6.14

[Aide Linux](https://codereviewvideos.com/course/symfony-deployment/video/symfony-4-lamp-stack)

# Stack technique
* [Symfony 5.2](https://symfony.com/doc/current/setup.html)
* [Twig](https://twig.symfony.com/)
* [React.js](https://fr.reactjs.org/) 
* [Bootstrap 4](https://getbootstrap.com/)
* [Webpack encore](https://symfony.com/doc/current/frontend.html)
* [EasyAdmin v3](https://symfony.com/doc/current/bundles/EasyAdminBundle/index.html)
* [Cocur/Slugify](https://github.com/cocur/slugify)
* [Stripe php](https://github.com/stripe/stripe-php)
* [Testing](https://symfony.com/doc/current/testing.html#the-phpunit-testing-framework)
* [Mailjet (En attente)](https://fr.mailjet.com/)

# Pour initialiser le projet

#### Créer son fichier .env.local si environneent de dev qui contiendra les informations de sa base de données. Sinon les commandes suivantes ne pourront pas fonctionner !
Exemple avec MariaDB: DATABASE_URL=mysql://db_user:db_password$@127.0.0.1:3306/ushopp?serverVersion=10.2.10-MariaDB

```
- composer install 
- php bin/console doctrine:database:create
- php bin/console doctrine:migrations:migrate
- npm install --force ou yarn install --force
- yarn encore dev npm run dev (Voir https://symfony.com/doc/current/frontend/encore/simple-example.html)
```
> Pour les tests avec phpunit il faut configuer les informations de la base de données dans le fichier .env.test


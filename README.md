# Recipes

Site de recettes de cuisine en Symfony 7.0

Page d'accueil en français: http://localhost:8000/fr/
HomePage in English: http://localhost:8000/en/

Adapté à partir d'un tutoriel de Grafikart

## Installation
- Installer les dépendances : 
	- `composer install`
- Créer la Base de données : 
	- `php bin/console doctrine:database:create`
- Créer un fichier ``.env.local`` avec ``DATABASE_URL`` :
	- `DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=8.0.31&charset=utf8mb4"`
- Exécuter les migrations présentes dans le projet : 
	- `php bin/console doctrine:migrations:migrate`
- Exécuter les fixtures : 
	- `php bin/console doctrine:fixtures:load`
- Configurer la boîte mail dans `.env.local` :
	- ``MAILER_DSN=smtp:null://localhost:1025``

- Ouvrir un serveur Symfony (pour symfony UX)
	- `symfony serve`

## Images du site

<img src="docs/home.png" alt="screenshot" style="width:500px;"/>
<img src="docs/contact.png" alt="screenshot" style="width:500px;"/>
<img src="docs/recettes.png" alt="screenshot" style="width:500px;"/>
<img src="docs/ajout-recette.png" alt="screenshot" style="width:500px;"/>
<img src="docs/categories.png" alt="screenshot" style="width:500px;"/>

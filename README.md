# Recipes

- `composer install`
- création du .env.local avec la DATABASE_URL :
`DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=8.0.31&charset=utf8mb4"`
- recréation de la bdd : 
`bin/console doctrine:database:create`
- exécuter les migrations présentes dans le projet (s'il y en a) : 
`bin/console doctrine:migrations:migrate`

- Configurer la boîte mail dans `.env.local`
	- ``MAILER_DSN=smtp:null://localhost:1025``

    
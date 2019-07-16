#Fuvahmulah Map API

This repository is the home to the backend api that powers Fuvahmulah Map project. It is based on Laravel 5.8.


### Setup
On terminal.
```bash
git clone git@github.com:fuvahmulah/maps-api.git
cd maps-api
composer install
npm install
cp .env.example .env
``` 

Open `.env` on your favourite editor and set the environment variables. Please note that if you are going to be using social authentication you would have to configure the credentials for them. 

Once you have set the environment variables, you may continue with...
```bash
php artisan key:generate
php artisan migrate
php artisan passport:client
```

Now you will be prompted to set up passport client. Once done you are ready to run the application.

### Seeding Data
You can seed data by running `php artisan db:seed` command.

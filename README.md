# Welcome to our awesome social network


## Get started

#### Clone the project:
```
git clone https://github.com/danielyandev/social.git
```

#### Copy .env.example to .env file
```
cp .env.example .env
```

#### Install dependencies
```
composer install
npm install
```

#### Compile assets
```
npm run prod
```

#### Migrate
```
php artisan migrate
```

#### Install laravel passport
```
php artisan passport:install
```

You will get client id and client secret, place them to .env file.
```
PASSPORT_PASSWORD_CLIENT_ID=
PASSPORT_PASSWORD_CLIENT_SECRET=
```

## Have fun
Register, find your friends and have a good time.

## For developers
Visit {your domain}/api/docs for api documentation

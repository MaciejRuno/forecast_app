# Forecast App

My portfolio app written in Symfony

### Prerequisites

* docker
* docker-compose
* php7.4
* composer

### Installing

1. Clone repo
```
git clone git@github.com:MaciejRuno/forecast_app.git .
```

2. Run composer install
```
composer install
```

3. Copy and fill in .env.local file
```
cp .env.local.dist .env.local
```

4. Run docker-compose
```
docker-compose up -d
```

### Running migrations
```
docker-compose exec app bin/console doctrine:migrations:migrate
```


# Github API Mock

## Aggiungere/aggiornare packagist fixture

Eseguire il comando 'wget' con l'url del package nella relativa directory

```bash

$ wget https://api.github.com/repos/seldaek/monolog

```

Rinominare il file nel fromato 'repository-{vendor-name}-{package-name}.json'

Se è una nuova fixture aggiornare il metodo loadFixtureToApiMockServer() e il metodo githubRepositoryUrlDataprovider()

```php

    /**
     * @return Generator<string, array<int, string>>
     */
    public function githubRepositoryUrlDataprovider(): Generator
    {
        yield 'repository badges/poser' => ['https://github.com/badges/poser'];
        yield 'repository doctrine/collections' => ['https://github.com/doctrine/collections'];
        yield 'repository seldaek/monolog' => ['https://github.com/seldaek/monolog'];
    }

    private static function loadFixtureToApiMockServer(): void
    {
        ApiMockServer::loadPackagistFixtureForPackage(
            '/repos/badges/poser',
            self::getFixtureContent(__DIR__ . '/Fixture/Github/repository-badges-poser.json')
        );
        ApiMockServer::loadPackagistFixtureForPackage(
            '/repos/doctrine/collections',
            self::getFixtureContent(__DIR__ . '/Fixture/Github/repository-doctrine-collections.json')
        );
        ApiMockServer::loadPackagistFixtureForPackage(
            '/repos/seldaek/monolog',
            self::getFixtureContent(__DIR__ . '/Fixture/Github/repository-seldaek-monolog.json')
        );
    }

```

## Current fixtures

### badges/poser

API_URL : https://api.github.com/repos/badges/poser

DATA: repository-badges-poser.json

### doctrine/collections

API_URL : https://api.github.com/repos/doctrine/collections

DATA: repository-doctrine-collections.json

### seldaek/monolog

API_URL : https://api.github.com/repos/seldaek/monolog

DATA: repository-seldaek-monolog.json

### sebastianbergmann/phpunit

API_URL : https://api.github.com/repos/sebastianbergmann/phpunit

DATA: repository-sebastianbergmann-phpunit.json


### oro/platform

API_URL : https://api.github.com/repos/orocrm/platform

DATA: repository-orocrm-platform.json

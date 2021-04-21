# Bitbucket API Mock

## Aggiungere/aggiornare bitbucket fixture

Eseguire il comando 'wget' con l'url del package nella relativa directory

```bash

$ wget https://api.bitbucket.org/2.0/repositories/rickymcalister/instagram-php-wrapper

```

Rinominare il file nel fromato 'repository-{vendor-name}-{package-name}.json'

Se è una nuova fixture aggiornare il metodo loadFixtureToApiMockServer() e il metodo bitbucketRepositoryUrlDataprovider()

```php

    /**
     * @return Generator<string, array<int, string>>
     */
    public function bitbucketRepositoryUrlDataprovider(): Generator
    {
        yield 'repository rickymcalister/instagram-php-wrapper' => ['https://bitbucket.org/rickymcalister/instagram-php-wrapper'];
        yield 'repository axtens/php-bignum' => ['https://bitbucket.org/axtens/php-bignum'];
        yield 'repository masnun/php-feeds' => ['https://bitbucket.org/masnun/php-feeds'];
    }

    private static function loadFixtureToApiMockServer(): void
    {

        ApiMockServer::loadPackagistFixtureForPackage(
            '/2.0/repositories/rickymcalister/instagram-php-wrapper',
            self::getFixtureContent(__DIR__ . '/Fixture/Bitbucket/repository-rickymcalister-instagram-php-wrapper.json')
        );
        ApiMockServer::loadPackagistFixtureForPackage(
            '/2.0/repositories/axtens/php-bignum',
            self::getFixtureContent(__DIR__ . '/Fixture/Bitbucket/repository-axtens-php-bignum.json')
        );
        ApiMockServer::loadPackagistFixtureForPackage(
            '/2.0/repositories/masnun/php-feeds',
            self::getFixtureContent(__DIR__ . '/Fixture/Bitbucket/repository-axtens-php-bignum.json')
        );    }

```

## Current fixtures

### rickymcalister/instagram-php-wrapper

API_URL : https://api.bitbucket.org/2.0/repositories/rickymcalister/instagram-php-wrapper

DATA: repository-rickymcalister-instagram-php-wrapper.json

### axtens/php-bignum

API_URL : https://api.bitbucket.org/2.0/repositories/axtens/php-bignum

DATA: repository-axtens-php-bignum.json

### masnun/php-feeds

API_URL : https://api.bitbucket.org/2.0/repositories/masnun/php-feeds

DATA: repository-masnus-php-feed.json

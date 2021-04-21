# Packagist API Mock

## Aggiungere/aggiornare packagist fixture

Eseguire il comando 'wget' con l'url del package nella relativa directory

```bash

$ wget https://packagist.org/packages/monolog/monolog.json

--2021-04-20 17:10:23--  https://packagist.org/packages/monolog/monolog.json
Risoluzione di packagist.org (packagist.org)... 135.125.205.55, 2001:41d0:701:1100::4f7e
Connessione a packagist.org (packagist.org)|135.125.205.55|:443... connesso.
Richiesta HTTP inviata, in attesa di risposta... 200 OK
Lunghezza: non specificato [application/json]
Salvataggio in: "monolog.json"

monolog.json.1                                               [ <=>                                                                                                                             ] 108,94K  --.-KB/s    in 0,09s   

2021-04-20 17:10:24 (1,13 MB/s) - "monolog.json" salvato [111556]


```

Rinominare il file nel fromato 'package-{vendor-name}-{package-name}.json'

Se è una nuova fixture aggiornare il metodo loadFixtureToApiMockServer() e il metodo packageNameDataprovider()

```php

    /**
     * @return Generator<string, array<int, string>>
     */
    public function packageNameDataprovider(): Generator
    {
        yield 'package badges/poser' => ['badges/poser'];
        yield 'package doctrine/collections' => ['doctrine/collections'];
        yield 'package monolog/monolog' => ['monolog/monolog'];
    }

    private static function loadFixtureToApiMockServer(): void
    {
        ApiMockServer::loadPackagistFixtureForPackage('/packages/badges/poser.json', self::getFixtureContent('/Fixture/Packagist/package-badges-poser.json'));
        ApiMockServer::loadPackagistFixtureForPackage('/packages/doctrine/collections.json', self::getFixtureContent('/Fixture/Packagist/package-doctrine-collections.json'));
        ApiMockServer::loadPackagistFixtureForPackage('/packages/monolog/monolog.json', self::getFixtureContent('/Fixture/Packagist/package-monolog-monolog.json'));
    }


```


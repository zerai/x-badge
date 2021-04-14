### Packagist Version Fixture Generator

## Generare una nuova fixture

1) Aggiungere una nuova riga richiamando la funzione 'generateFileFixtureForpackage(string $packageName, string $filename)'

esempio

```php
//generateFileFixtureForpackage('badges/poser', 'serialized-versions-for-package-badges-poser.txt');

//generateFileFixtureForpackage('doctrine/collections', 'serialized-versions-for-package-doctrine-collections.txt');

//generateFileFixtureForpackage('symfony/contracts', 'serialized-versions-for-package-symfony-contracts.txt');

//generateFileFixtureForpackage('ramsey/uuid', 'serialized-versions-for-package-ramsey-uuid.txt');

// fixture per nuovo package
generateFileFixtureForpackage('irrelevantvendor/irrelevant-package-name', 'serialized-versions-for-package-irrelevantvendor-irrelevant-package-name.txt');
```

2) eseguire lo script da cli.

```bash
php FixtureGenerator
```

3) Copiare il nuovo file nella directory di test 'badge/tests/Unit/Infrastructure/ReleasedVersions'

4) Aggiornare il metodo 'loadFixtureForPackage', aggiornare i/il data provider necessari/o. in ReleasedVersionsTest.
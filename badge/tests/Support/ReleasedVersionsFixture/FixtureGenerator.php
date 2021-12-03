<?php declare(strict_types=1);

use Packagist\Api\Client;

require \dirname(__DIR__, 4) . '/vendor/autoload.php';




function generateFileFixtureForpackage(string $packageName, string $filename): void
{
    $packagistClient = new Client();

    /** @phpstan-ignore-next-line */ /** @psalm-suppress all */
    $version = $packagistClient->get($packageName)->getVersions();

    $string_data = serialize($version);
    file_put_contents($filename, $string_data);
}

//generateFileFixtureForpackage('badges/poser', 'serialized-versions-for-package-badges-poser.txt');

//generateFileFixtureForpackage('doctrine/collections', 'serialized-versions-for-package-doctrine-collections.txt');

//generateFileFixtureForpackage('symfony/contracts', 'serialized-versions-for-package-symfony-contracts.txt');

//generateFileFixtureForpackage('ramsey/uuid', 'serialized-versions-for-package-ramsey-uuid.txt');



// deserialize example
//$string_data = file_get_contents("serialized-versions-for-package-badges-poser.txt");
//$array = unserialize($string_data);
//var_dump($array);

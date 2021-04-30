<?php declare(strict_types=1);

namespace Badge\Tests\Support\DomainBuilder\ApiMockServer;

use Badge\Infrastructure\Env;
use Badge\Tests\Support\DomainBuilder\Data\GitDefaultBranchData;
use Badge\Tests\Support\DomainBuilder\Data\PackagistData;
use RuntimeException;

class ApiMockServer
{
    private const EXPECTATION_ENDPOINT = '/mockserver/expectation';

    private const RESET_ENDPOINT = '/mockserver/reset';

    public static function loadPackagistFixtureByData(PackagistData $data, int $httpStatusCode = 200): void
    {
        self::loadFixture(
            self::getPackagistEndpointToMockByPackageName($data->name()),
            $data->mockedJson(),
            $httpStatusCode
        );
    }

    public static function loadDefaultBranchFixtureByData(string $mockedEndpoint, GitDefaultBranchData $data, int $httpStatusCode = 200): void
    {
        self::loadFixture(
            $mockedEndpoint,
            $data->mockedJson(),
            $httpStatusCode
        );
    }

    public static function loadCommittedFileFixtureByData(string $mockedEndpoint, GitDefaultBranchData $data, int $httpStatusCode = 200): void
    {
        self::loadFixtureForCommittedFile(
            $mockedEndpoint,
            '{}',
            $httpStatusCode
        );
    }

    public static function reset(): void
    {
        $ch = \curl_init();

        \curl_setopt($ch, CURLOPT_URL, self::resetEndpoint());
        \curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        \curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');

        $result = \curl_exec($ch);
        if (\curl_errno($ch)) {
            echo 'Error:' . \curl_error($ch);
        }
        \curl_close($ch);
    }

    private static function getPackagistEndpointToMockByPackageName(string $packageName): string
    {
        return \sprintf('/packages/%s.json', $packageName);
    }

    private static function loadFixture(string $endpointToMock, string $jsonResponse, int $httpStatusCode = 200): void
    {
        $expectetionTemplate = '
            {
                "httpRequest" : {
                    "method" : "GET",
                    "path" : "%s"
                },
                "httpResponse" : {
                    "statusCode": %s,
                    "body" : %s
                }
            }'
        ;

        $expectationDataRequest = \trim(\sprintf($expectetionTemplate, $endpointToMock, (string) $httpStatusCode, $jsonResponse));

        $ch = \curl_init();

        \curl_setopt($ch, CURLOPT_URL, self::expectationEndpoint());
        \curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        \curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        \curl_setopt($ch, CURLOPT_POSTFIELDS, $expectationDataRequest);

        $headers = [];
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        \curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        try {
            $result = \curl_exec($ch);
            if (\curl_errno($ch)) {
                self::reset();

                throw new RuntimeException('ApiMockServer error: ' . \curl_error($ch));
            }
            \curl_close($ch);
        } catch (\Throwable $th) {
            \curl_close($ch);

            throw $th;
        }
    }

    private static function loadFixtureForCommittedFile(string $endpointToMock, string $jsonResponse = '{}', int $httpStatusCode = 200, string $method = 'HEAD'): void
    {
        $expectetionTemplate = '
            {
                "httpRequest" : {
                    "method" : "HEAD",
                    "path" : "%s"
                },
                "httpResponse" : {
                    "statusCode": %s,
                    "body" : %s
                }
            }'
        ;

        $expectationDataRequest = \trim(\sprintf($expectetionTemplate, $endpointToMock, (string) $httpStatusCode, $jsonResponse));

        $ch = \curl_init();

        \curl_setopt($ch, CURLOPT_URL, self::expectationEndpoint());
        \curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        \curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        \curl_setopt($ch, CURLOPT_POSTFIELDS, $expectationDataRequest);

        $headers = [];
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        \curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        try {
            $result = \curl_exec($ch);
            if (\curl_errno($ch)) {
                self::reset();

                throw new RuntimeException('ApiMockServer error: ' . \curl_error($ch));
            }
            \curl_close($ch);
        } catch (\Throwable $th) {
            \curl_close($ch);

            throw $th;
        }
    }

    private static function expectationEndpoint(): string
    {
        return Env::get('API_MOCK_SERVER') . SELF::EXPECTATION_ENDPOINT;
    }

    private static function resetEndpoint(): string
    {
        return Env::get('API_MOCK_SERVER') . SELF::RESET_ENDPOINT;
    }
}

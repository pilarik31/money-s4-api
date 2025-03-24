<?php declare(strict_types=1);

namespace Fondly\Money;

use Fondly\Money\Directory\Utilities;
use Fondly\Money\Enum\HttpMethod;
use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\InvalidArgumentException;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Utils;
use UnexpectedValueException;

/**
 * Main class with wrapped HTTP client.
 * Contains methods to send requests and get Directories instances.
 */
final class Money
{
    /** HTTP client */
    public readonly Guzzle $client;

    private function __construct(Guzzle $client)
    {
        $this->client = $client;
    }

    /**
     * Creates a new instance with set base uri.
     */
    public static function create(): self
    {
        $config = [
            'base_uri' => '',
        ];
        return new self(new Guzzle($config));
    }

    /**
     * Sends a HTTP request to the given URI.
     *
     * @param string $uri Use absolute path to replace base URI, otherwise use relative to append.
     * @param array<string, mixed> $options
     *
     * @throws InvalidArgumentException When something is wrong with decode operation.
     * @throws UnexpectedValueException When decoded value is not an array.
     *
     * @return array<string, int|string|array<mixed>> Response data in array.
     */
    public function request(HttpMethod $method, string $uri, array $options = []): array
    {
        $request = Utils::jsonDecode(
            $this->client->request($method->value, $uri, $options)->getBody()->getContents(),
            assoc: true
        );
        if (is_array($request)) {
            return $request;
        }
        throw new UnexpectedValueException('Invalid response.');
    }

    public function query(HttpMethod $method, array $query = [], string $uri = ''): array
    {
        $response = $this->client->request(
            $method->value,
            $uri,
            [
                RequestOptions::QUERY => $query
            ]
        );

        return Utils::jsonDecode($response->getBody()->getContents(), assoc: true);
    }

    /**
     * Sends a HTTP GET request to the given URI.
     *
     * @param string $uri Use absolute path to replace base URI, otherwise use relative to append.
     * @param array<string, mixed> $options
     *
     * @throws InvalidArgumentException When something is wrong with decode operation.
     * @throws UnexpectedValueException When decoded value is not an array.
     * @throws ClientException When HTTP client returns 4xx code.
     *
     * @return array<string, int|string|array<mixed>> Response data in array.
     */
    public function get(string $uri, array $options = []): array
    {
        $request = Utils::jsonDecode(
            $this->client->get($uri, $options)->getBody()->getContents(),
            assoc: true
        );


        if (is_array($request)) {
            return $request;
        }
        throw new UnexpectedValueException('Invalid response.');
    }

    public function getUtilities(): Utilities
    {
        return new Utilities($this);
    }
}

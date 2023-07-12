<?php

namespace App\Service;

use App\Enum\UrlEnum;
use Exception;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiService
{
    public function __construct(
        private readonly HttpClientInterface $client
    ){}

    /**
     * @throws Exception
     * @throws TransportExceptionInterface
     * @throws DecodingExceptionInterface
     */
    public function getCharacters(?string $page = null): array
    {
        return $this->createRequest(UrlEnum::CHARACTER, ['page' => $page]);
    }

    /**
     * @throws Exception
     * @throws TransportExceptionInterface
     * @throws DecodingExceptionInterface
     */
    private function createRequest(UrlEnum $type,?array $options = null): array
    {
        // will throw exception on bad url
        $url = UrlEnum::getUrl($type);
        // set the page query param if there is one
        $clientOptions = [];
        if (is_array($options) && array_key_exists('page', $options)){
            $clientOptions['query'] = [
                'page' => $options['page']
            ];
        }
        // will throw when any error happens at the transport level.
        $data = $this->client->request(
            'GET',
            $url,
            ($clientOptions) ?: null
        );
        // will throw exception if data content-type cannot be decoded
        $data = $data->toArray();

        return $data;
    }
}

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

    public function getCharacterProfile(int $characterId): array
    {
        return $this->createRequest(UrlEnum::CHARACTER, ['character' => $characterId]);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws Exception
     */
    public function getEpisodeData(string $episode): array
    {
        if (preg_match('/\/(\d+)\/?$/', $episode, $matches)) {
            return $this->createRequest(UrlEnum::EPISODES, ['episode' => $matches[1]]);
        } else {
           throw new Exception('Could not get the epsode id');
        }
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
        if (is_array($options) && array_key_exists('character', $options)) {
            $url .= "/" . $options['character'];
        }else if (is_array($options) && array_key_exists('episode', $options)) {
            $url .= "/" . $options['episode'];
        }
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
            $clientOptions
        );
        // will throw exception if data content-type cannot be decoded
        $data = $data->toArray();

        return $data;
    }
}

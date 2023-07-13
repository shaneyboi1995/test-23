<?php

namespace App\Service;

use App\Enum\UrlEnum;
use Exception;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiService
{
    /**
     * @param HttpClientInterface $client
     */
    public function __construct(
        private readonly HttpClientInterface $client
    ){}

    /**
     * @param array $options
     * @return array
     * @throws Exception
     * @throws TransportExceptionInterface
     * @throws DecodingExceptionInterface
     */
    public function getCharacters(array $options): array
    {
        return $this->createRequest(UrlEnum::CHARACTER, $options);
    }

    /**
     * @param int $characterId
     * @return array
     * @throws TransportExceptionInterface
     * @throws DecodingExceptionInterface
     */
    public function getCharacterProfile(int $characterId): array
    {
        return $this->createRequest(UrlEnum::CHARACTER, ['character' => $characterId]);
    }

    /**
     * @param string $episode
     * @return array
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
     * @param UrlEnum $type
     * @param array|null $options
     * @return array
     * @throws Exception
     * @throws TransportExceptionInterface
     * @throws DecodingExceptionInterface
     */
    private function createRequest(UrlEnum $type,?array $options = null): array
    {
        // will throw exception on bad url
        $url = UrlEnum::getUrl($type);
        $clientOptions = [];
        // handle any options
        if (is_array($options)) {
            if (array_key_exists('character', $options)) {
                $url .= "/" . $options['character'];
            }else if (array_key_exists('episode', $options)) {
                $url .= "/" . $options['episode'];
            }

            if (array_key_exists('page', $options)){
                $clientOptions['query'] = [
                    'page' => $options['page'],
                ];
            }

            if (array_key_exists('name', $options)){
                $clientOptions['query']['name'] = $options['name'];
            }
            if (array_key_exists('status', $options)){
                $clientOptions['query']['status'] = $options['status'];
            }
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

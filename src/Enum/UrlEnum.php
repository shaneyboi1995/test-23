<?php

namespace App\Enum;

enum UrlEnum: string
{

    case BASE = 'https://rickandmortyapi.com/api';

    case CHARACTER = "character";

    case LOCATIONS = 'location';

    Case EPISODES = 'episode';

    /**
     * @throws \Exception
     */
    public static function getUrl(UrlEnum $type): string
    {
        return match($type) {
            self::CHARACTER => sprintf("%s/%s", self::BASE->value, self::CHARACTER->value),
            self::LOCATIONS => sprintf("%s/%s", self::BASE->value, self::LOCATIONS->value),
            self::EPISODES => sprintf("%s/%s", self::BASE->value, self::EPISODES->value),
            self::BASE => throw new \Exception('The url must be used in conjunction with a relevant endpoint')
        };
    }
}

<?php


namespace JsonParser;


use Exception;
use Exchanger\IParser;
use Validator\Validator;

class Parser implements IParser
{
    /**
     * @param string $jsonPath
     * @return array
     * @throws \JsonException | \Exception
     */
    public static function Parse(string $jsonPath): array
    {
        $json = json_decode(file_get_contents($jsonPath), true, 512, JSON_OBJECT_AS_ARRAY | JSON_THROW_ON_ERROR);

        if(! Validator::Validate($json['items'])) {
            throw new Exception("EAN13 check error");
        }

        return $json;
    }
}

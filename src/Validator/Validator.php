<?php


namespace Validator;


use JsonParser\IValidator;
use \Exception;

class Validator implements IValidator
{

    /**
     * @param array $items
     * @return bool
     * @throws Exception
     */
    public static function Validate(array $items): bool
    {
        foreach ($items as $item) {
            if(!self::isEAN13((int) $item['item']['barcode'])) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param int $code
     * @return bool
     */
    protected static function isEAN13(int $code): bool
    {
        return strlen((string) $code) === 13;
    }
}

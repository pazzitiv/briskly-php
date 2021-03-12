<?php


namespace JsonParser;


interface IValidator
{
    /**
     * @param array $object
     * @return bool
     */
    public static function Validate(array $object): bool;
}

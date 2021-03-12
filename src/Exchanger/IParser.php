<?php


namespace Exchanger;


interface IParser
{
    public static function Parse(string $source): array;
}

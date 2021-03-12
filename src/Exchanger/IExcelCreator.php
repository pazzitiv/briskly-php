<?php

namespace Exchanger;

interface IExcelCreator
{
    public static function Create(string $filename, array $data);
}

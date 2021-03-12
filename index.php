<?php
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

require_once 'XlsExchange.php';

(new \XlsExchange())
    ->setInputFile(__DIR__ . '/tmp/orders.json')
    ->setOutputFile(__DIR__ . '/tmp/items.xlsx')
    ->useFTP()
    ->export();

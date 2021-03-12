<?php

require_once 'XlsExchange.php';

(new \XlsExchange())
    ->setInputFile('/tmp/orders.json')
    ->setOutputFile('/tmp/items.xlsx')
    ->export();

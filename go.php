<?php

require 'vendor/autoload.php';

use DI\ContainerBuilder;
use Main\UnSeulODansLoser;

$containerBuilder = new ContainerBuilder();
$containerBuilder->useAutowiring(true);
$containerBuilder->addDefinitions('config.php');
$container = $containerBuilder->build();

(new UnSeulODansLoser(
    $container->get('twitteroauth'),
    $container->get('twitter.search'),
    $container->get('l.searchPattern')
))->launch();

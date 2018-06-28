<?php

use Abraham\TwitterOAuth\TwitterOAuth;
use Psr\Container\ContainerInterface;
use function DI\factory;

return [
    'twitter.consumerKey'       => '',
    'twitter.consumerSecret'    => '',
    'twitter.accessToken'       => '',
    'twitter.accessTokenSecret' => '',
    'twitteroauth' => factory(function (ContainerInterface $c) {
        return new TwitterOAuth(
            $c->get('twitter.consumerKey'),
            $c->get('twitter.consumerSecret'),
            $c->get('twitter.accessToken'),
            $c->get('twitter.accessTokenSecret')
        );
    }),
    'twitter.search' => [
        'q'     => '#looser',
        'lang'  => 'fr',
        'count' => 20
    ],
    'l.searchPattern' => '/#looser/i',
];

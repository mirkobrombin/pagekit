<?php

use Biskuit\Cookie\CookieJar;

return [

    'name' => 'cookie',

    'main' => function ($app) {

        $app['cookie'] = function () {
            return new CookieJar();
        };

    },

    'autoload' => [

        'Biskuit\\Cookie\\' => 'src'

    ],

    'config' => [

        'path'   => null,
        'domain' => null,

    ],

    'events' => [

        'request' => function ($event, $request) use ($app) {

            if (!$path = $this->config['path']) {
                $path = $request->getBasePath() ?: '/';
            }

            $app['cookie']->setDefaultPathAndDomain($path, $this->config['domain']);
        },

        'response' => function ($event, $request, $response) use ($app) {
            foreach ($app['cookie']->getQueuedCookies() as $cookie) {
                $response->headers->setCookie($cookie);
            }
        }

    ]

];

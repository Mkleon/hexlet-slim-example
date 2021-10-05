<?php

require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use DI\Container;

$container = new Container();
$container->set(
    'renderer',
    function () {
        return new \Slim\Views\PhpRenderer(__DIR__ . '/../templates');
    }
);

$app = AppFactory::createFromContainer($container);
$app->addErrorMiddleware(true, true, true);

$app->get(
    '/', 
    function ($request, $response) {
        $response->getBody()->write("Welcome to Slim!");
        return $response;
    }
);

$app->get(
    '/courses/{id}',
    function ($request, $response, array $args) {
        $id = $args['id'];
        return $response->write("Course id: {$id}");
    }
);

$app->get(
    '/users/{id}',
    function ($request, $response, $args) {
        $params = ['id' => $args['id']];

        return $this->get('renderer')->render($response, 'users/show.phtml', $params);
    }
);

$app->run();

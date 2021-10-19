<?php

require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use DI\Container;
use Hexlet\Slim\Example\Validator;
use Hexlet\Slim\Example\UserRepository;

$container = new Container();
$container->set(
    'renderer',
    function () {
        return new \Slim\Views\PhpRenderer(__DIR__ . '/../templates');
    }
);

$app = AppFactory::createFromContainer($container);
$app->addErrorMiddleware(true, true, true);

$repo = new UserRepository('user_repository.json');

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
)->setName('course');


$app->get(
    '/users',
    function ($request, $response, $args) use ($repo) {
        $term = $request->getQueryParam('term');
        $users = $repo->all();
        $filteredUsers = array_filter($users, fn ($item) => str_contains(strtolower($item['nickname']), strtolower($term)));

        $params = [
            'term' => $term,
            'users' => $filteredUsers
        ];

        return $this->get('renderer')->render($response, 'users/index.phtml', $params);
    }
)->setName('users');


$app->get(
    '/users/new',
    function ($request, $response) {
        $params = [
            'user' => ['nickname' => '', 'email' => ''],
            'errors' => []
        ];

        return $this->get('renderer')->render($response, 'users/new.phtml', $params);
    }
);


$app->get(
    '/users/{id}',
    function ($request, $response, $args) {
        $params = [
            'id' => $args['id']
        ];

        return $this->get('renderer')->render($response, 'users/show.phtml', $params);
    }
)->setName('user');

$router = $app->getRouteCollector()->getRouteParser();

$app->post(
    '/users',
    function ($request, $response) use ($repo, $router) {
        $validator = new Validator();
        $user = $request->getParsedBodyParam('user');
        $errors = $validator->validate($user);

        if (count($errors) === 0) {
            $repo->save($user);
            return $response->withRedirect($router->urlFor('users'), 302);
        }

        $params = [
            'user' => $user,
            'errors' => $errors
        ];

        return $this->get('renderer')->render($response, "users/new.phtml", $params);
    }
);

$app->run();

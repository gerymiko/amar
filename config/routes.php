<?php

use Slim\App;

return function (App $app) {
    $app->get('/', \App\Controller\HomeController::class)->setName('home');

    $app->post('/users', \App\Controller\UserCreateController::class);

    $app->post('/submission', \App\Controller\SubmissionController::class);
};

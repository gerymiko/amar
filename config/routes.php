<?php

use Slim\App;

return function (App $app) {
    $app->get('/', \App\Controller\HomeController::class)->setName('home');
    $app->post('/submission', \App\Controller\SubmissionController::class)->setName('submission');
    $app->get('/{id}', \App\Controller\ViewSubmissionController::class);
};

<?php

// Routes

//$app->get('/[{name}]', function ($request, $response, $args) {
//    // Sample log message
//    $this->logger->info("Slim-Skeleton '/' route");
//
//    // Render index view
//    return $this->renderer->render($response, 'index.phtml', $args);
//});

require_once '../application/controllers/UserController.php';

$app->get('/getUser/{id}', ['API\Controller\UserController', 'getUser']);
$app->get('/userGUI[/{id}]', ['API\Controller\UserController', 'userGUI']);
//$app->get('/userGUI[/{id}]', 'API\Controller\UserController:userGUI');
$app->put('/createUser', ['API\Controller\UserController', 'createUser']);
$app->post('/updateUser', ['API\Controller\UserController', 'updateUser']);
$app->delete('/deleteUser/{id}', ['API\Controller\UserController', 'deleteUser']);

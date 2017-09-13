<?php
// Routes
use Controllers\RequestController as RequestController;


$app->post('/write', function ($request) {
    $requestController = new RequestController();
    $response = $requestController->processRequest($request);

    return $response;
});

$app->post('/read', function ($request) {
    $requestController = new RequestController();
    $response = $requestController->processRequest($request);

    return $response;
});

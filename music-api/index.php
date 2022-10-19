<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
//var_dump($_SERVER["REQUEST_METHOD"]);
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';
require_once './includes/app_constants.php';
require_once './includes/helpers/helper_functions.php';

//--Step 1) Instantiate App.
$app = AppFactory::create();

//-- Step 2) Add routing middleware.
$app->addRoutingMiddleware();
//-- Step 3) Add error handling middleware.
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
//-- Step 4)
// TODO: change the name of the sub directory here. You also need to change it in .htaccess
$app->setBasePath("/music-api");

//-- Step 5) Include the files containing the definitions of the callbacks.
require_once './includes/routes/artists_routes.php';

//-- Step 6)
// TODO: And here we define app routes.
$app->get("/artists", "handleGetAllArtists");
$app->get("/artists/{artist_id}", "handleGetArtistById");


// Define app routes.
$app->get('/hello/{your_name}', function (Request $request, Response $response, $args) {
    //var_dump($args);
    $response->getBody()->write("Hello!" . $args["your_name"]);
    return $response;
});

// Run the app.
$app->run();
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
$app->addBodyParsingMiddleware();
//-- Step 3) Add error handling middleware.
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
//-- Step 4)
// TODO: change the name of the sub directory here. You also need to change it in .htaccess
$app->setBasePath("/music-api");

//-- Step 5) Include the files containing the definitions of the callbacks.
require_once './includes/routes/artists_routes.php';
require_once './includes/routes/customers_routes.php';
require_once './includes/routes/albums_routes.php';
require_once './includes/routes/tracks_routes.php';

//-- Step 6)
// TODO: And here we define app routes. 
$app->get("/artists", "handleGetAllArtists");
$app->post("/artists", "handleCreateArtists");
$app->put("/artists", "handleUpdateArtist");
$app->delete("/artists", "handleUnsupportedOperation"); // -- Unsupported

$app->get("/artists/{artist_id}", "handleGetArtistById");
$app->post("/artists/{artist_id}", "handleUnsupportedOperation"); // -- Unsupported
$app->put("/artists/{artist_id}", "handleUnsupportedOperation"); // -- Unsupported
$app->delete("/artists/{artist_id}", "handleDeleteArtistById");

$app->get("/artists/{artist_id}/albums", "handleGetAlbumsByArtistId");
$app->post("/artists/{artist_id}/albums", "handleUnsupportedOperation"); // -- Unsupported
$app->put("/artists/{artist_id}/albums", "handleUnsupportedOperation"); // -- Unsupported
$app->delete("/artists/{artist_id}/albums", "handleUnsupportedOperation"); // -- Unsupported

$app->get("/artists/{artist_id}/albums/{album_id}/tracks", "handleGetTrackByArtistAndAlbumId");
$app->post("/artists/{artist_id}/albums/{album_id}/tracks", "handleUnsupportedOperation"); // -- Unsupported
$app->put("/artists/{artist_id}/albums/{album_id}/tracks", "handleUnsupportedOperation"); // -- Unsupported
$app->delete("/artists/{artist_id}/albums/{album_id}/tracks", "handleUnsupportedOperation"); // -- Unsupported

$app->get("/customers", "handleGetAllCustomers");
$app->post("/customers", "handleUnsupportedOperation"); // -- Unsupported
$app->put("/customers", "handleUnsupportedOperation"); // -- Unsupported
$app->delete("/customers", "handleUnsupportedOperation"); // -- Unsupported

$app->get("/customers/{customer_id}", "handleUnsupportedOperation"); // -- Unsupported
$app->post("/customers/{customer_id}", "handleUnsupportedOperation"); // -- Unsupported
$app->put("/customers/{customer_id}", "handleUnsupportedOperation"); // -- Unsupported
$app->delete("/customers/{customer_id}", "handleDeleteCustomerById");

$app->get("/customers/{customer_id}/invoices", "handleGetPurchasedTracksByCustomerId");
$app->post("/customers/{customer_id}/invoices", "handleUnsupportedOperation"); // -- Unsupported
$app->put("/customers/{customer_id}/invoices", "handleUnsupportedOperation"); // -- Unsupported
$app->delete("/customers/{customer_id}/invoices", "handleUnsupportedOperation"); // -- Unsupported


// Run the app.
$app->run();

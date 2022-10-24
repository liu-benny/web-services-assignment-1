<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
//var_dump($_SERVER["REQUEST_METHOD"]);
use Slim\Factory\AppFactory;

require_once __DIR__ . './../models/BaseModel.php';
require_once __DIR__ . './../models/ArtistModel.php';

// Callback for HTTP GET /artists
//-- Supported filtering operation: by artist name.
function handleGetAllArtists(Request $request, Response $response, array $args) {
    $artists = array();
    $response_data = array();
    $response_code = HTTP_OK;
    $artist_model = new ArtistModel();

    // Step 1
    // Retreive the query string parameter from the request's URI.
    $filter_params = $request->getQueryParams();
    if (isset($filter_params["name"])) {
        // Fetch the list of artists matching the provided name.
        $artists = $artist_model->getWhereLike($filter_params["name"]);
    } else {
        // No filtering by artist name detected.
        $artists = $artist_model->getAll();
    } 
    // Step 2   
    // Handle serve-side content negotiation and produce the requested representation.    
    $requested_format = $request->getHeader('Accept');
    //--
    // Step 3
    //-- We verify the requested resource representation.    
    if ($requested_format[0] === APP_MEDIA_TYPE_JSON) {
        $response_data = json_encode($artists, JSON_INVALID_UTF8_SUBSTITUTE);
    } else {
        $response_data = json_encode(getErrorUnsupportedFormat());
        $response_code = HTTP_UNSUPPORTED_MEDIA_TYPE;
    }
    $response->getBody()->write($response_data);
    return $response->withStatus($response_code);
}

// URI: /artists/{artist_id}
function handleGetArtistById(Request $request, Response $response, array $args) {
    $artist_info = array();
    $response_data = array();
    $response_code = HTTP_OK;
    $artist_model = new ArtistModel();

    // Retreive the artist from the request's URI.
    $artist_id = $args["artist_id"];
    if (isset($artist_id)) {
        // Fetch the info about the specified artist.
        $artist_info = $artist_model->getArtistById($artist_id);
        if (!$artist_info) {
            // No matches found?
            $response_data = makeCustomJSONError("resourceNotFound", "No matching record was found for the specified artist.");
            $response->getBody()->write($response_data);
            return $response->withStatus(HTTP_NOT_FOUND);
        }
    }
    // Handle serve-side content negotiation and produce the requested representation.    
    $requested_format = $request->getHeader('Accept');
    //--
    //-- We verify the requested resource representation.    
    if ($requested_format[0] === APP_MEDIA_TYPE_JSON) {
        $response_data = json_encode($artist_info, JSON_INVALID_UTF8_SUBSTITUTE);
    } else {
        $response_data = json_encode(getErrorUnsupportedFormat());
        $response_code = HTTP_UNSUPPORTED_MEDIA_TYPE;
    }
    $response->getBody()->write($response_data);
    return $response->withStatus($response_code);
}

// URI: PUT /artists 
function handleCreateArtists(Request $request, Response $response, array $args){

    $artist_info = array();
    $response_data = array();
    $response_code = HTTP_CREATED;
    $artist_model = new ArtistModel();

    $parsed_data = $request->getParsedBody();

    $artists_id = "";
    $artists_name = "";

    foreach($parsed_data as $artist){

        $artists_record = array("ArtistId" => $artist['ArtistId'] , "Name" => $artist['Name']);
        $artist_model->createArtist($artists_record);
    }


    // for( $index = 0; $index < count($parsed_data); $index++){
    //     $artist = $parsed_data[$index];

    //     $artists_id .= $artist['ArtistId']. "-->";
    //     $artists_name .= $artist['Name'] . "-->";

    // }

    
    // $artist_info = $parsed_data;

    // Handle serve-side content negotiation and produce the requested representation.    
    $requested_format = $request->getHeader('Accept');
    //--
    //-- We verify the requested resource representation.    
    if ($requested_format[0] === APP_MEDIA_TYPE_JSON) {
        $response_data = makeCustomJSONMessage("Created","Record(s) has been successfully created.");
        // $response_data = json_encode($artist_info, JSON_INVALID_UTF8_SUBSTITUTE);
    } else {
        $response_data = json_encode(getErrorUnsupportedFormat());
        $response_code = HTTP_UNSUPPORTED_MEDIA_TYPE;
    }
    $response->getBody()->write($response_data);
    return $response->withStatus($response_code);
}
 

// URI: /artists 
function handleUpdateArtist(Request $request, Response $response, array $args){
    $artist_info = array();
    $response_data = array();
    $response_code = HTTP_OK;
    $artist_model = new ArtistModel();

    $parsed_data = $request->getParsedBody();

    $artists_id = "";
    $artists_name = "";

    foreach($parsed_data as $artist){

        $artists_record = array( "Name" => $artist['Name']);
        $artist_model->updateArtist($artists_record, array("ArtistId" => $artist['ArtistId']));
    }

    // $artist_info = $parsed_data;

    // Handle serve-side content negotiation and produce the requested representation.    
    $requested_format = $request->getHeader('Accept');
    //--
    //-- We verify the requested resource representation.    
    if ($requested_format[0] === APP_MEDIA_TYPE_JSON) {
        $response_data = makeCustomJSONMessage("Updated","Record(s) has been successfully updated.");
        // $response_data = json_encode($artist_info, JSON_INVALID_UTF8_SUBSTITUTE);
    } else {
        $response_data = json_encode(getErrorUnsupportedFormat());
        $response_code = HTTP_UNSUPPORTED_MEDIA_TYPE;
    }
    $response->getBody()->write($response_data);
    return $response->withStatus($response_code);
}

// URI: /artists/{artist_id}
function handleDeleteArtistById(Request $request, Response $response, array $args){
    $artist_info = array();
    $response_data = array();
    $response_code = HTTP_OK;
    $artist_model = new ArtistModel();

    // Retreive the artist from the request's URI.
    $artist_id = $args["artist_id"];
    
    if (isset($artist_id)) {
        // Fetch the info about the specified artist.
        $artist_info = $artist_model->getArtistById($artist_id);;
        if (!$artist_info) {
            // No matches found?
            $response_data = makeCustomJSONError("resourceNotFound", "No matching record was found for the specified artist.");
            $response->getBody()->write($response_data);
            return $response->withStatus(HTTP_NOT_FOUND);
        }
        else{
            $artist_model->deleteArtist(array("ArtistId" => $artist_id));
        }
    }
    // Handle serve-side content negotiation and produce the requested representation.    
    $requested_format = $request->getHeader('Accept');
    //--
    //-- We verify the requested resource representation.    
    if ($requested_format[0] === APP_MEDIA_TYPE_JSON) {
        $response_data = makeCustomJSONMessage("Deleted","ArtistId " . $artist_id . " has been successfully deleted.");
        // $response_data = json_encode($artist_info, JSON_INVALID_UTF8_SUBSTITUTE);
    } else {
        $response_data = json_encode(getErrorUnsupportedFormat());
        $response_code = HTTP_UNSUPPORTED_MEDIA_TYPE;
    }
    $response->getBody()->write($response_data);
    return $response->withStatus($response_code);

}

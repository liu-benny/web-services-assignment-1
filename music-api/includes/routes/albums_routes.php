<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


require_once __DIR__ . './../models/BaseModel.php';
require_once __DIR__ . './../models/AlbumModel.php';

// URI: /artists/{artist_id}/album
function handleGetAlbumsByArtistId(Request $request, Response $response, array $args){
    
    $album_info = array();
    $response_data = array();
    $response_code = HTTP_OK;
    $albumModel = new AlbumModel();

    $artist_id = $args["artist_id"];
    if (isset($artist_id)) {
        // Fetch the info about the specified artist.
        $album_info = $albumModel->getAlbumByArtistId($artist_id);
        if (!$album_info) {
            // No matches found?
            $response_data = makeCustomJSONError("resourceNotFound", "No matching record was found for the specified artist.");
            $response->getBody()->write($response_data);
            return $response->withStatus(HTTP_NOT_FOUND);
        }
    }

    // Handle serve-side content negotiation and produce the requested representation.    
    $requested_format = $request->getHeader('Accept');

    if ($requested_format[0] === APP_MEDIA_TYPE_JSON) {
        $response_data = json_encode($album_info, JSON_INVALID_UTF8_SUBSTITUTE);
    } else {
        $response_data = json_encode(getErrorUnsupportedFormat());
        $response_code = HTTP_UNSUPPORTED_MEDIA_TYPE;
    }
    $response->getBody()->write($response_data);
    return $response->withStatus($response_code);
}

?>
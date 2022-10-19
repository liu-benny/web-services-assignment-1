<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


require_once __DIR__ . './../models/BaseModel.php';
require_once __DIR__ . './../models/TrackModel.php';

// Callback for HTTP GET /artists/{artist_id}/albums/{album_id}/tracks)
function handleGetTrackByArtistAndAlbumId(Request $request, Response $response, array $args) {
    $track_info = array();
    $response_data = array();
    $response_code = HTTP_OK;
    $track_model = new TrackModel();

    // Retreive the artist from the request's URI.
    $artist_id = $args["artist_id"];
    $album_id = $args["album_id"];
    if (isset($artist_id) && isset($album_id)) {
        // Fetch the info about the specified artist.
        $track_info = $track_model->getTrackByArtistAndAlbumId($artist_id,$album_id);
        if (!$track_info) {
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
        $response_data = json_encode($track_info, JSON_INVALID_UTF8_SUBSTITUTE);
    } else {
        $response_data = json_encode(getErrorUnsupportedFormat());
        $response_code = HTTP_UNSUPPORTED_MEDIA_TYPE;
    }
    $response->getBody()->write($response_data);
    return $response->withStatus($response_code);
}

?>
<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once __DIR__ . './../models/BaseModel.php';
require_once __DIR__ . './../models/CustomerModel.php';

// Callback for HTTP GET /customers
//-- Supported filtering operation: by customer name.
function handleGetAllCustomers(Request $request, Response $response, array $args) {
    $customers = array();
    $response_data = array();
    $response_code = HTTP_OK;
    $customer_model = new CustomerModel();

    // Retreive the query string parameter from the request's URI.
    $filter_params = $request->getQueryParams();
    if (isset($filter_params["firstName"])) {
        // Fetch the list of customer matching the provided name.
        $customer = $customer_model->getWhereLike($filter_params["firstName"]);
    } else {
        // No filtering by customer name detected.
        $customers = $customer_model->getAll();
    }    
    // Handle serve-side content negotiation and produce the requested representation.    
    $requested_format = $request->getHeader('Accept');
    //--
    //-- We verify the requested resource representation.    
    if ($requested_format[0] === APP_MEDIA_TYPE_JSON) {
        $response_data = json_encode($customers, JSON_INVALID_UTF8_SUBSTITUTE);
    } else {
        $response_data = json_encode(getErrorUnsupportedFormat());
        $response_code = HTTP_UNSUPPORTED_MEDIA_TYPE;
    }
    $response->getBody()->write($response_data);
    return $response->withStatus($response_code);
}

function handleDeleteCustomerById(Request $request, Response $response, array $args){
    $customer = array();
    $response_data = array();
    $response_code = HTTP_OK;
    $customer_model = new CustomerModel();

    // Retreive the artist from the request's URI.
    $customer_id = $args["customer_id"];
    
    if (isset($customer_id)) {
        // Fetch the info about the specified artist.
        $customer = $customer_model->getCustomerById($customer_id);;
        if (!$customer) {
            // No matches found?
            $response_data = makeCustomJSONError("resourceNotFound", "No matching record was found for the specified artist.");
            $response->getBody()->write($response_data);
            return $response->withStatus(HTTP_NOT_FOUND);
        }
        else{
            $customer_model->deleteCustomer(array("CustomerId" => $customer_id));
        }
    }
    // Handle serve-side content negotiation and produce the requested representation.    
    $requested_format = $request->getHeader('Accept');
    //--
    //-- We verify the requested resource representation.    
    if ($requested_format[0] === APP_MEDIA_TYPE_JSON) {
        $response_data = json_encode($customer, JSON_INVALID_UTF8_SUBSTITUTE);
    } else {
        $response_data = json_encode(getErrorUnsupportedFormat());
        $response_code = HTTP_UNSUPPORTED_MEDIA_TYPE;
    }
    $response->getBody()->write($response_data);
    return $response->withStatus($response_code);
}
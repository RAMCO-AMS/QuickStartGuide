<?php

// PEM file is the cert file I use to validate certificate authenticity.
const PEM_FILE = 'cacert.pem';

// API URL is the same for every single API v2 request.
const API_URL = 'https://api.ramcoams.com/api/v2/';

// This is a fake, non-working API key, yours has to be substituted here.
const API_KEY = 'NAR-Stage-Fake-11bd0b401ae37118509d99949587f3ec0122e3de';


// request a list of all entities in Ramco
$post = array();
$post['key'] = API_KEY;
$post['operation'] = 'GetEntityTypes';
$json = curl_request($post);
$data = json_decode($json);
print_r($data);

// get data on some Contacts
// note that the filter statement can use AND/OR joiners and nesting
// data from related entities can be requested by adding to the attributes argument
//      example: cobalt_contact_cobalt_membership/cobalt_membertypeid
$post = array();
$post['key'] = API_KEY;
$post['operation'] = 'GetEntities';
$post['entity'] = 'Contact';
$post['filter'] = 'LastName<sb>#Th#';
$post['attributes'] = 'ContactId,FirstName,LastName,cobalt_EmailVerified,cobalt_NRDSID,cobalt_contact_cobalt_membership/cobalt_membertypeid,cobalt_contact_cobalt_membership/cobalt_OfficeId,cobalt_contact_cobalt_membership/statuscode';
$post['maxresults'] = '5';
$json = curl_request($post);
$data = json_decode($json);

print "<pre>";
print_r($data);
print "</pre>";

	/**
 * Handles posting a Racmco API request.
 * @param array $post arguments to be posted to the server
 * @return string JSON string with the API response
 */
function curl_request($post) {
    $curl = curl_init();
    
    // Set the request url and specify port 443 for SSL.
    curl_setopt($curl, CURLOPT_URL, API_URL);
    curl_setopt($curl, CURLOPT_PORT , 443);

    // Specify that the request should be posted and add the post data.
    curl_setopt($curl, CURLOPT_POST, True);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post);

    // Verbose can be turned on to see LOTS of detail about the request and response.
    curl_setopt($curl, CURLOPT_VERBOSE, False);
    
    // No custom headers are needed.
    curl_setopt($curl, CURLOPT_HEADER, False);

    // Tell curl how to verify the SSL certificate.
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, TRUE);
    curl_setopt($curl, CURLOPT_CAINFO, PEM_FILE);

    // Tell curl that curl_exec should return the response as a string instead of a direct output.
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    // Get the response.
    $resp_data = curl_exec($curl);
    $resp_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl); 
    
    // Return the response
    return $resp_data;
}



?>

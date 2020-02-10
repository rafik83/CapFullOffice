<?php

require __DIR__ . '/vendor/autoload.php';
//use GuzzleHttp\Client;




// client get all bulletin salary
$baseServiceURL = 'https://cpa.fulloffice.fr/api/bse';
$access_token='YzYyMjQ4YWQ5NDhlMmRiNDUzODJjN2ZiZGVkOGU0YzE3Mjk4YmM2MWZmZDJlMzRkMjM3ZDlkYjQ2OTJkYjA5Ng';// 26 bu sur plusieurs année, user_id = 4
//$access_token='M2E3ZGZjMjE3OTM5ZWRmOWJiZjI0NWFmMmNjMTIyYWU3Y2ZlMGFjMDRmNjU1MGI1N2UxZWIwNThjNjBmMGY1Zg';// salary sans bu, salary_id = 5
//$access_token='NDMzNWQ5ZTMwODVjNDc3NjYwY2U3MjY0NmJhYTM3M2ZiYTI1ODBjYzY4MDVmM2M1ODQzYmFmMTJhMzYwNTMxOQ';// 44 bu sur une année, user_id = 3
//$access_token='NTFkZTM3YjM4YTJhZGFlZGNjMjFlNzI1MzVmZDJkMzViMTE2ZTQzMmE5YThjYjMxOThkNzcwNDU2ZjEzZDFiYw';// 12 bu sur une année, user_id = 2
//$access_token='blablabla';// erreur401

$theHeaders = ['Content-Type' => 'application/json', 'Accept' => 'application/json','Authorization' => 'Bearer'.$access_token];//, 'Authorization' => 'Bearer access_token'

$tokenOAuth= $access_token;
$dateDebut='01/2014';
$dateFin='12/2017';

$data = array(
    'tokenOAuth'=>$tokenOAuth,
    'dateDebut'=>$dateDebut,
    'dateFin'=>$dateFin
    
);
$parameter = ['body' => json_encode($data)];
//$client = new Client();
$bool = false;
$client = new \GuzzleHttp\Client(array(
    'verify'=> $bool,
    'base_uri' => $baseServiceURL,
    'headers' => $theHeaders
        ));



// client get Bulletin PDF Salary



//$baseServiceURL = 'https://cpa.fulloffice.fr/api/bse/272';
////$access_token='NjUzYTFlYTM2ZDM5OTRlYWJjMzEzMTI3NTk1YzllOTdiNWFlNzNlYjA4NmNmYTU2MTc1YjkyYzgzOTY4N2QyZg';// 26 bu sur plusieurs année, user_id = 4
////$access_token='M2E3ZGZjMjE3OTM5ZWRmOWJiZjI0NWFmMmNjMTIyYWU3Y2ZlMGFjMDRmNjU1MGI1N2UxZWIwNThjNjBmMGY1Zg';// salary sans bu, salary_id = 5
//$access_token='MzliMzQ5NjhhN2E4MmZmODc1NDM3NDEzYmZlYzM1ZmMyM2QyYWYyMGMyN2MwMTI1NDZhY2E1OTU5NzNjOTM4OQ';// 44 bu sur une année, user_id = 3
////$access_token='NTFkZTM3YjM4YTJhZGFlZGNjMjFlNzI1MzVmZDJkMzViMTE2ZTQzMmE5YThjYjMxOThkNzcwNDU2ZjEzZDFiYw';// 12 bu sur une année, user_id = 2
////$access_token='blablabla';// erreur401
//$theHeaders = ['Content-Type' => 'application/json', 'Accept' => 'application/json'];//, 'Authorization' => 'Bearer access_token'
//
//$tokenOAuth= $access_token;
//
//$data = array(
//    'tokenOAuth'=>$tokenOAuth
//    
//);
//$parameter = ['body' => json_encode($data)];
//$client = new \GuzzleHttp\Client(array(
//    'verify'=> false,
//    'base_uri' => $baseServiceURL,
//    'headers' => $theHeaders
//        ));



$response = $client->request('GET', $baseServiceURL, $parameter);





//$data = json_decode($response->getBody()->getContents(), true);
$json = $response->getBody()->getContents();
//var_dump($response);
echo $json;
echo "\n\n";


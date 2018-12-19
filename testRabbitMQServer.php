#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');


function location($zipcode)
{
//$cSession=curl_init();

$date = date("Y-m-d");

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "http://data.tmsapi.com/v1.1/movies/showings?startDate=".$date."&zip=".$zipcode."&units=mi&api_key=umfufqjtkt5f87335hs8a45j",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_POSTFIELDS => "",
  CURLOPT_HTTPHEADER => array(
    "Postman-Token: 54f242cd-7409-4920-aea7-f3089b69fbc7",
    "cache-control: no-cache"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
  return $response;
}




}

function requestProcessor($request)
{
  echo "received request".PHP_EOL;
  var_dump($request);
  if(!isset($request['type']))
  {
    return "ERROR: unsupported message type";
  }
  switch ($request['type'])
  {
    
    case "zipcode":
	    return location($request['zipcode']);
  }
  return array("returnCode" => '0', 'message'=>"Server received request and processed");
}
$server = new rabbitMQServer("testRabbitMQ.ini","apiserver");
echo "testRabbitMQServer BEGIN".PHP_EOL;
$server->process_requests('requestProcessor');
exit();
?>

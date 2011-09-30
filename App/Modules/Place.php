<?php
include_once("Libs/FoursquareAPI.class.php");

//Middlewares

$placeDiscovery = function(){
	$places = array();
	$client_key = "2RQUQ00FWEYXU3H22VZFWYBXIIL1Y12GQOHMS0NTVMIYSSIN";
	$client_secret = "UUMORJWMLYSNDCJGG4D4DL4ITH1UVT2WGFXXKALJWSUDED3B"; 
	// Load the Foursquare API library
	$foursquare = new FoursquareAPI($client_key,$client_secret);
	// Prepare parameters
	$params = array("ll"=>"51.51283,-0.127505");
	// Perform a request to a public resource
	$response = $foursquare->GetPublic("venues/search",$params);
	$venues = json_decode($response);
	foreach($venues->response->groups as $group){
		foreach($group->items as $venue){
			$place = new stdClass;
			$placeType =  new stdClass;
			$isEatery=false;
			foreach($venue->categories as $category){	
				if( in_array("Food", $category->parents)){
					$isEatery=true;
					$placeType->type= $category->shortName;
					$placeType->fqid= $category->id;
				}
			}
			if($isEatery){			
				$place->name = $venue->name;
				$place->fqid = $venue->id;
				$place->type = $placeType;
				array_push($places,$place);
			}
		}
	}
	print_r($places);
};

//GET route
$app->get('/place/discovery/', $placeDiscovery, function () {
	
});

$app->get('/place/menu/', function () {
    echo $template;
});


$app->get('/place/type/', function () {
    echo $template;
});

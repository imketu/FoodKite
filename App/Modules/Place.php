<?php
include_once("Libs/FoursquareAPI.class.php");

//            Utilityware
//======================================

$getAllPlaceData = function($place) use ($app){
	$returnPlace = null;
	$foursquareCategorytype = $app->db->query("SELECT `id` FROM PlaceType WHERE `FoursquareCategoryId` = ?", array($place->type->fqid))->fetch(0);
	if(empty($foursquareCategorytype)){
		if($app->db->query("INSERT INTO `PlaceType` (`Type`, `FoursquareCategoryId`) VALUES(?, ?)", array($place->type->name, $place->type->fqid))->execute()){
			$place->type->id=	$app->db->last_id();
		}
	} else {
		$place->type->id=$foursquareCategorytype;
	}
	if(isset($place->type->id) && !empty($place->type->id)){
		$placeId = $app->db->query("SELECT `id` FROM Place WHERE `FoursquareVenueId` = ?", array($place->fqid))->fetch(0);
		if(empty($placeId)){
			if($app->db->query("INSERT INTO `Place` (`Name`, `PlaceType`, `FoursquareVenueId`) VALUES(?, ?, ?)", array($place->name, $place->type->id, $place->fqid))->execute()){
				$place->id=	$app->db->last_id();
			}
		} else {
			$place->id=$placeId;
		}
		if(isset($place->id) && !empty($place->id)){
			$returnPlace=$place;
		}
	}
		return $returnPlace;
};

$getManueForPlace = function($placeId) use ($app){
	$menu = $app->db->query("SELECT M.id  as MenuId, M.Place as PlaceId,SM.id as SubMenuId, SM.SubMenu, MT.id as MenuTypeId, MT.Type as MenuType, C.DishCount FROM Menu M JOIN SubMenu SM ON SM.Menu=M.id LEFT JOIN MenuType MT ON MT.id=SM.MenuType JOIN (SELECT count(*) as DishCount, SubMenu from Dish GROUP BY SubMenu) as  C ON C.SubMenu = SM.id WHERE M.Place = ?", $placeId)->fetch();
	$app->data=$menu;
};

$getPlaceName = function($placeId) use ($app){
	$placeName = $app->db->query("SELECT `Name` FROM Place WHERE `id` = ?", $placeId)->fetch();
	return (!empty($placeName))?$placeName[0]['Name']:"";
};





//              Middlewares
//======================================

$discoverPlace = function() use ($app,$getAllPlaceData){

	$places = array();
	$lat="51.51283"; 
	$long="-0.127505";
	
	// Prepare parameters
	$endpoint = "venues/search";
	$params = array("ll"=>"$lat,$long");

	// Prepare cacheKey
	$cacheKey = $endpoint.http_build_query($params);
	$cacheKey =  preg_replace("/[^a-zA-Z0-9_]/","",$cacheKey);

	if(($cachedResponse = $app->cache->load($cacheKey)) === false ){		/*in case a cache-miss */
	
		// Load the Foursquare API library
		$clientKey = $app->config('foursqaureClientKey');
		$clientSecret = $app->config('foursqaureClientSecret'); 
		$foursquare = new FoursquareAPI($clientKey,$clientSecret);

		// Perform a request to a public resource
		$response = $foursquare->GetPublic($endpoint,$params);
		$venues = json_decode($response);

		// Parse the Response
		foreach($venues->response->groups as $group){
			foreach($group->items as $venue){
				$place = new stdClass;
				$placeType =  new stdClass;
				$isEatery=false;
				foreach($venue->categories as $category){	
					if( in_array("Food", $category->parents)){
						$isEatery=true;
						$placeType->name= $category->shortName;
						$placeType->fqid= $category->id;
					}
				}
				if($isEatery){			
					$place->name = $venue->name;
					$place->fqid = $venue->id;
					$place->type = $placeType;
					$place=$getAllPlaceData($place);
					array_push($places, $place);
				}
			}
		}
		// Persist parsed data in app scope
		$app->data=$places;
		// Add data to cache
		$app->cache->save($places, $cacheKey);
	} else {	/*in case a cache-hit */
		$app->data=$cachedResponse; 
	}
};





//            GET route
//======================================
$app->get('/place/discovery/(:lat/:long/)', $discoverPlace, function () use ($app){
	$app->render('placeDiscovery.php', array("title"=> "Places near you", "places" => $app->data, "navbarValue" => 1) );
});


$app->get('/place/menu/:placeId/',  function ($placeId) use ($app, $getManueForPlace, $getPlaceName) {
	 $getManueForPlace($placeId);
   $app->render('submenuList.php', array("title"=> $getPlaceName($placeId)." > Menu", "submenus" =>$app->data, "navbarValue" => 1, "backUrl" => "/place/discovery/") );
});


$app->get('/place/type/', function () {
    echo $template;
});

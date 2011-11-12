<?php
$authenticateAdmin = function() use ($app){
	if (!isset($_SESSION['auth']) || empty($_SESSION['auth'])) {
		$app->redirect("/FoodKite/App/admin/");
	} 
};


$userLogIn = function($user=null, $pass=null) use ($app) {
	if(!empty($user) && !empty($pass)){
		$_SESSION['auth'] = $app->db->query("Select Handle From User WHERE Handle=? AND Pass = PASSWORD(?) AND Role=1", array($user,$pass))->fetch(0);
		if (isset($_SESSION['auth']) && !empty($_SESSION['auth'])) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;	
	}
};


$userLogOut = function() use ($app) {
	if (isset($_SESSION['auth']) || !empty($_SESSION['auth'])) {
		unset($_SESSION['auth']);
		session_destroy();
		$app->redirect("/FoodKite/App/admin/");
	} 
};


$ifPlaceExist = function($fqId) use ($app){
	$placeName = $app->db->query("SELECT `Name` FROM Place WHERE `FoursquareVenueId` = ?", $fqId)->fetch();
	return (!empty($placeName))?TRUE:FALSE;
};


$discoverPlace = function($address) use ($app,$ifPlaceExist){
	$places=array();
	// Load the Foursquare API library
	$clientKey = $app->config('foursqaureClientKey');
	$clientSecret = $app->config('foursqaureClientSecret'); 
	$foursquare = new FoursquareAPI($clientKey,$clientSecret);

	// Prepare parameters
	$endpoint = "venues/explore";
	$coord=$foursquare->GeoLocate($address);
	$params = array("ll"=>implode(",", $coord), "section" =>"food");

	// Perform a request to a public resource
	$response = $foursquare->GetPublic($endpoint,$params);
	$venues = json_decode($response);

	// Parse the Response
	foreach($venues->response->groups as $group){
		foreach($group->items as $item){
			$venue = $item->venue;
			$place = new stdClass;
			$placeType =  new stdClass;
			$isEatery=false;
			if(isset($venue->categories)){
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
					$place->address = isset($venue->location->address)? $venue->location->address:"";
					$place->city = isset($venue->location->city)? $venue->location->city:"";
					$place->country = isset($venue->location->country)? $venue->location->country:"";
					if(!$ifPlaceExist($place->fqid)){
						array_push($places, $place);
					}
				}
			}
		}
	}
	// Persist parsed data in app scope
	$app->data=$places;
};


$getPlace = function($fqid) use ($app, $getAllPlaceData){

	// Load the Foursquare API library
	$clientKey = $app->config('foursqaureClientKey');
	$clientSecret = $app->config('foursqaureClientSecret'); 
	$foursquare = new FoursquareAPI($clientKey,$clientSecret);

	// Prepare parameters
	$endpoint = "venues/$fqid";
	$params = array();

	// Perform a request to a public resource
	$response = $foursquare->GetPublic($endpoint,$params);
	$item = json_decode($response);

	// Parse the Response
	$venue = $item->response->venue;
	$place = new stdClass;
	$placeType =  new stdClass;
	$isEatery=false;
	if(isset($venue->categories)){
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
			$place->address = isset($venue->location->address)? $venue->location->address:"";
			$place->city = isset($venue->location->city)? $venue->location->city:"";
			$place->country = isset($venue->location->country)? $venue->location->country:"";	
			$place->lat = $venue->location->lat;
			$place->lng = $venue->location->lng;
			$place=$getAllPlaceData($place);
		}
	}
	
	// Persist parsed data in app scope
	$app->data=$place;
};

$getDishesForDeal= function($dealid) use ($app){ 
	$allDishes = $app->db->query("SELECT Dish FROM DealDishRel WHERE Deal = ?", $dealid)->fetch();
	if(empty($allDishes))
		return array();
	else
		return $allDishes;
};

$getDealDishRel = function($dealId) use($app){
	$dishesarray= array();
	$dishes =  $app->db->query(" SELECt `Dish` FROM DealDishRel WHERE
				`Deal`= ?",
				$dealId)->fetch();
	foreach($dishes as $dish){
			array_push($dishesarray, $dish["Dish"]);
	}
	return $dishesarray;
};

$setDealDishRel = function($dealId, $dishId) use($app){
	$app->db->query(" INSERT INTO DealDishRel 
				(`Deal`, `Dish`) VALUES (?,?)",
				array($dealId, $dishId))->execute();
};

$deleteDalDishRel = function($dealId, $dishId) use($app){

	$app->db->query(" DELETE FROM DealDishRel WHERE
				`Deal` =? AND `Dish`=?",
				array($dealId, $dishId))->execute();
};


$getDealDetail= function($placeid) use ($app, $getDishesForDeal){ 
	$deal = $app->db->query("SELECT * FROM Deals D WHERE Place = ? AND DealEndTime > now() AND IsDeleted=FALSE ORDER BY id DESC ", $placeid)->fetch(0);
	
	if(empty($deal)){
		return array();
	}else{
		$deal["Dishes"] = $getDishesForDeal($deal["id"]);
		return $deal;
	}
};


$delteDeal= function($dealId)  use ($app){
	if($app->db->query(" Update Deals SET IsDeleted=TRUE WHERE id = ?", $dealId)->execute()){
		return true;		
	} else{
		return false;
	}
};

$getDishesForPlace= function($placeid) use ($app){ 
	$allDishes = $app->db->query("SELECT D.`id`, D.`Name` FROM `Place` P 
																	JOIN `Menu` M ON M.`Place` = P.`id`
																	JOIN `SubMenu` S ON S.`Menu`= M.`id`
																	Join `Dish` D ON D.`SubMenu`= S.`id` AND D.ChildDishOfParent IS NULL
																	WHERE P.id=?", $placeid)->fetch();
	if(empty($allDishes))
		return array();
	else
		return $allDishes;
};

$setDeal= function($deal) use ($app, $getDealDishRel, $setDealDishRel, $deleteDalDishRel ){
	if(isset($deal['id']) && !empty($deal['id'])){
		if($app->db->query(" Update Deals SET
												`Name` = ?, `Description` = ?, `Place` =?,  `Coupon` =?, 
												`CouponValidityPeriod` =?, `DealStartTime` =?,`DealEndTime` =?
												WHERE	`id`=?",
				array($deal["Name"], $deal["Description"], $deal["Place"], $deal["Coupon"],$deal["CouponValidityPeriod"], $deal["DealStartTime"],$deal["DealEndTime"],$deal["id"])
				)->execute() )	{
			$dishes = $getDealDishRel($deal['id']);
			foreach($deal["Dishes"]  as $dish){
					if(!in_array($dish, $dishes)){
						$setDealDishRel($deal['id'], $dish);
					}
				}		
				unset($dish);
				foreach($dishes as $dish){
					if(!in_array($dish, $deal["Dishes"]) ){
						$deleteDalDishRel($deal["id"] , $dish);
					}
				}	
			return TRUE;
		}else{
	
			return FALSE;
		}
	} else{
		if($app->db->query(" INSERT INTO Deals 
				(`Name`, `Description`, `Place`,  `Coupon`, `CouponValidityPeriod`, `DealStartTime`,`DealEndTime`) VALUES (?,?,?,?,?,?,?)",
				array($deal["Name"], $deal["Description"], $deal["Place"], $deal["Coupon"],$deal["CouponValidityPeriod"], $deal["DealStartTime"],$deal["DealEndTime"] )
				)->execute() )	{
				$id=$app->db->last_id();
				foreach($deal["Dishes"] as $dish){
						$setDealDishRel($id, $dish);
				}
			return TRUE;
		}else{		
			return FALSE;
		}
	}
};


$getPlacePageCount = function($rowsPerPage) use ($app){
	$numberOfPlaces = $app->db->query("Select count(P.id) From Place P JOIN PlaceType PT ON PT.id=P.PlaceType WHERE P.IsDeleted=FALSE")->fetch(0);
	if(empty($numberOfPlaces))
		$numberOfPlaces=0;

	$app->TotalPages=ceil($numberOfPlaces/$rowsPerPage);
};


$getAllPlaces = function($page, $rowsPerPage) use ($app){
	$app->Places=array();
	$returnPlace = null;
	$allPlaces = $app->db->query("Select P.id PlaceID, P.Name PlaceName, P.PlaceType TypeID, M.IsPublished, PT.Type TypeName, P.Address, P. City, P.Country,  IFNULL(M.id,0) Menu   From Place P JOIN PlaceType PT ON PT.id=P.PlaceType LEFT JOIN Menu M ON M.Place = P.id WHERE P.IsDeleted=FALSE Order by P.id DESC limit ?,?;", array($page,$rowsPerPage))->fetch();
	$app->places = $allPlaces;
};


$deletePlace = function($placeId) use($app){
	if($app->db->query("UPDATE `Place` SET `IsDeleted`=TRUE WHERE `id` = ?", $placeId)->execute())	{
		$app->seleteSuccess = TRUE;
	}else{
		$app->seleteSuccess = FALSE;
	}
};


$getMenuMeta = function ($placeId=-1, $menuId = -1) use ($app){
	$app->menu = new stdClass;

	if($menuId<=0)
		$menuId = $app->db->query("Select id FROM `Menu` WHERE Place=?;", $placeId)->fetch(0);

	if(empty($menuId)){
		if($app->db->query("INSERT INTO `Menu` (`Place`) VALUES (?)", $placeId )->execute()){
				$menuId=	$app->db->last_id();
		} 
	}
	$app->menu->meta = $app->db->query("Select * FROM `Menu` WHERE id = ?;", $menuId)->fetch(0);

	$app->menu->submenu = $app->db->query("SELECT M.id  as MenuId, M.Place as PlaceId, SM.id as SubMenuId, SM.Name, C.DishCount FROM Menu M JOIN SubMenu SM ON SM.Menu=M.id Left JOIN (SELECT count(*) as DishCount, SubMenu from Dish WHERE  ChildDishOfParent IS NULL GROUP BY SubMenu) as  C ON C.SubMenu = SM.id WHERE M.Place = ?", $placeId)->fetch();

};


$getAttributes = function () use ($app){
	$app->attributes = $app->db->query("SELECT * FROM FoodAttribute;")->fetch();
};


$getSubMenuName= function($subMenuId)use ($app){
	$submenuName = $app->db->query("SELECT `Name` FROM SubMenu WHERE `id` = ?", $subMenuId)->fetch();
	return (!empty($submenuName))?$submenuName[0]['Name']:"";
};


$getSubMenuDetail= function($subMenuId)use ($app){
	$app->submenu = $app->db->query("SELECT S.`id`, S.`Name` FROM SubMenu S WHERE S.`id` = ?", $subMenuId)->fetch(0);
	$app->submenu['Attributes']=array();
	$attributes = $app->db->query("SELECT A.`Attribute` FROM `AttributeSubMenueRel` A WHERE A.`SubMenu` = ?", $subMenuId)->fetch();
	foreach($attributes as $attribute){
		array_push($app->submenu['Attributes'], $attribute['Attribute']);
	}
};


$editSubmenu= function($submenuId, $submenuName) use ($app){
	if($app->db->query("UPDATE `SubMenu` SET `Name`=? WHERE `id` = ?", array($submenuName, $submenuId))->execute())	{
		$app->seleteSuccess = TRUE;
	}else{
		$app->seleteSuccess = FALSE;
	}
};


$addSubmenu= function($menuId, $submenuName, $attributes)use ($app){
	if($app->db->query("INSERT INTO `SubMenu` (`Menu`, `Name`) VALUES (?,?)", array($menuId, $submenuName) )->execute()){
		$submenuId=	$app->db->last_id();
		foreach( $attributes as $attribute){
			$app->db->query("INSERT INTO `AttributeSubMenueRel` (`Attribute`, `SubMenu`) VALUES (?, ?)", array($attribute,$submenuId) )->execute();
		}
		$app->insertSuccess=TRUE;
	} else { 
		$app->insertSuccess=FALSE;
	}
};


$addSubmenuAttributeRell= function($submenuId,$attribute)use ($app){
	if($app->db->query("INSERT INTO `AttributeSubMenueRel` (`Attribute`, `SubMenu`) VALUES (?, ?)", array($attribute,$submenuId) )->execute()){
		$app->updateSuccess=TRUE;
	} else { 
		$app->updateSuccess=FALSE;
	}
};


$deleteSubmenuAttributeRell= function($submenuId,$attribute)use ($app){
	if($app->db->query("DELETE FROM `AttributeSubMenueRel` WHERE `Attribute` = ? AND `SubMenu`= ?", array($attribute,$submenuId) )->execute()){
		$app->updateSuccess=TRUE;
	} else { 
		$app->updateSuccess=FALSE;
	}
};


$menuPublishUnpublish= function($menuId)use ($app){
	if($app->db->query("UPDATE Menu SET IsPublished = 1 - IsPublished WHERE id = ?", $menuId )->execute()){
		$app->updateSuccess=TRUE;
	} else { 
		$app->updateSuccess=FALSE;
	}
};


$deleteDish = function($dishId) use ($app ){
	$app->deleteSuccess=FALSE;
	$childDishes = $app->db->query("SELECT id  FROM Dish WHERE `ChildDishOfParent` = ?;", $dishId)->fetch();

	foreach($childDishes as $childDish){
		if($app->db->query("DELETE FROM AttributeDishRel WHERE Dish = ?", $childDish['id'] )->execute()){
			if($app->db->query("DELETE FROM Dish WHERE id = ?", $childDish['id'] )->execute()){
				$app->deleteSuccess=TRUE;
			} else { 
				$app->deleteSuccess=FALSE;
			}
		}
	}

	if($app->db->query("DELETE FROM AttributeDishRel WHERE Dish = ?", $dishId )->execute()){
		if($app->db->query("DELETE FROM Dish WHERE id = ?", $dishId )->execute()){
			$app->deleteSuccess=TRUE;
		} else { 
			$app->deleteSuccess=FALSE;
		}
	}
};


$deleteSubMenu = function($submenuId) use ($deleteDish, $app){
	$app->deleteSuccess=FALSE;
	$dishIds = $app->db->query("Select id FROM `Dish` WHERE SubMenu=?;", $submenuId)->fetch();
	foreach($dishIds as $dishId){
		$deleteDish($dishId);
	}	
	if($app->db->query("DELETE FROM AttributeSubMenueRel WHERE submenu = ?", $submenuId )->execute()){
		if($app->db->query("DELETE FROM SubMenu WHERE id = ?", $submenuId )->execute()){
			$app->deleteSuccess=TRUE;
		} else { 
			$app->deleteSuccess=FALSE;
		}
	}
};


$getDishDetail=function($dishId) use ($app){
	$mainDish = $app->db->query("SELECT 
																		D.id, D.Name, D.Description,D.Price, D.Calories 
																		FROM 
																		Dish D 
																		WHERE D.`id` = ?", $dishId)->fetch(0);

	$dishes=	$app->db->query("SELECT 
																			D.id, D.`AdditionalDescription`,D.Price, D.Calories 
																			FROM 
																			Dish D 
																			WHERE D.ChildDishOfParent = ?", $dishId)->fetch();
	array_unshift($dishes, $mainDish);
	foreach($dishes as &$dish){
		$dish['Attributes']=array();
		$attributes = $app->db->query("SELECT A.`Attribute` FROM `AttributeDishRel` A WHERE A.`Dish` = ?", $dish['id'])->fetch();
		foreach($attributes as $attribute){
			array_push($dish['Attributes'], $attribute['Attribute']);
		}
	}
	unset($dish);
	$app->dishes=$dishes;
};


$getDishes=function($submenuId) use ($app){
	$app->dishes = $app->db->query("SELECT 
																		D.id, D.Name, D.Description,D.Price, D.Calories 
																		FROM 
																		Dish D 
																		WHERE D.ChildDishOfParent IS NULL and 
																		SubMenu = ?", $submenuId)->fetch();
	foreach($app->dishes as &$dish){
		$dish['child']=	$app->db->query("SELECT 
																			D.id, D.`AdditionalDescription`,D.Price, D.Calories 
																			FROM 
																			Dish D 
																			WHERE D.ChildDishOfParent = ?", $dish['id'])->fetch();
	}
	unset($dish);
};


$addDish  = function($dish=null) use ($app){
	$dishId=0;
	if($app->db->query("INSERT INTO `Dish` (`SubMenu`, `Name`, `Description`, `AdditionalDescription`, `Price`, `Calories`,`ChildDishOfParent`) VALUES (?, ?, ?, ?, ?, ?, ?)", array($dish->subMenu, $dish->name, $dish->description, $dish->additionalDescription, $dish->price, $dish->calories, $dish->parent))->execute()){
		$dishId=	$app->db->last_id();
		foreach( $dish->attributes as $attribute){
			$app->db->query("INSERT INTO `AttributeDishRel` (`Attribute`, `Dish`) VALUES (?, ?)", array($attribute,$dishId) )->execute();
		}
		$app->insertSuccess=TRUE;
	} else { 
		$app->insertSuccess=FALSE;
	}
	return $dishId;
};


$editDish  = function($dish=null) use ($app){
	if($app->db->query("UPDATE `Dish` SET `Name` = ? , `Description` = ? , `AdditionalDescription` =? , `Price` = ? , `Calories` = ? WHERE `id`=?", array( $dish->name, $dish->description, $dish->additionalDescription, $dish->price, $dish->calories, $dish->id))->execute()) {
		$app->updateSuccess=TRUE;
	} else { 
		$app->updateSuccess=FALSE;
	}
};


$addDishAttributeRell= function($dishId,$attribute)use ($app){
	if($app->db->query("INSERT INTO `AttributeDishRel` (`Attribute`, `Dish`) VALUES (?, ?)", array($attribute,$dishId) )->execute()){
		$app->updateSuccess=TRUE;
	} else { 
		$app->updateSuccess=FALSE;
	}
};


$deleteDishAttributeRell= function($dishId,$attribute)use ($app){
	if($app->db->query("DELETE FROM `AttributeDishRel` WHERE `Attribute` = ? AND `Dish`= ?", array($attribute,$dishId) )->execute()){
		$app->updateSuccess=TRUE;
	} else { 
		$app->updateSuccess=FALSE;
	}
};


//            routes
//======================================
$app->get('/admin/', function () use ( $app){
	if (!isset($_SESSION['auth']) || empty($_SESSION['auth'])) {
		$app->render('admin/login.php');
	} else {
		$app->redirect("/FoodKite/App/admin/places/");
	}
});

$app->post('/admin/', function () use ($userLogIn, $app){
	if(isset($_POST["FKUser"]) &&  isset($_POST["FKUserPass"]) ){
		if($userLogIn($_POST["FKUser"],$_POST["FKUserPass"])){
			$app->redirect("/FoodKite/App/admin/places/");
			$app->flash('msg', 'Welcome back'.$_SESSION['auth']);
		} else {
			$app->flash('error', 'UserId or Password is incorrect');
		}
	} else {
		$app->flash('info', 'Please enter and valid UserId and Password');	
	}
	$app->flashKeep();
	$app->redirect("/FoodKite/App/admin/");
});


$app->get('/admin/logout/', function () use ($userLogOut, $app){
	$userLogOut();
	$app->redirect("/FoodKite/App/admin/");
});


$app->get('/admin/places/(:page/)', $authenticateAdmin, function ($page=1) use ($getPlacePageCount, $getAllPlaces, $getDealDetail, $app){;
	$rowsPerPage=8;
	$pageOffset=($page-1)*$rowsPerPage;
	$getPlacePageCount($rowsPerPage);
	if(!empty($app->TotalPages)){
		$getAllPlaces($pageOffset, $rowsPerPage);
	}
 foreach($app->places as &$place){
		$place["Deal"] = $getDealDetail($place['PlaceID']);
	}
	unset($place);
	$app->render('admin/places.php',  array("title"=> "All Places", "page"=>$page, "allPages" => $app->TotalPages , "places" =>$app->places, "toolMenu" => 1, "toolSubMenu" => 1) );
});


$app->get('/admin/place/:placeId/delete/(:page/)',  $authenticateAdmin, function ($placeId,$page=1) use ($deletePlace, $getPlaceName, $app){
	$deletePlace($placeId); 	
	if($app->seleteSuccess){
		$app->flash('success', 'You have deleted the Place: '.$getPlaceName($placeId));
	}else{
		$app->flash('error', 'Fail to delete - '.$getPlaceName($placeId));
	} 
	$app->flashKeep();
	$app->redirect("/FoodKite/App/admin/places/$page/");
});


$app->get('/admin/place/add/', $authenticateAdmin, function () use ($app){
	$app->render('admin/addplace.php',  array("title"=> "Add Places", "toolMenu" => 1, "toolSubMenu" => 1) );
});


$app->post('/admin/place/add/', $authenticateAdmin, function () use ($discoverPlace, $app){
	$app->data = array();
	$streetAddress ="";
	$city="";
	if(isset($_POST["street_address"])  && isset($_POST["city"]) && trim($_POST["street_address"])!=""  && trim($_POST["city"])!=""){	
		$address = $_POST["street_address"].", ".$_POST["city"].", United Kingdom";
		$discoverPlace($address);
		$streetAddress =$_POST["street_address"];
		$city=$_POST["city"];
	} else {
		$app->flashNow('error', 'Please enter a valid street address/ city');
	}
	$app->render('admin/addplace.php', array("title" => "Add Places", "street" => $streetAddress, "city" => $city, "places" => $app->data, "toolMenu" => 1, "toolSubMenu" => 1) );
});


$app->get('/admin/place/:placeFQId/add/',  $authenticateAdmin, function ($placeFQId) use ($deletePlace, $getPlace, $app){
	$getPlace($placeFQId);
	if(	isset($app->data->id) ){
		$app->flashNow('success', 'You have added '.$app->data->name);
	} else {
		$app->flashNow('error', 'Action failed');
	}
	$app->render('admin/messages.php');
});


$app->get('/admin/menu/:placeId/',  $authenticateAdmin, function ($placeId) use ($getMenuMeta, $getPlaceName, $app) {
	 $getMenuMeta($placeId);
   $app->render('admin/menu.php', array("title"=> $getPlaceName($placeId)." > Menu", "menu" =>$app->menu, "toolMenu" => 2, "toolSubMenu" => 1) );
});


$app->get('/admin/menu/:menuId/changeStatus/',  $authenticateAdmin, function ($menuId) use ($getMenuMeta,  $menuPublishUnpublish, $app) {
	$menuPublishUnpublish($menuId);
	$getMenuMeta(0, $menuId);	
	if($app->updateSuccess){
		$msg=($app->menu->meta["IsPublished"]==1)?"Published":"Unpublished";
		$app->flash(($app->menu->meta["IsPublished"]==1)?"msg":"info", "You have <strong>$msg this Menu</strong>");
	}else{
		$msg=($app->menu->meta["IsPublished"]==1)?"Unpublish":"Publish";
		$app->flash('success', "Fail to <strong>$msg this Menu </strong>");
	}	
	$app->redirect("/FoodKite/App/admin/menu/".$app->menu->meta["Place"]."/");
});


$app->get('/admin/submenu/:menuId/add/', $authenticateAdmin, function ($menuId) use ($getAttributes, $getMenuMeta, $getPlaceName, $app) {
	$getAttributes();
	$getMenuMeta(0, $menuId);
  $app->render('admin/submenuform.php', array("title"=> $getPlaceName($app->menu->meta["Place"])." > Menu", "menuId" => $menuId, "placeId" => $app->menu->meta["Place"], "attributes" =>$app->attributes, "toolMenu" => 2, "toolSubMenu" => 1) );
});


$app->post('/admin/submenu/:menuId/add/',  $authenticateAdmin, function ($menuId) use ($addSubmenu, $getMenuMeta, $app) {
	
	if(isset($_POST["submenu"]) && !empty($_POST["submenu"]) && trim($_POST["submenu"])!==""){
		$submenuName= ucwords(trim($_POST["submenu"]));
		$addSubmenu($menuId, $submenuName, isset($_POST['attributes'])?$_POST['attributes']:array());
		$getMenuMeta(0, $menuId);
		if($app->insertSuccess){
			$app->flash('success', "You have success fully added submenu <strong>$submenuName</strong>");
		}else{
			$app->flash('error', "Failed to add submenu <strong>$submenuName</strong>");
		} 
		$app->flashKeep();
		$app->redirect("/FoodKite/App/admin/menu/".$app->menu->meta["Place"]."/");
	} else {
		$app->flash('error', 'The submenu name cannot be blank');
		$app->flashKeep();
		$app->redirect("/FoodKite/App/admin/submenu/$menuId/add/");
	}
});


$app->get('/admin/submenu/:menuId/:submenuId/edit/',  $authenticateAdmin, function ($menuId, $submenuId) use ($getAttributes, $getMenuMeta, $getPlaceName, $getSubMenuDetail, $app) {
	$getAttributes();
	$getMenuMeta(0, $menuId);
	$getSubMenuDetail($submenuId);
  $app->render('admin/submenuform.php', array("title"=> $getPlaceName($app->menu->meta["Place"])." > Menu", "menuId" => $menuId, "submenu" => $app->submenu, "placeId" => $app->menu->meta["Place"], "attributes" =>$app->attributes, "toolMenu" => 2, "toolSubMenu" => 1) );
});


$app->post('/admin/submenu/:menuId/:submenuId/edit/',  $authenticateAdmin, function ($menuId, $submenuId) use ($editSubmenu, $getMenuMeta, $getSubMenuDetail, $deleteSubmenuAttributeRell, $addSubmenuAttributeRell, $app) {
	$getSubMenuDetail($submenuId);
	if(isset($_POST["submenu"]) && !empty($_POST["submenu"]) && trim($_POST["submenu"])!==""){
		$submenuName= ucwords(trim($_POST["submenu"]));
		$editSubmenu($submenuId, $submenuName);
		$attributes = isset($_POST['attributes'])?$_POST['attributes']:array();

		foreach($attributes as $attribute){
			if(!in_array($attribute, $app->submenu['Attributes']) ){
				$addSubmenuAttributeRell($submenuId,$attribute);
			}
		}		
		unset($attribute);
		foreach($app->submenu['Attributes'] as $attribute){
			if(!in_array($attribute, $attributes) ){
				$deleteSubmenuAttributeRell($submenuId,$attribute);
			}
		}		
		
		$getMenuMeta(0, $menuId);
		$app->flash('success', "You have success fully added submenu <strong>$submenuName</strong>");
		$app->flashKeep();
		$app->redirect("/FoodKite/App/admin/menu/".$app->menu->meta["Place"]."/");
	} else {
		$app->flash('error', 'The submenu name cannot be blank');
		$app->flashKeep();
		$app->redirect("/FoodKite/App/admin/submenu/$menuId/add/");
	}
});


$app->get('/admin/submenu/:menuId/:submenuId/delete/',  $authenticateAdmin, function ($menuId, $submenuId) use ($getMenuMeta, $deleteSubMenu,  $app) {
	 $getMenuMeta(0, $menuId);
	 $deleteSubMenu ($submenuId);
  if($app->deleteSuccess){
		$app->flash("success", "You have success fully deleted the selected submenu");
	}else{
		$app->flash('error', "Fail to delete");
	}	
	$app->flashKeep();
	$app->redirect("/FoodKite/App/admin/menu/".$app->menu->meta["Place"]."/");
});


$app->get('/admin/dishes/:menuId/:submenuId/',  $authenticateAdmin, function ($menuId, $submenuId) use ($getMenuMeta, $getSubMenuName, $getPlaceName, $getDishes, $app) {
	$getMenuMeta(0, $menuId);
	$getDishes($submenuId);
  $app->render('admin/dish.php', array("title"=> $getPlaceName($app->menu->meta["Place"])." > Menu > ".$getSubMenuName($submenuId),"placerId" => $app->menu->meta["Place"], "menuId" => $menuId, "submenuId" =>$submenuId, "dishes" =>$app->dishes, "toolMenu" => 2, "toolSubMenu" => 1) );
});


$app->get('/admin/dish/:menuId/:submenuId/add/',  $authenticateAdmin, function ($menuId, $submenuId) use ($getAttributes, $getMenuMeta, $getSubMenuName, $getPlaceName,  $app) {
	$getAttributes();
	$getMenuMeta(0, $menuId);
  $app->render('admin/dishform.php', array("title"=> $getPlaceName($app->menu->meta["Place"])." > Menu > ".$getSubMenuName($submenuId), "menuId" => $menuId, "submenuId" =>$submenuId, "placeId" => $app->menu->meta["Place"], "attributes" =>$app->attributes, "toolMenu" => 2, "toolSubMenu" => 1) );
});


$app->get('/admin/dish/:menuId/:submenuId/:dishId/delete/',  $authenticateAdmin, function ($menuId, $submenuId, $dishId) use ($deleteDish, $getMenuMeta, $getSubMenuName, $getPlaceName,  $app) {
	$getMenuMeta(0, $menuId);
	$deleteDish($dishId);
 	if($app->deleteSuccess){
		$app->flash("success", "You have success fully deleted the selected dish");
	}else{
		$app->flash('error', "Fail to delete");
	}	
	$app->flashKeep();
	$app->redirect("/FoodKite/App/admin/dishes/$menuId/$submenuId/");
}); 

$app->post('/admin/dish/:menuId/:submenuId/add/',  $authenticateAdmin, function ($menuId, $submenuId) use ($getMenuMeta, $addDish,  $app) {
	$getMenuMeta(0, $menuId);
	try { 
		if(isset($_POST["dishcount"]) && isset($_POST["name"]) && trim($_POST["name"])!=="" && !empty($submenuId)){
			$dishName = ucwords(trim($_POST["name"]));
			$dishDescription=isset($_POST["description"])?trim($_POST["description"]):"";
			$additionalDescriptionFieldPrefix= "aditionaldescription";
			$piriceFieldPrefix = "price";
			$caloriesFieldPrefix = "calories";
			$attributesFieldPrfix = "attributes";
			$parentId = 0;

			for($i=1; $i<= $_POST["dishcount"]; $i++){		
				$dish = new stdClass;
				$dish->name = 	$dishName;
				$dish->description = 	$dishDescription;
				$dish->subMenu = $submenuId;
				$additionalDescriptionField= $additionalDescriptionFieldPrefix.$i;
				$dish->additionalDescription = 	isset($_POST[$additionalDescriptionField])?trim($_POST[$additionalDescriptionField]):"";
				
				$piriceField=$piriceFieldPrefix.$i;	
			//	if(isset($_POST[$piriceField]) && !is_numeric($_POST[$piriceField]))
			//		throw new Exception("Price should be a valid numeric value");
				$dish->price = 	(isset($_POST[$piriceField]) && is_numeric($_POST[$piriceField]) )? $_POST[$piriceField]:null;
	
				$caloriesField=$caloriesFieldPrefix.$i;
				if(isset($_POST[$caloriesField]) && !empty($_POST[$caloriesField]) && !is_numeric($_POST[$caloriesField]))
					throw new Exception("Calories should be numeric value");
				$dish->calories = 	(isset($_POST[$caloriesField]) && is_numeric($_POST[$caloriesField]))?trim($_POST[$caloriesField]):null;		
				$attributesField=$attributesFieldPrfix.$i;
				$dish->attributes = 	isset($_POST[$attributesField])?$_POST[$attributesField]:array();	
							
				$dish->parent=null;
				
				if($i==1){	
					$parentId=$addDish($dish);
					if(empty($parentId))
						throw new Exception("Insert faild");
				} elseif($parentId>0) {
					$dish->parent=$parentId;
					$addDish($dish);
				}
			}	
			
		} else {
			throw new Exception("Please enter a valid dish name");
		}
	} catch (Exception $e) {
		$app->flash('error', 'Fail to add the the dish');
		$app->flashKeep();
		$app->redirect("/FoodKite/App/admin/dish/$menuId/$submenuId/add/");
	}
	$app->flash("success", "You have success added a dish");
	$app->redirect("/FoodKite/App/admin/dishes/$menuId/$submenuId/");
});

$app->get('/admin/dish/:menuId/:submenuId/:dishId/edit/',  $authenticateAdmin, function ($menuId, $submenuId, $dishId) use ($getDishDetail, $getAttributes, $getMenuMeta, $getSubMenuName, $getPlaceName, $app) {
	$getAttributes();
	$getMenuMeta(0, $menuId);
	$getDishDetail($dishId);
  $app->render('admin/dishform.php', array("title"=> $getPlaceName($app->menu->meta["Place"])." > Menu > ".$getSubMenuName($submenuId), "menuId" => $menuId, "submenuId" =>$submenuId, "placeId" => $app->menu->meta["Place"], "dishes" => $app->dishes , "attributes" =>$app->attributes, "toolMenu" => 2, "toolSubMenu" => 1) );
});

$app->post('/admin/dish/:menuId/:submenuId/:dishId/edit/', $authenticateAdmin, function ($menuId, $submenuId, $dishId) use ($getMenuMeta, $getDishDetail, $addDish, $editDish, $addDishAttributeRell, $deleteDishAttributeRell, $app) {
	$getMenuMeta(0, $menuId);
	$getDishDetail($dishId);
	try { 
		if(isset($_POST["dishcount"]) && isset($_POST["name"]) && trim($_POST["name"])!=="" && !empty($submenuId)){
			
			$dishName = ucwords(trim($_POST["name"]));
			$dishDescription=isset($_POST["description"])?trim($_POST["description"]):"";
			$additionalDescriptionFieldPrefix= "aditionaldescription";
			$piriceFieldPrefix = "price";
			$caloriesFieldPrefix = "calories";
			$attributesFieldPrfix = "attributes";
			$parentId = 0;
			
			for($i=1; $i<= $_POST["dishcount"]; $i++){		
				$dish = new stdClass;
				$dish->name = 	$dishName;
				$dish->description = 	$dishDescription;
				$dish->subMenu = $submenuId;
				$additionalDescriptionField= $additionalDescriptionFieldPrefix.$i;
				$dish->additionalDescription = 	isset($_POST[$additionalDescriptionField])?trim($_POST[$additionalDescriptionField]):"";
				
				$piriceField=$piriceFieldPrefix.$i;	
				if(isset($_POST[$piriceField]) && !is_numeric($_POST[$piriceField]))
					throw new Exception("Price should be a valid numeric value");
				$dish->price = 	(isset($_POST[$piriceField]) )? $_POST[$piriceField]:null;
	
				$caloriesField=$caloriesFieldPrefix.$i;
				if(isset($_POST[$caloriesField]) && !empty($_POST[$caloriesField]) && !is_numeric($_POST[$caloriesField]))
					throw new Exception("Calories should be numeric value");
				$dish->calories = 	(isset($_POST[$caloriesField]) && is_numeric($_POST[$caloriesField]))?trim($_POST[$caloriesField]):null;		
				$attributesField=$attributesFieldPrfix.$i;
				$dish->attributes = 	isset($_POST[$attributesField])?$_POST[$attributesField]:array();	
						
				$dish->parent=null;

				if($i>count($app->dishes)){
					$dish->parent=$dishId;
					$addDish($dish);
				} else {
					$dish->id=$app->dishes[($i-1)]['id'];
					$editDish($dish);
					foreach($dish->attributes as $attribute){
						if(!in_array($attribute, $app->dishes[($i-1)]['Attributes']) ){
							$addDishAttributeRell($app->dishes[($i-1)]['id'],$attribute);
						}
					}		
					unset($attribute);
					foreach($app->dishes[($i-1)]['Attributes'] as $attribute){
						if(!in_array($attribute, $dish->attributes) ){
							$deleteDishAttributeRell($app->dishes[($i-1)]['id'],$attribute);
						}
					}		
				}
			
			}	
			
		} else {
			throw new Exception("Please enter a valid dish name");
		}
	} catch (Exception $e) { die($e);	
		$app->flash('error', 'Fail to edit the the dish');
		$app->flashKeep();
		$app->redirect("/FoodKite/App/admin/dish/$menuId/$submenuId/add/");
	}
	$app->flash("success", "You have success edited the dish");
	$app->redirect("/FoodKite/App/admin/dishes/$menuId/$submenuId/");
});


$app->get('/admin/place/:placeId/deal/edit/(:page/)',  $authenticateAdmin, function ($placeId, $page) use ($getDishesForPlace, $getDealDetail, $getPlaceName, $getDealDishRel, $app) {
	$dishes = $getDishesForPlace($placeId); 
	$dealDetail=$getDealDetail($placeId);

	if(isset($dealDetail["id"]))
		$dealDetail['Dishes']=$getDealDishRel($dealDetail["id"]);

  $app->render('admin/dealForm.php', array("title"=> $getPlaceName($placeId)." > Deal - Add", "placeId" => $placeId, "dishes" =>$dishes, "dealDetail" => $dealDetail, "toolMenu" => 1, "page" =>$page, "toolSubMenu" => 1) );
});


$app->post('/admin/place/:placeId/deal/edit/(:page/)',  $authenticateAdmin, function ($placeId,$page) use ($setDeal, $getDealDetail, $getPlaceName, $app) {

	try { 
		if( (isset($_POST["deal-title"]) && trim($_POST["deal-title"])!=="" ) &&
				(isset($_POST["deal-description"]) && trim($_POST["deal-description"])!=="" ) &&
				(isset($_POST["coupon"]) && trim($_POST["coupon"])!=="" ) &&
				(isset($_POST["coupon-validity"]) && is_numeric($_POST["coupon-validity"])!=="" ) &&
				(isset($_POST["start-date"]) && trim($_POST["start-date"])!=="" ) &&
				(isset($_POST["end-date"]) && trim($_POST["end-date"])!=="" ) &&
				(isset($_POST["dishes"]) )
			){
				
				$deal = $getDealDetail($placeId);
				$deal['Place'] = $placeId;
				$deal['Name'] = trim($_POST["deal-title"]);
				$deal['Description'] =trim($_POST["deal-description"]);
				$deal['Coupon']= trim($_POST["coupon"]);
				$deal['CouponValidityPeriod']= $_POST["coupon-validity"];		
				$deal['DealStartTime'] = $_POST["start-date"];
				$deal['DealEndTime'] =  $_POST["end-date"] ;
				$deal['Dishes'] = $_POST["dishes"];
				$setDeal($deal);	
				$app->flash("success", "You have success added a deal");
		} else {
			throw new Exception("Please enter a valid deal name");
		}
	} catch (Exception $e) {
		$app->flash('error', 'Fail to add a deal');
	}
	$app->flashKeep();
	$app->redirect("/FoodKite/App/admin/places/$page/");

});



$app->get('/admin/place/:placeId/deal/:dealId/delete/(:page/)',  $authenticateAdmin, function ($placeId, $dealId,$page) use ($delteDeal, $getPlaceName, $app) {
	if($delteDeal($dealId)){
		$app->flash("success", "You have success deleted the deal");
	} else{
		$app->flash('error', 'Fail to delete the deal');
	}
	$app->redirect("/FoodKite/App/admin/places/$page/");
});

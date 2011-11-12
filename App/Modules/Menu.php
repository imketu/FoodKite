<?php

//            Utilityware
//======================================

$getDishesForSubmenu = function($submenuId) use ($app){
	$dishes = $app->db->query("SELECT D.id DishId,D.Name, D.Description, D.Price, D.Calories, D.SubMenu FROM Dish D WHERE D.SubMenu = ? AND D.`ChildDishOfParent` IS NULL", $submenuId)->fetch();
	$app->data=$dishes;
};

$getDishesDetail = function($dishId) use ($app){
	$dishe = $app->db->query("SELECT 
															D.id DishId, D.Name, D.Description, D.Price, D.Calories, D.SubMenu, E.id DealId, E.Name DealName, E.Description DealDescription
														FROM 
															Dish D 
														LEFT JOIN 
															(	Select De.id, De.Name, De.Description, DDR.`Dish`
																From Deals De JOIN DealDishRel DDR ON DDR.`Deal`=De.`id` and DDR.`Dish`=?
																WHERE  De.`DealEndTime` > now() AND De.`IsDeleted`=FALSE ORDER BY De.`id` 
															) AS E
														ON E.Dish = D.id
														WHERE D.id= ? AND D.`ChildDishOfParent` IS NULL;", array($dishId,$dishId))->fetch();
	if(isset($dishe[0])){
		$app->data=$dishe[0];
	}else{
		$app->data=null;
	}
};


$getSubmenuName = function($submenuId) use ($app){
	$submenuName = $app->db->query("SELECT `Name` FROM `SubMenu` WHERE `id` = ?", $submenuId)->fetch();
	return (!empty($submenuName))?$submenuName[0]['Name']:"";
};

$checkTuckIns = function ($userId, $dishId) use ($app){
return $app->db->query("SELECT count(*) FROM TuckIn T WHERE T.User=? AND T.Dish=?", array($userId, $dishId))->fetch(0);
};



//GET route
//======================================

$app->get('/menu/submenu/:submenuId/:placeId/', $authenticateUsers,  function ($submenuId,$placeId) use ($app, $getDishesForSubmenu, $getPlaceName, $getSubmenuName){
	$getDishesForSubmenu($submenuId);
	$place=$getPlaceName($placeId);
	$logoutUrl = $app->facebook->getLogoutUrl();
	$app->render('dishList.php', array("title"=> $place." > Menu > ".$getSubmenuName($submenuId), "logoutUrl" => $logoutUrl, "place"=>array("Id" => $placeId, "Name" => $place), "dishes" =>$app->data, "navbarValue" => 1, "backUrl" => "/place/menu/$placeId/") );
});

$app->get('/menu/dish/:dishId/:submenuId/:placeId/', $authenticateUsers,  function ($dishId, $submenuId,$placeId) use ($app, $getDishesDetail, $checkTuckIns,$getPlaceName, $getSubmenuName){
	$getDishesDetail($dishId);
	$place=$getPlaceName($placeId);
	$logoutUrl = $app->facebook->getLogoutUrl();
	$tuckinCount=$checkTuckIns($_SESSION["USER_ID"], $dishId);
	$app->render('dishPage.php', array("title"=> $place." > Menu > ".$app->data["Name"], "logoutUrl" => $logoutUrl, "tuckinCount" =>$tuckinCount, "place"=>array("Id" => $placeId, "Name" => $place), "submenu"=> $submenuId, "dish" =>$app->data, "navbarValue" => 1, "backUrl" => "/menu/submenu/$submenuId/$placeId/") );
});


$app->get('/menu/dish/:dishId/:submenuId/:placeId/deal/', $authenticateUsers,  function ($dishId, $submenuId,$placeId) use ($app, $getDishesDetail,  $getPlaceName, $getSubmenuName){
	$getDishesDetail($dishId);
	$place=$getPlaceName($placeId);
	$logoutUrl = $app->facebook->getLogoutUrl();
	
	$app->render('dealDialog.php', array("title"=> $place." > Menu > ".$app->data["Name"]." > Deal",  "place"=>array("Id" => $placeId, "Name" => $place), "submenu"=> $submenuId, "dish" =>$app->data, "navbarValue" => 1 , "isDialog" =>true) );
});

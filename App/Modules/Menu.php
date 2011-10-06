<?php

//            Utilityware
//======================================

$getDishesForSubmenu = function($submenuId) use ($app){
	$dishes = $app->db->query("SELECT D.id DishId,I.Name, I.Description, D.Price, D.Calories, D.SubMenu, D.Menu FROM Dish D JOIN Item I ON I.id=D.Item WHERE D.SubMenu = ?", $submenuId)->fetch();
	$app->data=$dishes;
};

$getDishesDetail = function($dishId) use ($app){
	$dishe = $app->db->query("SELECT D.id DishId,I.Name, I.Description, D.Price, D.Calories, D.SubMenu, D.Menu FROM Dish D JOIN Item I ON I.id=D.Item WHERE D.id= ?", $dishId)->fetch();
	$app->data=$dishe[0];
};


$getSubmenuName = function($submenuId) use ($app){
	$submenuName = $app->db->query("SELECT `SubMenu` Name FROM `SubMenu` WHERE `id` = ?", $submenuId)->fetch();
	return (!empty($submenuName))?$submenuName[0]['Name']:"";
};





//GET route
//======================================

$app->get('/menu/submenu/:submenuId/:placeId/',  function ($submenuId,$placeId) use ($app, $getDishesForSubmenu, $getPlaceName, $getSubmenuName){
	$getDishesForSubmenu($submenuId);
	$place=$getPlaceName($placeId);
	$app->render('dishList.php', array("title"=> $place." > Menu > ".$getSubmenuName($submenuId), "place"=>array("Id" => $placeId, "Name" => $place), "dishes" =>$app->data, "navbarValue" => 1, "backUrl" => "/place/menu/$placeId/") );
});

$app->get('/menu/dish/:dishId/:submenuId/:placeId/',  function ($dishId, $submenuId,$placeId) use ($app, $getDishesDetail, $getPlaceName, $getSubmenuName){
	$getDishesDetail($dishId);
	$place=$getPlaceName($placeId);
	$app->render('dishPage.php', array("title"=> $place." > Menu > ".$app->data["Name"], "place"=>array("Id" => $placeId, "Name" => $place),  "dish" =>$app->data, "navbarValue" => 1, "backUrl" => "/menu/submenu/$submenuId/$placeId/") );
});

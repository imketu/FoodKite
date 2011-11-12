<?php

$setTuckIn = function ($dishIs, $userId, $channel, $dealId, $result, $comment, $phrase) use ($app){
	
	if($app->db->query("INSERT INTO `TuckIn` (`Dish`, `User`, `Channel`, `Deal`, `Result`, `Comment`, `Phrase`) VALUES (?, ?, ?, ?, ?, ?, ?)", array($dishIs, $userId, $channel, $dealId, $result, $comment, $phrase) )->execute()){
			return	$app->db->last_id();
	} 
	return null;
};

$setJob = function ($fbId, $fbToken, $fbPostId, $tuckId) use ($app){ 
	if($app->db->query("INSERT INTO `TuckInJobQueue` (`FBId`, `FBToken`, `FBPostId`, `TuckInId`, `ExpiryTime`, `IsComplete`, `LikeCount`) VALUES (?, ?, ?, ?, DATE_SUB(now(), INTERVAL -30 MINUTE), 0, 0)", array($fbId, $fbToken, $fbPostId, $tuckId) )->execute()){
		return TRUE;
	} 
	return FALSE;
};

$getLiveTuckIns = function ($userId) use ($app){
return $app->db->query("SELECT T.id, T.Phrase, D.Name DealName, P.Name PlaceName, J.LikeCount,  TIMESTAMPDIFF(MINUTE, now(), J.Expirytime) TimeLeft, IF(J.ExpiryTime > now(), 1,0) IsActive FROM TuckIn T JOIN TuckInJobQueue J ON J.TuckInId=T.id JOIN Deals D ON D.id = T.Deal JOIN Place P ON P.id=D.Place WHERE User=? AND Result=1", $userId )->fetch();
};

$getWonTuckIns= function ($userId) use ($app){
return $app->db->query("SELECT T.id, T.Phrase, D.Name DealName, P.Name PlaceName FROM TuckIn T JOIN Deals D ON D.id = T.Deal JOIN Place P ON P.id=D.Place WHERE User=? AND Result=2", $userId )->fetch();
};

$getLostTuckIns = function ($userId) use ($app){
return $app->db->query("SELECT T.id, T.Phrase, D.Name DealName, P.Name PlaceName FROM TuckIn T JOIN Deals D ON D.id = T.Deal JOIN Place P ON P.id=D.Place WHERE User=? AND Result=3", $userId )->fetch();
};


$getCoupon = function ($userId, $tuckInId) use ($app){
	return $app->db->query("SELECT T.id, TIMESTAMPDIFF(MINUTE,now(),C.Expirytime) TimeLeft, IF(C.ExpiryTime > now(), 1,0) IsActive,  D.Name DealName, P.Name PlaceName, D.Coupon FROM TuckIn T JOIN Deals D ON D.id = T.Deal JOIN Place P ON P.id=D.Place JOIN Coupon C ON C.TuckInId = T.id WHERE User=? AND Result=2 AND T.id=?", array($userId, $tuckInId))->fetch(0);
};


$getTuckinPhrase = function ($dishName, $placeName, $dishId, $submenuId, $placeId, $rootUrl){
	if(!empty($dishName) && !empty( $placeName)){
		$dishUrl = $rootUrl."/menu/dish/$dishId/$submenuId/$placeId/";
		$placeUrl = $rootUrl."/place/menu/$placeId/";
		return "I have just Tucked In to a ".$dishName." @ ".$placeName."!";
	}
	return "";
};


$app->get('/tuckin/:dishId/:submenuId/:placeId/',  function($dishId, $submenuId, $placeId) use ($getDishesDetail, $getPlaceName,$getTuckinPhrase, $app){
	$getDishesDetail($dishId);
	$app->render('tuckinDialog.php', array("title"=> "Tuck In ".$app->data["Name"], "dishName"=> $app->data["Name"], "tuckinTitle"=> $getTuckinPhrase($app->data["Name"],$getPlaceName($placeId), $dishId, $submenuId, $placeId, "http://m.foodkite.com/FoodKite/App"), "dishDescription"=>$app->data["Description"], "place" => $getPlaceName($placeId), "isDialog" =>true));
});


$app->post('/tuckin/:dishId/:submenuId/:placeId/',  function($dishId, $submenuId, $placeId) use ($getDishesDetail, $getPlaceName, $getTuckinPhrase, $setTuckIn, $setJob, $app){
	try {	
		$getDishesDetail($dishId);
		$comment = (isset($_POST["message"]) && !empty($_POST["message"]))?$_POST["message"]:"";
		$phrase =  $getTuckinPhrase($app->data["Name"],$getPlaceName($placeId), $dishId, $submenuId, $placeId, "http://m.foodkite.com/FoodKite/App");
		$attachment =  array(
        'access_token' => $_SESSION["USER_TOKEN"] ,
        'message' => $comment,
       	'name' => $phrase,
				'link' => "http://m.foodkite.com/FoodKite/App/",
        'description' => $app->data["Description"]
        );
  	$fbPostReturn=$app->facebook->api('/me/feed', 'POST', $attachment);
    $fbPostId=isset( $fbPostReturn['id'])?$fbPostReturn['id']:null;

    if(!empty($fbPostId)){
        $dishId=$app->data["DishId"];
        $userId=$_SESSION["USER_ID"];
        $channel = "Facebook";
        if(isset($app->data["DealId"]) && !empty($app->data["DealId"])){
                $dealId = $app->data["DealId"];
                $result = 1; //if there is the deal the tuck in is live
        } else{
                $dealId = null;
                $result = 0; //if there is the deal the tuck in is passive
        }

        $tuckInId = $setTuckIn($dishId, $userId, $channel, $dealId, $result, $comment , $phrase);

        if(!empty($tuckInId) && !empty($dealId)){
                $setJob($_SESSION["USER_FBID"], $_SESSION["USER_TOKEN"], $fbPostId, $tuckInId);
        }
    }		
	} catch (FacebookApiException $e) {
		error_log($e);
	}
 $app->redirect("/FoodKite/App/menu/dish/$dishId/$submenuId/$placeId/");
});

$app->get('/tuckin/prizes/live/', function() use ($getLiveTuckIns, $app){
	$liveDealTuckins = $getLiveTuckIns($_SESSION["USER_ID"]);
	$logoutUrl = $app->facebook->getLogoutUrl();
	$app->render('tuckinsLive.php', array("title"=> "Your Live tuck-ins",  "logoutUrl" => $logoutUrl, "tuckins" => $liveDealTuckins, "navbarValue" => 3) );
});

$app->get('/tuckin/prizes/won/', function() use ($getWonTuckIns, $setJob, $app){
	$WonDeals = $getWonTuckIns($_SESSION["USER_ID"]);
	$logoutUrl = $app->facebook->getLogoutUrl();
	$app->render('tuckinsWon.php', array("title"=> "Prizes you have won!",  "logoutUrl" => $logoutUrl, "tuckins" => $WonDeals, "navbarValue" => 3) );
});

$app->get('/tuckin/:tuckInId/coupon/', function($tuckInId) use ($getCoupon, $setJob, $app){
	$coupon = $getCoupon($_SESSION["USER_ID"], $tuckInId);
	$app->render('coupon.php', array("title"=> "Prizes you have won!",  "coupon" => $coupon, "backUrl" => "/tuckin/prizes/won/", "navbarValue" => 3) );
});


$app->get('/tuckin/prizes/lost/', function() use ($getLostTuckIns, $setJob, $app){
	$lostTuckIns = $getLostTuckIns($_SESSION["USER_ID"]);
	$logoutUrl = $app->facebook->getLogoutUrl();
	$app->render('tuckinsLost.php', array("title"=> "Deals, that you have lost!",  "logoutUrl" => $logoutUrl, "tuckins" => $lostTuckIns, "navbarValue" => 3) );
});


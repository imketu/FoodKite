<?php

$isRegisteredUser = function($FacebookId) use ($app){
	$returnPlace = null;
	$usersCount = $app->db->query("SELECT count(*) FROM `User` WHERE `FacebookId` =  ?", array($FacebookId))->fetch(0);
	if(!empty($usersCount)){
		return true;
	} 
		return false;
};

$getUserId = function($FacebookId) use ($app){
	$returnPlace = null;
	$usersId = $app->db->query("SELECT UserId FROM `User` WHERE `FacebookId` =  ?", array($FacebookId))->fetch(0);
	return $usersId;
};

$registerUser = function($user) use ($app){
	$returnPlace = null;
	$usersCount = $app->db->query("SELECT count(*) FROM `User` WHERE `Handle` =  ?", array($user["handle"]))->fetch(0);
	if(!empty($usersCount)){
		 return false; 
	} 
	if($app->db->query("INSERT INTO `User` (`EmailId`, `FacebookId`, `FBAccessTocken`, `Handle`) VALUES (?, ?, ?, ?)", $user )->execute()){
		return true; 
	}
	return false;
};



$authenticateUsers = function () use($app){

		if(isset($_SESSION["USER_FBID"])) {		
			return true;
		} else {
			$app->redirect("/FoodKite/App/login-panel/");
		}
	};

	$loginWebUsers = function () use($app, $isRegisteredUser, $getUserId){
		// See if there is a user from a cookie
		$user = $app->facebook->getUser();
		
		if($user){
			try {
				// Proceed knowing you have a logged in user who's authenticated.
				$user_profile = $app->facebook->api('/me');
				if(!empty($user_profile)){ 	
					$_SESSION["USER_FBID"]=$user;
					$_SESSION["USER_NAME"] = $user_profile["name"];
					$_SESSION["USER_EMAIL"] = $user_profile["email"];
					$_SESSION["USER_TOKEN"] = $app->facebook->getAccessToken();
					if($isRegisteredUser($user)){
						$_SESSION["USER_ID"]= $getUserId($user);
						return true;
					} else {
						$app->redirect("/FoodKite/App/users/register/");
					}
				}
			} catch (FacebookApiException $e) {
				error_log($e);
				$user=null;
			}
		}  
		return false;
	};


$app->get('/users/register/',  function() use ($app){
	$app->render('register.php', array("title"=> "Sign Up", "email"=>  $_SESSION["USER_EMAIL"],  "name" => $_SESSION["USER_NAME"], "navbarValue" => 1) );
});


$app->post('/user/register/',  function() use ($app, $registerUser){
	if(isset($_POST["handle"]) && !empty($_POST["handle"]) && trim($_POST["handle"])!==""){
		if(preg_match('/[a-z][a-z0-9_]{3,45}/i',$_POST["handle"])){
			$token=$app->facebook->getAccessToken();
			if( $registerUser( array("email" => $_SESSION["USER_EMAIL"], "facebookId"=> $_SESSION["USER_FBID"], "facebookToken"=> $token, "handle"=>trim($_POST["handle"])) ) ) { 
				$_SESSION["USER_ID"] = $app->db->last_id();
				$app->redirect("/FoodKite/App/");
			} else {	
				$app->flash('error', 'User handle is not available');
				$app->flashKeep();
				$app->redirect("/FoodKite/App/users/register/");
			}
		} else {
			$app->flash('error', 'The user handle can only contain alphabets, number and underscore. The name should always start with alphabates and atleast 4 charecters long');
			$app->flashKeep();
			$app->redirect("/FoodKite/App/users/register/");
		}
	} else {
		$app->flash('error', 'The user handle cannot be blank');
		$app->flashKeep();
		$app->redirect("/FoodKite/App/users/register/");
	}	
});


$getUsersStats= function ($userId) use ($app){
	$userStats=array('tuckins'=>0, 'win'=>0, 'loss'=>0);
	$records =  $app->db->query("SELECT count(*) Counts, Result FROM TuckIn T WHERE T.User=? GROUP BY Result", array($userId))->fetch();
	foreach($records as $record){

		$userStats['tuckins'] +=  $record['Counts'];

		if( $record['Result'] == 2)
			$userStats['win'] =  $record['Counts'];

		if( $record['Result'] == 3)
			$userStats['loss'] =  $record['Counts'];
	}
	return $userStats;
};

$getUserTuckInHistory= function ($userId) use ($app){
	return $app->db->query("SELECT Id, Phrase, TuckInTime, Result FROM TuckIn T WHERE T.User=? LIMIT 30", array($userId))->fetch();
};



$app->get('/user/me/', $authenticateUsers, function () use ($getUsersStats, $getUserTuckInHistory, $app){
	$logoutUrl = $app->facebook->getLogoutUrl();
	$stats = $getUsersStats($_SESSION["USER_ID"]);
	$history = $getUserTuckInHistory($_SESSION["USER_ID"]);
	$app->render('profile.php', array("title"=> "My Profile", "logoutUrl" => $logoutUrl, "user"=>array("id" => $_SESSION["USER_ID"], "name"=>$_SESSION["USER_NAME"]) , "stats" => $stats, "tuckinHistory"=>$history, "navbarValue" => 4) );
});

?>

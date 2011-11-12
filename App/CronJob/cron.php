<?php
require '../Libs/Facebook/facebook.php';
include_once('../Libs/DatabaseAbstraction.Class.php');



$maxLikes=1;
$maxJobProcessPerMin = 50;
$facebook = new Facebook(array(
	'appId'  => '252574368127488',
	'secret' => '30878cf580a58b83c80281bebd1aac99',
));

$db = new DB('localhost', 'root', 'admin', 'FoodkiteDBV2');


function win($tuckInId){
	global $db;
	$db->query("Update `TuckIn` set  Result=2 WHERE id=?", $tuckInId )->execute();
	$result=$db->query("SELECT D.CouponValidityPeriod FROM TuckIn T JOIN Deals D ON D.id = T.Deal WHERE T.id=?",  $tuckInId)->fetch(0);

	if(!empty($result)){
		$validityTime = $result *60;
		$db->query("INSERT INTO `Coupon` (`TuckInId`, `ExpiryTime`) VALUES (?, DATE_SUB(now(), INTERVAL -$validityTime MINUTE))", $tuckInId )->execute();
	}
}


function lose($tuckInId){
	global $db;
	$db->query("Update `TuckIn` set  Result=3 WHERE id=?", $tuckInId )->execute();
}


function updateJob($jobId, $isComplete, $likeCount){
	global $db;
	$db->query("Update `TuckInJobQueue` set  `LikeCount`=?, `IsComplete`=?,  LastUpdated=now() WHERE id=?", array($likeCount,$isComplete, $jobId) )->execute();
}



$jobs = $db->query("SELECT 
										`id`,`FBId`, `FBToken`, `FBPostId`, `TuckInId`, `IsComplete`, `LikeCount`, IF(ExpiryTime > now(), 1,0) IsActive
									FROM `TuckInJobQueue`
									WHERE
										`IsComplete`=FALSE
									ORDER BY
										LastUpdated ASC" )->fetch();

foreach( $jobs as $job){	
	try{
		$jobId=$job["id"];
		$user= $job["FBId"];
		$post= $job["FBPostId"];
		$likeCount=0;
		$isComplete = FALSE;
		$result = $facebook->api("/$post/likes",
		array('access_token' => $job["FBToken"],'limit'=>$maxLikes));
		if(isset($result['data'])){
			$likeCount=count($result['data']);
		} 

		if($likeCount==$maxLikes){
			win($job["TuckInId"]);
			$isComplete=TRUE;
		} else if($job["IsActive"]==0){
			lose($job["TuckInId"]);
			$isComplete=TRUE;
		}
	} catch (FacebookApiException $e) {
		error_log($e);
	}

	updateJob($jobId, $isComplete, $likeCount);

}

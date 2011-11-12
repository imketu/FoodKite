<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
	<title><?php echo $title?></title>  
	<meta name="viewport" content="initial-scale=1, maximum-scale=1"> 
	<link rel="stylesheet" href="/FoodKite/App/Templates/css/jquery.mobile-1.0b2.css" />
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.6.2.min.js"></script>
	<script type="text/javascript" src="/FoodKite/App/Templates/js/text/javascript/geo.js"></script>		
	<script type="text/javascript">
	 			function del_cookie(name) { 
            document.cookie = name + '=; expires=Thu, 01-Jan-70 00:00:01 GMT;';
        }

		    function currentPositionTest() {
    
						var successHandler = function() {
								del_cookie('user_latitude');
				        del_cookie('user_longitude');
				        document.cookie = "user_latitude=" + p.coords.latitude;
				        document.cookie = "user_longitude=" + p.coords.longitude;
				        location.reload(true);
						};
			
						var errorHandler = function (errorObj) {    
								alert(errorObj.code + ": " + errorObj.message);
						};
			
						navigator.geolocation.getCurrentPosition(
								successHandler, errorHandler, 
								{timeout: 20000, enableHighAccuracy: true, maximumAge: 10000});    
				};
				currentPositionTest();
		</script>

	<!-- <script type="text/javascript">
		(function($ , window, undefined) {
			$( window.document ).bind('mobileinit', function(){
				geo_position_js.init();
				geo_position_js.getCurrentPosition(success,error);			
			 });
	
		})(jQuery,window); 

		function success(p)
		{
			alert(p.coords.latitude+"-"+p.coords.longitude);
		}
		
		function error(e)
		{
				alert(e.message);
		} 		
  </script> -->
	<script type="text/javascript" src="http://code.jquery.com/mobile/1.0b2/jquery.mobile-1.0b2.min.js"></script>
	<?php if(isset($isDialog) && $isDialog===TRUE){ ?>
		
	<?php } ?>
</head>
<body>
<?php if(!isset($isDialog) || $isDialog===FALSE){ ?>
<div data-role="page" data-theme="a" id="share" >
	
	<div data-role="header" data-theme="b">
			<?php if(isset($backUrl) && !empty($backUrl)){ ?>
			<a href="/FoodKite/App<?php echo $backUrl ?>" data-icon="arrow-l" data-direction="reverse">back</a>
			<?php } ?>
			<h1 style="color:#fff;">foodkite</span></h1>
			<?php if(isset($logoutUrl) && !empty($logoutUrl)){ ?>
			<a href="/FoodKite/App/logout/" data-icon="gear" data-direction="reverse" rel="external">Log out</a>
			<?php } ?>
	</div>
	<div data-role="navbar">
		<ul>
			<li><a href="/FoodKite/App/place/discovery/" <?php echo ($navbarValue==1)?'class="ui-btn-active"':'';?>>Places</a></li>
			<li><a href="/FoodKite/App/tuckin/prizes/live/" <?php echo ($navbarValue==3)?'class="ui-btn-active"':'';?>>Tuck ins </a></li>
			<li><a href="/FoodKite/App/user/me/" <?php echo ($navbarValue==4)?'class="ui-btn-active "':'';?>>Me</a></li>
		</ul>
	</div><!-- /navbar -->
	<?php } ?>

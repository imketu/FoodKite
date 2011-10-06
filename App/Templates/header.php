<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
	<title><?php echo $title?></title>  
	<meta name="viewport" content="initial-scale=1, maximum-scale=1"> 
		<link rel="stylesheet" href="/FoodKite/App/Templates/css/jquery.mobile-1.0b2.css" />
		<script type="text/javascript" src="http://code.jquery.com/jquery-1.6.2.min.js"></script>
		<script type="text/javascript" src="http://code.jquery.com/mobile/1.0b2/jquery.mobile-1.0b2.min.js"></script>
</head>
<body>
<div data-role="page" data-theme="a" id="share" >
	<div data-role="header" data-position="fixed" data-theme="b">
			<?php if(isset($backUrl) && !empty($backUrl)){ ?>
			<a href="/FoodKite/App<?php echo $backUrl ?>" data-icon="arrow-l" data-direction="reverse">back</a>
			<?php } ?>
			<h1 style="color:#fff;">foodkite</span></h1>
	</div>

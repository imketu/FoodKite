<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Foodkite Admin</title>
<link rel="stylesheet" href="/FoodKite/App/Templates/admin/css/screen.css" type="text/css" media="screen" title="default" />
<!--[if IE]>
<link rel="stylesheet" media="all" type="text/css" href="css/pro_dropline_ie.css" />
<![endif]-->

<!--  jquery core -->
<script src="/FoodKite/App/Templates/admin/js/jquery/jquery-1.4.1.min.js" type="text/javascript"></script>pt>

<!-- Custom jquery scripts -->
<script src="/FoodKite/App/Templates/admin/js/jquery/custom_jquery.js" type="text/javascript"></script>

<!-- MUST BE THE LAST SCRIPT IN <HEAD></HEAD></HEAD> png fix -->
<script src="/FoodKite/App/Templates/admin/js/jquery/jquery.pngFix.pack.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
$(document).pngFix( );
});
</script>
</head>
<body id="login-bg"> 
 
<!-- Start: login-holder -->
<div id="login-holder">

	<!-- start logo -->
	<div id="logo-login">
		<a href="#"><img src="/FoodKite/App/Templates/admin/images/shared/logo.png" width="156" height="40" alt="" /></a>
	</div>
	<!-- end logo -->
	
	<div class="clear"></div>
	
	<!--  start loginbox ................................................................................. -->
	<div id="loginbox">
	
	<!--  start login-inner -->
	<div id="login-inner">
		<form action="" method="post">
			<table border="0" cellpadding="0" cellspacing="0">
			<tr>
				<th>User:</th>
				<td><input type="text" value=""  name="FKUser" class="login-inp" /></td>
			</tr>
			<tr>
				<th>Password:</th>
				<td><input type="password" value=""  name="FKUserPass" class="login-inp" /></td>
			</tr>
			<tr>
				<th></th>
				<td></td>
			</tr>
			<tr>
				<th></th>
				<td><input type="submit" value="" class="submit-login"  /></td>
			</tr>
			</table>
		</form>
	</div>
 	<!--  end login-inner -->
	<div class="clear"></div>
 </div>
 <!--  end loginbox -->
 
</div>
<!-- End: login-holder -->
</body>
</html>

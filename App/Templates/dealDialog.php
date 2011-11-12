<?php include("header.php")?>	
<div data-role="page" data-theme="a" id="share" >
	<div data-role="header" data-position="fixed" data-theme="b">
			<h2>Deal for you!</h2>
	</div>
	<div data-role="content" data-theme="b"> 
		<h3><?php echo ucfirst($dish["DealName"]);?></h3>
		<p><?php echo ucfirst($dish["DealDescription"]);?></p>
	</div>
</div>
<?php include("footer.php")?>

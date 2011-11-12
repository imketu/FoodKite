<?php include("header.php")?>	
<div data-role="page" data-theme="a" id="share" >
	<div data-role="header" data-position="fixed" data-theme="b">
			<h2>Tuck In <?php echo $dishName; ?></h2>
	</div>
	<div data-role="content" data-theme="b"> 
		<form action="" method="post"> 
			<div data-role="fieldcontain">
				<label for="handle">Your Message:</label>
				<textarea name="message" id="message" value=""></textarea>
			</div>
			<br/>
			<br/>
			<em> Your Facebook message:</em>
			<br/>
			<div style="display:block; margin-top:10px; margin-bottom:30px;"  data-theme="e">
				<h4> <?php echo $tuckinTitle; ?>!</h4>
				<p>
					<?php echo (!empty($dishDescription))?$dishDescription:""; ?>
				</p>
			</div>
			<br/>
			<input type="submit" data-role="none" value="Post Your Tuck in"> 
			<br/>
			<br/>
		</form>
	</div>
</div>
<?php include("footer.php")?>

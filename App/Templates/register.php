<?php include("header.php")?>
	<div data-role="content" data-theme="b"> 
		<form action="/FoodKite/App/user/register/" method="post"> 
			<div  data-role="header" > 
				<h2 style="float:left; margin-left:15px;">Hello <?php echo ucwords($name); ?></h2>
			</div>
			<div style="display:block; margin-top:10px; margin-bottom:30px;">
				<div style="display:inline; float:left; margin-left:10px;">Please choose your user handle for FoodKite
				</div>
			</div>
			<br/>
			<div data-role="fieldcontain">
				<label for="email">Your Email:</label>
				<input type="text" name="email" id="email" value="<?php echo $email?>" readonly="readonly"/>
			</div>
			<div data-role="fieldcontain">
				<label for="handle">Foodkite Handle:</label>
				<input type="text" name="handle" id="handle" value="" placeholder="user_handle" />
				<?php if(isset($flash['error'])) :?>
					<p style="color: red"><?php echo $flash['error']?></p>
				<?php endif; ?>
			</div>
			<br/>
			<br/>
			<input type="submit" data-role="none" value="Register"> 
			<a href="#" data-rel="back">Back</a>
			<br/>
			<br/>
		</form>
	</div>
<?php include("footer.php")?>

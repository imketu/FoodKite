<?php include("header.php")?>
	<div data-role="content" data-theme="b"> 
			<div  data-role="header" > 
				<h2 style="float:left; margin-left:15px;"><?php echo ucwords($dish['Name']); ?></h2>
			</div>
			<div style="display:block; margin-top:10px; margin-bottom:30px;"><div style="display:inline; float:left; margin-left:6px;"><div class="ui-icon ui-icon-home"></div></div> <div style="display:inline; float:left; margin-left:10px;"><?php echo $place["Name"] ?></div></div>
			<br/>
			<div class="ui-grid-a">	
				<div class="ui-block-a"><strong>Description:</strong> </div>			
				<div class="ui-block-b"> <?php echo ucfirst($dish["Description"]); ?></div> 
				<br/>
				<br/>
				<div class="ui-block-a"><strong>Price:</strong> </div>
				<div class="ui-block-b"> <?php echo $dish["Price"]; ?> &pound;</div>
			</div>
			<br/>
			<br/>
			<a href="#" data-role="button">Tuck In</a> 
			<br/>
			<br/>
	</div>
<?php include("footer.php")?>r

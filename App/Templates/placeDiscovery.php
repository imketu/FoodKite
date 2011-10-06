<?php include("header.php")?>
	<div data-role="content"> 
			<div  data-role="header" data-theme="a"> 
				<p style="float:left; margin-left:15px;"><?php echo $title?></p> 
			</div>
			<ul data-role="listview" data-inset="true" data-theme="c">
				<?php	foreach($places as $place){ ?>
				<li><a href="<?php echo '/FoodKite/App/place/menu/'.$place->id."/" ?>" data-transition="slide"><?php echo $place->name?></a></li>
				<?php } ?>
			</ul>
	</div>
<?php include("footer.php")?>

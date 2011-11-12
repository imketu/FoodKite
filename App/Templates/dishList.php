<?php include("header.php")?>
	<div data-role="content"> 
			<div  data-role="header" data-theme="a"> 
				<p style="float:left; margin-left:15px;"><?php echo $title?></p> 
			</div>
			<ul data-role="listview" data-inset="true" data-theme="d">
				<?php	foreach($dishes as $dish){ ?>
				<li>
						<a href="<?php echo '/FoodKite/App/menu/dish/'.$dish['DishId'].'/'.$dish['SubMenu'].'/'.$place['Id'].'/'; ?>" data-transition="slide"><?php echo ucwords($dish["Name"]) ?></a>
						<span class="ui-li-count" > &pound;<?php echo $dish["Price"] ?>  </span>
				</li>				
				<?php } ?>
			</ul>
	</div>
<?php include("footer.php")?>r

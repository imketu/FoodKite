<?php include("header.php")?>
	<div data-role="content" data-theme="b"> 
			<div  data-role="header" > 
				<h2 style="float:left; margin-left:15px; display: inline;"><?php echo ucwords($dish['Name']); ?></h2>
				<?php if(isset($dish["DealName"]) && !empty($dish["DealName"]) ):?>
					<a href="/FoodKite/App/menu/dish/<?php echo $dish['DishId']."/".$submenu."/".$place["Id"]."/deal/";?>" data-role="button" data-inline="true" data-rel="dialog" data-transition="pop" data-icon="star" data-theme="d" style="float:left; margin-left:250px;">Deal</a> 
				<?php endif; ?>
			<br/>
			</div>
			<div style="display:block; margin-top:10px; margin-bottom:30px;"><div style="display:inline; float:left; margin-left:6px;"><div class="ui-icon ui-icon-home"></div></div> <div style="display:inline; float:left; margin-left:10px;"><?php echo $place["Name"] ?></div></div>
			<br/>
			<div class="ui-grid-a">	
				<div class="ui-block-a"><strong>Description:</strong> </div>			
				<div class="ui-block-b"> <?php echo ucfirst($dish["Description"]); ?></div> 
				<br/>
				<br/>
				<div class="ui-block-a"><strong>Price:</strong> </div>
				<div class="ui-block-b"> &pound;<?php echo $dish["Price"]; ?></div>
			</div>
			<br/>
			<br/>
			
			<?php if($tuckinCount==0):?>
				<a href="/FoodKite/App/tuckin/<?php echo $dish['DishId']."/".$submenu."/".$place["Id"]."/";?>" data-role="button" data-inline="true" data-rel="dialog" data-transition="pop" data-icon="check" data-theme="e">Tuck In</a> 
			<?php else:?>
				<em color="#222">You have tucked in this dish!</em>
			<?php endif; ?>
			<br/>
			<br/>
	</div>
<?php include("footer.php")?>

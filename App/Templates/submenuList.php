<?php include("header.php")?>
	<div data-role="content"> 
			<div  data-role="header" data-theme="a"> 
				<p style="float:left; margin-left:15px;"><?php echo $title?></p> 
			</div>
			<?php if(isset($submenus) && !empty($submenus)): ?>
			<ul data-role="listview" data-inset="true" data-theme="d">
				<?php	foreach($submenus as $submenu){ ?>
				<li>
						<a href="<?php echo '/FoodKite/App/menu/submenu/'.$submenu['SubMenuId'].'/'.$submenu['PlaceId']."/" ?>" data-transition="slide"><?php echo $submenu["Name"] ?></a>
						<span class="ui-li-count" ><?php echo $submenu["DishCount"] ?></span>
				</li>				
				<?php } ?>
			</ul>
		<?php else: ?>
			<div data-role="collapsible" data-content-theme="c">
			 <h3>Add to menu for this Place</h3>
			 <p>The add to menu interface is not live yet.</p>
		</div>
	<?php endif; ?>
	</div>
<?php include("footer.php")?>r

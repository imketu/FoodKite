<?php include("header.php")?>
	<div data-role="content"> 
			<div data-role="navbar">
				<ul>
					<li><a href="/FoodKite/App/tuckin/prizes/live/" >Live</a></li>
					<li><a href="/FoodKite/App/tuckin/prizes/won/">won</a></li>
					<li><a href="#" class="ui-btn-active">Lost</a></li>
				</ul>
		</div>
			<?php if(isset($tuckins) && !empty($tuckins)): ?>
			<ul data-role="listview" data-inset="true" data-theme="d">
				<?php	foreach($tuckins as $tuckin){ 
					?>
				<li>
					<em style="color:#333"><?php echo $tuckin["DealName"]; ?></em><br/>
					<em style="color:#555">&nbsp;@ <?php echo $tuckin["PlaceName"]; ?></em>
				</li>				
				<?php 
				} ?>
			</ul>
	<?php endif; ?>
	</div>
<?php include("footer.php")?>


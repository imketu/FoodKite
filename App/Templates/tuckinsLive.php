<?php include("header.php")?>
	<div data-role="content"> 
			<div data-role="navbar">
				<ul>
					<li><a href="#" class="ui-btn-active ui-state-persist">Live</a></li>
					<li><a href="/FoodKite/App/tuckin/prizes/won/">won</a></li>
					<li><a href="/FoodKite/App/tuckin/prizes/lost/">Lost</a></li>
				</ul>
		</div>
			<?php if(isset($tuckins) && !empty($tuckins)): ?>
			<ul data-role="listview" data-inset="true" data-theme="d">
				<?php	foreach($tuckins as $tuckin){ 
						if($tuckin["IsActive"]==1){
					?>
				<li>
					<em style="color:#333"><?php echo $tuckin["DealName"]; ?></em><br/>
					<em style="color:#555">&nbsp;@ <?php echo $tuckin["PlaceName"]; ?></em><br/>
					<div  style="color:#777; display:block; height:30px;">
						<div style="display:inline; float:left">
							Likes recived: <b><?php echo $tuckin["LikeCount"]; ?></b>
						</div>
						<div style="display:inline; float:right; ">
							Time left: <b><?php echo $tuckin["TimeLeft"]." mins"; ?></b>
						</div>

					</div>
				</li>				
				<?php 
					}
				} ?>
			</ul>
	<?php endif; ?>
	</div>
<?php include("footer.php")?>r


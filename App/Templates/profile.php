<?php include("header.php")?>
	<div data-role="content"> 
			<ul data-role="listview" data-inset="true" data-theme="c">
				<li>
		      <img src="https://graph.facebook.com/<?php echo $user['id']; ?>/picture" title="<?php echo $user['name']; ?>" style="margin:6px;"/>
		      <h3><?php echo $user['name']; ?></h3>
 		   </li>
			 <li>
					<div class="ui-grid-b">
						<div class="ui-block-a" style="text-align:center">
							Tuck Ins
						</div>
						<div class="ui-block-b" style="text-align:center">
							Won
						</div>
						<div class="ui-block-c" style="text-align:center">
							Lost
						</div>
						<div class="ui-block-a" style="text-align:center">
							<?php echo $stats['tuckins']; ?>
						</div>
						<div class="ui-block-b" style="text-align:center">
								<?php echo $stats['win']; ?>
						</div>
						<div class="ui-block-c" style="text-align:center">
							&nbsp;&nbsp;<?php echo $stats['loss']; ?>
				</li>
			</div>
			</ul> 
			<?php if(isset($tuckinHistory) && !empty($tuckinHistory)):?>
			<br/>
			<h3>My tuck-in history:</h3>
			<ul data-role="listview" data-theme="c" data-inset="true">
					<?php foreach($tuckinHistory as $tuckin) {?>
					<li><?php echo $tuckin["Phrase"]?></li>
					<?php } ?>
			</ul>
			<?php endif; ?>
	</div>
<?php include("footer.php")?>

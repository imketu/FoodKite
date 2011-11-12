<?php include("header.php")?>
	<div data-role="content"> 
		
			<?php if(isset($coupon) && !empty($coupon)): ?>
			<h3><?php echo $coupon['DealName']?></h3>
			<em>&nbsp@ <?php echo $coupon['PlaceName']?></em>
			<hr>
			<p><?php echo $coupon['Coupon']?></p>
			<br/>
			<hr/>
			<em style="color:<?php echo ($coupon['IsActive']==1)?"#green":"#red"; ?> !important"><?php echo ($coupon['IsActive']==1)?$coupon['TimeLeft']." min validity left":"Expired" ;?></em>
		</div>
	<?php endif; ?>
	</div>
<?php include("footer.php")?>r

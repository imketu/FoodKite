<?php include("header.php")?>
<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
	<!-- start content -->
	<div id="content">

		<!--  start page-heading -->
		<div id="page-heading">
			<a href="/FoodKite/App/admin/places/<?php echo $page;?>/" style="color:#FFF; background:#e63; line-height:24px; padding:4px"><- Return</a>
			<h1><?php echo $title; ?></h1>
		</div>
		<div class="clear">&nbsp;</div>
	<!-- end page-heading -->
		<?php include('messages.php'); ?>

		 <form name="chooseDateForm" id="chooseDateForm" action="" method="post">
			<table border="0" width="65%" cellpadding="0" cellspacing="0" id="id-form">
				<tr>
					<th valign="top">Title:</th>
					<td><input type="text" name="deal-title" class="inp-form" <?php echo (isset($dealDetail) && isset($dealDetail['Name']))?"value='".$dealDetail['Name']."'": "" ;?> /></td>
					<td>
						<div class="error-left"></div>
						<div class="error-inner">This field is required.</div>
					</td>
				</tr>
				<tr>
					<th valign="top">Description:</th>
					<td>
						<textarea rows="6" cols="22"type="text" name="deal-description"><?php echo (isset($dealDetail) &&  !empty($dealDetail['Description']))?$dealDetail['Description']:'' ?></textarea>
					</td>
					<td>
						<div class="error-left"></div>
						<div class="error-inner">This field is required.</div>
					</td>
				</tr>
				<tr>
					<th valign="top">Coupon:</th>
					<td>
						<textarea rows="6" cols="22"type="text" name="coupon"><?php echo (isset($dealDetail) &&  !empty($dealDetail['Coupon']))?$dealDetail['Coupon']:'' ?></textarea>
					</td>
					<td>
						<div class="error-left"></div>
						<div class="error-inner">This field is required.</div>
					</td>
				</tr>
				<tr>
					<th valign="top">Coupon Validity (hr):</th>
					<td>
						<input rows="6" cols="22"type="text" name="coupon-validity" value="<?php echo (isset($dealDetail) &&  !empty($dealDetail['CouponValidityPeriod']))?$dealDetail['CouponValidityPeriod']:'' ?>"/>
					</td>
					<td>
						<div class="error-left"></div>
						<div class="error-inner">This field is required.</div>
					</td>
				</tr>
				<tr>
					<th valign="top">Start Date (yyyy-mm-dd): </th>
					<td>
							<input name="start-date" id="date1" class="date-pick" value="<?php  echo (isset($dealDetail) &&  !empty($dealDetail['DealStartTime']))?$dealDetail['DealStartTime']:'' ?>"/>
					</td>
					<td>
						<div class="error-left"></div>
						<div class="error-inner">This field is required.</div>
					</td>
				</tr>
				<tr>
					<th valign="top">End Date (yyyy-mm-dd):</th>
					<td>
							<input name="end-date" id="date2" class="date-pick" value="<?php  echo (isset($dealDetail) &&  !empty($dealDetail['DealEndTime']))?$dealDetail['DealEndTime']:'' ?>"/>
					</td>
					<td>
						<div class="error-left"></div>
						<div class="error-inner">This field is required.</div>
					</td>
				</tr>
				<tr>
					<th valign="top">Select Dishesh:</th>
					<td>
						<select id="d" name="dishes[]" multiple="multiple" style="width:80%"">
							<?php foreach($dishes as $dish) { ?>
							<option value="<?php echo $dish['id']?>" <?php echo (isset($dealDetail) && isset($dealDetail['Dishes']) && in_array($dish['id'],$dealDetail['Dishes'] ))?"selected": "" ;?>><?php echo $dish['Name']?></option>
							<?php } ?>
						</select>
					</td>
					<td><div class="bubble-left"></div>
					<div class="bubble-inner">You can select multiple options by pressing <strong>Ctrl</strong></div>
					<div class="bubble-right"></div></td>
				</tr>
				<tr><td></td></tr>
				<tr>
					<th>&nbsp;</th>
					<td valign="top">
						<input type="submit" value="" class="form-submit" />
					</td>
					<td></td>
			</tr>
			</table>
		</form>
		<div class="clear">&nbsp;</div>
		
	</div>
	<!--  end content -->
	<div class="clear">&nbsp;</div>
</div>
<!--  end content-outer........................................................END -->
<?php include("footer.php")?>


<?php include("header.php"); ?>
<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
<!-- start content -->

<div id="content">

	<!--  start page-heading -->
	<div id="page-heading">
		<a href="/FoodKite/App/admin/places/" style="color:#FFF; background:#e63; line-height:24px; padding:4px"><- Return</a>
		<h1><?php echo $title; ?></h1>
		<div class="clear"></div>
	</div>
	<!-- end page-heading -->

	<table border="0" width="100%" cellpadding="0" cellspacing="0" id="content-table">
	<tr>
		<th rowspan="3" class="sized"><img src="/FoodKite/App/Templates/admin/images/shared/side_shadowleft.jpg" width="20" height="300" alt="" /></th>
		<th class="topleft"></th>
		<td id="tbl-border-top">&nbsp;</td>
		<th class="topright"></th>
		<th rowspan="3" class="sized"><img src="/FoodKite/App/Templates/admin/images/shared/side_shadowright.jpg" width="20" height="300" alt="" /></th>
	</tr>
	<tr>
		<td id="tbl-border-left"></td>
		<td>
		<!--  start content-table-inner ...................................................................... START -->
		<div id="content-table-inner">
		
			<!--  start table-content  -->
			<div id="table-content">
			<span id="response">
				<?php include('messages.php'); ?>		
			</span>
			<!--  start product-table ..................................................................................... -->
			<form d="myForm" action="" method="post">

				<table border="0" width="900" cellpadding="0" cellspacing="0" id="id-form">
					<tr>
						<th class=" minwidth-1" colspan="4">Search an address to add places:</th>
					</tr>
					<tr>
						<td valign="middle" align="right" width="150" style="color:#396; font-weight:bold; padding-right:15px;">Street Address:</td>
						<td width="150"><input type="text" name="street_address" class="inp-form" value="<?php echo isset($street)?$street:''; ?>"/></td>
						<td valign="middle" align="right" width="100" style="color:#396; font-weight:bold; padding-right:15px;">City:</td>
						<td width="150"><input type="text" name="city" class="inp-form" value="<?php echo isset($city)?$city:''; ?>" /></td>
						<td width="100" valign="middle" align="right">
							<input type="submit" value="" class="form-submit" />
						</td>
					</tr>
				</table>
				<?php if(isset($places) && !empty($places)) { ?>
					<table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
					<tr>
						<th class="table-header-repeat line-left minwidth-1"><a href="">Name</a></th>
						<th class="table-header-repeat line-left"><a href="">Address</a></th>
						<th class="table-header-repeat line-left"><a href="">Type</a></th>
						<th class="table-header-options line-left"><a href="">Options</a></th>
					</tr>
					<?php	foreach($places as $place) { 
									$address="";
									if(!empty($place->address)){
										$address=	$place->address;
									}
									if(!empty($place->city)){
										$address = empty($address)?$address :"$address , ";
										$address .=	$place->city;
									}
									if(!empty($place->country)){
										$address = empty($address)?$address :"$address , ";
										$address.=	$place->country;
									} ?>
					<tr>
						<td><?php echo $place->name; ?></td>
						<td><?php echo $address; ?></td>
						<td><?php echo $place->type->name; ?></td>
						<td class="options-width" >
							<a id ="xhrlinks" href="/FoodKite/App/admin/place/<?php echo $place->fqid; ?>/add/" title="Add this place" class="icon-5 info-tooltip"></a>
						</td>
					</tr>
					<?php } ?>
					</table>
					<!--  end product-table................................... --> 
					</form>
				</div>
				<!--  end content-table  -->
				
				<!--  start paging..................................................... -->
				<table border="0" cellpadding="0" cellspacing="0" id="paging-table">
				<tr>
				</table>
				<!--  end paging................ -->
			
				<div class="clear"></div>
			 
			</div>
			<!--  end content-table-inner ............................................END  -->
			</td>
			<td id="tbl-border-right"></td>
		</tr>
		<tr>
			<th class="sized bottomleft"></th>
			<td id="tbl-border-bottom">&nbsp;</td>
			<th class="sized bottomright"></th>
		</tr>
		</table>
	<?php } ?>
	<div class="clear">&nbsp;</div>

</div>
<!--  end content -->
<div class="clear">&nbsp;</div>
</div>
<!--  end content-outer........................................................END -->
<?php include("footer.php"); ?>

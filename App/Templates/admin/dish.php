<?php include("header.php")?>
<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
<!-- start content -->
<div id="content">

	<!--  start page-heading -->
	<div id="page-heading">
		<a href="/FoodKite/App/admin/menu/<?php echo $placerId;?>/" style="color:#FFF; background:#e63; line-height:24px; padding:4px"><- Return</a>
		<h1><?php echo $title; ?></h1>
	</div>



	<!-- end page-heading -->
		<table border="0" width="100%" cellpadding="0" cellspacing="0" id="content-table" >
			<tr><td>
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
			
								<?php include('messages.php'); ?>		
						 
								<!--  start product-table ..................................................................................... -->
								<table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
								<tr>
									<th class="table-header-repeat line-left minwidth-1" width=50><a href="">Dish</a>	</th>
									<th class="table-header-repeat line-left " width=120><a href="">Description</a></th>
									<th class="table-header-options line-left" width=40><a href="">Price</a></th>
									<th class="table-header-options line-left" width=40><a href="">Calories</a></th>
									<th class="table-header-options line-left" width=50x"><a href="">Options</a></th>
								</tr>
								<?php	foreach($dishes as $dish){ 
								?>
								<tr >
									<td <?php echo(count($dish["child"])>0)?"rowspan=".(count($dish["child"]) + 1 ):0 ?> class="line-left" > <?php echo $dish["Name"]; ?></td>
									<td class="line-left"><?php echo !empty($dish["Description"])?$dish["Description"]:""; ?></td>
									<td class="line-left"><?php echo !empty($dish["Price"])?$dish["Price"]:"";?></td>
									<td class="line-left"><?php echo $dish["Calories"];?></td>
									<td class="options-width">
									<a href="/FoodKite/App/admin/dish/<?php echo $menuId; ?>/<?php echo $submenuId; ?>/<?php echo $dish['id']; ?>/delete/" title="Delete Dish" class="icon-2 info-tooltip" ONCLICK="decision('Do you want to delete \'<?php echo $dish["Name"]; ?>\'? This will delete the dish!')"></a>
									</td>
								</tr>
									<?php	foreach($dish["child"] as $childDish){ ?>
										<tr style="background:#eef">
											<td class="line-left"><?php echo !empty($childDish["AdditionalDescription"])?"&#8224;&nbsp;&nbsp;&nbsp;".$childDish["AdditionalDescription"]:""; ?></td>
											<td class="line-left"><?php echo !empty($childDish["Price"])?$childDish["Price"]:""; ?></td>
											<td class="line-left"><?php echo $childDish["Calories"]; ?></td>
											<td class="options-width">
											<a href="/FoodKite/App/admin/dish/<?php echo $menuId; ?>/<?php echo $submenuId; ?>/<?php echo $childDish['id']; ?>/delete/" title="Delete this varient" class="icon-2 info-tooltip" ONCLICK="decision('Do you want to delete this varient of \'<?php echo $dish["Name"]; ?>\'? This will delete the dish!')"></a>
											<a href="/FoodKite/App/admin/dish/<?php echo $menuId; ?>/<?php echo $submenuId; ?>/<?php echo $childDish['id']; ?>/edit/" title="Edit Submenu" class="icon-5  info-tooltip" ></a>											
											</td>
										</tr>
									<?php } ?>
								<?php } ?>
								</table>
								<!--  end product-table................................... --> 
								</form>
							</div>
							<!--  end content-table  -->

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
			</td>
			<td valign="top" style="padding-top:25px;">

				<!--  start related-activities -->
				<div id="related-activities">
		
					<!--  start related-act-top -->
					<div id="related-act-top">
					<img src="/FoodKite/App/Templates/admin/images/forms/header_related_act.gif" width="271" height="43" alt="" />
					</div>
					<!-- end related-act-top -->
		
					<!--  start related-act-bottom -->
					<div id="related-act-bottom">
		
						<!--  start related-act-inner -->
						<div id="related-act-inner">
			
							<div class="left"><a href=""><img src="/FoodKite/App/Templates/admin/images/forms/icon_plus.gif" width="21" height="21" alt="" /></a></div>
							<div class="right">
								<h5>Add New Dish</h5>
								<ul class="greyarrow">
									<li><a href="/FoodKite/App/admin/dish/<?php echo $menuId; ?>/<?php echo $submenuId; ?>/add/">Click here to Add a Dish</a></li> 
								</ul>
							</div>

				
							<div class="clear"></div>
				
						</div>
						<!-- end related-act-inner -->
						<div class="clear"></div>
		
					</div>
					<!-- end related-act-bottom -->
	
				</div>
				<!-- end related-activities -->

			</td></tr>
		</table>
	<div class="clear">&nbsp;</div>

</div>
<!--  end content -->
<div class="clear">&nbsp;</div>
</div>
<!--  end content-outer........................................................END -->
<?php include("footer.php")?>


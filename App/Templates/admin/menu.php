<?php include("header.php")?>
<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
<!-- start content -->
<div id="content">

	<!--  start page-heading -->
	<div id="page-heading">
		<a href="/FoodKite/App/admin/places/" style="color:#FFF; background:#e63; line-height:24px; padding:4px"><- Return</a>
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
									<th class="table-header-repeat line-left minwidth-1"><a href="">Submenu</a>	</th>
									<th class="table-header-repeat line-left minwidth-1"><a href="">Number of listed items</a></th>
									<th class="table-header-options line-left"><a href="">Options</a></th>
								</tr>
								<?php	foreach($menu->submenu as $submenu){ 
					
								?>
								<tr >
									<td><?php echo $submenu["Name"]; ?></td>
									<td><?php echo $submenu["DishCount"]; ?></td>
									<td class="options-width">
									<a href="/FoodKite/App/admin/submenu/<?php echo $submenu['MenuId']; ?>/<?php echo $submenu['SubMenuId']; ?>/delete/" title="Delete Submenu" class="icon-2 info-tooltip" ONCLICK="decision('Do you want to delete \'<?php echo $submenu["Name"]; ?>\'? This will delete the submenu and all the items listed under the submenu!')"></a>
									<a href="/FoodKite/App/admin/submenu/<?php echo $submenu['MenuId']; ?>/<?php echo $submenu['SubMenuId']; ?>/edit/" title="Edit Submenu" class="icon-5  info-tooltip" > </a>
									<a href="/FoodKite/App/admin/dishes/<?php echo $submenu['MenuId']; ?>/<?php echo $submenu['SubMenuId']; ?>/" title="Manage Dishes" class="icon-1 info-tooltip"></a>
									</td>
								</tr>
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
								<h5>Add New Submenu</h5>
								<ul class="greyarrow">
									<li><a href="/FoodKite/App/admin/submenu/<?php echo $menu->meta['id']; ?>/add/">Click here to Add a Submenu</a></li> 
								</ul>
							</div>
				
							<div class="clear"></div>
							<div class="lines-dotted-short"></div>
				
							<div class="left"><a href=""><img src="/FoodKite/App/Templates/admin/images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
							<div class="right">
								<h5><?php echo ($menu->meta['IsPublished']==0)? 'Publish' : 'Unpublish'; ?> This Menu</h5>
								This Menu is notPublished
								<ul class="greyarrow">
									<li><a href="/FoodKite/App/admin/menu/<?php echo $menu->meta['id']; ?>/changeStatus/"">Click here to <?php echo ($menu->meta['IsPublished']==0)? 'Publish' : 'Unpublish'; ?> </a></li> 
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


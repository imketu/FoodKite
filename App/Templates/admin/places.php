<?php include("header.php"); ?>
<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
<!-- start content -->
<div id="content">

	<!--  start page-heading -->
	<div id="page-heading">
		<h1 style="display:inline; float:left; padding-top: 5px;"><?php echo $title; ?></h1>
		<!--  start related-activities -->
		<div id="related-activities"  style="display:inline; float:right;">
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
						<h5>Add New Places</h5>
						<ul class="greyarrow">
							<li><a href="/FoodKite/App/admin/place/add/">Search and add places</a></li> 
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
			
				<?php include('messages.php'); ?>		
		 
				<!--  start product-table ..................................................................................... -->
				<form id="mainform" action="">
				<table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
				<tr>
					<th class="table-header-repeat line-left minwidth-1"><a href="">Id</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">Name</a></th>
					<th class="table-header-repeat line-left"><a href="">Address</a></th>
					<th class="table-header-repeat line-left"><a href="">Type</a></th>
					<th class="table-header-repeat line-left"><a href="">Published</a></th>
					<th class="table-header-options line-left"><a href="">Options</a></th>
					<th class="table-header-options line-left"><a href="">Deal</a></th>
				</tr>
				<?php	foreach($places as $place){ 
					$address="";
					if(!empty($place["Address"])){
						$address=	$place["Address"];
					}
					if(!empty($place["City"])){
						$address = empty($address)?$address :"$address , ";
						$address .=	$place["City"];
					}

					if(!empty($place["Country"])){
						$address = empty($address)?$address :"$address , ";
						$address.=	$place["Country"];
					}
				?>
				<tr>
					<td><?php echo $place["PlaceID"]; ?></td>
					<td><?php echo $place["PlaceName"]; ?></td>
					<td><?php echo $address; ?></td>
					<td><?php echo $place["TypeName"]; ?></td>
					<td><?php echo ($place["IsPublished"])?"<strong style='color:green'>Yes<strong>":"<strong style='color:Red'>No<strong>"; ?></td>
					<td class="options-width">
					<a href="/FoodKite/App/admin/place/<?php echo $place['PlaceID']; ?>/delete/<?php echo $page; ?>/" title="Delete Place" class="icon-2 info-tooltip" ONCLICK="decision('Do you want to delete \'<?php echo $place["PlaceName"]; ?>\' ?')"></a>
					<a href="/FoodKite/App/admin/menu/<?php echo $place['PlaceID']; ?>/" title="<?php echo ($place['Menu']>0)?'Show Menu' : 'Add Menu'; ?>" class="<?php echo ($place['Menu']>0)?'icon-3' : 'icon-1'; ?> info-tooltip"></a>
					</td>
					<td>
						<?php if(!empty($place["Deal"])): ?>
								<table border="0">
									<tr border="0">
										<td border="0"><em><?php echo $place["Deal"]["Name"]?></em></td>
									</tr>
									<tr border="0">
										<td align="middle" border="0"><u><?php echo date( 'jS M, Y',  strtotime($place["Deal"]["DealStartTime"])	);?></u> &nbsp &nbsp to &nbsp &nbsp 
										<u><?php echo date( 'jS M, Y',  strtotime($place["Deal"]["DealEndTime"])	);?></u></td>
									</tr>
									<tr>
										<td>
											<a href="/FoodKite/App/admin/place/<?php echo $place['PlaceID']; ?>/deal/<?php echo $place["Deal"]["id"]?>/delete/<?php echo $page; ?>/" title="Delete Place" class="icon-2 info-tooltip" ONCLICK="decision('Do you want to delete \'<?php echo $place["PlaceName"]; ?>\' ?')"></a>
											<a href="/FoodKite/App/admin/place/<?php echo $place['PlaceID']; ?>/deal/edit/<?php echo $page; ?>/" title="<?php echo ($place['Menu']>0)?'Show Menu' : 'Add Menu'; ?>" class="icon-1 info-tooltip"></a>
										</td>
									</tr>
							</table>
						<?php else: ?>
							<a href="/FoodKite/App/admin/place/<?php echo $place['PlaceID']; ?>/deal/edit/<?php echo $page; ?>/" title="<?php echo ($place['Menu']>0)?'Show Menu' : 'Add Menu'; ?>" class="icon-1 info-tooltip"></a>
						<?php	endif; ?>

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
			<td>
				<a href="<?php echo ($page>1)?'/FoodKite/App/admin/places/'.($page-1).'/':'#'; ?>" class="page-left"></a>
				<div id="page-info">Page "<?php echo $page; ?></div>
				<a href="<?php echo ($page==$allPages)?'#':'/FoodKite/App/admin/places/'.($page+1).'/'; ?>" class="page-right"></a>
			</td>
			<td>
			</td>
			</tr>
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
	<div class="clear">&nbsp;</div>

</div>
<!--  end content -->
<div class="clear">&nbsp;</div>
</div>
<!--  end content-outer........................................................END -->
<?php include("footer.php"); ?>

<?php include("header.php")?>
<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
<!-- start content -->
<div id="content">

	<!--  start page-heading -->
	<div id="page-heading">
		<a href="/FoodKite/App/admin/menu/<?php echo $placeId;?>" style="color:#FFF; background:#e63; line-height:24px; padding:4px"><- Return</a>
		<h1><?php echo $title; ?></h1>
	</div>
	<div class="clear">&nbsp;</div>
<!-- end page-heading -->
	<?php include('messages.php'); ?>

	 <form action="" method="post">
		<table border="0" width="65%" cellpadding="0" cellspacing="0" id="id-form">
			<tr>
				<th valign="top">Submenu:</th>
				<td><input type="text" name="submenu" class="inp-form" <?php echo (isset($submenu) && isset($submenu['Name']))?"value='".$submenu['Name']."'": "" ;?> /></td>
				<td>
					<div class="error-left"></div>
					<div class="error-inner">This field is required.</div>
				</td>
			</tr>
			<tr>
				<th valign="top">Select Attributes:</th>
				<td>
					<select id="d" name="attributes[]" multiple="multiple" style="width:80%"">
						<?php foreach($attributes as $attribute) { ?>
						<option value="<?php echo $attribute['id']?>" <?php echo (isset($submenu) && isset($submenu['Attributes']) && in_array($attribute['id'],$submenu['Attributes'] ))?"selected": "" ;?>><?php echo $attribute['Attribute']?></option>
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


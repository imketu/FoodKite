<?php include("header.php")?>
<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
<!-- start content -->
<div id="content">

	<!--  start page-heading -->
	<div id="page-heading">
		<a href="/FoodKite/App/admin/dishes/<?php echo $menuId; ?>/<?php echo $submenuId; ?>/" style="color:#FFF; background:#e63; line-height:24px; padding:4px"><- Return</a>
		<h1><?php echo $title; ?></h1>
	</div>
	<div class="clear">&nbsp;</div>
<!-- end page-heading -->
	<?php include('messages.php'); ?>

	 <form id="myForm" action="" method="post">
		<div style="padding-bottom: 12px; margin-bottom: 15px; border-bottom: #ccc dashed 1px">
		<?php if(isset($dishes) && !empty($dishes)) {
		$i=1;
		foreach($dishes as $dish){
		?>
			<?php if($i==1){ ?>
			<table border="0" width="700" cellpadding="0" cellspacing="0" id="id-form">
					<tr>
						<th valign="top" width="150">Dish Name:</th>
						<td width="250"><input type="text" name="name" class="inp-form" value="<?php echo (!empty($dish['Name']))?$dish['Name']:'' ?>" /></td>
						<td>
							<div class="error-left"></div>
							<div class="error-inner">This field is required.</div>
						</td>
					</tr>
					<tr>
						<th valign="top" width="150">Description:</th>
						<td width="250"><textarea rows="6" cols="22"type="text" name="description"><?php echo (!empty($dish['Description']))?$dish['Description']:'' ?></textarea></td>
						<td>	</td>
					</tr>
				</table>
				<?php } ?>
				<div class="clonedForm" id="name<?php echo $i; ?>"  <?php echo ($i!=1)?'style="width:705px; border:#99A solid 2px; margin: 5px 0; padding:5px 2px 5px 2px;"':""?>>
					<table border="0" width="700" cellpadding="0" cellspacing="0" id="id-form">
						<tr <?php echo ($i==1)?'id="hidden" style="display:none"':'' ?>>
						<th valign="top" colspan="3" style="background:#cfd; margin: 0; padding:4px 10px">Additional information for this varient.</th>
						<td>
						</td>
					</tr>
					<tr  <?php echo ($i==1)?'id="hidden" style="display:none"':'' ?>>
						<th valign="top" width="150">Adiitional Description:</th>
						<td width="250"><textarea rows="3" cols="22"type="text" id="aditionaldescription" name="aditionaldescription<?php echo $i; ?>"><?php echo (!empty($dish['AdditionalDescription']))?$dish['AdditionalDescription']:'' ?></textarea></td>
						<td>
						</td>
					</tr>
					<tr>
						<th valign="top" width="150">Price:</th>
						<td width="250"><input type="text" id="price" name="price<?php echo $i; ?>" class="inp-form" value="<?php echo (!empty($dish['Price']))?$dish['Price']:'' ?>"/></td>
						<td>
						</td>
					</tr>
					<tr>
						<th valign="top" width="150">Calories:</th>
						<td width="250"><input type="text" id="calories" name="calories<?php echo $i; ?>" class="inp-form" value="<?php echo (!empty($dish['Calories']))?$dish['Calories']:'' ?>"/></td>
						<td></td>
					</tr>
					<tr>
						<th valign="top">Select Attributes:</th>
						<td>
							<select id="attributes" name="attributes<?php echo $i; ?>[]" multiple="multiple" style="width:80%"">
								<?php foreach($attributes as $attribute) { ?>
								<option value="<?php echo $attribute['id']?>" <?php echo (isset($dish['Attributes']) && in_array($attribute['id'],$dish['Attributes'] ))?"selected": "" ;?>><?php echo $attribute['Attribute']?></option>
								<?php } ?>
							</select>
						</td>
						<td><div class="bubble-left"></div>
						<div class="bubble-inner">You can select multiple options by pressing <strong>Ctrl</strong></div>
						<div class="bubble-right"></div></td>
					</tr>
				</table>
			 </div>
			<?php $i++;
				 } ?>
				<div style="padding:6px">
					 <input type="button" id="btnAdd" value=" Add a varient of this dish " />
					 <input type="button" id="btnDel" value=" Remove varient " />
					 <input type="hidden" id="dishcount" name="dishcount" value="<?php echo count($dishes); ?>"/>
				</div>
		
	<?php } else { ?>
		
			<table border="0" width="700" cellpadding="0" cellspacing="0" id="id-form">
					<tr>
						<th valign="top" width="150">Dish Name:</th>
						<td width="250"><input type="text" name="name" class="inp-form" /></td>
						<td>
							<div class="error-left"></div>
							<div class="error-inner">This field is required.</div>
						</td>
					</tr>
					<tr>
						<th valign="top" width="150">Description:</th>
						<td width="250"><textarea rows="6" cols="22"type="text" name="description"></textarea></td>
						<td>	</td>
					</tr>
				</table>
				<div class="clonedForm" id="name1">
					<table border="0" width="700" cellpadding="0" cellspacing="0" id="id-form">
						<tr id="hidden" style="display:none">
						<th valign="top" colspan="3" style="background:#cfd; margin: 0; padding:4px 10px">Additional information for this varient.</th>
						<td>
						</td>
					</tr>
					<tr id="hidden" style="display:none">
						<th valign="top" width="150">Adiitional Description:</th>
						<td width="250"><textarea rows="3" cols="22"type="text" id="aditionaldescription" name="aditionaldescription1"></textarea></td>
						<td>
						</td>
					</tr>
					<tr>
						<th valign="top" width="150">Price:</th>
						<td width="250"><input type="text" id="price" name="price1" class="inp-form" /></td>
						<td>
						</td>
					</tr>
					<tr>
						<th valign="top" width="150">Calories:</th>
						<td width="250"><input type="text" id="calories" name="calories1" class="inp-form" /></td>
						<td></td>
					</tr>
					<tr>
						<th valign="top">Select Attributes:</th>
						<td>
							<select id="attributes" name="attributes1[]" multiple="multiple" style="width:80%"">
								<?php foreach($attributes as $attribute) { ?>
								<option value="<?php echo $attribute['id']?>"><?php echo $attribute['Attribute']?></option>
								<?php } ?>
							</select>
						</td>
						<td><div class="bubble-left"></div>
						<div class="bubble-inner">You can select multiple options by pressing <strong>Ctrl</strong></div>
						<div class="bubble-right"></div></td>
					</tr>
				</table>
			 </div>
				<div style="padding:6px">
					 <input type="button" id="btnAdd" value=" Add a varient of this dish " />
					 <input type="button" id="btnDel" value=" Remove varient " />
					 <input type="hidden" id="dishcount" name="dishcount" value="1"/>
				</div>
				<?php } ?>
		</div>
		<table border="0" width="700" cellpadding="0" cellspacing="0" id="id-form">
			<tr>
				<th>&nbsp;</th>
				<td valign="middle" align="right">
					<input type="submit" value="" class="form-submit" />
				</td>
				<td></td>
		</tr>
		</table><br/>
		
	</form>
	<div class="clear">&nbsp;</div>
		
</div>
<!--  end content -->
<div class="clear">&nbsp;</div>
</div>
<!--  end content-outer........................................................END -->



<?php include("footer.php")?>




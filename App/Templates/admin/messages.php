		<!--  start message-yellow -->
			<?php if(isset($flash['info'])) {?>
				<div id="message-yellow">
				<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td class="yellow-left">Alert. <?php echo $flash['info']; ?></td>
					<td class="yellow-right"><a class="close-yellow"><img src="/FoodKite/App/Templates/admin/images/table/icon_close_yellow.gif"   alt="" /></a></td>
				</tr>
				</table>
				</div>
				<!--  end message-yellow -->
			<?php } elseif(isset($flash['error'])) {?>
				<!--  start message-red -->
				<div id="message-red">
				<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td class="red-left">Error. <?php echo $flash['error']; ?></td>
					<td class="red-right"><a class="close-red"><img src="/FoodKite/App/Templates/admin/images/table/icon_close_red.gif"   alt="" /></a></td>
				</tr>
				</table>
				</div>
				<!--  end message-red -->
			<?php } elseif(isset($flash['msg'])) {?>	
				<!--  start message-blue -->
				<div id="message-blue">
				<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td class="blue-left"> <?php echo $flash['msg']; ?> </td>
					<td class="blue-right"><a class="close-blue"><img src="/FoodKite/App/Templates/admin/images/table/icon_close_blue.gif"   alt="" /></a></td>
				</tr>
				</table>
				</div>
				<!--  end message-blue -->
			<?php } elseif(isset($flash['success'])) {?>	
				<!--  start message-green -->
				<div id="message-green">
				<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td class="green-left">Success. <?php echo $flash['success']; ?></td>
					<td class="green-right"><a class="close-green"><img src="/FoodKite/App/Templates/admin/images/table/icon_close_green.gif"   alt="" /></a></td>
				</tr>
				</table>
				</div>
			<?php } ?>	
				<!--  end message-green -->

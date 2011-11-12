<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Foodkite Admin</title>
<link rel="stylesheet" href="/FoodKite/App/Templates/admin/css/screen.css" type="text/css" media="screen" title="default" />
<!--[if IE]>
<link rel="stylesheet" media="all" type="text/css" href="css/pro_dropline_ie.css" />
<![endif]-->

<!--  jquery core -->
<script src="/FoodKite/App/Templates/admin/js/jquery/jquery-1.4.1.min.js" type="text/javascript"></script>


<script type="text/javascript">
    $(document).ready(function() {
				var minDishNumber = $('.clonedForm').length;
				if (minDishNumber == 5)
                $('#btnAdd').attr('disabled','disabled');

				$('a#xhrlinks').bind('click',function(event){
						event.preventDefault();
						$.get(this.href,{},function(response){ 
				 	    $('#response').html(response);
						})	
						var close = $(this).closest("tr");
						$.each(close, function(i){
							$(this).css('display', 'none');;
						});
				 });

        $('#btnAdd').click(function() {
            var num     = $('.clonedForm').length; // how many "duplicatable" input fields we currently have
            var newNum  = new Number(num + 1);      // the numeric ID of the new input field being added
						
            // create the new element via clone(), and manipulate it's ID using newNum value
            var newElem = $('#name'+num).clone();
						elcolor = "#"+(11-num*2)+(11-num*2)+(11-num*2)+" solid 4px"
						$(newElem).css({width:'705px', border:elcolor, margin: '5px 0', padding: '5px 2px 5px 2px'})
            // manipulate the name/id values of the input inside the new element
            newElem.attr('id', 'name' + newNum);

						$("#hidden", newElem).css('display', '');
						$("textarea", newElem).each(function(index, value) { 
							elementname = $(this).attr('id')+newNum;
							$(this).attr('name',elementname)
						});
						$("input", newElem).each(function(index, value) { 
							elementname = $(this).attr('id')+newNum;
							$(this).attr('name',elementname); 
							$(this).val("");
						});
						
						$("select", newElem).each(function(index, value) { 
							elementname = $(this).attr('id')+newNum+"[]";
							$(this).attr('name',elementname); 
						});
            // insert the new element after the last "duplicatable" input field
            $('#name' + num).after(newElem);

            // enable the "remove" button
            $('#btnDel').attr('disabled','');
						$('#dishcount').val(newNum);

            // business rule: you can only add 5 names
            if (newNum == 5)
                $('#btnAdd').attr('disabled','disabled');
        });

        $('#btnDel').click(function() {
            var num = $('.clonedForm').length; // how many "duplicatable" input fields we currently have
            $('#name'+num).remove();     // remove the last element
						$('#dishcount').val(num-1);
            // enable the "add" button
            $('#btnAdd').attr('disabled','');
						
            // if only one element remains, disable the "remove" button
            if (num-1 == minDishNumber)
                $('#btnDel').attr('disabled','disabled');
        });

        $('#btnDel').attr('disabled','disabled');
    });
</script>



<!--  checkbox styling script -->
<script src="/FoodKite/App/Templates/admin/js/jquery/ui.core.js" type="text/javascript"></script>
<script src="/FoodKite/App/Templates/admin/js/jquery/ui.checkbox.js" type="text/javascript"></script>
<script src="/FoodKite/App/Templates/admin/js/jquery/jquery.bind.js" type="text/javascript"></script>
<script type="text/javascript">
$(function(){
	$('input').checkBox();
	$('#toggle-all').click(function(){
 	$('#toggle-all').toggleClass('toggle-checked');
	$('#mainform input[type=checkbox]').checkBox('toggle');
	return false;
	});
});

function decision(message, url){
if(confirm(message)) location.href = url;
}

</script>  

<![if !IE 7]>

<!--  styled select box script version 1 -->
<script src="/FoodKite/App/Templates/admin/js/jquery/jquery.selectbox-0.5.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('.styledselect').selectbox({ inputClass: "selectbox_styled" });
});
</script>
 

<![endif]>

<!--  styled select box script version 2 --> 
<script src="/FoodKite/App/Templates/admin/js/jquery/jquery.selectbox-0.5_style_2.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('.styledselect_form_1').selectbox({ inputClass: "styledselect_form_1" });
	$('.styledselect_form_2').selectbox({ inputClass: "styledselect_form_2" });
});
</script>

<!--  styled select box script version 3 --> 
<script src="/FoodKite/App/Templates/admin/js/jquery/jquery.selectbox-0.5_style_2.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('.styledselect_pages').selectbox({ inputClass: "styledselect_pages" });
});
</script>

<!--  styled file upload script --> 
<script src="/FoodKite/App/Templates/admin/js/jquery/jquery.filestyle.js" type="text/javascript"></script>
<script type="text/javascript" charset="utf-8">
  $(function() {
      $("input.file_1").filestyle({ 
          image: "images/forms/choose-file.gif",
          imageheight : 21,
          imagewidth : 78,
          width : 310
      });
  });
</script>

<!-- Custom jquery scripts -->
<script src="/FoodKite/App/Templates/admin/js/jquery/custom_jquery.js" type="text/javascript"></script>
 
<!-- Tooltips -->
<script src="js/jquery/jquery.tooltip.js" type="text/javascript"></script>
<script src="js/jquery/jquery.dimensions.js" type="text/javascript"></script>
<script type="text/javascript">
$(function() {
	$('a.info-tooltip ').tooltip({
		track: true,
		delay: 0,
		fixPNG: true, 
		showURL: false,
		showBody: " - ",
		top: -35,
		left: 5
	});
});
</script> 


<!--  date picker script -->
<link rel="stylesheet" href="/FoodKite/App/Templates/admin/css/datePicker.css" type="text/css" />
<script src="/FoodKite/App/Templates/admin/js/jquery/date.js" type="text/javascript"></script>
<script src="/FoodKite/App/Templates/admin/js/jquery/jquery.datePicker.js" type="text/javascript"></script>
<script type="text/javascript" charset="utf-8">
 $(function()
{

// initialise the "Select date" link
$('#date-pick')
	.datePicker(
		// associate the link with a date picker
		{
			createButton:false,
			startDate:'01/11/2011',
			endDate:'31/2/2012'
		}
	).bind(
		// when the link is clicked display the date picker
		'click',
		function()
		{
			updateSelects($(this).dpGetSelected()[0]);
			$(this).dpDisplay();
			return false;
		}
	).bind(
		// when a date is selected update the SELECTs
		'dateSelected',
		function(e, selectedDate, $td, state)
		{
			updateSelects(selectedDate);
		}
	).bind(
		'dpClosed',
		function(e, selected)
		{
			updateSelects(selected[0]);
		}
	);
	
var updateSelects = function (selectedDate)
{
	var selectedDate = new Date(selectedDate);
	$('#d option[value=' + selectedDate.getDate() + ']').attr('selected', 'selected');
	$('#m option[value=' + (selectedDate.getMonth()+1) + ']').attr('selected', 'selected');
	$('#y option[value=' + (selectedDate.getFullYear()) + ']').attr('selected', 'selected');
}
// listen for when the selects are changed and update the picker
$('#d, #m, #y')
	.bind(
		'change',
		function()
		{
			var d = new Date(
						$('#y').val(),
						$('#m').val()-1,
						$('#d').val()
					);
			$('#date-pick').dpSetSelected(d.asString());
		}
	);

// default the position of the selects to today
var today = new Date();
updateSelects(today.getTime());

// and update the datePicker to reflect it...
$('#d').trigger('change');
});
</script>

<!-- MUST BE THE LAST SCRIPT IN <HEAD></HEAD></HEAD> png fix -->
<script src="/FoodKite/App/Templates/admin/js/jquery/jquery.pngFix.pack.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
$(document).pngFix( );
});
</script>
</head>
<body> 
<!-- Start: page-top-outer -->
<div id="page-top-outer">    

<!-- Start: page-top -->
<div id="page-top">

	<!-- start logo -->
	<div id="logo">
	<a href="/FoodKite/App/admin/places/"><img src="/FoodKite/App/Templates/admin/images/shared/logo.png" width="156" height="40" alt="" /></a>
	</div>
	<!-- end logo -->
	
	<!--  start top-search -->
	<div id="top-search">
	</div>
 	<!--  end top-search -->
 	<div class="clear"></div>

</div>
<!-- End: page-top -->

</div>
<!-- End: page-top-outer -->
	
<div class="clear">&nbsp;</div>
 
<!--  start nav-outer-repeat................................................................................................. START -->
<div class="nav-outer-repeat"> 
<!--  start nav-outer -->
<div class="nav-outer"> 

		<!-- start nav-right -->
		<div id="nav-right">
			<div class="nav-divider">&nbsp;</div>
			<a href="/FoodKite/App/admin/logout/" id="logout"><img src="/FoodKite/App/Templates/admin/images/shared/nav/nav_logout.gif" width="64" height="14" alt="" /></a>
			<div class="clear">&nbsp;</div>
		</div>
		<!-- end nav-right -->


		<!--  start nav -->
		<div class="nav">
		<div class="table">
		
		<ul class="<?php echo ($toolMenu==1)?'current' : 'select'; ?>"><li><a href="#nogo"><b>Places</b><!--[if IE 7]><!--></a><!--<![endif]-->
		<!--[if lte IE 6]><table><tr><td><![endif]-->
		<div class="<?php echo ($toolMenu==1)?'select_sub show' : 'select_sub'; ?>">
		</div>
		<!--[if lte IE 6]></td></tr></table></a><![endif]-->
		</li>
		</ul>
		
		<div class="nav-divider">&nbsp;</div>
		                    
		<ul class="<?php echo ($toolMenu==2)?'current' : 'select'; ?>"><li><a href="#nogo"><b>Menu</b><!--[if IE 7]><!--></a><!--<![endif]-->
		<!--[if lte IE 6]><table><tr><td><![endif]-->
		<div class="<?php echo ($toolMenu==1)?'select_sub show' : 'select_sub'; ?>">
		</div>
		<!--[if lte IE 6]></td></tr></table></a><![endif]-->
		</li>
		</ul>
		
		<div class="nav-divider">&nbsp;</div>
		
		<ul class="<?php echo ($toolMenu==3)?'current' : 'select'; ?>"><li><a href="#nogo"><b>Dish</b><!--[if IE 7]><!--></a><!--<![endif]-->
		<!--[if lte IE 6]><table><tr><td><![endif]-->
		<div class="<?php echo ($toolMenu==1)?'select_sub show' : 'select_sub'; ?>">
			<ul class="sub">
			</ul>
		</div>
		<!--[if lte IE 6]></td></tr></table></a><![endif]-->
		</li>
		</ul>
		
		<div class="clear"></div>
		</div>
		<!--  start nav -->

</div>
<div class="clear"></div>
<!--  start nav-outer -->
</div>
<!--  start nav-outer-repeat................................................... END -->

 <div class="clear"></div>

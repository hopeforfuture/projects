<?php echo $header; ?>
<?php
$dob = '';
$hobby_arr = array();
$u_food = '';
$u_smoking = '';
$u_drink = '';
if(isset($op) && ($op == 'edit'))
{
	$dobarr = explode("-", $uinfo->u_dob);
	$dob = $dobarr[2]."/".$dobarr[1]."/".$dobarr[0];
	$hobby_arr = explode(",", $uinfo->u_hobby);

	$u_food = $uinfo->u_food;
	$u_smoking = $uinfo->u_smoking;
	$u_drink = $uinfo->u_drink;
}
elseif(isset($op) && ($op == 'add'))
{
	$hobby_arr = explode(",", set_value("hobby"));
	$u_food = set_value('u_food');
	$u_smoking = set_value('u_smoking');
	$u_drink = set_value('u_drink');
}
?>
<form autocomplete="off" method="post" id="form1" action="<?php echo $action; ?>" enctype="multipart/form-data">
<table border="0" align="center">
	<tr>
		<td colspan="3" align="center">
			<span style="color:red; font-weight: bold;"><?php echo ($op == 'add') ? 'ADD USER': 'EDIT USER'; ?></span>
		</td>
	</tr>

	<tr>
		<td>Name*</td>
		<td>:</td>
		<td>
			<input style="width:350px;" type="text" name="u_name" id="u_name" value="<?php echo ($op == 'add') ?  set_value('u_name') :  $uinfo->u_name; ?>">
		</td>
	</tr>


	<tr>
		<td>Email*</td>
		<td>:</td>
		<td>
			<input style="width:350px;" type="text" name="u_email" id="u_email" value="<?php echo  ($op == 'add') ?  set_value('u_email') :  $uinfo->u_email ; ?>">
		</td>
	</tr>

	<?php if($op == 'add') { ?>
	<tr>
		<td>Password*</td>
		<td>:</td>
		<td>
			<input type="password" name="u_pswd" id="u_pswd" style="width:350px;">
		</td>
	</tr>

	<tr>
		<td>Confirm Password*</td>
		<td>:</td>
		<td>
			<input type="password" name="u_conf_pswd" id="u_conf_pswd" style="width:350px;">
		</td>
	</tr>
	<?php } ?>
	<tr>
		<td valign="top">Address*</td>
		<td valign="top">:</td>
		<td valign="top">
			<textarea name="u_address" id="u_address" rows="5" cols="46"><?php echo ($op == 'add') ?  set_value('u_address') :  $uinfo->u_address; ?></textarea>
		</td>
	</tr>

	<tr>
		<td>Contact*</td>
		<td>:</td>
		<td>
			<input style="width:350px;" type="text" name="u_contact" id="u_contact" value="<?php echo ($op == 'add') ?  set_value('u_contact') :  $uinfo->u_contact; ?>">
		</td>
	</tr>

	<tr>
		<td valign="top">Profile Image</td>
		<td valign="top">:</td>
		<td valign="top">
			<input style="width:350px;" type="file" name="u_image" id="u_image" onchange="loadFile(event)"><br/>
			<img id="output" src="<?php echo (($op == 'edit') && !empty($uinfo->u_image_thumb)) ? base_url().'uploads/thumb/'.$uinfo->u_image_thumb : ''; ?>" />
		</td>
	</tr>

	<tr>
		<td valign="top">Gender*</td>
		<td valign="top">:</td>
		<td valign="top">
			<select name="u_gender" id="u_gender" style="width: 350px;">
				<option value="">---Select Your Gender---</option>

				<option <?php if((set_value('u_gender') == 'M') && ($op == 'add')){ echo 'selected';} elseif(($op == 'edit') && ($uinfo->u_gender == 'M')){ echo 'selected'; } ?> value="M">M</option>

				<option <?php if((set_value('u_gender') == 'F') && ($op == 'add')){ echo 'selected';} elseif(($op == 'edit') && ($uinfo->u_gender == 'F')){ echo 'selected'; } ?> value="F">F</option>
			</select>
		</td>
	</tr>

	<tr>
		<td valign="top">Date of Birth*</td>
		<td valign="top">:</td>
		<td valign=" top">
			<input style="width:350px;" type="text" name="u_dob" id="u_dob" value="<?php echo ($op == 'add') ? set_value('u_dob') : $dob; ?>">
		</td>
	</tr>

	<tr>
		<td valign="top">Marital Status*</td>
		<td valign="top">:</td>
		<td valign="top">
			<select name="u_married" id="u_married" style="width: 350px;">
				<option value="">---Select Your Marital Status---</option>
				<option <?php if(set_value('u_married') == 'M' && ($op == 'add')){ echo 'selected';} elseif(($op == 'edit') && ($uinfo->u_married == 'M')){ echo 'selected';} ?> value="M">Married</option>
				<option <?php if(set_value('u_married') == 'U' && ($op == 'add')){ echo 'selected';} elseif(($op == 'edit') && ($uinfo->u_married == 'U')){ echo 'selected';} ?> value="U">Single</option>
			</select>
		</td>
	</tr>

	<tr>
		<td valign="top">Hobby*</td>
		<td valign="top">:</td>
		<td valign="top">
			<?php
			foreach($hobbylist as $hl)
			{
			?>
				<input <?php if(in_array($hl->hb_id, $hobby_arr)){ echo 'checked';} ?> class="hobbyclass" type="checkbox" name="hobby[]" value="<?php echo $hl->hb_id; ?>">&nbsp;<?php echo $hl->hb_name; ?>&nbsp;
			<?php
			}
			?>
		</td>
	</tr>

	<tr>
		<td valign="top">Food Habit</td>
		<td valign="top">:</td>
		<td valign="top">
			<select name="u_food" id="u_food" style="width: 350px;">
				<option value="">---Select Your Food Habit---</option>
				<option <?php if($u_food == 'V'){ echo 'selected';} ?> value="V">Vegeterian</option>
				<option <?php if($u_food == 'NV'){ echo 'selected';} ?> value="NV">Non Vegeterian</option>
				<option <?php if($u_food == 'E'){ echo 'selected';} ?> value="E">Eggiterian</option>
				<option <?php if($u_food == 'NSP'){ echo 'selected';} ?> value="NSP">Not Specified</option>
			</select>
		</td>
	</tr>

	<tr>
		<td valign="top">Smoking Habit</td>
		<td valign="top">:</td>
		<td valign="top">
			<select name="u_smoking" id="u_smoking" style="width: 350px;">
				<option value="">---Select Your Smiking Habit---</option>
				<option <?php if($u_smoking == 'NS'){ echo 'selected';} ?> value="NS">Non Smoker</option>
				<option <?php if($u_smoking == 'RS'){ echo 'selected';} ?> value="RS">Regular Smoker</option>
				<option <?php if($u_smoking == 'OS'){ echo 'selected';} ?> value="OS">Occational Smoker</option>
				<option <?php if($u_smoking == 'NSP'){ echo 'selected';} ?> value="NSP">Not Specified</option>
			</select>
		</td>
	</tr>

	<tr>
		<td valign="top">Drinking Habit</td>
		<td valign="top">:</td>
		<td valign="top">
			<select name="u_drink" id="u_drink" style="width: 350px;">
				<option value="">---Select Your Drinking Habit---</option>
				<option <?php if($u_drink == 'ND'){ echo 'selected';} ?> value="ND">Non Drinker</option>
				<option <?php if($u_drink == 'RD'){ echo 'selected';} ?> value="RD">Regular Drinker</option>
				<option <?php if($u_drink == 'OD'){ echo 'selected';} ?> value="OD">Occational Drinker</option>
				<option <?php if($u_drink == 'NSP'){ echo 'selected';} ?> value="NSP">Not Specified</option>
			</select>
		</td>
	</tr>

	<tr>
		<td colspan="4" align="center">
			<input type="hidden" name="u_id" id="u_id" value="<?php echo ($op == 'add') ? 0 : $uinfo->u_id; ?>">
			<?php
			if($op == 'edit')
			{
			?>
				<input type="hidden" name="u_image" value="<?php echo $uinfo->u_image; ?>" />
				<input type="hidden" name="u_image_thumb" value="<?php echo $uinfo->u_image_thumb; ?>" />
				<input type="hidden" name="last_updated" value="<?php echo strtotime($uinfo->updated_at); ?>">
			<?php
			}
			?>
			<button type="submit" name="btnCreate" id="btnCreate"><?php echo ($op == 'add') ? 'ADD USER' : 'UPDATE USER'; ?> </button>
			<button type="button" name="btnBack" id="btnBack">BACK</button>
		</td>
	</tr>

</table>
</form>
<?php echo $footer; ?>

<script>
  var loadFile = function(event) {
    var output = document.getElementById('output');
    output.src = URL.createObjectURL(event.target.files[0]);
  };

  $(document).ready(function(){
  		$( "#u_dob" ).datepicker({
      		changeMonth: true,
      		changeYear: true,
      		maxDate: "-1Y",
      		dateFormat: "dd/mm/yy"
    	});

    	function validateEmail($email) {
  			var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
  			return emailReg.test( $email );
		}

		$("#btnBack").click(function(e){
			e.preventDefault();
			window.location.href = '<?php echo base_url(); ?>'+'user/list';
			
		});

    	$("#btnCreate").click(function(e){
    		e.preventDefault();
    		var uid = Number($("#u_id").val());
    		var name = $("#u_name").val().trim();
    		var email = $("#u_email").val().trim();
    		if(uid == 0)
    		{
    			var pswd = $("#u_pswd").val();
    			var conf_pswd = $("#u_conf_pswd").val();
    		}
    		
    		var address = $("#u_address").val().trim();
    		var contact = $("#u_contact").val().trim();
    		var gender = $("#u_gender").val();
    		var dob = $("#u_dob").val().trim();
    		var married = $("#u_married").val();
    		var hobby_num = $(".hobbyclass:checked").length;
    		var food_habit = $("#u_food").val();
    		var smoke_habit = $("#u_smoking").val();
    		var drink_habit = $("#u_drink").val();

    		var node_list = document.getElementsByTagName('input');
	        var count = 0;
	        var sFileName = '';
	        var sFileExtension = '';
	        var node = '';

	        var error_str = '';
	        var error_name = '';
	        var error_email = '';
	        var error_pswd = '';
	        //var error_conf_pswd = '';
	        var error_address = '';
	        var error_contact = '';
	        var error_gender = '';
	        var error_dob = '';
	        var error_married = '';
	        var error_hobby = '';
	        var error_img = '';


	        for (var i = 0; i < node_list.length; i++) 
        	{
             	node = node_list[i];
	            if (node.getAttribute('type') === 'file') 
	            {
	                 sFileName = node.value;
	                 sFileExtension = sFileName.split('.')[sFileName.split('.').length - 1].toLowerCase();
	                 if(sFileName.length > 0)
	                 {
	                 	if((sFileExtension == 'jpg') || (sFileExtension == 'png') || (sFileExtension == 'gif') || (sFileExtension == 'jpeg')) 
	                 	{
	                    
	                 		error_img = '';
	                	}
	                	else
	                	{
	                		error_img = 'Not a valid image.<br/>';
	                	}
	                 }
	            }

	         }

	         if(name == '')
	         {
	         	error_name = 'Name required.<br/>';
	         }
	         else 
	         {
	         	error_name = '';
	         }
	         if(email == '')
	         {
	         	error_email = 'Email required.<br/>';
	         }
	         else if(email.length > 0)
	         {
	         	if(!validateEmail(email))
	         	{
	         		error_email = 'Invalid email.<br/>';
	         	}
	         	else if(validateEmail(email))
	         	{
	         		var targeturl = '<?php echo base_url(); ?>'+'ajax/checkduplicateemail';

	         		$.ajax({

	         			type: "post",
	         			url: targeturl,
	         			data: {email: email, u_id: uid},
	         			async: false,
	         			success: function(response)
	         			{
	         				var res = JSON.parse(response);

	         				if(res.status == true)
	         				{
	         					error_email = '';
	         				}
	         				else if(res.status == false)
	         				{
	         					error_email = 'email already exists.<br/>';
	         				}
	         			}

	         		});
	         	}
	         }
	         if(uid == 0)
	         {
	         	if(pswd == '')
		         {
		         	error_pswd = 'Password can not be empty.<br/>';
		         }
		         else if(pswd.length < 6)
		         {
		         	error_pswd = 'Minimum length of password should be 6.<br/>';
		         }
		         else if(pswd.length > 16)
		         {
		         	error_pswd = 'Maximum length of password should be 16.<br/>';
		         }
		         else if(pswd != conf_pswd)
		         {
		         	error_pswd = 'Password mismatch occur.<br/>';
		         }
		         else 
		         {
		         	error_pswd = '';
		         }
	         }
	         

	         if(address == '')
	         {
	         	error_address = 'Address required.<br/>';
	         }
	         else 
	         {
	         	error_address = '';
	         }

	         if(contact == '')
	         {
	         	error_contact = 'Contact No. required.<br/>';
	         }
	         else if(contact.length < 10)
	         {
	         	error_contact = 'Minimum 10 digit contact no required.<br/>';
	         }
	         else
	         {
	         	error_contact = '';
	         }

	         if(gender == '')
	         {
	         	error_gender = 'Gender required.<br/>';
	         }
	         else
	         {
	         	error_gender = '';
	         }

	         if(dob == '')
	         {
	         	error_dob = 'DOB required.<br/>';
	         }
	         else
	         {
	         	error_dob = '';
	         }

	         if(married == '')
	         {
	         	error_married = 'Marital status required.<br/>';
	         }
	         else
	         {
	         	error_married = '';
	         }

	         if(hobby_num == 0)
	         {
	         	error_hobby = 'Please choose atleast one hobby.<br/>';
	         }
	         else
	         {
	         	error_hobby = '';
	         }

	         error_str = error_name + error_email + error_pswd + error_address + error_contact
	                     + error_img + error_gender + error_dob + error_married + error_hobby;

	         if(error_str.length > 0)
	         {
	         	$(".modal-body").html(error_str);
	         	$("#myModal").modal();
	         	return false;
	         }

        
    		document.getElementById("form1").submit();
    	});
  });
</script>
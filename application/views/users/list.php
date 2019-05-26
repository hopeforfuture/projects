<?php echo $header; ?>
<div>
	<form method="post" id="formsearch">
	<table align="center">
		<tr>
			<td>
				<input value="<?php echo (isset($keyword) && ($keyword != 'NA')) ? $keyword : ''; ?>" type="text" name="keyword" id="keyword" placeholder="Search">
			</td>
			<td>&nbsp;</td>
			<td>
				<select name="gender" id="gender">
					<option value="">---Select Gender---</option>
					<option <?php echo (isset($gender) && ($gender == 'M')) ? 'selected' : ''; ?> value="M">Male</option>
					<option <?php echo (isset($gender) && ($gender == 'F')) ? 'selected' : ''; ?> value="F">Female</option>
				</select>
			</td>
			<td>&nbsp;</td>
			<td>
				<button type="submit" id="btnSearch">Search</button>
			</td>
			<?php
			if(isset($keyword) || isset($gender))
			{
			?>
				<td>&nbsp;<a href="<?php echo base_url(); ?>"><strong>BACK</strong></a></td>
			<?php
			}
			?>
		</tr>
	</table>
	</form>
</div>
<table border="1" align="center">
	<?php
	if(!empty($this->session->flashdata('msg')))
	{
		echo "<tr><td colspan='6' align='center'><span style='color:red;font-weight:bold;'>".$this->session->flashdata('msg')."</span></td></tr>";
	}
	?>
	<tr>
		<td colspan="6" align="right">
			<?php
			if($show == 'LP')
			{					
			?>
				<a href="<?php echo base_url('user/create') ?>">Create User</a>&nbsp; |
				<a href="<?php echo base_url('user/trash') ?>">View Trash</a>	
			<?php 
			}
			elseif($show == 'TP')
			{
			?>
				<a href="<?php echo base_url('user/list') ?>">View List</a>	
			<?php
			}
			?>
		</td>
	</tr>
	<tr>
		<th style="text-align: center;">Serial NO</th>
		<th style="text-align: center;">Name</th>
		<th style="text-align: center;">Email Address</th>
		<th style="text-align: center;">Contact NO</th>
		<th style="text-align: center;">Profile Picture</th>
		<th style="text-align: center;">Action</th>
	</tr>

	<?php
	$i = 1;
	if(count($users) == 0)
	{
	?>
		<tr><td colspan="6" align="center"><span style="color: red; font-weight: bold;">No record found.</span></td></tr>
	<?php
	}
	else {
		foreach($users as $user)
		{
			$path = (!empty($user->u_image_thumb)) ? base_url()."uploads/thumb/" : base_url()."uploads/misc/";
			$imgsrc = (!empty($user->u_image_thumb)) ? $path.$user->u_image_thumb : $path."default.jpg";
			$width = (empty($user->u_image_thumb)) ? 80 : '';
			$height = (empty($user->u_image_thumb)) ? 60 : '';
		?>
			<tr>
				<td align="center"><?php echo $startindex; ?></td>
				<td align="center"><?php echo ucwords($user->u_name); ?></td>
				<td align="center"><?php echo $user->u_email; ?></td>
				<td align="center"><?php echo $user->u_contact; ?></td>
				<td align="center">
					<img src="<?php echo $imgsrc; ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>"/>
				</td>
				<td align="center">
					<?php
					if($show == 'LP')
					{

					?>
						<a href="<?php echo base_url('user/edit/'.$user->u_id); ?>">[EDIT]</a> | 
						<a onclick="javascript:return confirm('Confirm Delete?');" href="<?php echo base_url('user/remove/'.$user->u_id.'/0'); ?>">[DELETE]</a> |
						<a class="common" id="<?php echo $user->u_id; ?>" href="Javascript: return void(0)">[VIEW]</a>

					<?php 
					}
					else if($show == 'TP')
					{
					?>
						<a onclick="javascript:return confirm('Confirm Restore?');" href="<?php echo base_url('user/restore/'.$user->u_id.'/1'); ?>">[Restore]</a> | 
						<a onclick="javascript:return confirm('Confirm Delete?');" href="<?php echo base_url('user/permanent_del/'.$user->u_id); ?>">[PERMANENT DELETE]</a>
					<?php
					}
					?>

				</td>
			</tr>
		<?php
		$startindex++;
		}
		?>
		
		<tr>
			<td colspan="6" align="right">
				<?php echo (isset($links)) ? $links : 'Total record found : '. count($users); ?>
			</td>
		</tr>

		<?php
	}
	?>

</table>

<div id="uview" align="center" style="display: none;">
	<table cellpadding="1" cellpadding="3">
		<tr>
			<td colspan="3" align="center"><strong>User Detail View</strong></td>
		</tr>
		<tr>
			<td>Name</td>
			<td>:</td>
			<td style="font-weight: bold; " id="tbl_name"></td>
		</tr>
		<tr>
			<td>Email</td>
			<td>:</td>
			<td style="font-weight: bold; " id="tbl_email"></td>
		</tr>
		<tr>
			<td>Address</td>
			<td>:</td>
			<td style="font-weight: bold; " id="tbl_address"></td>
		</tr>
		<tr>
			<td>Contact</td>
			<td>:</td>
			<td style="font-weight: bold; " id="tbl_contact"></td>
		</tr>
		<tr>
			<td valign="top">Profile Image</td>
			<td valign="top">:</td>
			<td valign="top">
				<img id="tbl_img" src="" />
			</td>
		</tr>
		<tr>
			<td>Gender</td>
			<td>:</td>
			<td style="font-weight: bold; " id="tbl_sex"></td>
		</tr>
		<tr>
			<td>Date of birth</td>
			<td>:</td>
			<td style="font-weight: bold; " id="tbl_dob"></td>
		</tr>
		<tr>
			<td>Marriage Status</td>
			<td>:</td>
			<td style="font-weight: bold; " id="tbl_marriage"></td>
		</tr>
		<tr>
			<td>Hobby</td>
			<td>:</td>
			<td style="font-weight: bold; " id="tbl_hobby"></td>
		</tr>
		<tr>
			<td>Food Habit</td>
			<td>:</td>
			<td style="font-weight: bold; " id="tbl_food"></td>
		</tr>
		<tr>
			<td>Smoking Habit</td>
			<td>:</td>
			<td style="font-weight: bold; " id="tbl_smoke"></td>
		</tr>
		<tr>
			<td>Drinking Habit</td>
			<td>:</td>
			<td style="font-weight: bold; " id="tbl_drink"></td>
		</tr>
	</table>
</div>

<?php echo $footer; ?>

<script type="text/javascript">
	$(document).ready(function(){


		$("body").on("click", "#btnSearch", function(e){

			e.preventDefault();
			var keyword = $("#keyword").val().trim();
			var gender = $("#gender").val();
			var errstr = '';
			if((keyword == '') && (gender == ''))
			{
				errstr = 'Please specify any search criteria.';
			}
			else
			{
				errstr = '';
			}
			if(keyword.length == 0)
			{
				keyword = 'NA';
			}
			if(gender.length == 0)
			{
				gender = 'NA';
			}
			if(errstr.length > 0)
			{
				$(".modal-body").html(errstr);
				$("#myModal").modal();
	         	return false;
			}
			var targeturl = '<?php echo base_url(); ?>'+'user/search/'+keyword+'/'+gender;
			document.getElementById("formsearch").action = targeturl;
			document.getElementById("formsearch").submit();

		});




		$("body").on("click", ".common", function(){
			var u_id = $(this).attr('id');
			var target_url = '<?php echo base_url("ajax/userdetail") ?>';

			$.ajax({

				type: "post",
				url: target_url,
				data: {uid: u_id},
				success: function(response){
					$("#uview").show();
					var res = JSON.parse(response);

					$("#tbl_name").html(res.name);
					$("#tbl_email").html(res.email);
					$("#tbl_address").html(res.address);
					$("#tbl_contact").html(res.contact);
					$("#tbl_img").attr('src', res.thumb);
					$("#tbl_sex").html(res.gender);
					$("#tbl_dob").html(res.dob);
					$("#tbl_marriage").html(res.married);
					$("#tbl_hobby").html(res.hobby);
					$("#tbl_food").html(res.food);
					$("#tbl_smoke").html(res.smoking);
					$("#tbl_drink").html(res.drinking);
				}

			});
		});
	});
</script>
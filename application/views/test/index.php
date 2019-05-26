<?php echo $header; ?>
<table>
	<tr>
		<td colspan="2" align="center">
			<button type="button" id="btnShow">Reload</button>	
		</td>
	</tr>
	<tr>
		<th>Serial No</th>
		<th>Fruit Name</th>
	</tr>
	<?php
		$i = 1;
		if(isset($fruits) && count($fruits) > 0)
		{
			foreach($fruits as $fruit)
			{
			?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $fruit; ?></td>
				</tr>
			<?php
				$i++;
			}

			?>
			<tr>
				<td colspan="2" align="center">
					Total record found: <span id="fcount" style="color: red; font-weight: bold;"><?php echo count($fruits); ?></span>

					<!--First key: <span id="fstart" style="color: red; font-weight: bold;"></span>-->
				</td>
			</tr>
			<?php
		}
	?>
</table>
<?php echo $footer; ?>

<script type="text/javascript">
	$(document).ready(function(){
		$("#btnShow").click(function(){

			$.ajax({

				type: "post",
				url: '<?php echo base_url(); ?>'+'test/loaddata',
				success: function(response)
				{
					var res = JSON.parse(response);
					$("#fcount").html(res.count);
					//$("#fstart").html(res.first);
				}

			});

		});
	});
</script>
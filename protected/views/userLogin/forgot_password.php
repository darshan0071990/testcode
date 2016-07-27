<style>
	input[type="text"],input[type="password"]{
		font-size:18px;
		border-color:#BDC7D8;
	}
</style>
<div class="sign_in"> 
	Forgot Password
</div>

		<div class="control-group">
		<input class="span4" type="text" name="username" placeholder="Username" id="username" required>
		</div>
		<div id="status"></div>
		<table style="margin: 0 auto;">
		<tr>
			<td>
				<button id="verify_btn" class="btn btn-success" onclick="submitData()" type="submit">Verify</button>
			</td>
			<td>
				<?php echo CHtml::button('To Login Page', array('onclick' =>'js:document.location.href="login"','class'=>'btn btn_grey')); ?>
			</td>
		</tr>
		</table>
<script>
	function submitData(){
		var username = $("#username").val();
		$.ajax({
			url: '<?php echo Yii::app()->request->baseUrl; ?>/userLogin/forgot_password',
			dataType: 'json',
			data:{'username':username},
			type:'POST',
			beforeSend: function() {
				$("#verify_btn").prop('disabled', true);
			},
			success: function(data){
					$("#verify_btn").prop('disabled', false);
				$("#status").html(data);
			}
		});
	}
	
</script>
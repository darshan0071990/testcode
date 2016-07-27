<style>
	input[type="text"],input[type="password"]{
		font-size:18px;
		border-color:#BDC7D8;
	}
	.form-horizontal .controls {
		margin-left: 0px; !important
	}
</style>
<div class="sign_in"> 
	Reset Password
</div>

		<?php $form = $this->beginWidget(			'bootstrap.widgets.TbActiveForm', array(
			'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
			'id'=>'change-password',
			'enableClientValidation'=>true,
			'clientOptions'=>array(
				'validateOnSubmit'=>true,
			),
			'htmlOptions' => array('class'=>'')
		));
		?>
		
		<?php echo $form->passwordFieldControlGroup($model,'resetPassword',array('label'=>false,'placeholder'=>'Password','class'=>'span4')); ?>
		
		<?php echo $form->passwordFieldControlGroup($model,'resetConfirmPassword',array('label'=>false,'placeholder'=>'Confirm Password','class'=>'span4')); ?>
		
		<input value="<?php echo $user_id; ?>" name="user_id" type="hidden">
		
		<?php
			$flashMessages = Yii::app()->user->getFlashes();
			if ($flashMessages) {
				foreach($flashMessages as $key => $message) {
		?>
					<div class="alert alert-<?php echo $key; ?>">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<?php echo $message; ?>.
					</div>
		<?php
				}
			}
		?>
		<table style="margin: 0 auto;">
		<tr>
			<td>
				<button id="verify_btn" class="btn btn-success" type="submit">Change Password</button>
			</td>
			<td>
				<?php echo CHtml::button('To Login Page', array('onclick' =>'js:document.location.href="login"','class'=>'btn btn_grey')); ?>
			</td>
		</tr>
		</table>
		<?php $this->endWidget(); ?>
<script>
	<?php if($flag) { ?>
	$( document ).ready(function() {
		$("#ResetForm_resetPassword").prop('disabled', true);
		$("#ResetForm_resetConfirmPassword").prop('disabled', true);
		$("#verify_btn").prop('disabled', true);
	});
	<?php } ?>
</script>
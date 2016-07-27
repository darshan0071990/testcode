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
<fieldset>
	<legend>Change Password</legend>

	<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
		'id'=>'change-password',
		'enableClientValidation'=>true,
		'clientOptions'=>array(
			'validateOnSubmit'=>true,
		),
		'htmlOptions' => array('class'=>'')
	));
	?>

		<input type="hidden" value="<?php echo Yii::app()->user->id; ?>" name="User[id]">

		<?php echo $form->passwordFieldControlGroup($model,'resetPassword',array( 'class'=>'span4')); ?>
		<?php echo $form->passwordFieldControlGroup($model,'resetConfirmPassword',array( 'class'=>'span4')); ?>
		
		<?php echo TbHtml::formActions(array(TbHtml::submitButton('Submit', array('color' => TbHtml::BUTTON_COLOR_PRIMARY)))); ?>
		
	<?php $this->endWidget(); ?>
</fieldset>
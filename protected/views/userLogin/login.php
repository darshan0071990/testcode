<style>
	input[type="text"],input[type="password"]{
		font-size:18px;
		border-color:#BDC7D8;
	}
</style>
<div class="sign_in"> 
	Sign In
</div>
<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm',array(
 'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),)); ?>
		<div class="control-group">
		<input class="span4" type="text" name="LoginForm[username]" placeholder="Username" required>
		</div>
		<div class="control-group">
		<input class="span4"type="password" name="LoginForm[password]" placeholder="Password" required>
		</div>
		<?php if(Yii::app()->user->hasFlash('error')){ ?> 
			<div class="alert">
				<strong>Warning!</strong><?php echo " ".Yii::app()->user->getFlash('error'); ?>
			</div>
		<?php } ?>
		<table style="margin: 0 auto;">
		<tr>
		<td><button class="btn btn-success" type="submit">Login</button></td>
		<td><?php echo CHtml::button('Forgot Password', array('onclick' =>'js:document.location.href="forgot_password"','class'=>'btn btn_grey')); ?></td>
		<td><button class="btn btn_grey"  onclick="location.href='<?php echo Yii::app()->createUrl('users/register') ?>'">Register</button></td>
		</tr>
		</table>
	
<?php $this->endWidget(); ?>
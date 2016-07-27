<?php
/* @var $this UsersController */
/* @var $model Users */

$this->breadcrumbs=array(
	'Home'=>array('home/index'),
	'Users'=>array('index'),
	'Import Users',
);
?>

<div id="body_content" class="container">

<div class='panel panel-default'>
	
	<div class='panel-heading'>
		Import Users
	</div>
	
	<div class='panel-body'>
	
	<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	 'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
	 'action'=>Yii::app()->request->baseUrl."/users/import_csv_sql"));?>
		
		<input type='hidden' name='filename' value='<?php echo $filename; ?>' />
		
		<?php echo $form->dropDownListControlGroup($model, 'fname',$csv_heaader); ?>
		
		<?php echo $form->dropDownListControlGroup($model, 'lname',$csv_heaader); ?>
		
		<?php echo $form->dropDownListControlGroup($model, 'email',$csv_heaader); ?>
		
		<?php echo $form->dropDownListControlGroup($model, 'role',CHtml::listData(Yii::app()->authManager->getItemChildren(Yii::app()->user->roles),'name','name')); ?>
		
		<?php 
			$group_id = CHtml::listData(json_decode(Batch::model()->groupName(),true),'id','name');
			
			ksort($group_id);
		?>
		
		<?php echo $form->dropDownListControlGroup($model, 'batch_id',$group_id); ?>
		
		<div class='clear'></div>
			
		<hr>
		
		<div class='form_action'>
			<?php
				echo TbHtml::submitButton('Submit', array('color' => TbHtml::BUTTON_COLOR_PRIMARY));
			?>
		</div>
	
	<?php $this->endWidget(); ?>
 
</div><!-- form -->

</div>
</div>
</div>
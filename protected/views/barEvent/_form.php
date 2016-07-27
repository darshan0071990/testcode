<div id="body_content" class="container">

<div class='panel panel-default'>
	<div class='panel-heading'>
		Bar Event Details
	</div>
	<div class='panel-body'>

    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
	'id'=>'bar-event-form',
	'enableAjaxValidation'=>false,
	'clientOptions'=>array(
				'validateOnSubmit' => true
			),
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),

)); ?>


    <?php echo $form->errorSummary($model); ?>


            <?php echo $form->textFieldControlGroup($model,'event_name',array('span'=>5,'maxlength'=>255)); ?>


            <?php echo $form->textAreaControlGroup($model,'event_discription',array('span'=>5,'row'=>5)); ?>
		
			<hr>
			<?php if(Yii::app()->user->roles === "Admin"){ ?>
				<?php $bar_list = BarDetail::model()->findAll();  ?>
			<?php } else { ?>
			<?php $bar_list = BarDetail::model()->findAllByAttributes(array('created_by'=>Yii::app()->user->id));  ?>
			<?php } ?>
			
			<?php echo $form->dropDownListControlGroup($model,'bar_id',CHtml::listData($bar_list,'id','bar_name'),array('empty' => 'Select Bar','span'=>5)); ?>
			
			<hr>
			
			<div class="control-group">
				<label class="control-label required" for="BarEvent_start_time">Start Date <span class="required">*</span></label>
				<div class="input-append" style="margin-left: 20PX;" >
					<?php
					$this->widget('zii.widgets.jui.CJuiDatePicker', array(
						'model' => $model,
						'attribute' => 'start_date',
						'options'=>array(
							'dateFormat'=>'yy-mm-dd',
						),
					));
					?>
					<?php echo $form->error($model,'start_date'); ?>
				</div>
			</div>
			
			<div class="control-group">
				<label class="control-label required" for="BarEvent_start_time">Start Time <span class="required">*</span></label>

				<div class="input-append" style="margin-left: 20PX;" >
					<?php $this->widget('yiiwheels.widgets.timepicker.WhTimePicker',
						array(
							'model' => $model,
							'attribute' => 'start_time',
						)
					);?>
				</div>
			</div>

			<hr>
			
			<?php echo $form->fileFieldControlGroup($model,'featured_pic',array('span'=>5,'maxlength'=>255)); ?>

			<?php if(!$model->isNewRecord) {
				$folder = Yii::app()->getBaseUrl(true)."/images/barevent/".$model->id."/".$model->featured_pic;
				echo "<div class='controls'><a href='".$folder."' target='__blank'>".$model->featured_pic.'</a></div>';
			} ?>
			<hr>
			
			<?php echo $form->dropDownListControlGroup($model,'status',array('0'=>'Active','1'=>'Inactive'),array('class'=>'input-small')); ?>

			<hr>
     <div class='form_action'>

        <?php echo TbHtml::submitButton($model->isNewRecord ? 'Create' : 'Save',array(

		    'color'=>TbHtml::BUTTON_COLOR_PRIMARY,

		    'size'=>TbHtml::BUTTON_SIZE_LARGE,

		)); ?>
    </div>
	
	<p class="help-block">Fields with <span class="required">*</span> are required.</p>


    <?php $this->endWidget(); ?>


</div><!-- form -->
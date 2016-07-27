<div id="body_content" class="container">

<div class='panel panel-default'>
	<div class='panel-heading'>
		Club Event Details
	</div>
	<div class='panel-body'>
    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'club-event-form',
	'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
		'enableAjaxValidation'=>false,
		'clientOptions'=>array(
			'validateOnSubmit' => true
		),
		'htmlOptions'=>array('enctype'=>'multipart/form-data'),

	)); ?>

    <?php echo $form->errorSummary($model); ?>

            <?php echo $form->textFieldControlGroup($model,'event_name',array('span'=>5,'maxlength'=>100)); ?>

            <?php echo $form->textAreaControlGroup($model,'event_discription',array('span'=>5,'rows'=>5)); ?>
			
			<hr>
        	
			<?php $club_list = ClubDetail::model()->findAllByAttributes(array('created_by'=>Yii::app()->user->id));  ?>
			
			
			<?php echo $form->dropDownListControlGroup($model,'club_id',CHtml::listData($club_list,'id','club_name'),array('empty' => 'Select Club','span'=>5)); ?>

			<hr>
			
            <?php echo $form->textFieldControlGroup($model,'event_fee',array('span'=>3)); ?>

            <?php echo $form->textFieldControlGroup($model,'reservation_fee',array('span'=>3)); ?>

			<hr>
			<div class="control-group">
				<label class="control-label required" for="ClubEvent_start_time">Start Date <span class="required">*</span></label>
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
				<label class="control-label required" for="ClubEvent_start_time">Start Time <span class="required">*</span></label>

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
			
            <?php echo $form->fileFieldControlGroup($model,'image_url',array('span'=>5,'maxlength'=>255)); ?>
			<?php if(!$model->isNewRecord) {
				$folder = Yii::app()->getBaseUrl(true)."/images/clubevent/".$model->id."/".$model->image_url;
				echo "<div class='controls'><a href='".$folder."' target='__blank'>".$model->image_url.'</a></div>';
			} ?>
			<hr>
			
			
			<?php echo $form->dropDownListControlGroup($model,'status',array('0'=>'Active','1'=>'Inactive'),array('class'=>'input-small')); ?>
			<hr>
        <div class="form-action">
        <?php echo TbHtml::submitButton($model->isNewRecord ? 'Create' : 'Save',array(
		    'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
		    'size'=>TbHtml::BUTTON_SIZE_LARGE,
		)); ?>
    </div>

    <?php $this->endWidget(); ?>
<p class="help-block">Fields with <span class="required">*</span> are required.</p>
</div><!-- form -->
</div><!-- form -->
</div><!-- form -->
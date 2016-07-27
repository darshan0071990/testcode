<div id="body_content" class="container">

<div class='panel panel-default'>
	<div class='panel-heading'>
		Club Details
	</div>
	<div class='panel-body'>
    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
	'id'=>'club-detail-form',
		'enableAjaxValidation'=>false,
		'clientOptions'=>array(
			'validateOnSubmit' => true
		),
		'htmlOptions'=>array('enctype'=>'multipart/form-data'),
	)); ?>
    

    <?php echo $form->errorSummary($model); ?>

            <?php echo $form->textFieldControlGroup($model,'club_name',array('span'=>5,'maxlength'=>255)); ?>

			<hr>
			
			<div class="control-group">
				<div class="controls">
					<button class="btn btn-success" type="button" onclick="lookupGeoData();">Map my Address</button>
					</input>
				</div>
			</div>
			
            <?php echo $form->textFieldControlGroup($model,'address',array('span'=>5,'maxlength'=>255)); ?>

            <?php echo $form->textFieldControlGroup($model,'city',array('span'=>5,'maxlength'=>100)); ?>
			
		<?php echo $form->textFieldControlGroup($model,'country',array('span'=>5,'maxlength'=>100)); ?>
		
		<?php echo $form->textFieldControlGroup($model,'zip_code',array('span'=>5)); ?>
	
		 <?php echo $form->hiddenField($model,'latitude',array('span'=>5,'maxlength'=>255)); ?>

            <?php echo $form->hiddenField($model,'longtitude',array('span'=>5,'maxlength'=>255)); ?>
			
			<hr>
			
            <?php echo $form->textFieldControlGroup($model,'phone_no',array('span'=>5,'maxlength'=>15)); ?>

            <?php echo $form->textFieldControlGroup($model,'mobile_no',array('span'=>5,'maxlength'=>10)); ?>

			<hr>
			
            <?php echo $form->fileFieldControlGroup($model,'featured_pic',array('span'=>5,'maxlength'=>255)); ?>

			<?php if(!$model->isNewRecord) {
				$folder = Yii::app()->getBaseUrl(true)."/images/club/".$model->id."/".$model->featured_pic;
				echo "<div class='controls'><a href='".$folder."' target='__blank'>".$model->featured_pic.'</a></div>';
			} ?>

        <div class='form_action'>
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

<script src="http://api.mygeoposition.com/api/geopicker/api.js" type="text/javascript"></script>
<script>
function lookupGeoData() {            
	myGeoPositionGeoPicker({
		startAddress     : 'White House, Washington',
		returnFieldMap   : {
			'ClubDetail_latitude' : '<LAT>',
			'ClubDetail_longtitude' : '<LNG>',
			'ClubDetail_city' : '<CITY>',   /* ...or <COUNTRY>, <STATE>, <DISTRICT>,
												  <CITY>, <SUBURB>, <ZIP>, <STREET>, <STREETNUMBER> */
			'ClubDetail_address' : '<ADDRESS>' ,
			'ClubDetail_country' : '<COUNTRY>',
			'ClubDetail_zip_code' : '<ZIP>',
		  }
	});
}
</script>
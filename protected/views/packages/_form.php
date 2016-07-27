<div id="body_content" class="container">

    <div class='panel panel-default'>
        <div class='panel-heading'>
            Packages
        </div>
        <div class='panel-body'>

            <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
	'id'=>'packages-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
                'enableAjaxValidation'=>false,
                'clientOptions'=>array(
                    'validateOnSubmit' => true
                ),
                )); ?>

            <?php echo $form->textFieldControlGroup($model,'package_name',array('span'=>3,'maxlength'=>255)); ?>

            <?php echo $form->textAreaControlGroup($model,'features',array('rows'=>6, 'cols'=>50)); ?>


            <?php echo $form->textFieldControlGroup($model,'no_of_events',array('span'=>2)); ?>

            <?php echo $form->textFieldControlGroup($model,'amount',array('span'=>2)); ?>

            <?php echo $form->dropDownListControlGroup($model, 'duration',
                array('1'=>'1 Month','2'=>'2 Months','3'=>'3 Months','4'=>'4 Months','5'=>'5 Months','6'=>'6 Months', '7'=>'7 Months', '8'=>'8 Months', '9'=>'9 Months','10'=>'10 Months','11'=>'11 Months','12'=>'1 Year'), array('empty' => '-- Duration --')); ?>


            <?php echo $form->dropDownListControlGroup($model, 'type',
                array('Club'=>'Club','Bar'=>'Bar'), array('empty' => '-- Subscription Type --')); ?>
            <?php echo $form->textFieldControlGroup($model,'promo_code',array('span'=>2,'maxlength'=>10)); ?>

            <?php echo $form->textFieldControlGroup($model,'discounted_amount',array('span'=>2 )); ?>

            <div class='form_action'>

                <?php echo TbHtml::submitButton($model->isNewRecord ? 'Create' : 'Save',array(

                    'color'=>TbHtml::BUTTON_COLOR_PRIMARY,

                    'size'=>TbHtml::BUTTON_SIZE_LARGE,

                )); ?>
            </div>

            <p class="help-block">Fields with <span class="required">*</span> are required.</p>


            <?php $this->endWidget(); ?>

</div><!-- form -->
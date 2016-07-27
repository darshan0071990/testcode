<?php
$this->breadcrumbs=array(
    'Home'=>array('home/index'),
    'Bar Events'=>array('index'),
    'Bar Event Images',
);

?>

<div id="body_content" class="container">

    <div class='panel panel-default'>
        <div class='panel-heading'>
            Bar Event Images
        </div>
        <div class='panel-body'>

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
    'id'=>'bar-event-album-form',
 'enableAjaxValidation'=>false,
    'clientOptions'=>array(
        'validateOnSubmit' => true
    ),
    'htmlOptions'=>array('enctype'=>'multipart/form-data'),

)); ?>
            <?php // echo $form->fileFieldControlGroup($model,'img_url',array('span'=>5)); ?>
			<?php echo $form->error($model,'img_url'); ?>
			<?php
			  $this->widget('CMultiFileUpload', array(
				'model'=>$model,
				'attribute'=>'img_url',
				'accept'=>'jpg|gif|jpeg|png',
				'denied'=>'File is not allowed',
				'max'=>100, // max 10 files
				 'duplicate' => 'Duplicate file!',
                  'htmlOptions' => array('multiple' => 'multiple'),
				));
			?>

            <?php echo $form->hiddenField($model,'bar_event_id',array('value'=>$id)); ?>

<hr>
<div class='form_action'>

    <?php echo TbHtml::submitButton($model->isNewRecord ? 'Upload' : 'Save',array(

        'color'=>TbHtml::BUTTON_COLOR_PRIMARY,

        'size'=>TbHtml::BUTTON_SIZE_LARGE,

    )); ?>
</div>
<?php $this->endWidget(); ?>
            <?php
            $flashMessages = Yii::app()->user->getFlashes();
            if ($flashMessages) {
                echo '<ul class="flashes">';
                foreach($flashMessages as $key => $message) {
                    echo '<li><div class="flash-' . $key . '">' . $message . "</div></li>\n";
                }
                echo '</ul>';
            }
            ?>
        </div>
    </div>
</div>
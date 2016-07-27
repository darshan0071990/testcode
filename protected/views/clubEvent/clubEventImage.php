<?php
$this->breadcrumbs=array(
    'Home'=>array('home/index'),
    'Club Events'=>array('index'),
    'Club Event Images',
);

?>

<div id="body_content" class="container">

    <div class='panel panel-default'>
        <div class='panel-heading'>
Club Event Images
</div>
        <div class='panel-body'>

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
    'id'=>'club-event-album-form',

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
<?php // echo $form->fileFieldControlGroup($model,'image_url',array('span'=>5)); ?>
		<?php echo $form->error($model,'img_url'); ?>
		<?php
		  $this->widget('CMultiFileUpload', array(
			'model'=>$model,
			'attribute'=>'image_url',
			'accept'=>'jpg|gif|jpeg',
			'denied'=>'File is not allowed',
			'max'=>10, // max 10 files
			'duplicate' => 'Duplicate file!',
			));
		?>

<?php echo $form->hiddenField($model,'club_event_id',array('value'=>$id)); ?>

<hr>
<div class='form_action'>

    <?php echo TbHtml::submitButton($model->isNewRecord ? 'Submit' : 'Save',array(

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
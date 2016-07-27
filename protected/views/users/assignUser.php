<div class='ajax_nav_container'>
<?php echo TbHtml::button($modern_btn_title, array(
	'style' => TbHtml::BUTTON_COLOR_PRIMARY,
	//'size' => TbHtml::BUTTON_SIZE_LARGE,
	'data-toggle' => 'modal',
	'data-target' => '#'.$controller.'_target_model',
)); ?>

<div id='<?php echo $id ?>'>
<?php 	
	$this->widget('yiiwheels.widgets.grid.WhGridView', array(
	'fixedHeader' => true,
	'headerOffset' => 40,
	'type'=>'striped bordered',
	'dataProvider' => $dataProvider,
	'responsiveTable' => true,
	'trClickable' => true,
	'trClickUrl' => $trClickUrl,
	'template' => "{items}{pager}{summary}",
	'columns' => array(
			array(
				'name'=>'Name',
				'value'=>'$data->name',
				'htmlOptions'=>array('style'=>'word-wrap:break-word')
			),
			array(
				'class'=>'TbButtonColumn',
				'template'=>'{delete}',
				'buttons'=>array(
					'delete' => array(
						'url'=>$trDeleteUrl,
						'icon'=>'fa fa-times',
						'appendIcon'=>'',
					)
				)
			)
		),
	)); 
?>
</div>
<?php $form = $this->beginWidget('CActiveForm', array(
    'id'=>$controller.'_target_model_form',
    'enableAjaxValidation'=>true,
    'enableClientValidation'=>true,
	'action' => $submit_url
)); ?>
	<input type='hidden' name='id' value='<?php echo $id; ?>' />
	<?php $this->widget('bootstrap.widgets.TbModal', array(
		'id' => $controller.'_target_model',
		'header' => $modern_btn_title,
		'content' => $this->renderPartial('/course/_add_users_course',array('url'=>$autoSuggest['url'],'model'=>$autoSuggest['model'],'result'=>$autoSuggest['result']),true),
		'footer' => array(
			TbHtml::button('Close', array('data-dismiss' => 'modal')),
			TbHtml::submitButton('Submit', array('color' => TbHtml::BUTTON_COLOR_PRIMARY))
		 ),
	)); ?>
<?php $this->endWidget(); ?>

<script>
$('#<?php echo $controller; ?>_target_model_form').submit(function(e){
	e.preventDefault();
	var clicked = $(this);
	$.ajax({
		url:clicked.attr('action'),
		data:clicked.serialize(),
		success:function(data){
			$('#<?php echo $controller ?>_target_model').modal('hide');
			clicked.find('#assign_user').find('.select2-choices li.select2-search-choice').remove();
			clicked.find('#assign_user').find('.select2-offscreen').val('');
			$.ajax({
				url: window.location.href,
				type: 'get',
				success:function(data){
					$('#<?php echo $id ?> table.items').html($("#<?php echo $id ?> table.items", data).html());
				}
			});
		},
	});
});
</script>
</div>
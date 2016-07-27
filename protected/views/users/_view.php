<style>
	.menu_item_container .table th {
		font-weight:normal;
		width:20%;
	}
</style>

<div class="menu_item_container">
	<?php
	$this->widget('yiiwheels.widgets.detail.WhDetailView', array(
		'data'       => $model,
		'htmlOptions' => array('class'=>'table table-condensed table-bordered'),
		'attributes' => array(
			'title',
			
			array( //edit related record
				'name' => 'fname',
				'value'=>$model->fname,
				'htmlOptions'=>array('word-wrap'=>'break-word'),
			),
		
			'lname',
			array( //edit related record
				'name' => 'email',
				'value'=>$model->email,
				'htmlOptions'=>array('style' => 'word-wrap:break-word;'),
			),
			'mobile',
			'address',
			'city',
			'zip',
			'country',
		)
	));
	?>
</div>
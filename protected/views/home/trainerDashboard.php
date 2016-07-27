<style>
	.table{
		margin-bottom:0px;
	}
</style>
<div class='span5 well'>
	<h2> <a href='<?php echo Yii::app()->controller->createUrl("course/index") ?>'> Course : (<?php echo $data['course_count']; ?>) </a> </h2>
	
	<?php 	
		$this->widget('yiiwheels.widgets.grid.WhGridView', array(
		'fixedHeader' => true,
		'headerOffset' => 40,
		'type'=>'condensed',
		'dataProvider' => $data['course'],
		'responsiveTable' => true,
		'trClickable' => true,
		'trClickUrl' => 'Yii::app()->controller->createUrl("course/$data->id")',
		'template' => "{items}",
		'columns' => array(
				array(
					'header'=>false,
					'name'=>'Course',
					'value' =>  '$data->name',
					'htmlOptions'=>array('style'=>'word-wrap:break-word')
				),
			),
		)); 
	?>
</div>
<div class='span5 well'>
	<h2> <a href='<?php echo Yii::app()->controller->createUrl("users/index") ?>'> User : (<?php echo $data['user_count']; ?>) </a> </h2>
	
	<?php 	
		$this->widget('yiiwheels.widgets.grid.WhGridView', array(
		'fixedHeader' => true,
		'headerOffset' => 40,
		'type'=>'condensed',
		'dataProvider' => $data['user'],
		'responsiveTable' => true,
		'trClickable' => true,
		'trClickUrl' => 'Yii::app()->controller->createUrl("users/$data->id")',
		'template' => "{items}",
		'columns' => array(
				array(
					'header'=>false,
					'name'=>'Course',
					'value' =>  '$data->name',
					'htmlOptions'=>array('style'=>'word-wrap:break-word')
				),
			),
		)); 
	?>
</div>
<div class='span5 well'>
	<h2> <a href='<?php echo Yii::app()->controller->createUrl("announcement/index") ?>'> Announcement : (<?php echo $data['annoucement_count']; ?>) </a></h2>
	<?php 	
		$this->widget('yiiwheels.widgets.grid.WhGridView', array(
		'fixedHeader' => true,
		'headerOffset' => 40,
		'type'=>'condensed',
		'dataProvider' => $data['annoucement'],
		'responsiveTable' => true,
		'trClickable' => true,
		'trClickUrl' => 'Yii::app()->controller->createUrl("announcement/view/$data->id")',
		'template' => "{items}",
		'columns' => array(
				array(
					'header'=>false,
					'name'=>'Course',
					'value' => function($data,$row){
						return $data->subject;
					},
					'type'=>'raw',
					'htmlOptions'=>array('style'=>'word-wrap:break-word')
				),
			),
		)); 
	?>
</div>
<div class='span5 well'>
	<h2> <a href='<?php echo Yii::app()->controller->createUrl("messages/messages_view") ?>'> Message </a></h2>
	<?php 	
		$this->widget('yiiwheels.widgets.grid.WhGridView', array(
		'fixedHeader' => true,
		'headerOffset' => 40,
		'type'=>'condensed',
		'dataProvider' => $data['messages'],
		'responsiveTable' => true,
		'trClickable' => true,
		'trClickUrl' => 'Yii::app()->controller->createUrl("messages/messages_view/$data->id")',
		'template' => "{items}",
		'columns' => array(
				array(
					'header'=>false,
					'name'=>'Course',
					'value' => function($data,$row){
						return $data->subject;
					},
					'type'=>'raw',
					'htmlOptions'=>array('style'=>'word-wrap:break-word')
				),
			),
		)); 
	?>
</div>
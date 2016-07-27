<style>
	.table{
		margin-bottom:0px;
	}
</style>
<?php
	$this->breadcrumbs=array(
		'Dashboard',
	);
?>
<div class='span6'>
	<div class='span6' style='margin-left:0'>
		<div class="panel panel-default">
			<!-- Default panel contents -->
			<div class="panel-heading">
				 <a href='<?php echo Yii::app()->controller->createUrl("users/index") ?>'> Users: (<?php echo $data['user_count']; ?>) </a>
			</div>
	
			<?php 	
				$this->widget('yiiwheels.widgets.grid.WhGridView', array(
				'fixedHeader' => true,
				'headerOffset' => 40,
				'type'=>'condensed',
				'dataProvider' => $data['user'],
				'responsiveTable' => false,
				'trClickable' => true,
				'trClickUrl' => 'Yii::app()->controller->createUrl("users/$data->id")',
				'template' => "{items}",
				'columns' => array(
						array(
							'header'=>'Name',
							'headerHtmlOptions' => array('style' => 'display:none'),
							'name'=>'Course',
							'value' =>  '$data->name',
							'htmlOptions'=>array('style'=>'word-wrap:break-word')
						),
					),
				)); 
			?>
		</div>
	</div>
</div>
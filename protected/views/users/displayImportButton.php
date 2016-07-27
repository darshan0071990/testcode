<?php
/* @var $this UsersController */
/* @var $model Users */

$this->breadcrumbs=array(
	'Home'=>array('home/index'),
	'Users'=>array('index'),
	'Import Users',
);
?>
<div id="body_content" class="container">

<div class='panel panel-default'>
	<div class='panel-heading'>
		Import User
	</div>
	<div class='panel-body'>

	<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	 'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,'action'=>Yii::app()->request->baseUrl."/users/import",'htmlOptions'=>array('enctype'=>"multipart/form-data"))); ?>
	 
		<?php echo $form->fileFieldControlGroup($model, 'file',array('class'=>'span4')); ?>
		
		<div class='clear'></div>
			
		<hr>
				
		<div class='form_action'>
			<?php
				echo TbHtml::submitButton('Submit', array('color' => TbHtml::BUTTON_COLOR_PRIMARY));
			?>
		</div>
		
	<?php $this->endWidget(); ?> 
	
	<?php if(isset($error_list) && !empty($error_list)){ 
		$dataProvider = new CArrayDataProvider($error_list);
		$this->widget('yiiwheels.widgets.grid.WhGridView', array(
			'fixedHeader' => true,
			'headerOffset' => 40,
			'type'=>'striped bordered',
			'dataProvider' => new CArrayDataProvider($error_list),
			'responsiveTable' => true,
			'template' => "{items}{pager}{summary}",
			'columns' => array(
				array(
					'name' => 'Sr No.',
					'value'=>'$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',
				),
				array(
					'name' => 'First Name',
					'value'=>'$data->fname'
				),
				array(
					'name' => 'Last Name',
					'value'=>'$data->lname'
				),
				array(
					'name' => 'Email Name',
					'value'=>'$data->email'
				),
			),
		)); 		
	} ?>
</div>
</div>
</div>
</div>
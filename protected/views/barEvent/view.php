<?php
/* @var $this BarEventController */
/* @var $model BarEvent */
?>

<?php
$this->breadcrumbs=array(
	'Home'=>array('home/index'),
	'Bar Events'=>array('index'),
	$model->event_name,
);

?>

<div id="body_content" class="container">


<div class='panel panel-default'>
	<div class='panel-heading'>
		Bar Event Details
	</div>
	<div class='panel-body'>
	
<?php $this->widget('zii.widgets.CDetailView',array(
    'htmlOptions' => array(
        'class' => 'table table-striped table-condensed table-hover',
    ),
    'data'=>$model,
    'attributes'=>array(
		'event_name',
		'event_discription',
		array(
			'label'=>'Bar',
			'value'=>$model->bar->bar_name,
		),
		array(
			'label'=>'Start Date',
			'value'=>$model->start_date,
		),
		array(
			'label'=>'Start Time',
			'value'=>$model->start_time,
		),
		array(
			'label'=>'Created On',
			'value'=>date("d F Y H:i:s",$model->created_date),
		),
		
		'featured_pic',
		array(
			'label'=>'Status',
			'value'=>($model->status=="0")?("Active"):("Suspended"),
		),
	),
)); ?>
</div>
    <a class="btn btn-primary pull-right" href=<?php echo Yii::app()->createUrl('barEvent/barEventImage', array('id'=>$model->id));?>>Add Images</a>
</div>

    <?php
    $this->widget('yiiwheels.widgets.grid.WhGridView', array(
        'fixedHeader' => true,
        'headerOffset' => 40,
        'type'=>'bordered',
        'dataProvider' => $data,
        'trClickable'=>false,
        'responsiveTable' => true,
        'template' => "{items}{pager}{summary}",
        'pagerCssClass' => 'pagination-right',
        'columns' => array(
            array(
                'name' => 'Sr. No',
                'value'=>'$row+1',
                'htmlOptions'=>array('style'=>'word-wrap:break-word')
            ),
            array(
                'header' => 'img_url',
                'value' => 'CHtml::link($data->img_url, Yii::app()->getBaseUrl(true)."/images/barevent/".$data->bar_event_id."/EventAlbum/".$data->img_url ,array("target"=>"_blank"))',
                'type'  => 'raw',
                'htmlOptions'=>array('style'=>'word-wrap:break-word')
            ),


            array(
                'header'=>'Action',
                'class'=>'TbButtonColumn',
                'template'=>'{view}{update}{delete}',
                'htmlOptions'=>array('style'=>'font-size:18px;'),
                'buttons'=>array (

                    'delete'=> array(
                        'icon'=>'fa fa-trash-o',
                        'appendIcon'=>'',
                        'url'=>'$this->grid->controller->createUrl("/barEvent/AlbumDelete", array("id"=>$data->id))',
                    ),

                ),
            ),
        ),
    )); ?>
</div>
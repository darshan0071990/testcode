<?php
/* @var $this PackagesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
    'Home'=>array('home/index'),
    'Buy Packages',
);
?>

<div id='body_content' class='container'>
    <?php
    $this->widget('yiiwheels.widgets.grid.WhGridView', array(
        'fixedHeader' => true,
        'headerOffset' => 40,
        'type'=>'bordered',
        'dataProvider' => $dataProvider,
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
            'package_name',
            'features',
            'no_of_events',
            array(
                'name' => 'amount',
                'header' => 'Subscription Cost',
                'value'=>'$data->amount',
                'htmlOptions'=>array('style'=>'word-wrap:break-word;width:30%;')
            ),
            array(
                'name' => 'duration',
                'header' => 'Subscription Duration',
                'value'=>'$data->duration',
                'value'=>function($data,$row){
                        return $data->duration." Months";
                },
                'htmlOptions'=>array('style'=>'word-wrap:break-word;width:30%;')
            ),
            array
            (
                'class'=>'CButtonColumn',
                'template'=>'{buy}',
                'buttons'=>array
                (
                    'buy' => array
                    (
                        'label'=>'Buy this Subscription',
                        'url'=>'Yii::app()->createUrl("packages/checkout", array("id"=>$data->id))',
                    ),
                ),
            ),
        ),
    )); ?>
</div>


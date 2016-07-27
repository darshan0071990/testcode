<?php
/* @var $this PackagesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
    'Home'=>array('home/index'),
    'Packages',
);

?>

<div id='body_content' class='container'>
    <?php
    $this->widget('yiiwheels.widgets.grid.WhGridView', array(
        'fixedHeader' => true,
        'headerOffset' => 40,
        'type'=>'bordered',
        'dataProvider' => $dataProvider,
        'trClickable'=>true,
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
            'amount',
            'duration',
            'promo_code',
            array(
                'header'=>'Action',
                'class'=>'TbButtonColumn',
                'template'=>'{view}{update}{delete}',
                'htmlOptions'=>array('style'=>'font-size:18px;'),
                'buttons'=>array (
                    'view'=> array(
                        'icon'=>'fa fa-info-circle',
                        'appendIcon'=>'',
                        'url'=>'$this->grid->controller->createUrl("/packagesEvent/update", array("id"=>$data->id))',
                    ),

                    'delete'=> array(
                        'icon'=>'fa fa-trash-o',
                        'appendIcon'=>'',
                    ),

                ),
            ),
        ),
    )); ?>
</div>

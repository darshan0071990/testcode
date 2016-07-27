<?php
/* @var $this ClubDetailController */
/* @var $data ClubDetail */
?>

<div class="view">

    	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('club_name')); ?>:</b>
	<?php echo CHtml::encode($data->club_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('address_line_1')); ?>:</b>
	<?php echo CHtml::encode($data->address_line_1); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('city_id')); ?>:</b>
	<?php echo CHtml::encode($data->city_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('latitude')); ?>:</b>
	<?php echo CHtml::encode($data->latitude); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('longtitude')); ?>:</b>
	<?php echo CHtml::encode($data->longtitude); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('phone_no')); ?>:</b>
	<?php echo CHtml::encode($data->phone_no); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('club_featured_pic')); ?>:</b>
	<?php echo CHtml::encode($data->club_featured_pic); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('mobile')); ?>:</b>
	<?php echo CHtml::encode($data->mobile); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('featured_pic')); ?>:</b>
	<?php echo CHtml::encode($data->featured_pic); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created_by')); ?>:</b>
	<?php echo CHtml::encode($data->created_by); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created_date')); ?>:</b>
	<?php echo CHtml::encode($data->created_date); ?>
	<br />

	*/ ?>

</div>
<?php
/* @var $this ClubEventController */
/* @var $data ClubEvent */
?>

<div class="view">

    	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('event_name')); ?>:</b>
	<?php echo CHtml::encode($data->event_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('event_discription')); ?>:</b>
	<?php echo CHtml::encode($data->event_discription); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('club_id')); ?>:</b>
	<?php echo CHtml::encode($data->club_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('city_id')); ?>:</b>
	<?php echo CHtml::encode($data->city_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('event_fee')); ?>:</b>
	<?php echo CHtml::encode($data->event_fee); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('reservation_fee')); ?>:</b>
	<?php echo CHtml::encode($data->reservation_fee); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('image_url')); ?>:</b>
	<?php echo CHtml::encode($data->image_url); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created_date')); ?>:</b>
	<?php echo CHtml::encode($data->created_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('start_date')); ?>:</b>
	<?php echo CHtml::encode($data->start_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('end_date')); ?>:</b>
	<?php echo CHtml::encode($data->end_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	*/ ?>

</div>
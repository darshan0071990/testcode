<?php
/* @var $this PackagesController */
/* @var $data Packages */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('package_name')); ?>:</b>
	<?php echo CHtml::encode($data->package_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('features')); ?>:</b>
	<?php echo CHtml::encode($data->features); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('no_of_events')); ?>:</b>
	<?php echo CHtml::encode($data->no_of_events); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('amount')); ?>:</b>
	<?php echo CHtml::encode($data->amount); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('duration')); ?>:</b>
	<?php echo CHtml::encode($data->duration); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('promo_code')); ?>:</b>
	<?php echo CHtml::encode($data->promo_code); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('discounted_amount')); ?>:</b>
	<?php echo CHtml::encode($data->discounted_amount); ?>
	<br />

	*/ ?>

</div>
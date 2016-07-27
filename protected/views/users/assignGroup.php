<div class='ajax_nav_container'>
<?php
	echo $this->renderPartial('/users/assignUser',array(
		'id'=>$id,
		'dataProvider'=>$userAssign,
		'controller'=>'groups',
		'submit_url'=>Yii::app()->controller->createUrl('/groups/userAssign'),
		'modern_btn_title'=>'Add User',
		'trClickUrl' => 'Yii::app()->controller->createUrl("users/view/$data->id")',
		'trDeleteUrl' => 'Yii::app()->controller->createUrl( "/groups/deleteUserAssign?uid=$data->id&id='.$id.'" )',
		'autoSuggest'=>array(
			'url' =>Yii::app()->controller->createUrl('users/autocomplete'),
			'model' => $model_user,
			'result' => 'data.fname+" "+data.lname'
		)
	),true);
?>
</div>
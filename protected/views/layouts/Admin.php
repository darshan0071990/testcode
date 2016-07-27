<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<meta name="viewport" content="width=device-width, initial-scale=1.0">
            <!-- blueprint CSS framework -->

	<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/theme_main.css" rel="stylesheet">

	<?php Yii::app()->bootstrap->register(); ?>


	<title><?php echo CHtml::encode($this->pageTitle); ?></title>

	<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap-3-3.css" rel="stylesheet">

		<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/theme_light.css" rel="stylesheet">

			<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/font-awesome.min.css" rel="stylesheet">

				</head>
				<body>
					<!-- Header Navigation Bar -->
					<div class='top_menu'>
						<?php
						$user = $user = Yii::app()->user->getState('user');
						$this->widget('bootstrap.widgets.TbNavbar', array(
							'display' => TbHtml::NAVBAR_DISPLAY_FIXEDTOP,
							'brandLabel' => 'Whats42nite',
							'brandUrl' => $this->createUrl('/home/index'),
							'collapse' => true,
							'items' => array(
								array(
									'class' => 'bootstrap.widgets.TbNav',
									'htmlOptions' => array('class' => 'pull-right'),
									'items' => array(
										array('label' => 'Home', 'url' => array('/home/index')),
										array('label' => 'Bar', 'items' => array(
												array('label' => 'View Bar Details', 'url' => array('/barDetail/index')),
												array('label' => 'View Bar Events', 'url' => array('/barEvent/index')),
												array('label' => 'Add Bar', 'url' => array('/barDetail/create')),
												array('label' => 'Add Bar Events', 'url' => array('/barEvent/create')),
											),
										),
										array('label' => 'Club', 'items' => array(
												array('label' => 'View Club Details', 'url' => array('/clubDetail/index')),
												array('label' => 'View Club Events', 'url' => array('/clubEvent/index')),
												array('label' => 'Add Club', 'url' => array('/clubDetail/create')),
												array('label' => 'Add Club Events', 'url' => array('/clubEvent/create')),
											),
										),
										array('label' => 'Users', 'items' => array(
												array('label' => 'View Users', 'url' => array('/users/index')),
												array('label' => 'View BarOwners', 'url' => array('/users/viewbar')),
												array('label' => 'View ClubOwners', 'url' => array('/users/viewclub')),
												array('label' => 'Owners Registration', 'url' => array('/users/ownersReg')),
											),
										),
										array('label' => 'Packages', 'items' => array(
												array('label' => 'View Packages', 'url' => array('/packages/index')),
												array('label' => 'Add Packages', 'url' => array('/packages/create')),
											),
										),
										array('label' => 'Reports', 'items' => array(
												array('label' => 'Events Report', 'url' => array('/report/index')),
												array('label' => 'Owners Subscription Report', 'url' => array('/report/owners')),
											),
										),
										array('label' => $user['display_name'], 'items' => array(
												array('label' => 'Profile', 'url' => array('/users/update/' . $user['user_id'])),
												array('label' => 'Change Password', 'url' => array('/users/change_password')),
												TbHtml::menuDivider(),
												array('label' => 'Logout', 'url' => array('/userLogin/logout')),
											),
											'htmlOptions' => array('class' => 'nav_user_dropdown'),
										),
									),
								),
							),
						));
						?>
					</div>
					<div class="main_wrapper">
						<div class='nav-breadcrumbs'>
							<?php echo TbHtml::breadcrumbs($this->breadcrumbs); ?>
						</div>
						<?php echo $content; ?>
					</div>

				</body>
</html>
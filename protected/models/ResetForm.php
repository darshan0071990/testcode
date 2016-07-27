<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class ResetForm extends CFormModel
{
	public $resetPassword;
	public $resetConfirmPassword;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			array('resetConfirmPassword,resetPassword', 'required'),
			array('resetConfirmPassword', 'compare', 'compareAttribute' => 'resetPassword','message'=>'Password doesn\'t match'),
		);
	}
	
	public function attributeLabels(){
		return array(
			'resetPassword' => 'Password',
			'resetConfirmPassword' => 'Confirm Password'
		);
	}
}
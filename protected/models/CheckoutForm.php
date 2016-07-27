<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class CheckoutForm extends CFormModel
{
    public $full_name;
    public $type;
    public $credit_card;
    public $month;
    public $year;
    public $amount;
    public $promo_code;
    public $cvv;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return array(
            array('full_name,cvv,credit_card,month,year,amount', 'required'),

        );
    }

    public function attributeLabels(){
        return array(
            'full_name' => 'Full Name',
            'credit_card' => 'Credit Card',
            'month' => 'Month',
            'year' => 'Year',
            'amount' => 'Amount',
        );
    }
}
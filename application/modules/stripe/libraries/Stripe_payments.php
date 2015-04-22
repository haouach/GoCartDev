<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class stripe_payments
{
    var $method_name = 'Credit Card (by stripe)';
    
    /*
    checkout_form()
    this public function returns an array, the first part being the name of the payment type
    that will show up beside the radio button the next value will be the actual form if there is no form, then it should equal false
    there is also the posibility that this payment method is not approved for this purchase. in that case, it should return a blank array 
    */
    
    //these are the front end form and check public functions
    public function checkout_form($post = false)
    {
        $settings   = \CI::Settings()->get_settings('stripe');
        $enabled    = $settings['enabled'];
        
        $form           = [];
        
        if($enabled == 1)
        {
            $form['name']   = $this->method_name;
            $form['form']   = $this->partial('front_end_form', array('stripe'=>$settings), true);
            
            return $form;
        }
        else
        {
            return [];
        }
    }
    public function checkout_check()
    {

        $token  = $_POST['stripeToken'];
        $error  = false;
        if(empty($token))
        {
            $error = 'There was an error processing your payment.';
        }

        if($error)
        {
            return $error;
        }
        else
        {
            CI::Session()->set_userdata('stripe_token', $token);
            return false;
        }
        
    }
    
    public function description()
    {
        //create a description from the session which we can store in the database
        //this will be added to the database upon order confirmation
        
        /*
        access the payment information with the  $_POST variable since this is called
        from the same place as the checkout_check above.
        */
        
        return 'Credit Card';
        
        /*
        for a credit card, this may look something like
        
        $cart['payment']['description'] = 'Card Type: Visa
        Name on Card: John Doe<br/>
        Card Number: XXXX-XXXX-XXXX-9976<br/>
        Expires: 10/12<br/>';
        */  
    }
    
    //back end installation public functions
    public function install()
    {
        $config = [];
        
        //default mode
        $config['mode']                 = 'test';
        
        //secret and publishable keys for test mode
        $config['test_secret_key']      = '';
        $config['test_publishable_key'] = '';
        
        //secret and publishable keys for live mode
        $config['live_secret_key']      = '';
        $config['live_publishable_key'] = '';
        
        //by default this method is disabled.
        $config['enabled']              = 0;
        
        //set a default blank setting for flatrate shipping
        \CI::Settings()->save_settings('stripe', $config);
    }
    
    public function uninstall()
    {
        \CI::Settings()->delete_settings('stripe');
    }
    
    //payment processor
    public function process_payment()
    {
        require('stripe_lib.php');
        
        $token      = CI::Session()->userdata('stripe_token');
        $settings   = \CI::Settings()->get_settings('stripe');
        if($settings['mode'] == 'test')
        {
            $key    = $settings['test_secret_key'];
        }
        else
        {
            $key    = $settings['live_secret_key'];
        }
        $amount     = floatval(\GC::total())*100;
        
        $customer   = \CI::Login()->customer();
        
        $description    = $customer['firstname'].' '.$customer['lastname'].' '.$customer['email'];
        
        Stripe::setApiKey($key);
        
        try {
            $charge = Stripe_Charge::create(array("amount"  => $amount
                                        ,"currency"         => 'usd'
                                        ,"card"             => $token
                                        ,'description'      => $description));
            //we win!
            CI::Session()->set_userdata('stripe_id', $charge->id);
            return false;
        }
        catch (Exception $e) {
            return $e->getMessage();
        }
    }
    
    //admin end form and check public functions
    public function form($post = false)
    {
        if(!$post)
        {
            $settings   = \CI::Settings()->get_settings('stripe');
        }
        else
        {
            $settings   = $post;
        }
        
        return $this->view('stripe_admin_form', array('settings'=>$settings), true);
    }
    
    public function check()
    {   
        $error  = false;
        
        //there is no need for error checking on this form, but this is how it will generally be done.
        //test against $_POST 
        
        if($_POST['mode'] == 'test')
        {
            if(empty($_POST['test_secret_key']) || empty($_POST['test_publishable_key']))
            {
                $error  = 'To operate in test mode you must provide a "Test Secret Key" and a "Test Publishable Key"!';
            }
        }
        else
        {
            if(empty($_POST['live_secret_key']) || empty($_POST['live_publishable_key']))
            {
                $error  = 'To operate in live mode you must provide a "Live Secret Key" and a "Live Publishable Key"!';
            }
        }
        
        
        //count the errors
        if($error)
        {
            return $error;
        }
        else
        {
            //we save the settings if it gets here
            \CI::Settings()->save_settings('stripe', $_POST);
            return false;
        }
    }
}

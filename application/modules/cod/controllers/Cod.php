<?php namespace GoCart\Controller;
/**
 * Cod Class
 *
 * @package     GoCart
 * @subpackage  Controllers
 * @category    Cod
 * @author      Clear Sky Designs
 * @link        http://gocartdv.com
 */

class Cod extends Front { 

    public function __construct()
    {       
        parent::__construct();
        \CI::lang()->load('cod');
    }

    //back end installation functions
    public function checkoutForm()
    {
        //set a default blank setting for flatrate shipping
        \CI::Settings()->save_settings('payment_modules', array('cod'=>'1'));
        \CI::Settings()->save_settings('cod', array('enabled'=>'1'));

        $this->partial('codCheckoutForm');
    }

    public function getName()
    {
        echo lang('charge_on_delivery');
    }
}
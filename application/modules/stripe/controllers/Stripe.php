<?php namespace GoCart\Controller;
/**
 * Stripe Class
 *
 * @package     GoCart
 * @subpackage  Controllers
 * @category    Stripe
 * @author      Clear Sky Designs
 * @link        http://gocartdv.com
 */

class Stripe extends Front { 

    public function __construct()
    {
        parent::__construct();
        \CI::lang()->load('stripe');
    }

    public function checkoutForm()
    {
        $settings = \CI::Settings()->get_settings('stripe');
        return $this->partial('stripe', ['settings'=>$settings], true);
    }

    public function getName()
    {
        echo lang('stripe_method_name');
    }
}
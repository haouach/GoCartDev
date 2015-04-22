<?php namespace GoCart\Controller;
/**
 * Checkout Class
 *
 * @package     GoCart
 * @subpackage  Controllers
 * @category    Checkout
 * @author      Clear Sky Designs
 * @link        http://gocartdv.com
 */

class Checkout extends Front {

    public $customer;

    public function __construct()
    {
        parent::__construct();

        if (config_item('require_login'))
        {
            \CI::Login()->isLoggedIn('checkout');
        }

        if(!config_item('allow_os_purchase') && config_item('inventory_enabled'))
        {
            // double check the inventory of each item before proceeding to checkout
            $inventory_check = \GC::check_inventory();

            if($inventory_check)
            {
                // product is not longer available
                \CI::session()->set_flashdata('error', $inventory_check);
                redirect('cart/view_cart');
            }
        }

        $this->customer = \CI::Login()->customer();
    }

    public function submitOrder()
    {
        //check the grand total, if it's 0 continue, if not look for the paid/pending flag in the status.
        if(\GC::getGrandTotal() == 0)
        {
            //submit the order
            $orderId = \GC::submitOrder();
            redirect('orderPlaced/'.$orderId);
        }
    }

    public function index()
    {
        $data['addresses'] = \CI::Customers()->get_address_list($this->customer['id']);
        $this->view('checkout', $data);
    }

    public function addressList()
    {
        $data['addresses'] = \CI::Customers()->get_address_list($this->customer['id']);
        $this->partial('checkout/address_list', $data);
    }

    public function address()
    {
        $type = \CI::input()->post('type');
        $id = \CI::input()->post('id');
        
        $address = \CI::Customers()->get_address($id);

        if($address['customer_id'] != $this->customer['id'])
        {
            echo json_encode(['error'=>lang('error_address_not_found')]);
        }
        else
        {
            if($type == 'shipping')
            {
                \GC::setAttribute('shipping_address_id',$id);
            }
            elseif($type == 'billing')
            {
                \GC::setAttribute('billing_address_id',$id);
            }

            \GC::saveCart();

            echo json_encode(['success'=>true]);
        }
    }

    public function shippingMethods()
    {
        if(\GC::orderRequiresShipping())
        {
            $this->partial('shippingMethods', [
                'rates'=>\GC::getShippingMethodOptions(),
                'requiresShipping'=>true
            ]);
        }
        else
        {
            $this->partial('shippingMethods', ['rates'=>[], 'requiresShipping'=>false]);
        }
    }

    public function setShippingMethod()
    {
        $rates = \GC::getShippingMethodOptions();
        $hash = \CI::input()->post('method');

        foreach($rates as $key=>$rate)
        {
            $test = md5(json_encode(['key'=>$key, 'rate'=>$rate]));
            if($hash == $test)
            {
                \GC::setShippingMethod($key, $rate, $hash);
                echo json_encode(['success'=>true]);
                return false;
            }
        }

        echo json_encode(['error'=>lang('shipping_method_is_no_longer_valid')]);
    }

    public function paymentMethods()
    {
        global $paymentModules;

        $modules = [];
        foreach($paymentModules as $paymentModule)
        {
            $className = '\GoCart\Controller\\'.$paymentModule['class'];
            $modules[$paymentModule['key']] = $paymentModule;
            $modules[$paymentModule['key']]['class'] = new $className;
        }

        ksort($modules);
        $this->partial('paymentMethods', ['modules'=>$modules]);
    }
}
<?php namespace GoCart\Controller;
/**
 * AdminCoupons Class
 *
 * @package     GoCart
 * @subpackage  Controllers
 * @category    AdminCoupons
 * @author      Clear Sky Designs
 * @link        http://gocartdv.com
 */

class AdminCoupons extends Admin { 

var $coupon_id;
    
    public function __construct()
    {       
        parent::__construct();
        
        \CI::auth()->check_access('Admin', true);
        \CI::load()->model('Coupons');
        \CI::load()->model('Products');
        \CI::lang()->load('coupon');
    }
    
    public function index()
    {
        $data['page_title'] = lang('coupons');
        $data['coupons']    = \CI::Coupons()->getCoupons();
        
        $this->view('coupons', $data);
    }
    
    
    public function form($id = false)
    {

        \CI::load()->helper(array('form', 'date'));
        \CI::load()->library('form_validation');
        
        \CI::form_validation()->set_error_delimiters('<div class="error">', '</div>');
        
        $this->coupon_id    = $id;
        
        $data['page_title']     = lang('coupon_form');
        
        //default values are empty if the product is new
        $data['id']                     = '';
        $data['code']                   = '';
        $data['start_date']             = '';
        $data['whole_order_coupon']     = 0;
        $data['max_product_instances']  = '';
        $data['end_date']               = '';
        $data['max_uses']               = '';
        $data['reduction_target']       = '';
        $data['reduction_type']         = '';
        $data['reduction_amount']       = '';
        
        $added = [];
        
        if ($id)
        {   
            $coupon     = \CI::Coupons()->getCoupon($id);

            //if the product does not exist, redirect them to the product list with an error
            if (!$coupon)
            {
                \CI::session()->set_flashdata('message', lang('error_not_found'));
                redirect('admin/product');
            }
            
            //set values to db values
            $data['id'] = $coupon->id;
            $data['code'] = $coupon->code;
            $data['start_date'] = $coupon->start_date;
            $data['end_date'] = $coupon->end_date;
            $data['whole_order_coupon'] = $coupon->whole_order_coupon;
            $data['max_product_instances'] = $coupon->max_product_instances;
            $data['num_uses'] = $coupon->num_uses;
            $data['max_uses'] = $coupon->max_uses;
            $data['reduction_target'] = $coupon->reduction_target;
            $data['reduction_type'] = $coupon->reduction_type;
            $data['reduction_amount'] = $coupon->reduction_amount;
            
            $added = \CI::Coupons()->getProductIds($id);
        }
        
        \CI::form_validation()->set_rules('code', 'lang:code',
            ['trim', 'required',
                ['code_callable', function($str)
                    {
                        $code = \CI::Coupons()->checkCode($str, $this->coupon_id);
                        if ($code)
                        {
                            \CI::form_validation()->set_message('code_callable', lang('error_already_used'));
                            return FALSE;
                        }
                        else
                        {
                            return TRUE;
                        }
                    }
                ]
            ]
        );
        \CI::form_validation()->set_rules('max_uses', 'lang:max_uses', 'trim|numeric');
        \CI::form_validation()->set_rules('max_product_instances', 'lang:limit_per_order', 'trim|numeric');
        \CI::form_validation()->set_rules('whole_order_coupon', 'lang:whole_order_discount');
        \CI::form_validation()->set_rules('reduction_target', 'lang:reduction_target', 'trim|required');
        \CI::form_validation()->set_rules('reduction_type', 'lang:reduction_type', 'trim');
        \CI::form_validation()->set_rules('reduction_amount', 'lang:reduction_amount', 'trim|numeric');
        \CI::form_validation()->set_rules('start_date', 'lang:start_date');
        \CI::form_validation()->set_rules('end_date', 'lang:end_date');
        
        // create product list
        $products = \CI::Products()->getProducts();
        
        // set up a 2x2 row list for now
        $data['product_rows'] = "";
        $x=0;
        while(TRUE) { // Yes, forever, until we find the end of our list
            if ( !isset($products[$x] )) break; // stop if we get to the end of our list
            $checked = "";
            if(in_array($products[$x]->id, $added))
            {
                $checked = "checked='checked'";
            }
            $data['product_rows']  .=  "<tr><td><input type='checkbox' name='product[]' value='". $products[$x]->id ."' $checked></td><td> ". $products[$x]->name ."</td>";
            
            $x++;
            
            //reset the checked value to nothing
            $checked = "";
            if ( isset($products[$x] )) { // if we've gotten to the end on this row
                if(in_array($products[$x]->id, $added))
                {
                    $checked = "checked='checked'";
                }
                $data['product_rows']  .=   "<td><input type='checkbox' name='product[]' value='". $products[$x]->id ."' $checked><td><td> ". $products[$x]->name ."</td></tr>";
            } else {
                $data['product_rows']  .=   "<td> </td></tr>";
            }
            
            $x++;
        } 
        
    
        if (\CI::form_validation()->run() == FALSE)
        {
            $this->view('coupon_form', $data);
        }
        else
        {
            $save['id'] = $id;
            $save['code'] = \CI::input()->post('code');
            $save['start_date'] = \CI::input()->post('start_date');
            $save['end_date'] = \CI::input()->post('end_date');
            $save['max_uses'] = \CI::input()->post('max_uses');
            $save['whole_order_coupon'] = \CI::input()->post('whole_order_coupon');
            $save['max_product_instances'] = \CI::input()->post('max_product_instances');
            $save['reduction_target'] = \CI::input()->post('reduction_target');
            $save['reduction_type'] = \CI::input()->post('reduction_type');
            $save['reduction_amount'] = \CI::input()->post('reduction_amount');

            if($save['start_date']=='')
            {
                $save['start_date'] = null;
            }
            if($save['end_date']=='')
            {
                $save['end_date'] = null;
            }
            
            $product = \CI::input()->post('product');
            
            // save coupon
            $promo_id = \CI::Coupons()->save($save);
            
            // save products if not a whole order coupon
            //   clear products first, then save again (the lazy way, but sequence is not utilized at the moment)
            \CI::Coupons()->removeProduct($id);
            
            if(!$save['whole_order_coupon'] && $product) 
            {
                while(list(, $product_id) = each($product))
                {
                    \CI::Coupons()->addProduct($promo_id, $product_id);
                }
            }
            
            // We're done
            \CI::session()->set_flashdata('message', lang('message_saved_coupon'));
            
            //go back to the product list
            redirect('admin/coupons');
        }
    }

    public function delete($id = false)
    {
        if ($id)
        {   
            $coupon = \CI::Coupons()->getCoupon($id);
            //if the promo does not exist, redirect them to the customer list with an error
            if (!$coupon)
            {
                \CI::session()->set_flashdata('error', lang('error_not_found'));
                redirect('admin/coupons');
            }
            else
            {
                \CI::Coupons()->deleteCoupon($id);
                
                \CI::session()->set_flashdata('message', lang('message_coupon_deleted'));
                redirect('admin/coupons');
            }
        }
        else
        {
            //if they do not provide an id send them to the promo list page with an error
            \CI::session()->set_flashdata('message', lang('error_not_found'));
            redirect('admin/coupons');
        }
    }
}
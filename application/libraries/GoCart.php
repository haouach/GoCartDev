<?php
/**
 * GoCart Class
 *
 * @package     GoCart
 * @subpackage  Library
 * @category    GoCart
 * @author      Clear Sky Designs
 * @link        http://gocartdv.com
 */

class GoCart {

    protected $cart;
    protected $customer;
    protected $items;
    public function __construct() 
    {
        $this->getCart(true);
    }

    public function saveCart()
    {
        //is the shipping method still valid?
        $this->testShippingMethodValidity();

        //calculate coupon discounts first
        $this->calculateCouponDiscounts();

        //add up the subtotal (coupon discounts included at line items)
        $this->getSubtotal();

        //calculate tax based on post-coupon price
        $this->setTaxes();

        //calculate gift card discounts
        $this->calculateGiftCardDiscounts();

        //save the cart information
        CI::Orders()->saveOrder((array)$this->cart);

        //refresh the cart details.
        $this->getCart(true);
    }

    public function addGiftCard($giftCard)
    {
        foreach($this->items as $item)
        {
            if($item->description == $giftCard->code && $item->type == 'gift card')
            {
                return ['success'=>false, 'error'=>lang('gift_card_already_applied')];
            }
        }

        $item = (object)['product_id'=>0, 'shippable'=>0, 'taxable'=>0, 'fixed_quantity'=>1, 'type'=>'gift card', 'name'=>lang('gift_card'), 'price'=>($giftCard->beginning_amount - $giftCard->amount_used), 'description'=>$giftCard->code];
        $this->insertItem(['product'=>$item]);

        return ['success'=>true];
    }

    public function addCoupon($code)
    {
        //get the coupon
        $coupon = \CI::Coupons()->getCouponByCode($code);
        if(!$coupon)
        {
            return json_encode(['error'=>lang('invalid_coupon_code')]);
        }
        //is coupon valid
        if(\CI::Coupons()->isValid($coupon))
        {
            //does the coupon apply to any products?
            if($this->isCouponApplied($coupon))
            {
                return json_encode(['error'=>lang('coupon_already_applied')]);
            }
            else
            {
                //Whole order coupons are not combinable with any other coupons
                foreach($this->items as $item)
                {
                    if($item->type == 'coupon')
                    {
                        $couponDetails = json_decode($item->excerpt);
                        if($coupon->whole_order_coupon || $couponDetails->whole_order_coupon)
                        {
                            return json_encode(['error'=>lang('whole_order_coupon_not_combinable')]);
                        }

                    }
                }
                $item = (object)['product_id'=>0, 'shippable'=>0, 'taxable'=>0, 'fixed_quantity'=>1, 'type'=>'coupon', 'name'=>lang('coupon'), 'price'=>0, 'total_price'=>0, 'description'=>$coupon->code, 'excerpt' => json_encode($coupon)];
                $this->insertItem(['product'=>$item]);
                return json_encode(['message'=>lang('coupon_applied')]);
            }
        }
        else
        {
            return json_encode(['error'=>lang('coupon_invalid')]);
        }
    }

    public function doesCouponApply($coupon)
    {
        if($coupon->reduction_target == 'price')
        {
            if($coupon->whole_order_coupon)
            {
                return true;
            }
            else
            {
                foreach($this->items as $item)
                {
                    if(in_array($item->product_id, $coupon->product_list))
                    {
                        return true;
                    }
                }
            }
        }
        else //free shipping
        {
            //supplement this with a free shipping module
            return true;
        }
    }

    public function isCouponApplied($coupon)
    {
        foreach($this->items as $item)
        {
            if($item->type == 'coupon' && $item->description == $coupon->code)
            {
                return true;
            }
        }
        return false;
    }
    
    private function calculateCouponDiscounts()
    {
        $coupons = [];
        $products = [];
        $discounts = [];

        for($i=0; $i<count($this->items); $i++)
        {
            if($this->items[$i]->type == 'coupon')
            {
                $coupons[] = $this->items[$i];
            }
            elseif($this->items[$i]->type == 'product')
            {
                //remove all discounts
                $this->items[$i]->coupon_code = '';
                $this->items[$i]->coupon_discount = '';
                $this->items[$i]->coupon_discount_quantity = '';
            }
        }

        if(count($coupons) > 0)
        {
            foreach($coupons as $code)
            {
                $coupon = \CI::Coupons()->getCouponByCode($code->description);

                if($coupon->whole_order_coupon)
                {
                    for($i=0; $i < count($this->items); $i++)
                    {
                        if($this->items[$i]->type == 'product')
                        {
                            $this->items[$i]->coupon_code = $code->description;

                            //whole order coupon applies to every instance of the item;
                            $this->items[$i]->coupon_discount_quantity = $this->items[$i]->quantity;

                            if($coupon->reduction_type == 'percent')
                            {
                                $percent = ($coupon->reduction_amount/100);
                                $this->items[$i]->coupon_discount = $this->items[$i]->total_price * $percent;
                            }
                            else //fixed
                            {
                                $this->items[$i]->coupon_discount = $coupon->reduction_amount;
                            }

                            $this->insertItem(['product'=>$this->items[$i], 'quantity'=>$this->items[$i]->quantity]);
                        }
                    }
                }
                else //discount on individual products
                {
                    foreach($this->items as $item)
                    {
                        if(in_array($item->product_id, $coupon->product_list))
                        {
                            //add the coupon to this item
                            if($coupon->reduction_type == 'percent')
                            {
                                $percent = ($coupon->reduction_amount/100);
                                $discount = $item->total_price * $percent;

                                //$newPrice = $this->items[$i]->total_price - $discount;
                                //$this->items[$i]->total_price = max(0, $discount);

                                //find out which discounts are greatest
                                $discounts[$item->id][$coupon->code] = $discount;

                            }
                            else //fixed
                            {
                                $discounts[$item->id][$coupon->code] = $coupon->reduction_amount;
                                //$this->items[$i]->total_price = max(0, $coupon->reduction_amount);
                            }
                        }
                    }
                }
            }
        }

        if(count($discounts) > 0)
        {

            foreach($discounts as $product_id => $discount)
            {
                $max = array_shift( ( array_keys( $discount, max($discount) ) ) );
                for($i=0; $i<count($this->items); $i++)
                {
                    if($this->items[$i]->id == $product_id)
                    {
                        $this->items[$i]->coupon_code = $max;
                        $this->items[$i]->coupon_discount = max($discount);
                        $this->items[$i]->coupon_discount_quantity = $this->items[$i]->quantity;
                    }
                    // this will take either the new discount, or the removal of the old as set initially.
                    $this->insertItem(['product'=>$this->items[$i], 'quantity'=>$this->items[$i]->quantity]);
                }
            }
        }
        else
        {
            // no discounts, loop through and resave all without discounts.
            for($i=0; $i<count($this->items); $i++)
            {
                if($this->items[$i]->type == 'product')
                {
                    $this->insertItem(['product'=>$this->items[$i], 'quantity'=>$this->items[$i]->quantity]);
                }
            }
        }
    }

    private function calculateGiftCardDiscounts()
    {
        $total = 0;

        $giftCards = [];
        foreach($this->items as $item)
        {
            if($item->type != 'gift card') //gift card
            {
                if(isset($item->coupon_discount))
                {
                    $total += ($item->total_price * $item->quantity) - ($item->coupon_discount * $item->coupon_discount_quantity);
                }
                else
                {
                    $total += ($item->total_price * $item->quantity);
                }
            }
            else
            {
                $giftCards[] = $item;
            }
        }

        $total = round($total, 2);
        foreach($giftCards as $giftCard)
        {
            //find out how much can be applied
            if($total > $giftCard->price)
            {
                $giftCard->total_price = -($giftCard->price);
            }
            else
            {
                $giftCard->total_price = -($total);
            }

            // update the "total" so we don't go negative
            $total = $total + $giftCard->total_price;

            //update the item with the new "total_price" and excerpt
            $giftCard->excerpt = lang('gift_card_amount_applied').' '.format_currency($giftCard->total_price).'<br>'.lang('gift_card_amount_remaining').' '.format_currency($giftCard->price+$giftCard->total_price);
            
            $this->insertItem(['product'=>$giftCard]);
        }
    }

    public function setAttribute($key, $value)
    {
        $this->cart->$key = $value;
    }

    public function getAttribute($key)
    {
        return $this->cart->$key;
    }

    public function getCart($refresh = false)
    {
        if($refresh)
        {
            $this->customer = CI::Login()->customer();
            $this->cart = CI::Orders()->getCustomerCart($this->customer['id']);
            if(!$this->cart)
            {
                //create a new cart
                CI::Orders()->saveOrder(['status' => 'cart', 'customer_id' => $this->customer['id']]);
                $this->cart = CI::Orders()->getCustomerCart($this->customer['id']); 
            }
            $this->getCartItems(true);
        }

        return $this->cart;
    }

    public function getCartItems($refresh = false)
    {
        if($refresh || empty($this->items))
        {
            $this->items = CI::Orders()->getItems($this->cart->id);
        }
        
        return $this->items;
    }

    public function getCartItem($id)
    {
        foreach($this->items as $item)
        {
            if($item->id == $id)
            {
                return $item;
            }
        }
    }

    public function insertItem($data=[])
    {
        $product = false;
        $quantity = 1;
        $postedOptions = false;

        extract($data);

        if(is_int($product))
        {
            $product = \CI::Products()->getProduct($product);

            if(!$product)
            {
                return json_encode(['error'=>lang('error_product_not_found')]);
            }
            $product->product_id = $product->id;

            //remove some fields
            $remove = ['id', 'primary_category', 'quantity', 'related_products', 'google_feed', 'seo_title', 'meta', 'enabled'];
            foreach($remove as $r)
            {
                unset($product->$r);
            }

            $product->type = 'product';
        }

        //set the correct order ID
        $product->order_id = $this->cart->id;

        $update = false;
        if(empty($product->hash))
        {
            $product->hash = md5(json_encode($product).json_encode($postedOptions));
            
            //set defaults for new items
            $product->coupon_discount = 0;
            $product->coupon_discount_quantity = 0;
            $product->coupon_code = '';
        }
        else
        {
            //this is an update
            $update = true;
        }
        
        //loop through the products in the cart and make sure we don't have this in there already. If we do get those quantities as well
        $qty_count = $quantity;

        $this->getCartItems(); // refresh the cart items

        foreach($this->items as $item)
        {
            if(intval($item->product_id) == intval($product->product_id))
            {
                if($item->hash != $product->hash) //if the hashes match, skip this step (this is an update)
                {
                    $qty_count = $qty_count + $item->quantity;
                }
                
            }

            if($item->hash == $product->hash && !$update) //if this is an update skip this step
            {
                //if the item is already in the cart, send back a message
                return json_encode(['message'=>lang('item_already_added')]);
            }
        }

        if(!config_item('allow_os_purchase') && (bool)$product['track_stock'])
        {
            $stock = \CI::Products()->get_product($product->product_id);

            if($stock->quantity < $qty_count)
            {
                return json_encode(['error'=>sprintf(lang('not_enough_stock'), $stock->name, $stock->quantity)]);
            }
        }

        if (!$quantity || $quantity <= 0 || $product->fixed_quantity==1)
        {
            $product->quantity = 1;
        }
        else
        {
            $product->quantity = $quantity;
        }

        //create save options array here for use later.
        $saveOptions = [];

        if(!$update && $product->product_id) // if not an update or non-product, try and run the options
        {
            //set the base "total_price"
            if($product->saleprice > 0)
            {
                $product->total_price = $product->saleprice;
            }
            else
            {
                $product->total_price = $product->price;
            }

            //set base "total_weight"
            $product->total_weight = $product->weight;

            $productOptions = \CI::ProductOptions()->getProductOptions($product->product_id);

            //option error vars
            $optionError = false;
            $optionErrorMessage = lang('option_error').'<br/>';
            
            //lets validate the options
            foreach($productOptions as $productOption)
            {
                // are we missing any required values?
                $optionValue = false;
                if(!empty($postedOptions[$productOption->id]))
                {
                    $optionValue = $postedOptions[$productOption->id];
                }

                if((int)$productOption->required && !$optionValue) 
                {
                    $optionError = true;
                    $optionErrorMessage .= "- ". $productOption->name .'<br/>';
                    continue; // don't bother processing this particular option any further
                }

                //create options to save to the database in case we get past the errors
                if($productOption->type == 'checklist')
                {
                    if(is_array($optionValue))
                    {
                        foreach($optionValue as $ov)
                        {
                            foreach($productOption->values as $productOptionValue)
                            {
                                if($productOptionValue->id == $ov)
                                {
                                    $saveOptions[] = [
                                        'option_name'=>$productOption->name,
                                        'value'=>$productOptionValue->value,
                                        'price'=>$productOptionValue->price,
                                        'weight'=>$productOptionValue->weight
                                    ];
                                    $product->total_weight += $productOptionValue->weight;
                                    $product->total_price += $productOptionValue->price;
                                }
                            }
                        }
                    }
                }
                else //every other form type we support
                {
                    $saveOption = [];
                    if($productOption->type == 'textfield' || $productOption->type == 'textarea')
                    {
                        $saveOption['value'] = $optionValue;
                        $productOptionValue = $productOption->values[0];
                    }
                    else //radios and checkboxes
                    {
                        foreach($productOption->values as $ov)
                        {
                            if($ov->id == $optionValue)
                            {
                                $productOptionValue = $ov;
                                break;
                            }
                        }
                        $saveOption['value'] = $optionValue;
                    }

                    $saveOption['option_name'] = $productOption->name;
                    $saveOption['price'] = $productOptionValue->price;
                    $saveOption['weight'] = $productOptionValue->weight;
                    
                    //add it to the array;
                    $saveOptions[] = $saveOption;

                    //update the total weight and price
                    $product->total_weight += $productOptionValue->weight;
                    $product->total_price += $productOptionValue->price;
                }
            }

            if($optionError)
            {
                return json_encode(['error'=>$optionErrorMessage]);
            }
        }

        //save the product
        $product_id = \CI::Orders()->saveItem((array)$product);

        //save the options if we have them
        foreach($saveOptions as $saveOption)
        {
            $saveOption['order_item_id'] = $product_id;
            $saveOption['order_id'] = $this->cart->id;
            \CI::Orders()->saveItemOption($saveOption);
        }
        if($update)
        {
            foreach($this->items as $key => $item)
            {
                if($item->id == $product_id)
                {
                    $this->items[$key] = $product;
                }
            }
        }
        else
        {
            $product->id = $product_id;
            $this->items[] = $product;
        }

        //get current item count
        $itemCount = $this->totalItems();

        if($update)
        {
            return json_encode(['message'=>lang('cart_updated'), 'itemCount'=>$itemCount]);
        }
        else
        {
            return json_encode(['message'=>lang('item_added_to_cart'), 'itemCount'=>$itemCount]);
        }
    }

    
    // This saves the confirmed order 
    public function submitOrder() {

        CI::load()->model('order_model');
        CI::load()->model('Product_model');
        

        $this->cart->order_number = str_replace('.', '-', microtime(true)).$this->cart->id;
        $this->cart->status = config_item('order_status');
        $this->cart->ordered_on = date('Y-m-d H:i:s');

        $this->saveCart();
        
        return $this->cart->ordered_on;

        die;
        /*
        // Process any per-item operations
        $download_package = []; //create digital package array
        foreach ($this->_cart_contents['items'] as $item)
        {
            
            // Process Gift Card purchase               
            if($this->gift_cards_enabled && isset($item['gc_info'])) 
            {
                $gc_data = [];
                $gc_data['order_number'] = $order_id;
                $gc_data['beginning_amount'] = $item['price'];
                $gc_data['code'] = $item['code'];
                $gc_data= array_merge($gc_data, $item['gc_info']);
                
                CI::Gift_card_model()->save_card($gc_data);
                
                //send the recipient a message
                CI::Gift_card_model()->send_notification($gc_data);    
            }

            
            // Process Downloadable Products
            if(!empty($item['file_list']))
            {
                // compile a list of all the items that can be downloaded for this order
                $download_package[] = $item['file_list'];
            }
            
            //deduct any quantities from the database
            if(!$item['is_gc'])
            {
                $product        = CI::Product_model()->find($item['id']);
                $new_quantity   = intval($product->quantity) - intval($item['quantity']);
                $product_quantity   = ['id'=>$product->id, 'quantity'=>$new_quantity];
                CI::Product_model()->save($product_quantity);
            }
        }
        //add the digital packages to the database
        if(!empty($download_package))
        {
            // create the record, send the email
            CI::DigitalProducts()->add_download_package($download_package, $order_id);
        }
            
            

        // update the balance of any gift cards used to purchase the order
        if($this->gift_cards_enabled && isset($this->_cart_contents['gc_list']))
        {
            CI::Gift_card_model()->update_used_card_balances($this->_cart_contents['gc_list']);
        }           
        
        // touch any used product coupons (increment usage)
        if(isset($this->_cart_contents['applied_coupons']))
        {
            foreach($this->_cart_contents['applied_coupons'] as $code=>$content)
                CI::Coupons()->touch_coupon($code);
        }
        
        // touch free shipping coupon
        if($this->_cart_contents['free_shipping_coupon'])
        {
            CI::Coupons()->touch_coupon($this->_cart_contents['free_shipping_coupon']);
        }
        
        // touch whole order coupon
        if(isset($this->_cart_contents['whole_order_discount_cp']))
        {
            CI::Coupons()->touch_coupon($this->_cart_contents['whole_order_discount_cp']);
        }

        return $order_id;*/
    }
   
    public function getTaxableTotal()
    {
        $total = 0;
        foreach($this->items as $item)
        {
            if($item->taxable)
            {
                if(isset($item->coupon_discount))
                {
                    $total += ($item->total_price * $item->quantity) - ($item->coupon_discount * $item->coupon_discount_quantity);
                }
                else
                {
                    $total += ($item->total_price * $item->quantity);
                }
            }
        }

        return round($total, 2);
    }

    public function getSubtotal()
    {
        $total = 0;
        foreach($this->items as $item)
        {
            if($item->type == 'product')
            {
                $total += ($item->total_price * $item->quantity) - ($item->coupon_discount * $item->coupon_discount_quantity);
            }
        }
        $total = round($total, 2);

        $this->cart->subtotal = $total;
        return $total;
    }

    public function getTotalWeight()
    {
        $total = 0;
        foreach($this->items as $item)
        {
            if($item->type == 'product')
            {
                $total += ($item->total_weight * $item->quantity);
            }
        }

        return $total;
    }

    public function getGrandTotal()
    {
        $total = 0;
        foreach($this->items as $item)
        {
            if(isset($item->coupon_discount))
            {
                $math = ($item->total_price * $item->quantity) - ($item->coupon_discount * $item->coupon_discount_quantity);
            }
            else
            {
                $math = ($item->total_price * $item->quantity);
            }
            $total = $total+$math;
        }
        $total = round($total, 2);
        $this->cart->grandtotal = $total;
        return $total;
    }

    public function setTaxes()
    {
        $tax = CI::Tax()->getTaxes();
        
        //remove any existing tax charges
        $this->removeItemsOfType('tax');

        if($tax > 0)
        {
            $item = (object)['product_id'=>0, 'shippable'=>0, 'taxable'=>0, 'fixed_quantity'=>1, 'type'=>'tax', 'name'=>lang('taxes'), 'total_price'=>$tax];
            $this->insertItem(['product'=>$item]);
        }
    }
    
    public function setShippingMethod($key, $rate, $hash)
    {
        //first remove any existing shipping methods
        $this->removeItemsOfType('shipping');

        $shipping = (object)['product_id'=>0, 'shippable'=>0, 'taxable'=>0, 'fixed_quantity'=>1, 'type'=>'shipping', 'name'=>$key, 'total_price'=>$rate, 'description'=>$hash];

        if(config_item('tax_shipping'))
        {
            $shipping->taxable = 1;
        }

        $this->insertItem(['product'=>$shipping, 'recalcuateShipping'=>false]);
    }

    public function getShippingMethod()
    {
        foreach($this->items as $item)
        {
            if($item->type == 'shipping')
            {
                return $item;
            }
        }
        return false;
    }

    public function orderRequiresShipping()
    {
        foreach($this->items as $item)
        {
            if((bool)$item->shippable)
            {
                return true;
            }
        }
        return false;
    }

    public function getShippingMethodOptions()
    {
        global $shippingModules;

        $rates = [];
        foreach($shippingModules as $shippingModule)
        {
            $className = '\GoCart\Controller\\'.$shippingModule['class'];
            $rates = $rates+(new $className)->rates();
        }
        return $rates;
    }

    public function testShippingMethodValidity()
    {
        if($this->orderRequiresShipping())
        {
            //if shipping is not required, then remove any shipping methods.
            $this->removeItemsOfType('shipping');
        }
        else
        {
            $shippingExists = false;
            $shippingMethod = $this->getShippingMethod();
            if(is_object($shippingMethod))
            {
                $shippingMethods = $this->getShippingMethodOptions();
                foreach($shippingMethods as $key=>$rate)
                {
                    $hash = md5( json_encode(['key'=>$key, 'rate'=>$rate]) );
                    if($hash == $shippingMethod->description)
                    {
                        $shippingExists = true;
                    }
                }
            }
            if(!$shippingExists)
            {
                $this->removeItemsOfType('shipping');
            }
        }
    }

    public function removeItemsOfType($type)
    {
        foreach($this->items as $item)
        {
            if($item->type == $type)
            {
                $this->removeItem($item->id);
            }
        }
    }

    public function removeItem($id)
    {
        CI::Orders()->removeItem($this->cart->id, $id);

        for($i=0; $i < count($this->items); $i++)
        {
            if($this->items[$i]->id == $id)
            {
                unset($this->items[$i]);
            }
        }
        
        //reset the array keys
        $this->items = array_values($this->items);
    }

    public function totalItems()
    {
        $count = 0;
        foreach($this->items as $item)
        {
            if($item->type == 'product')
            {
                $count += $item->quantity;
            }
        }
        return $count;
    }
}
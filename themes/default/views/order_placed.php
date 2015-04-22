<div class="page-header">
    <h1><?php echo lang('order_number');?>: <?php echo $order_id;?></h1>
</div>
<?php
// content defined in canned messages
echo $download_section;
?>


<div class="col-nest">
    <div class="col" data-cols="5/7">

        <div class="alert blue">
        Order number <?php echo $order_id;?> has been placed!
        </div>

        <table class="table summary horizontal-border zebra" style="margin-top:20px;">
            <thead>
                <tr>
                    <th style="width:10%;"><?php echo lang('sku');?></th>
                    <th style="width:20%;"><?php echo lang('name');?></th>
                    <th style="width:10%;"><?php echo lang('price');?></th>
                    <th><?php echo lang('description');?></th>
                    <th style="width:10%;"><?php echo lang('quantity');?></th>
                    <th style="width:8%;" class="text-right"><?php echo lang('totals');?></th>
                </tr>
            </thead>
            
            <tfoot>
                <?php if($go_cart['group_discount'] > 0)  : ?> 
                <tr>
                    <td colspan="5"><strong><?php echo lang('group_discount');?></strong></td>
                    <td class="text-right"><?php echo format_currency(0-$go_cart['group_discount']); ?></td>
                </tr>
                <?php endif; ?>

                <tr>
                    <td colspan="5"><strong><?php echo lang('subtotal');?></strong></td>
                    <td class="text-right"><?php echo format_currency($go_cart['subtotal']); ?></td>
                </tr>
                
                <?php if($go_cart['coupon_discount'] > 0)  : ?> 
                <tr>
                    <td colspan="5"><strong><?php echo lang('coupon_discount');?></strong></td>
                    <td class="text-right"><?php echo format_currency(0-$go_cart['coupon_discount']); ?></td>
                </tr>

                <?php if($go_cart['order_tax'] != 0) : // Only show a discount subtotal if we still have taxes to add (to show what the tax is calculated from) ?> 
                <tr>
                    <td colspan="5"><strong><?php echo lang('discounted_subtotal');?></strong></td>
                    <td class="text-right"><?php echo format_currency($go_cart['discounted_subtotal']); ?></td>
                </tr>
                <?php endif;

                endif; ?>
                <?php // Show shipping cost if added before taxes
                if(config_item('tax_shipping') && $go_cart['shipping_cost']>0) : ?>
                <tr>
                    <td colspan="5"><strong><?php echo lang('shipping');?></strong></td>
                    <td class="text-right"><?php echo format_currency($go_cart['shipping_cost']); ?></td>
                </tr>
                <?php endif ?>
                
                <?php if($go_cart['order_tax'] != 0) : ?> 
                <tr>
                    <td colspan="5"><strong><?php echo lang('taxes');?></strong></td>
                    <td class="text-right"><?php echo format_currency($go_cart['order_tax']); ?></td>
                </tr>
                <?php endif;?>
                
                <?php // Show shipping cost if added after taxes
                if(!config_item('tax_shipping') && $go_cart['shipping_cost']>0) : ?>
                <tr>
                    <td colspan="5"><strong><?php echo lang('shipping');?></strong></td>
                    <td class="text-right"><?php echo format_currency($go_cart['shipping_cost']); ?></td>
                </tr>
                <?php endif;?>
                
                <?php if($go_cart['gift_card_discount'] != 0) : ?> 
                <tr>
                    <td colspan="5"><strong><?php echo lang('gift_card');?></strong></td>
                    <td class="text-right"><?php echo format_currency(0-$go_cart['gift_card_discount']); ?></td>
                </tr>
                <?php endif;?>
                <tr> 
                    <td colspan="5"><strong><?php echo lang('grand_total');?></strong></td>
                    <td class="text-right"><?php echo format_currency($go_cart['total']); ?></td>
                </tr>
            </tfoot>

            <tbody>
            <?php
            $subtotal = 0;
            foreach ($go_cart['contents'] as $cartkey=>$product):?>
                <tr>
                    <td><?php echo $product['sku'];?></td>
                    <td><?php echo $product['name']; ?></td>
                    <td><?php echo format_currency($product['base_price']);   ?></td>
                    <td><?php
                        if(isset($product['options'])) {
                            foreach ($product['options'] as $name=>$value)
                            {
                                if(is_array($value))
                                {
                                    echo '<div>'.$name.':<br/>';
                                    foreach($value as $item)
                                        echo '- '.$item.'<br/>';
                                    echo '</div>';
                                } 
                                else 
                                {
                                    echo '<div>'.$name.': '.$value.'</div>';
                                }
                            }
                        }
                        ?></td>
                    <td><?php echo $product['quantity'];?></td>
                    <td class="text-right"><?php echo format_currency($product['price']*$product['quantity']); ?>               </td>
                </tr>
                    
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="col order-details" data-cols="2/7">
        <div>
            <?php
            $ship = $customer['ship_address'];
            $bill = $customer['bill_address'];
            ?>
            <div class="col" data-cols="1">
                <strong><?php echo lang('shipping_information')?></strong><br>
                <?php echo format_address($ship, TRUE);?><br/>
                <?php echo $ship['email'];?><br/>
                <?php echo $ship['phone'];?>

                <hr>
            </div>
            
            <div class="col" data-cols="1">
                <strong><?php echo lang('billing_information');?></strong><br>
                <?php echo format_address($bill, TRUE);?><br/>
                <?php echo $bill['email'];?><br/>
                <?php echo $bill['phone'];?>

                <hr>
            </div>

            <?php if(!empty($referral)): //don't show this section if either of these items exist?>
            <div class="col" data-cols="1">
                <strong><?php echo lang('heard_about');?></strong><br>
                <?php echo $referral;?>

                <hr>
            </div>
            <?php endif;?>

            <?php if(!empty($shipping_notes)):?>
            <div class="col" data-cols="1">
                <strong><?php echo lang('shipping_instructions');?></strong><br>
                <?php echo $shipping_notes;?>
                
                <hr>
            </div>
            <?php endif;?>

            <?php if(!empty($shipping['method'])):?>
            <div class="col" data-cols="1">
                <strong style="padding-top:10px;"><?php echo lang('shipping_method');?></strong><br>
                <?php echo $shipping['method']; ?>

                <hr>
            </div>
            <?php endif;?>

            <?php if(!empty($payment['description'])):?>
            <div class="col" data-cols="1">
                <strong><?php echo lang('payment_information');?></strong><br>
                <?php echo $payment['description']; ?>
            </div>
            <?php endif;?>
        </div>
    </div>
</div>
<div class="page-header">
    <h1>
        <?php echo lang('gift_card');?>
    </h1>
</div>

<div class="col-nest">
    <div class="col product-images" data-cols="1/3">
        <?php echo theme_img('gift_card.svg', lang('gift_card'));?>
    </div>

    <div class="col" data-cols="2/3">
        <div class="product-details">
            <?php echo form_open('cart/gift_card');?>
                <?php if(is_array($preset_values)):?>
                
                    <div class="col" data-cols="1/3">
                        <label class="radiolist">
                            <?php   
                            if(set_value('amount')=='preset_amount')
                            {
                                $checked = true;
                            }
                            else
                            {
                                $checked = false;
                            }

                            echo form_radio('amount', 'preset_amount', $checked);
                            ?>
                    
                            <?php echo lang('gift_card_choose_amount');?>
                        </label>
                    </div>
                    <div class="col" data-cols="1/3">
                        
                        <?php 
                            foreach($preset_values as $value)
                            {
                                $options[$value] = "\$$value";
                            }
                            echo form_dropdown('preset_amount', $options, set_value('preset_amount'));
                        ?>
                    </div>

                    <br class="clear">

                <?php endif;?>

                <?php if($allow_custom_amount):?>              
                    
                    <div class="col" data-cols="1/3">
                        <label class="radiolist">
                        <?php   
                        if(set_value('amount')=='custom_amount')
                        {
                            $checked = true;
                        }
                        else
                        {
                            $checked = false;
                        }

                        echo form_radio('amount', 'custom_amount', $checked);
                        ?>

                        <?php echo lang('gift_card_custom_amount');?>
                        </label>
                    </div>
                    <div class="col" data-cols="1/3">
                        <?php echo form_input('custom_amount', set_value('custom_amount'));?>
                    </div>
                    
                    <br class="clear">
                <?php endif;?>

                <div class="col" data-cols="1/3">
                    <label><?php echo lang('gift_card_to');?></label>
                    <?php echo form_input('gc_to_name', set_value('gc_to_name')); ?>
                </div>
                
                <div class="col" data-cols="1/3">
                    <label><?php echo lang('gift_card_email');?></label>
                    <?php echo form_input('gc_to_email', set_value('gc_to_email')); ?>
                </div>
                
                <div class="col" data-cols="1/3">
                    <label><?php echo lang('gift_card_from');?></label>
                    <?php echo form_input('gc_from', set_value('gc_from')); ?>
                </div>

                <div class="col" data-cols="1">
                    <label><?php echo lang('gift_card_message');?></label>
                    <?php 
                    $data = array(
                      'name' => 'message',
                      'rows' => '5',
                      'cols' => '30'
                    );
            
                    echo form_textarea($data,set_value('message')); ?>
                </div>
                <div class="col" data-cols="1">
                    <input type="submit" class="blue" value="<?php echo lang('form_add_to_cart');?>"/>
                </div>
                <br class="clear">
            </form>        
        </div>
    </div>
</div>
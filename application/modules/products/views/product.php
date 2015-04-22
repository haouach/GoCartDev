<div class="page-header">
    <h1><?php echo $product->name;?></h1>
</div>

<div class="col-nest">
    <div class="col" data-cols="1/3" data-medium-cols="1/3">
        <div id="primaryZoom">
        <?php
        $photo = theme_img('no_picture.png', lang('no_image_available'));

        if(!empty($product->images[0]))
        {
            foreach($product->images as $photo)
            {
                if(isset($photo['primary']))
                {
                    $primary = $photo;
                }
            }
            if(!isset($primary))
            {
                $tmp = $product->images; //duplicate the array so we don't lose it.
                $primary = array_shift($tmp);
            }

            $photo = '<img src="'.base_url('uploads/images/full/'.$primary['filename']).'" alt="'.$product->seo_title.'" data-caption="'.htmlentities(nl2br($primary['caption'])).'"/>';
        }
        echo $photo
        ?>
        </div>
        <?php if(!empty($primary['caption'])):?>
        <div class="product-caption">
            <?php echo $primary['caption'];?>
        </div>
        <?php endif;?>

        <?php if(count($product->images) > 1):?>
            <?php foreach($product->images as $image): if($image != $primary):?>
                <div class="zoomedAway" style="position:absolute; top:-10000px;" id="pic-<?php echo md5($image['alt']);?>">
                    <div id="pic-<?php echo md5($image['alt']);?>">
                        <img src="<?php echo base_url('uploads/images/full/'.$image['filename']);?>" data-caption="<?php echo htmlentities(nl2br($image['caption']));?>"/>
                    </div>
                </div>
            <?php endif; endforeach;?>
        <?php endif;?>

        <?php if(count($product->images) > 1):?>
            <div class="col-nest product-images">
                
                <?php foreach($product->images as $image):?>
                    <div class="col mobile product-thumbnail" data-cols="1/4" data-medium-cols="1/4" data-small-cols="1/4" style="margin:15px 0px;">
                        <img src="<?php echo base_url('uploads/images/full/'.$image['filename']);?>" data-caption="<?php echo htmlentities(nl2br($image['caption']));?>"/>
                    </div>
                <?php endforeach;?>
                
            </div>
        <?php endif;?>
    </div>


    <div class="col pull-right" data-cols="2/3" data-medium-cols="2/3">
        <div id="productAlerts"></div>
        <?php
        if($product->saleprice > 0)
        {
            echo '<div class="sale-price"><small>on sale</small>';
            echo ' <span>'.format_currency($product->saleprice).'/year</span></div>';
        }
        else
        {
            echo '<div class="product-price"><span>'.format_currency($product->price).'/year</span></div>';   
        }
        ?>

        <br class="clear">
        
        <div class="product-details">

            <div class="productDescription">
                <?php echo $product->description; ?>
            </div>

            <?php echo form_open('cart/add-to-cart', 'id="add-to-cart"');?>
            <input type="hidden" name="cartkey" value="<?php echo CI::session()->flashdata('cartkey');?>" />
            <input type="hidden" name="id" value="<?php echo $product->id?>"/>

            <?php if(count($options) > 0): ?>
                <?php foreach($options as $option):
                    $required = '';
                    if($option->required)
                    {
                        $required = ' class="required"';
                    }
                    ?>
                        <div class="col-nest">
                            <div class="col" data-cols="1/3">
                                <label<?php echo $required;?>><?php echo $option->name;?></label>
                            </div>
                            <div class="col" data-cols="2/3">
                        <?php
                        if($option->type == 'checklist')
                        {
                            $value  = [];
                            if($posted_options && isset($posted_options[$option->id]))
                            {
                                $value  = $posted_options[$option->id];
                            }
                        }
                        else
                        {
                            if(isset($option->values[0]))
                            {
                                $value  = $option->values[0]->value;
                                if($posted_options && isset($posted_options[$option->id]))
                                {
                                    $value  = $posted_options[$option->id];
                                }
                            }
                            else
                            {
                                $value = false;
                            }
                        }

                        if($option->type == 'textfield'):?>
                            <input type="text" name="option[<?php echo $option->id;?>]" value="<?php echo $value;?>"/>
                        <?php elseif($option->type == 'textarea'):?>
                            <textarea name="option[<?php echo $option->id;?>]"><?php echo $value;?></textarea>
                        <?php elseif($option->type == 'droplist'):?>
                            <select name="option[<?php echo $option->id;?>]">
                                <option value=""><?php echo lang('choose_option');?></option>

                            <?php foreach ($option->values as $values):
                                $selected   = '';
                                if($value == $values->id)
                                {
                                    $selected   = ' selected="selected"';
                                }?>

                                <option<?php echo $selected;?> value="<?php echo $values->id;?>">
                                    <?php echo($values->price != 0)?' (+'.format_currency($values->price).') ':''; echo $values->name;?>
                                </option>

                            <?php endforeach;?>
                            </select>
                        <?php elseif($option->type == 'radiolist'):?>
                            <label class="radiolist">
                            <?php foreach ($option->values as $values):

                                $checked = '';
                                if($value == $values->id)
                                {
                                    $checked = ' checked="checked"';
                                }?>
                                <div>
                                    <input<?php echo $checked;?> type="radio" name="option[<?php echo $option->id;?>]" value="<?php echo $values->id;?>"/>
                                    <?php echo($values->price != 0)?'(+'.format_currency($values->price).') ':''; echo $values->name;?>
                                </div>
                            <?php endforeach;?>
                            </label>
                        <?php elseif($option->type == 'checklist'):?>
                            <label class="checklist"<?php echo $required;?>>
                            <?php foreach ($option->values as $values):

                                $checked = '';
                                if(in_array($values->id, $value))
                                {
                                    $checked = ' checked="checked"';
                                }?>
                                <div><input<?php echo $checked;?> type="checkbox" name="option[<?php echo $option->id;?>][]" value="<?php echo $values->id;?>"/>
                                <?php echo($values->price != 0 && $option->name != 'Buy a Sample')?'('.format_currency($values->price).') ':''; echo $values->name;?></div>    
                            <?php endforeach; ?>
                            </label>
                        <?php endif;?>
                        </div>
                    </div>
                <?php endforeach;?>
            <?php endif;?>
            
            <div class="text-right">
            <?php if(!config_item('inventory_enabled') || config_item('allow_os_purchase') || !(bool)$product->track_stock || $product->quantity > 0) : ?>
                
                <?php if(!$product->fixed_quantity) : ?>
                    
                        <strong>Quantity&nbsp;</strong> 
                        <input type="text" name="quantity" value="1" style="width:50px; display:inline"/>&nbsp;
                        <button class="blue" type="button" value="submit" onclick="addToCart($(this));"><i class="icon-cart"></i> <?php echo lang('form_add_to_cart');?></button>
                <?php else: ?>
                        <button class="blue" type="button" value="submit" onclick="addToCart($(this));"><i class="icon-cart"></i> <?php echo lang('form_add_to_cart');?></button>
                <?php endif;?>
                
            <?php endif;?>
                </div>
            </form>

        </div>

    </div>
        
    <?php if(!empty($product->related_products)):?>
    <div class="related_products" data-cols="1">

        <div class="page-header" style="margin-top:30px;">
            <h3><?php echo lang('related_products_title');?></h3>
        </div>

        <?php
        $mod = 1;
        foreach($product->related_products as $relate):?>
            <div class="col" data-cols="1/4" data-medium-cols="1/3" data-small-cols="1/2">
                <?php
                $photo  = theme_img('no_picture.png');
                $relate->images    = array_values((array)json_decode($relate->images));

                if(!empty($relate->images[0]))
                {
                    $primary    = $relate->images[0];
                    foreach($relate->images as $photo)
                    {
                        if(isset($photo->primary))
                        {
                            $primary    = $photo;
                        }
                    }

                    $photo  = base_url('uploads/images/medium/'.$primary->filename);
                }
                ?>
                <div onclick="window.location = '<?php echo site_url($relate->slug); ?>'" class="category-item">
                    <?php if((bool)$relate->track_stock && $relate->quantity < 1 && config_item('inventory_enabled')):?>
                        <div class="category-item-note yellow"><?php echo lang('out_of_stock');?></div>
                    <?php elseif($relate->saleprice > 0):?>
                        <div class="category-item-note red"><?php echo lang('on_sale');?></div>
                    <?php endif;?>
                    
                    <div class="previewImg"><img src="<?php echo $photo;?>"></div>

                    <div class="category-item-details">
                        <?php echo $relate->name;?>
                    </div>

                    <div class="categoryItemHover">
                        <div class="look">
                            <i class="icon-search"></i>
                        </div>
                        <div class="price">
                            <?php echo ( $relate->saleprice>0?format_currency($relate->saleprice):format_currency($relate->price) );?>
                        </div>
                    </div>

                </div>
            </div>
        <?php endforeach;?>

    </div>
    <?php endif;?>

</div>

<script>

    function addToCart(btn)
    {
        $('.product-details').spin();
        btn.attr('disabled', true);
        var cart = $('#add-to-cart');
        $.post(cart.attr('action'), cart.serialize(), function(data){
            if(data.message != undefined)
            {
                $('#productAlerts').html('<div class="alert green">'+data.message+' <a href="<?php echo site_url('checkout');?>"> <?php echo lang('view_cart');?></a> <i class="close"></i></div>');
                updateItemCount(data.itemCount);
                cart[0].reset();
            }
            else if(data.error != undefined)
            {
                $('#productAlerts').html('<div class="alert red">'+data.error+' <i class="close"></i></div>');
            }

            $('.product-details').spin(false);
            btn.attr('disabled', false);
        }, 'json');
    }

    var banners = false;
    $(document).ready(function(){
        banners = $('#banners').html();
    })
    
    $('.product-images img').click(function(){
        if(banners)
        {
            $.gumboTray(banners);
            $('.banners').gumboBanner($('.product-images img').index(this));
        }
    });

    function updateZoom(item){
        if(item == 'orig')
        {
            $('#primaryZoom').show();
            $('.zoomedAway').css({position:'absolute'});
        }
        else
        {
            var selected = item.split('|||');
            $('#primaryZoom').hide();
            $('.zoomedAway').css({position:'absolute'});
            $('#pic-'+selected[1]).css({position:'static'});
        }
    }

    $('.tabs').gumboTabs();
</script>

<?php if(count($product->images) > 1):?>
<script id="banners" type="text/template">
    <div class="banners">
        <?php
        foreach($product->images as $image):?>
                <div class="banner" style="text-align:center;">
                    <img src="<?php echo base_url('uploads/images/full/'.$image['filename']);?>" style="max-height:600px; margin:auto;"/>
                    <?php if(!empty($image['caption'])):?>
                        <div class="caption">
                            <?php echo $image['caption'];?>
                        </div>
                    <?php endif; ?>
                </div>
        <?php endforeach;?>
        <a class="controls" data-direction="back"><i class="icon-chevron-left"></i></a>
        <a class="controls" data-direction="forward"><i class="icon-chevron-right"></i></a>
        <div class="banner-timer"></div>
    </div>
</script>
<?php endif;?>
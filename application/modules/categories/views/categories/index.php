<?php if(count($products) == 0):?>

    <h2 style="margin:50px 0px; text-align:center; line-height:30px;">
        <?php echo lang('no_products');?>
    </h2>

<?php else:?>

    <div class="category-items element">
    <?php foreach($products as $product):?>
        <div class="col" data-l-cols="1/4" data-m-cols="1/2" data-s-cols="1">
            <?php
            $product->images = array_values($product->images);
            $photo  = theme_img('no_picture.png');

            if(!empty($product->images[0]))
            {
                $primary    = $product->images[0];
                foreach($product->images as $photo)
                {
                    if(isset($photo['primary']))
                    {
                        $primary    = $photo;
                    }
                }

                $photo  = base_url('uploads/images/medium/'.$primary['filename']);
            }
            ?>
            <div onclick="window.location = '<?php echo site_url('/product/'.$product->slug); ?>'" class="category-item" >
                <?php if((bool)$product->track_stock && $product->quantity < 1 && config_item('inventory_enabled')):?>
                    <div class="category-item-note yellow"><?php echo lang('out_of_stock');?></div>
                <?php elseif($product->saleprice > 0):?>
                    <div class="category-item-note red"><?php echo lang('on_sale');?></div>
                <?php endif;?>
                
                <div class="previewImg"><img src="<?php echo $photo;?>"></div>

                <div class="category-item-details">
                    <?php echo $product->name;?>
                </div>

                <div class="categoryItemHover">
                    <div class="look">
                        <i class="icon-search"></i>
                    </div>
                    <div class="price">
                        <?php echo ( $product->saleprice>0?format_currency($product->saleprice):format_currency($product->price) );?>
                    </div>
                </div>

            </div>
        </div>
    <?php endforeach;?>
    </div>

<?php endif;?>
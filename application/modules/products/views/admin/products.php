<?php
//set "code" for searches
if(!empty($term)):
    $term = json_decode($term);
    if(!empty($term->term) || !empty($term->category_id)):?>
        <div class="alert alert-info">
            <?php echo sprintf(lang('search_returned'), intval($total));?>
        </div>
    <?php endif;?>
<?php endif;?>

<script type="text/javascript">
function areyousure()
{
    return confirm('<?php echo lang('confirm_delete_product');?>');
}
</script>
<style type="text/css">
    .pagination {
        margin:0px;
        margin-top:-3px;
    }
</style>
<div class="row">
    <div class="span12" style="border-bottom:1px solid #f5f5f5;">
        <div class="row">
            <div class="span4">
                <?php echo CI::pagination()->create_links();?>  &nbsp;
            </div>
            <div class="span8">
                <?php echo form_open('admin/products', 'class="form-inline" style="float:right"');?>
                    <fieldset>
                        <?php
                        
                        function list_categories($id, $categories, $sub='') {

                            foreach ($categories[$id] as $cat):?>
                            <option class="span2" value="<?php echo $cat->id;?>"><?php echo  $sub.$cat->name; ?></option>
                            <?php
                            if (isset($categories[$cat->id]) && sizeof($categories[$cat->id]) > 0)
                            {
                                $sub2 = str_replace('&rarr;&nbsp;', '&nbsp;', $sub);
                                $sub2 .=  '&nbsp;&nbsp;&nbsp;&rarr;&nbsp;';
                                list_categories($cat->id, $categories, $sub2);
                            }
                            endforeach;
                        }
                        
                        if(!empty($categories))
                        {
                            echo '<select name="category_id">';
                            echo '<option value="">'.lang('filter_by_category').'</option>';
                            list_categories(0, $categories);
                            echo '</select>';
                            
                        }?>
                        
                        <input type="text" class="span2" name="term" placeholder="<?php echo lang('search_term');?>" /> 
                        <button class="btn" name="submit" value="search"><?php echo lang('search')?></button>
                        <a class="btn" href="<?php echo site_url('admin/products');?>">Reset</a>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>

<?php echo form_open('admin/products/bulk_save', array('id'=>'bulk_form'));?>
    <table class="table table-striped">
        <thead>
            <tr>
                <?php
                    foreach(['sku', 'name', 'price', 'saleprice', 'quantity', 'enabled'] as $thead)
                    {
                        echo '<th>';

                        $uristring = 'admin/products/'.$rows.'/'.$thead.'/';
                        $icon = '';
                        if ($order_by == $thead)
                        {
                            if($sort_order == 'asc')
                            {
                                $uristring .= 'desc/';
                                $icon   = ' <i class="icon-chevron-up"></i>';
                            }
                            else
                            {
                                $uristring .= 'asc/';
                                $icon   = ' <i class="icon-chevron-down"></i>';
                            }
                        }
                        else
                        {
                            $uristring .='asc/';
                        }

                        echo '<a href="'.site_url($uristring.$code.'/'.$page).'">'.lang($thead).$icon.'</a></th>';
                    }
                ?>
                <th>
                    <span class="btn-group pull-right">
                        <button class="btn" href="#"><i class="icon-ok"></i> <?php echo lang('bulk_save');?></button>
                        <a class="btn" style="font-weight:normal;"href="<?php echo site_url('admin/products/form');?>"><i class="icon-plus-sign"></i> <?php echo lang('add_new_product');?></a>
                    </span>
                </th>
            </tr>
        </thead>
        <tbody>
        <?php echo (count($products) < 1)?'<tr><td style="text-align:center;" colspan="7">'.lang('no_products').'</td></tr>':''?>
    <?php foreach ($products as $product):?>
            <tr>
                <td><?php echo form_input(array('name'=>'product['.$product->id.'][sku]','value'=>form_decode($product->sku), 'class'=>'span1'));?></td>
                <td><?php echo form_input(array('name'=>'product['.$product->id.'][name]','value'=>form_decode($product->name), 'class'=>'span2'));?></td>
                <td><?php echo form_input(array('name'=>'product['.$product->id.'][price]', 'value'=>set_value('price', $product->price), 'class'=>'span1'));?></td>
                <td><?php echo form_input(array('name'=>'product['.$product->id.'][saleprice]', 'value'=>set_value('saleprice', $product->saleprice), 'class'=>'span1'));?></td>
                <td><?php echo ((bool)$product->track_stock)?form_input(array('name'=>'product['.$product->id.'][quantity]', 'value'=>set_value('quantity', $product->quantity), 'class'=>'span1')):'N/A';?></td>
                <td>
                    <?php
                        $options = array(
                              '1'   => lang('enabled'),
                              '0'   => lang('disabled')
                            );

                        echo form_dropdown('product['.$product->id.'][enabled]', $options, set_value('enabled',$product->enabled), 'class="span2"');
                    ?>
                </td>
                <td>
                    <span class="btn-group pull-right">
                        <a class="btn" href="<?php echo site_url('admin/products/form/'.$product->id);?>"><i class="icon-pencil"></i>  <?php echo lang('edit');?></a>
                        <a class="btn" href="<?php echo site_url('admin/products/form/'.$product->id.'/1');?>"><i class="icon-share-alt"></i> <?php echo lang('copy');?></a>
                        <a class="btn btn-danger" href="<?php echo site_url('admin/products/delete/'.$product->id);?>" onclick="return areyousure();"><i class="icon-trash icon-white"></i> <?php echo lang('delete');?></a>
                    </span>
                </td>
            </tr>
    <?php endforeach; ?>
        </tbody>
    </table>
</form>
<?php $GLOBALS['option_value_count'] = 0;?>
<style type="text/css">
	.sortable { list-style-type: none; margin: 0; padding: 0; width: 100%; }
	.sortable li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; height: 18px; }
	.sortable li>span { position: absolute; margin-left: -1.3em; margin-top:.4em; }
</style>

<script type="text/javascript">
//<![CDATA[

$(document).ready(function() {
	$(".sortable").sortable();
	$(".sortable > span").disableSelection();
	//if the image already exists (phpcheck) enable the selector

	<?php if($id) : ?>
	//options related
	var ct	= $('#option_list').children().size();
	// set initial count
	option_count = <?php echo count($ProductOptions); ?>;
	<?php endif; ?>

	photos_sortable();
});

function addProduct_image(data)
{
	p	= data.split('.');
	
	var photo = '<?php add_image("'+p[0]+'", "'+p[0]+'.'+p[1]+'", '', '', '', base_url('uploads/images/thumbnails'));?>';
	$('#gc_photos').append(photo);
	$('#gc_photos').sortable('destroy');
	photos_sortable();
}

function remove_image(img)
{
	if(confirm('<?php echo lang('confirm_remove_image');?>'))
	{
		var id	= img.attr('rel');
		$('#gc_photo_'+id).remove();
	}
}

function photos_sortable()
{
	$('#gc_photos').sortable({	
		handle : '.gc_thumbnail',
		items: '.gc_photo',
		axis: 'y',
		scroll: true
	});
}

function remove_option(id)
{
	if(confirm('<?php echo lang('confirm_remove_option');?>'))
	{
		$('#option-'+id).remove();
	}
}

//]]>
</script>


<?php echo form_open('admin/products/form/'.$id ); ?>
<div class="row">
	<div class="span8">
		<div class="tabbable">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#product_info" data-toggle="tab"><?php echo lang('details');?></a></li>
				<?php //if there aren't any files uploaded don't offer the client the tab
				if (count($file_list) > 0):?>
				<li><a href="#product_downloads" data-toggle="tab"><?php echo lang('digital_content');?></a></li>
				<?php endif;?>
				<li><a href="#product_categories" data-toggle="tab"><?php echo lang('categories');?></a></li>
				<li><a href="#ProductOptions" data-toggle="tab"><?php echo lang('options');?></a></li>
				<li><a href="#product_related" data-toggle="tab"><?php echo lang('related_products');?></a></li>
				<li><a href="#product_photos" data-toggle="tab"><?php echo lang('images');?></a></li>
				<?php if(config_item('google_product_fields')): ?>
				<li><a href="#google_products" data-toggle="tab"><?php echo lang('google_products');?></a></li>
				<?php endif; ?>
			</ul>
		</div>
		<div class="tab-content">
			<div class="tab-pane active" id="product_info">
				<div class="row">
					<div class="span8">
						<?php
						$data	= array('placeholder'=>lang('name'), 'name'=>'name', 'value'=>set_value('name', $name), 'class'=>'span8');
						echo form_input($data);
						?>
					</div>
				</div>
				
				<div class="row">
					<div class="span8">
						
						<?php
						$data	= array('name'=>'description', 'class'=>'redactor', 'value'=>set_value('description', $description));
						echo form_textarea($data);
						?>
						
					</div>
				</div>
				
				<div class="row">
					<div class="span8">
						<label><?php echo lang('excerpt');?></label>
						<?php
						$data	= array('name'=>'excerpt', 'value'=>set_value('excerpt', $excerpt), 'class'=>'span8', 'rows'=>5);
						echo form_textarea($data);
						?>
					</div>
				</div>
				
				<div class="row">
					<div class="span8">
						<fieldset>
							<legend><?php echo lang('inventory');?></legend>
							<div class="row" style="padding-top:10px;">
								<div class="span3">
									<label for="track_stock"><?php echo lang('track_stock');?> </label>
									<?php
								 	$options = array(	 '1'	=> lang('yes')
														,'0'	=> lang('no')
														);
									echo form_dropdown('track_stock', $options, set_value('track_stock',$track_stock), 'class="span3"');
									?>
								</div>
								<div class="span3">
									<label for="fixed_quantity"><?php echo lang('fixed_quantity');?> </label>
									<?php
								 	$options = array(	 '0'	=> lang('no')
														,'1'	=> lang('yes')
														);
									echo form_dropdown('fixed_quantity', $options, set_value('fixed_quantity',$fixed_quantity), 'class="span3"');
									?>
								</div>
								<div class="span2">
									<label for="quantity"><?php echo lang('quantity');?> </label>
									<?php
									$data	= array('name'=>'quantity', 'value'=>set_value('quantity', $quantity), 'class'=>'span2');
									echo form_input($data);
									?>
								</div>
							</div>
						</fieldset>
					</div>
				</div>
				<div class="row">
					<div class="span8">
						<fieldset>
							<legend><?php echo lang('header_information');?></legend>
							<div class="row" style="padding-top:10px;">
								<div class="span8">
									
									<label for="slug"><?php echo lang('slug');?> </label>
									<?php
									$data	= array('name'=>'slug', 'value'=>set_value('slug', $slug), 'class'=>'span8');
									echo form_input($data);
                                    ?>


									
									<label for="seo_title"><?php echo lang('seo_title');?> </label>
									<?php
									$data	= array('name'=>'seo_title', 'value'=>set_value('seo_title', $seo_title), 'class'=>'span8');
									echo form_input($data);
									?>

									<label for="meta"><?php echo lang('meta');?> <i><?php echo lang('meta_example');?></i></label> 
									<?php
									$data	= array('name'=>'meta', 'value'=>set_value('meta', html_entity_decode($meta)), 'class'=>'span8');
									echo form_textarea($data);
									?>
								</div>
							</div>
						</fieldset>
					</div>
				</div>
			</div>
			
			<div class="tab-pane" id="product_downloads">
				<div class="alert alert-info">
					<?php echo lang('digital_products_desc'); ?>
				</div>
				<fieldset>
					<table class="table table-striped">
						<thead>
							<tr>
								<th><?php echo lang('filename');?></th>
								<th><?php echo lang('title');?></th>
								<th style="width:70px;"><?php echo lang('size');?></th>
								<th style="width:16px;"></th>
							</tr>
						</thead>
						<tbody>
						<?php echo (count($file_list) < 1)?'<tr><td style="text-align:center;" colspan="6">'.lang('no_files').'</td></tr>':''?>
						<?php foreach ($file_list as $file):?>
							<tr>
								<td><?php echo $file->filename ?></td>
								<td><?php echo $file->title ?></td>
								<td><?php echo $file->size ?></td>
								<td><?php echo form_checkbox('downloads[]', $file->id, in_array($file->id, $product_files)); ?></td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				</fieldset>
			</div>
			
			<div class="tab-pane" id="product_categories">
				<div class="row">
					<div class="span8">
						<?php if(isset($categories[0])):?>
							<label><strong><?php echo lang('select_a_category');?></strong></label>
							<table class="table table-striped">
							    <thead>
									<tr>
										<th><?php echo lang('name')?></th>
                                        <th><?php echo lang('enabled')?></th>
                                        <th><?php echo 'Primary category'; ?></th>
									</tr>
								</thead>
							<?php
							function list_categories($parent_id, $cats, $sub='', $product_categories, $primary_category) {
			
								foreach ($cats[$parent_id] as $cat):?>
								<tr>
									<td><?php echo  $sub.$cat->name; ?></td>
									<td>
										<input type="checkbox" name="categories[]" value="<?php echo $cat->id;?>" <?php echo(in_array($cat->id, $product_categories))?'checked="checked"':'';?>/>
									</td>
                                    <td>
                                        <input type="radio" name="primary_category" value="<?php echo $cat->id;?>" <?php echo ($primary_category == $cat->id)?'checked="checked"':'';?>/>
                                    </td>
								</tr>
								<?php
								if (isset($cats[$cat->id]) && sizeof($cats[$cat->id]) > 0)
								{
									$sub2 = str_replace('&rarr;&nbsp;', '&nbsp;', $sub);
										$sub2 .=  '&nbsp;&nbsp;&nbsp;&rarr;&nbsp;';
									list_categories($cat->id, $cats, $sub2, $product_categories, $primary_category);
								}
								endforeach;
							}
						
						
							list_categories(0, $categories, '', $product_categories, $primary_category);
						
							?>

						</table>
					<?php else:?>
						<div class="alert"><?php echo lang('no_available_categories');?></div>
					<?php endif;?>
					</div>
				</div>
			</div>
			
			<div class="tab-pane" id="ProductOptions">
				<div class="row">
					<div class="span8">
						<div class="pull-right" style="padding:0px 0px 10px 0px;">
							<select id="option_options" style="margin:0px;">
								<option value=""><?php echo lang('select_option_type')?></option>
								<option value="checklist"><?php echo lang('checklist');?></option>
								<option value="radiolist"><?php echo lang('radiolist');?></option>
								<option value="droplist"><?php echo lang('droplist');?></option>
								<option value="textfield"><?php echo lang('textfield');?></option>
								<option value="textarea"><?php echo lang('textarea');?></option>
							</select>
							<input id="add_option" class="btn" type="button" value="<?php echo lang('add_option');?>" style="margin:0px;"/>
						</div>
					</div>
				</div>
				
				<script type="text/javascript">
				
				$( "#add_option" ).click(function(){
					if($('#option_options').val() != '')
					{
						add_option($('#option_options').val());
						$('#option_options').val('');
					}
				});
				
				function add_option(type)
				{
					//increase option_count by 1
					option_count++;
					
					<?php
					$value			= array(array('name'=>'', 'value'=>'', 'weight'=>'', 'price'=>'', 'limit'=>''));
					$js_textfield	= (object)array('name'=>'', 'type'=>'textfield', 'required'=>false, 'values'=>$value);
					$js_textarea	= (object)array('name'=>'', 'type'=>'textarea', 'required'=>false, 'values'=>$value);
					$js_radiolist	= (object)array('name'=>'', 'type'=>'radiolist', 'required'=>false, 'values'=>$value);
					$js_checklist	= (object)array('name'=>'', 'type'=>'checklist', 'required'=>false, 'values'=>$value);
					$js_droplist	= (object)array('name'=>'', 'type'=>'droplist', 'required'=>false, 'values'=>$value);
					?>
					if(type == 'textfield')
					{
						$('#options_container').append('<?php add_option($js_textfield, "'+option_count+'");?>');
					}
					else if(type == 'textarea')
					{
						$('#options_container').append('<?php add_option($js_textarea, "'+option_count+'");?>');
					}
					else if(type == 'radiolist')
					{
						$('#options_container').append('<?php add_option($js_radiolist, "'+option_count+'");?>');
					}
					else if(type == 'checklist')
					{
						$('#options_container').append('<?php add_option($js_checklist, "'+option_count+'");?>');
					}
					else if(type == 'droplist')
					{
						$('#options_container').append('<?php add_option($js_droplist, "'+option_count+'");?>');
					}
				}
				
				function add_option_value(option)
				{
					
					option_value_count++;
					<?php
					$js_po	= (object)array('type'=>'radiolist');
					$value	= (object)array('name'=>'', 'value'=>'', 'weight'=>'', 'price'=>'');
					?>
					$('#option-items-'+option).append('<?php add_option_value($js_po, "'+option+'", "'+option_value_count+'", $value);?>');
				}
				
				$(document).ready(function(){
					$('body').on('click', '.option_title', function(){
						$($(this).attr('href')).slideToggle();
						return false;
					});
					
					$('body').on('click', '.delete-option-value', function(){
						if(confirm('<?php echo lang('confirm_remove_value');?>'))
						{
							$(this).closest('.option-values-form').remove();
						}
					});
					
					
					
					$('#options_container').sortable({
						axis: "y",
						items:'tr',
						handle:'.handle',
						forceHelperSize: true,
						forcePlaceholderSize: true
					});
					
					$('.option-items').sortable({
						axis: "y",
						handle:'.handle',
						forceHelperSize: true,
						forcePlaceholderSize: true
					});
				});
				</script>
				<style type="text/css">
					.option-form {
						display:none;
						margin-top:10px;
					}
					.option-values-form
					{
						background-color:#fff;
						padding:6px 3px 6px 6px;
						-webkit-border-radius: 3px;
						-moz-border-radius: 3px;
						border-radius: 3px;
						margin-bottom:5px;
						border:1px solid #ddd;
					}
					
					.option-values-form input {
						margin:0px;
					}
					.option-values-form a {
						margin-top:3px;
					}
				</style>
				<div class="row">
					<div class="span8">
						<table class="table table-striped"  id="options_container">
							<?php
							$counter	= 0;
							if(!empty($ProductOptions))
							
							{
								foreach($ProductOptions as $po)
								{
									$po	= (object)$po;
									if(empty($po->required)){$po->required = false;}

									add_option($po, $counter);
									$counter++;
								}
							}?>
								
						</table>
					</div>
				</div>
			</div>

			<div class="tab-pane" id="product_related">
				<div class="row">
					<div class="span8">
						<label><strong><?php echo lang('select_a_product');?></strong></label>
					</div>
				</div>
				<div class="row">
					<div class="span2" style="text-align:center">
						<div class="row">
							<div class="span2">
								<input class="span2" type="text" id="product_search" />
								<script type="text/javascript">
								$('#product_search').keyup(function(){
									$('#product_list').html('');
									run_product_query();
								});
						
								function run_product_query()
								{
									$.post("<?php echo site_url('admin/products/product_autocomplete/');?>", { name: $('#product_search').val(), limit:10},
										function(data) {
									
											$('#product_list').html('');
									
											$.each(data, function(index, value){
									
												if($('#related_product_'+index).length == 0)
												{
													$('#product_list').append('<option id="product_item_'+index+'" value="'+index+'">'+value+'</option>');
												}
											});
									
									}, 'json');
								}
								</script>
							</div>
						</div>
						<div class="row">
							<div class="span2">
								<select class="span2" id="product_list" size="5" style="margin:0px;"></select>
							</div>
						</div>
						<div class="row">
							<div class="span2" style="margin-top:8px;">
								<a href="#" onclick="add_related_product();return false;" class="btn" title="Add Related Product"><?php echo lang('add_related_product');?></a>
							</div>
						</div>
					</div>
					<div class="span6">
						<table class="table table-striped" style="margin-top:10px;">
							<tbody id="product_items_container">
							<?php
							foreach($related_products as $rel)
							{
								echo related_items($rel->id, $rel->name);
							}
							?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			
			<div class="tab-pane" id="product_photos">
				<div class="row">
					<iframe id="iframe_uploader" src="<?php echo site_url('admin/products/product_image_form');?>" class="span8" style="height:75px; border:0px;"></iframe>
				</div>
				<div class="row">
					<div class="span8">
						
						<div id="gc_photos">
							
						<?php
						foreach($images as $photo_id=>$photo_obj)
						{
							if(!empty($photo_obj))
							{
								$photo = (array)$photo_obj;
								add_image($photo_id, $photo['filename'], $photo['alt'], $photo['caption'], isset($photo['primary']));
							}

						}
						?>
						</div>
					</div>
				</div>
			</div>
			<?php if(config_item('google_product_fields')): ?>
			<div class="tab-pane" id="google_products">
				<div class="row">
					<div class="span8">
						<label><strong><?php echo 'Google taxonomy category';?></strong></label>
						<?php
							$source_url = 'http://www.google.com/basepages/producttype/taxonomy.en-US.txt';
							function get_data_nodes($taxonomy_file, $getTopLevel = false) {
								   
									$str = file_get_contents($taxonomy_file);
								   
									$str = preg_replace('/^#.*\\n/m', '', $str);
									$str = str_replace("\r", '', $str);
								   
									$data = [];
									$data = explode("\n",$str);            
									$data_nodes = [];
									$delimiter = ' > ';  
									$key = 0;
									$max_deep = 0;
								
									foreach($data as $k => $value) {
								   
									if (!empty($value)) {
										   
											$key++;             
										   
											$row = explode($delimiter,$value);
										   
											$count_row = count($row);
										   
											$max_deep = ($max_deep<$count_row)?$count_row:$max_deep;
										   
											switch($count_row) {
													case 1: { $parent = 0; $buffer0 = $key; } break;
													case 2: { $parent = $buffer0; $buffer1 = $key; } break;
													case 3: { $parent = $buffer1; $buffer2 = $key; } break;
													case 4: { $parent = $buffer2; $buffer3 = $key; } break;
													case 5: { $parent = $buffer3; $buffer4 = $key; } break;
													case 6: { $parent = $buffer4; $buffer5 = $key; } break;
													case 7: { $parent = $buffer5; $buffer6 = $key; } break;
													case 8: { $parent = $buffer6; $buffer7 = $key; } break;
													case 9: { $parent = $buffer7; $buffer8 = $key; } break;
													case 10: { $parent = $buffer8; $buffer9 = $key; } break;
											}
										   
											$index = $count_row - 1;
											$category = trim($row[$index]);  
											
											$data_nodes[] = array(
															'key' => $key,
															'parent' => $parent,
															'category' => $category,
															'value' => $value,
															'level' => $count_row,
													);										
									}
								   
									}
								   
									return array($max_deep,$data_nodes);
							 
							}
							 
							function get_sub_nodes($array_data) {
								   
									$outputArray = [];
									$nodeRefs = array(0 => &$outputArray);
								   
									foreach ($array_data as $element) {
								   
											$parent = &$nodeRefs[$element['parent']];             					   
											$parent[$element['key']] = array(
													'key' => $element['key'],
													'category' => $element['category'],
													'value' => $element['value'],
													'parent' => $element['parent'],
													'level' => $element['level'],
													'sub' => []
											);				
											$nodeRefs[$element['key']] = &$parent[$element['key']]['sub'];  
									}	
									 return $outputArray;
							}
							$data_result = get_data_nodes($source_url);
							$data_nodes = $data_result[1];
							$data_sub_nodes = get_sub_nodes($data_nodes);
						 ?>		
						<select id="parentCateg" class="categories" onchange="getChilds(this.value, 1)">
						</select>
						<select style="display:none;" class="categories" onchange="getChilds(this.value, 2)" id="subCateg1">
						</select>
						<select style="display:none;" class="categories" onchange="getChilds(this.value, 3)" id="subCateg2">
						</select>
						<select style="display:none;" class="categories" onchange="getChilds(this.value, 4)" id="subCateg3">
						</select>
						<select style="display:none;" class="categories" onchange="getChilds(this.value, 5)" id="subCateg4">
						</select>
						<select style="display:none;" class="categories" onchange="getChilds(this.value, 6)" id="subCateg5">
						</select>
						<select style="display:none;" class="categories" onchange="getChilds(this.value, 7)" id="subCateg6">
						</select>
							<script type="text/javascript">
							
							function getChilds(parentID, Counter)
							{
							var categories = '';
							var dataCategories = <?php echo json_encode($data_sub_nodes);  ?>;

							if(parentID)
							{
								switch (Counter)
								{
								case 1: categories  = dataCategories[parentID].sub;  break;
								case 2: categories  = dataCategories[$('#parentCateg').val()].sub[parentID].sub;  break;
								case 3: categories  = dataCategories[$('#parentCateg').val()].sub[$('#subCateg1').val()].sub[parentID].sub;  break;
								case 4: categories  = dataCategories[$('#parentCateg').val()].sub[$('#subCateg1').val()].sub[$('#subCateg2').val()].sub[parentID].sub;  break;
								case 5: categories  = dataCategories[$('#parentCateg').val()].sub[$('#subCateg1').val()].sub[$('#subCateg2').val()].sub[$('#subCateg3').val()].sub[parentID].sub;  break;
								case 6: categories  = dataCategories[$('#parentCateg').val()].sub[$('#subCateg1').val()].sub[$('#subCateg2').val()].sub[$('#subCateg3').val()].sub[$('#subCateg4').val()].sub[parentID].sub;  break;
								case 7: categories  = dataCategories[$('#parentCateg').val()].sub[$('#subCateg1').val()].sub[$('#subCateg2').val()].sub[$('#subCateg3').val()].sub[$('#subCateg4').val()].sub[$('#subCateg5').val()].sub[parentID].sub;	  break;
								
								}
								if (jQuery.isEmptyObject(categories) == false)
								{
									switch (Counter)
									{
										case 1: $('#subCateg2').hide(); $('#subCateg2').empty(); $('#subCateg3').hide(); $('#subCateg3').empty(); $('#subCateg4').hide(); $('#subCateg4').empty(); $('#subCateg5').hide(); $('#subCateg5').empty(); $('#subCateg6').hide(); $('#subCateg5').empty(); break;
										case 2: $('#subCateg3').hide(); $('#subCateg3').empty(); $('#subCateg4').hide(); $('#subCateg4').empty(); $('#subCateg5').hide(); $('#subCateg5').empty(); $('#subCateg6').hide(); $('#subCateg6').empty(); break;
										case 3: $('#subCateg4').hide(); $('#subCateg4').empty(); $('#subCateg5').hide(); $('#subCateg5').empty(); $('#subCateg6').hide(); $('#subCateg6').empty(); break;
										case 4: $('#subCateg5').hide(); $('#subCateg5').empty(); $('#subCateg6').hide(); $('#subCateg6').empty(); break;
										case 5: $('#subCateg6').hide(); $('#subCateg6').empty(); break;
									}
									
									$('#subCateg'+Counter).empty();
									$('#subCateg'+Counter).show();
									$.each(categories, function(val, text) {
										$('#subCateg'+Counter).append(
											$('<option></option>').val(val).html(text.category)
										);
									});
								}
								else
								{		
									for ( var i = Counter; i <= 7; i++ ) 
									{
										$('#subCateg'+i).hide();
									}
								
								}	
							}
								$.each(dataCategories, function(val, text) {					
									$('#parentCateg').append(
												$('<option></option>').val(val).html(text.category)
									);
								});
								var categoryString = '';
								categoryString = $('#parentCateg :selected').text()+' > '+ ( ($('#subCateg1 :selected').text() != '') ? $('#subCateg1 :selected').text()+' > ' : '') + ( ($('#subCateg2 :selected').text() != '') ? $('#subCateg2 :selected').text()+' > ' : '') + ( ($('#subCateg3 :selected').text() != '') ? $('#subCateg3 :selected').text()+' > ' : '') + ( ($('#subCateg4 :selected').text() != '') ? $('#subCateg4 :selected').text()+' > ' : '') + ( ($('#subCateg5 :selected').text() != '') ? $('#subCateg5 :selected').text()+' > ' : '') + ( ($('#subCateg6 :selected').text() != '') ? $('#subCateg6 :selected').text()+' > ' : '');
								$('#google_category').val(categoryString.slice(0,-3));
							}
							getChilds();
							</script>
							<input type="hidden" id="google_category" name="google_feed[category]" value=""/>
							<label for="sale_price_effective_date_start"><?php echo 'Sale price effective date start'; ?></label>	
							<?php	$data	= array('name'=>'google_feed[sale_price_effective_date_start]', 'value'=>set_value('google_feed[sale_price_effective_date_start]', $google_feed->sale_price_effective_date_start), 'class'=>'span4');
								echo form_input($data);
							?>
							<label for="sale_price_effective_date_end"><?php echo 'Sale price effective date end'; ?></label>	
							<?php	$data	= array('name'=>'google_feed[sale_price_effective_date_end]', 'value'=>set_value('google_feed[sale_price_effective_date_end]', $google_feed->sale_price_effective_date_end), 'class'=>'span4');
								echo form_input($data);
							?>
							
							<label for="brand"><?php echo 'Brand'; ?></label>
							<?php	$data	= array('name'=>'google_feed[brand]', 'value'=>set_value('google_feed[brand]', $google_feed->brand), 'class'=>'span4');
								echo form_input($data);
							?>
							
							<label for="gtin"><?php echo 'gtin'; ?></label>
							<?php	$data	= array('name'=>'google_feed[gtin]', 'value'=>set_value('google_feed[gtin]', $google_feed->gtin), 'class'=>'span4');
								echo form_input($data);
							?>

							<label for="mpn"><?php echo 'mpn'; ?></label>
							<?php	$data	= array('name'=>'google_feed[mpn]', 'value'=>set_value('google_feed[mpn]', $google_feed->mpn), 'class'=>'span4');
								echo form_input($data);
							?>
							
							<label for="identifier_exists"><?php echo 'identifier_exists'; ?></label>
							<?php echo form_dropdown('google_feed[identifier_exists]', ['TRUE', 'FALSE'], $google_feed->identifier_exists); ?>
							
							<label for="gender"><?php echo 'Gender'; ?></label>
							<?php echo form_dropdown('google_feed[gender]', ['Male', 'Female', 'Unisex'], $google_feed->gender); ?>
							
							<label for="age_group"><?php echo 'Age group'; ?></label>
							<?php	$data	= array('name'=>'google_feed[age_group]', 'value'=>set_value('google_feed[age_group]', $google_feed->age_group), 'class'=>'span4');
								echo form_input($data);
							?>
							
							<label for="color"><?php echo 'Color'; ?></label>
							<?php	$data	= array('name'=>'google_feed[color]', 'value'=>set_value('google_feed[color]', $google_feed->color), 'class'=>'span4');
								echo form_input($data);
							?>
							
							<label for="size"><?php echo 'Size'; ?></label>
							<?php	$data	= array('name'=>'google_feed[size]', 'value'=>set_value('google_feed[size]', $google_feed->size), 'class'=>'span4');
								echo form_input($data);
							?>
							
							<label for="size_type"><?php echo 'Size Type'; ?></label>
							<?php	$data	= array('name'=>'google_feed[size_type]', 'value'=>set_value('google_feed[size_type]', $google_feed->size_type), 'class'=>'span4');
								echo form_input($data);
							?>
							
							<label for="size_system"><?php echo 'Size System'; ?></label>
							<?php	$data	= array('name'=>'google_feed[size_system]', 'value'=>set_value('google_feed[size_system]', $google_feed->size_system), 'class'=>'span4');
								echo form_input($data);
							?>
							
							<label for="material"><?php echo 'Material'; ?></label>
							<?php	$data	= array('name'=>'google_feed[material]', 'value'=>set_value('google_feed[material]', $google_feed->material), 'class'=>'span4');
								echo form_input($data);
							?>
							
							<label for="pattern"><?php echo 'Pattern'; ?></label>
							<?php	$data	= array('name'=>'google_feed[pattern]', 'value'=>set_value('google_feed[pattern]', $google_feed->pattern), 'class'=>'span4');
								echo form_input($data);
							?>
							
							<label for="shipping_weight"><?php echo 'Shipping Weight'; ?></label>
							<?php	$data	= array('name'=>'google_feed[shipping_weight_number]', 'value'=>set_value('google_feed[shipping_weight_number]', $google_feed->shipping_weight_number), 'class'=>'span4');
								echo form_input($data);
							?>
							<?php	$data	= array('name'=>'google_feed[shipping_weight_unit]', 'value'=>set_value('google_feed[shipping_weight_unit]', $google_feed->shipping_weight_unit), 'class'=>'span2');
								echo form_input($data);
							?>

							
							<label for="multipack"><?php echo 'Multipack'; ?></label>
							<?php	$data	= array('name'=>'google_feed[multipack]', 'value'=>set_value('google_feed[multipack]', $google_feed->multipack), 'class'=>'span4');
								echo form_input($data);
							?>
							
							<label for="is_bundle"><?php echo 'Is bundle ?'; ?></label>
							<?php echo form_dropdown('google_feed[is_bundle]', ['TRUE', 'FALSE'], $google_feed->is_bundle); ?>
							
							<label for="adult"><?php echo 'Is Adult ?'; ?></label>
							<?php echo form_dropdown('google_feed[adult]', ['TRUE', 'FALSE'], $google_feed->adult); ?>
							
							<label for="adwords_grouping"><?php echo 'Adwords grouping'; ?></label>
							<?php	$data	= array('name'=>'google_feed[adwords_grouping]', 'value'=>set_value('google_feed[adwords_grouping]', $google_feed->adwords_grouping), 'class'=>'span4');
								echo form_input($data);
							?>
							
							<label for="adwords_labels"><?php echo 'Adwords labels'; ?></label>
							<?php	$data	= array('name'=>'google_feed[adwords_labels]', 'value'=>set_value('google_feed[adwords_labels]', $google_feed->adwords_labels), 'class'=>'span4');
								echo form_input($data);
							?>
							
							<label for="adwords_redirect"><?php echo 'Adwords redirect'; ?></label>
							<?php	$data	= array('name'=>'google_feed[adwords_redirect]', 'value'=>set_value('google_feed[adwords_redirect]', $google_feed->adwords_redirect), 'class'=>'span4');
								echo form_input($data);
							?>
							
							<label for="custom_label_0"><?php echo 'Custom label 0'; ?></label>
							<?php	$data	= array('name'=>'google_feed[custom_label_0]', 'value'=>set_value('google_feed[custom_label_0]', $google_feed->custom_label_0), 'class'=>'span4');
								echo form_input($data);
							?>
							
							<label for="custom_label_1"><?php echo 'Custom label 1'; ?></label>
							<?php	$data	= array('name'=>'google_feed[custom_label_1]', 'value'=>set_value('google_feed[custom_label_1]', $google_feed->custom_label_1), 'class'=>'span4');
								echo form_input($data);
							?>
							
							<label for="custom_label_2"><?php echo 'Custom label 2'; ?></label>
							<?php	$data	= array('name'=>'google_feed[custom_label_2]', 'value'=>set_value('google_feed[custom_label_2]', $google_feed->custom_label_2), 'class'=>'span4');
								echo form_input($data);
							?>
							
							<label for="custom_label_3"><?php echo 'Custom label 3'; ?></label>
							<?php	$data	= array('name'=>'google_feed[custom_label_3]', 'value'=>set_value('google_feed[custom_label_3]', $google_feed->custom_label_3), 'class'=>'span4');
								echo form_input($data);
							?>
							
							<label for="custom_label_4"><?php echo 'Custom label 4'; ?></label>
							<?php	$data	= array('name'=>'google_feed[custom_label_4]', 'value'=>set_value('google_feed[custom_label_4]', $google_feed->custom_label_4), 'class'=>'span4');
								echo form_input($data);
							?>
							
							<label for="expiration_date"><?php echo 'Expiration date'; ?></label>
							<?php	$data	= array('name'=>'google_feed[expiration_date]', 'value'=>set_value('google_feed[expiration_date]', $google_feed->expiration_date), 'class'=>'span4');
								echo form_input($data);
							?>
							
							<label for="unit_pricing_measure"><?php echo 'Unit pricing measure'; ?></label>
							<?php	$data	= array('name'=>'google_feed[unit_pricing_measure_number]', 'value'=>set_value('google_feed[unit_pricing_measure_number]', $google_feed->unit_pricing_measure_number), 'class'=>'span4');
								echo form_input($data);
							?>
							<?php echo form_dropdown('google_feed[unit_pricing_measure_unit]', ['mg', 'g', 'kg', 'ml', 'cl', 'l', 'cbm', 'cm', 'm', 'sqm'], $google_feed->unit_pricing_measure_unit); ?>
							
							<label for="unit_pricing_base_measure"><?php echo 'Unit pricing base measure'; ?></label>
							<input type="text" name="unit_pricing_base_measure_number" id="unit_pricing_base_measure_number">
							<?php echo form_dropdown('google_feed[unit_pricing_base_measure_unit]', ['mg', 'g', 'kg', 'ml', 'cl', 'l', 'cbm', 'cm', 'm', 'sqm'], $google_feed->unit_pricing_base_measure_unit); ?>
							
							<label for="energy_efficiency_class"><?php echo 'Energy efficiency class'; ?></label>
							<?php	$data	= array('name'=>'google_feed[energy_efficiency_class]', 'value'=>set_value('google_feed[energy_efficiency_class]', $google_feed->energy_efficiency_class), 'class'=>'span4');
								echo form_input($data);
							?>
							
							<h4<?php echo 'Loyalty points'; ?></h4>
							<label>Program name</label> <?php	$data	= array('name'=>'google_feed[loyalty_points_program]', 'value'=>set_value('google_feed[loyalty_points_program]', $google_feed->loyalty_points_program), 'class'=>'span3');
								echo form_input($data);
							?>
							<label>Points</label> <?php	$data	= array('name'=>'google_feed[loyalty_points_program_points]', 'value'=>set_value('google_feed[loyalty_points_program_points]', $google_feed->loyalty_points_program_points), 'class'=>'span3');
								echo form_input($data);
							?>
							<label>Ratio</label> <?php	$data	= array('name'=>'google_feed[loyalty_points_ratio]', 'value'=>set_value('google_feed[loyalty_points_ratio]', $google_feed->loyalty_points_ratio), 'class'=>'span3');
								echo form_input($data);
							?>
							
							<h4><?php echo 'Installment'; ?></h4>
							<label>Months</label> <?php	$data	= array('name'=>'google_feed[installment_months]', 'value'=>set_value('google_feed[installment_months]', $google_feed->installment_months), 'class'=>'span3');
								echo form_input($data);
							?>
							<label>Amount</label> <?php	$data	= array('name'=>'google_feed[installment_amount]', 'value'=>set_value('google_feed[installment_amount]', $google_feed->installment_amount), 'class'=>'span3');
								echo form_input($data);
							?>
					</div>	
				
				</div>			
			</div>
			<?php endif; ?>
		</div>
	</div>
	<div class="span4">
		<?php
	 	$options = array(	 '0'	=> lang('disabled')
							,'1'	=> lang('enabled')
							);
		echo form_dropdown('enabled', $options, set_value('enabled',$enabled), 'class="span4"');
		?>
		
		<?php
		$options = array(	 '1'	=> lang('shippable')
							,'0'	=> lang('not_shippable')
							);
		echo form_dropdown('shippable', $options, set_value('shippable',$shippable), 'class="span4"');
		?>
		
		<?php
		$options = array(	 '1'	=> lang('taxable')
							,'0'	=> lang('not_taxable')
							);
		echo form_dropdown('taxable', $options, set_value('taxable',$taxable), 'class="span4"');
		?>
		
		<label for="sku"><?php echo lang('sku');?></label>
		<?php
		$data	= array('name'=>'sku', 'value'=>set_value('sku', $sku), 'class'=>'span4');
		echo form_input($data);?>
		
		<label for="weight"><?php echo lang('weight');?> </label>
		<?php
		$data	= array('name'=>'weight', 'value'=>set_value('weight', $weight), 'class'=>'span4');
		echo form_input($data);?>
		
		<label for="price"><?php echo lang('price');?></label>
		<?php
		$data	= array('name'=>'price', 'value'=>set_value('price', $price), 'class'=>'span4');
		echo form_input($data);?>
		
		<label for="saleprice"><?php echo lang('saleprice');?></label>
		<?php
		$data	= array('name'=>'saleprice', 'value'=>set_value('saleprice', $saleprice), 'class'=>'span4');
		echo form_input($data);?>
	</div>
</div>

<div class="form-actions">
	<button type="submit" class="btn btn-primary"><?php echo lang('save');?></button>
</div>
</form>

<?php
function add_image($photo_id, $filename, $alt, $caption, $primary=false)
{

	ob_start();
	?>
	<div class="row gc_photo" id="gc_photo_<?php echo $photo_id;?>" style="background-color:#fff; border-bottom:1px solid #ddd; padding-bottom:20px; margin-bottom:20px;">
		<div class="span2">
			<input type="hidden" name="images[<?php echo $photo_id;?>][filename]" value="<?php echo $filename;?>"/>
			<img class="gc_thumbnail" src="<?php echo base_url('uploads/images/thumbnails/'.$filename);?>" style="padding:5px; border:1px solid #ddd"/>
		</div>
		<div class="span6">
			<div class="row">
				<div class="span2">
					<input name="images[<?php echo $photo_id;?>][alt]" value="<?php echo $alt;?>" class="span2" placeholder="<?php echo lang('alt_tag');?>"/>
				</div>
				<div class="span2">
					<input type="radio" name="primary_image" value="<?php echo $photo_id;?>" <?php if($primary) echo 'checked="checked"';?>/> <?php echo lang('primary');?>
				</div>
				<div class="span2">
					<a onclick="return remove_image($(this));" rel="<?php echo $photo_id;?>" class="btn btn-danger" style="float:right; font-size:9px;"><i class="icon-trash icon-white"></i> <?php echo lang('remove');?></a>
				</div>
			</div>
			<div class="row">
				<div class="span6">
					<label><?php echo lang('caption');?></label>
					<textarea name="images[<?php echo $photo_id;?>][caption]" class="span6" rows="3"><?php echo $caption;?></textarea>
				</div>
			</div>
		</div>
	</div>

	<?php
	$stuff = ob_get_contents();

	ob_end_clean();
	
	echo replace_newline($stuff);
}


function add_option($po, $count)
{
	ob_start();
	?>
	<tr id="option-<?php echo $count;?>">
		<td>
			<a class="handle btn btn-mini"><i class="icon-align-justify"></i></a>
			<strong><a class="option_title" href="#option-form-<?php echo $count;?>"><?php echo $po->type;?> <?php echo (!empty($po->name))?' : '.$po->name:'';?></a></strong>
			<button type="button" class="btn btn-mini btn-danger pull-right" onclick="remove_option(<?php echo $count ?>);"><i class="icon-trash icon-white"></i></button>
			<input type="hidden" name="option[<?php echo $count;?>][type]" value="<?php echo $po->type;?>" />
			<div class="option-form" id="option-form-<?php echo $count;?>">
				<div class="row-fluid">
				
					<div class="span10">
						<input type="text" class="span10" placeholder="<?php echo lang('option_name');?>" name="option[<?php echo $count;?>][name]" value="<?php echo $po->name;?>"/>
					</div>
					
					<div class="span2" style="text-align:right;">
						<input class="checkbox" type="checkbox" name="option[<?php echo $count;?>][required]" value="1" <?php echo ($po->required)?'checked="checked"':'';?>/> <?php echo lang('required');?>
					</div>
				</div>
				<?php if($po->type!='textarea' && $po->type!='textfield'):?>
				<div class="row-fluid">
					<div class="span12">
						<a class="btn" onclick="add_option_value(<?php echo $count;?>);"><?php echo lang('add_item');?></a>
					</div>
				</div>
				<?php endif;?>
				<div style="margin-top:10px;">

					<div class="row-fluid">
						<?php if($po->type!='textarea' && $po->type!='textfield'):?>
						<div class="span1">&nbsp;</div>
						<?php endif;?>
						<div class="span3"><strong>&nbsp;&nbsp;<?php echo lang('name');?></strong></div>
						<div class="span2"><strong>&nbsp;<?php echo lang('value');?></strong></div>
						<div class="span2"><strong>&nbsp;<?php echo lang('weight');?></strong></div>
						<div class="span2"><strong>&nbsp;<?php echo lang('price');?></strong></div>
						<div class="span2"><strong>&nbsp;<?php echo ($po->type=='textfield')?lang('limit'):'';?></strong></div>
					</div>
					<div class="option-items" id="option-items-<?php echo $count;?>">
					<?php if($po->values):?>
						<?php
						foreach($po->values as $value)
						{
							$value = (object)$value;
							add_option_value($po, $count, $GLOBALS['option_value_count'], $value);
							$GLOBALS['option_value_count']++;
						}?>
					<?php endif;?>
					</div>
				</div>
			</div>
		</td>
	</tr>
	
	<?php
	$stuff = ob_get_contents();

	ob_end_clean();
	
	echo replace_newline($stuff);
}

function add_option_value($po, $count, $valcount, $value)
{
	ob_start();
	?>
	<div class="option-values-form">
		<div class="row-fluid">
			<?php if($po->type!='textarea' && $po->type!='textfield'):?><div class="span1"><a class="handle btn btn-mini" style="float:left;"><i class="icon-align-justify"></i></a></div><?php endif;?>
			<div class="span3"><input type="text" class="span12" name="option[<?php echo $count;?>][values][<?php echo $valcount ?>][name]" value="<?php echo $value->name ?>" /></div>
			<div class="span2"><input type="text" class="span12" name="option[<?php echo $count;?>][values][<?php echo $valcount ?>][value]" value="<?php echo $value->value ?>" /></div>
			<div class="span2"><input type="text" class="span12" name="option[<?php echo $count;?>][values][<?php echo $valcount ?>][weight]" value="<?php echo $value->weight ?>" /></div>
			<div class="span2"><input type="text" class="span12" name="option[<?php echo $count;?>][values][<?php echo $valcount ?>][price]" value="<?php echo $value->price ?>" /></div>
			<div class="span2">
			<?php if($po->type=='textfield'):?><input class="span12" type="text" name="option[<?php echo $count;?>][values][<?php echo $valcount ?>][limit]" value="<?php echo $value->limit ?>" />
			<?php elseif($po->type!='textarea' && $po->type!='textfield'):?>
				<a class="delete-option-value btn btn-danger btn-mini pull-right"><i class="icon-trash icon-white"></i></a>
			<?php endif;?>
			</div>
		</div>
	</div>
	<?php
	$stuff = ob_get_contents();

	ob_end_clean();

	echo replace_newline($stuff);
}
//this makes it easy to use the same code for initial generation of the form as well as javascript additions
function replace_newline($string) {
  return trim((string)str_replace(array("\r", "\r\n", "\n", "\t"), ' ', $string));
}
?>
<script type="text/javascript">
//<![CDATA[
var option_count		= <?php echo $counter?>;
var option_value_count	= <?php echo $GLOBALS['option_value_count'];?>

function add_related_product()
{
	//if the related product is not already a related product, add it
	if($('#related_product_'+$('#product_list').val()).length == 0 && $('#product_list').val() != null)
	{
		<?php $new_item	 = str_replace(array("\n", "\t", "\r"),'',related_items("'+$('#product_list').val()+'", "'+$('#product_item_'+$('#product_list').val()).html()+'"));?>
		var related_product = '<?php echo $new_item;?>';
		$('#product_items_container').append(related_product);
		run_product_query();
	}
	else
	{
		if($('#product_list').val() == null)
		{
			alert('<?php echo lang('alert_select_product');?>');
		}
		else
		{
			alert('<?php echo lang('alert_product_related');?>');
		}
	}
}

function remove_related_product(id)
{
	if(confirm('<?php echo lang('confirm_remove_related');?>'))
	{
		$('#related_product_'+id).remove();
		run_product_query();
	}
}

function photos_sortable()
{
	$('#gc_photos').sortable({	
		handle : '.gc_thumbnail',
		items: '.gc_photo',
		axis: 'y',
		scroll: true
	});
}
//]]>
</script>
<style>
.tree > ul > li {
float: left;
width: 50%;
}
</style>
<?php
function related_items($id, $name) {
	return '
			<tr id="related_product_'.$id.'">
				<td>
					<input type="hidden" name="related_products[]" value="'.$id.'"/>
					'.$name.'</td>
				<td>
					<a class="btn btn-danger pull-right btn-mini" href="#" onclick="remove_related_product('.$id.'); return false;"><i class="icon-trash icon-white"></i> '.lang('remove').'</a>
				</td>
			</tr>
		';
}
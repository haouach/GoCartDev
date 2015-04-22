
<script type="text/javascript">
function areyousure()
{
    return confirm('<?php echo lang('confirm_delete_address');?>');
}
</script>

<a class="btn" style="float:right;"href="<?php echo site_url('admin/customers/address_form/'.$customer->id);?>"><i class="icon-plus-sign"></i> <?php echo lang('add_new_address');?></a>
<table class="table">
    <thead>
        <tr>
            <th><?php echo lang('name');?></th>
            <th><?php echo lang('contact');?></th>
            <th><?php echo lang('address');?></th>
            <th><?php echo lang('locality');?></th>
            <th><?php echo lang('country');?></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php echo (count($addresses) < 1)?'<tr><td style="text-align:center;" colspan="6">'.lang('no_addresses').'</td></tr>':''?>
<?php foreach ($addresses as $address):?>
        <tr>
            <td>
                <?php echo $address['lastname']; ?>, <?php echo $address['firstname']; ?>
                <?php echo (!empty($address['company']))?'<br/>'.$address['company']:'';?>
            </td>
            
            <td>
                <?php echo  $address['phone']; ?><br/>
                <a href="mailto:<?php echo  $address['email'];?>"><?php echo  $address['email']; ?></a>
            </td>
            
            <td>
                <?php echo $address['address1'];?>
                <?php echo (!empty($address['address2']))?'<br/>'.$address['address2']:'';?>
            </td>
            
            <td>
                <?php echo $address['city'];?>, <?php echo $address['zone'];?> <?php echo $address['zip'];?> 
            </td>
            
            <td><?php echo $address['country'];?></td>
            
            <td>
                <div class="btn-group" style="float:right">
                
                    <a class="btn" href="<?php echo site_url('admin/customers/address_form/'.$customer->id.'/'.$address['id']);?>"><i class="icon-pencil"></i> <?php echo lang('edit');?></a>
                    
                    <a class="btn btn-danger" href="<?php echo site_url('admin/customers/delete_address/'.$customer->id.'/'.$address['id']);?>" onclick="return areyousure();"><i class="icon-trash icon-white"></i> <?php echo lang('delete');?></a>
                </div>
            </td>
        </tr>
<?php endforeach; ?>
    </tbody>
</table>
<?php if(count($shipping_modules) >0): ?>
    <table class="table table-striped">
        <tbody>
        <?php foreach($shipping_modules as $module): ?>
            <tr>
                <td><?php echo $module['name']; ?></td>
                <td>
                    <span class="btn-group pull-right">
                <?php if(array_key_exists($module['class'], $enabled_modules)): ?>
                    <a class="btn" href="<?php echo site_url('admin/'.$module['key'].'/form/');?>"><i class="icon-wrench"></i> <?php echo lang('settings');?></a>
                    <a class="btn btn-danger" href="<?php echo site_url('admin/'.$module['key'].'/uninstall/');?>" onclick="return areyousure();"><i class="icon-minus"></i> <?php echo lang('uninstall');?></a>
                <?php else: ?>
                    <a class="btn" href="<?php echo site_url('admin/'.$module['key'].'/install/');?>"><i class="icon-ok"></i> <?php echo lang('install');?></a>
                <?php endif; ?>
                    </span>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
</div>
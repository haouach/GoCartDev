<?php if(count($shipping_modules) >0): ?>
	<table class="table table-striped">
		<tbody>
		<?php foreach($shipping_modules as $module=>$enabled): ?>
			<tr>
				<td><?php echo humanize($module); ?></td>
				<td>
					<span class="btn-group pull-right">
				<?php if($enabled): ?>
					<a class="btn" href="<?php echo site_url('admin/shipping/settings/'.$module);?>"><i class="icon-wrench"></i> <?php echo lang('settings');?></a>
					<a class="btn btn-danger" href="<?php echo site_url('admin/shipping/uninstall/'.$module);?>" onclick="return areyousure();"><i class=" icon-minus icon-white"></i> <?php echo lang('uninstall');?></a>
				<?php else: ?>
					<a class="btn" href="<?php echo site_url('admin/shipping/install/'.$module);?>"><i class="icon-ok"></i> <?php echo lang('install');?></a>
				<?php endif; ?>
					</span>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>
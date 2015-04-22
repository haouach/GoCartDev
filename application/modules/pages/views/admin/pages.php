<script type="text/javascript">
function areyousure()
{
    return confirm('<?php echo lang('confirm_delete');?>');
}
</script>
<div class="btn-group pull-right">
    <a class="btn" href="<?php echo site_url('admin/pages/form'); ?>"><i class="icon-plus-sign"></i> <?php echo lang('add_new_page');?></a>
    <a class="btn" href="<?php echo site_url('admin/pages/link_form'); ?>"><i class="icon-plus-sign"></i> <?php echo lang('add_new_link');?></a>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th style="max-width:20px;"></th>
            <th><?php echo lang('title');?></th>
            <th/>
        </tr>
    </thead>
    
    <?php echo (count($pages) < 1)?'<tr><td style="text-align:center;" colspan="2">'.lang('no_pages_or_links').'</td></tr>':''?>
    <?php if($pages):?>
    <tbody>
        
        <?php
        define('ADMIN_FOLDER', config_item('admin_folder'));
        function list_pages($parent_id, $pages, $sub='') {
            
            foreach ($pages[$parent_id] as $page):?>
            <tr>
                <td><?php echo ($page->parent_id == -1)?'<i class="icon-eye-close"></i>':'';?></td>
                <td><?php echo  $sub.$page->title; ?></td>
                <td>
                    <div class="btn-group" style="float:right">
                        <?php if(!empty($page->url)): ?>
                            <a class="btn" href="<?php echo site_url(ADMIN_FOLDER.'/pages/link_form/'.$page->id); ?>"><i class="icon-pencil"></i> <?php echo lang('edit');?></a>
                            <a class="btn" href="<?php echo $page->url;?>" target="_blank"><i class="icon-play-circle"></i> <?php echo lang('follow_link');?></a>
                        <?php else: ?>
                            <a class="btn" href="<?php echo site_url(ADMIN_FOLDER.'/pages/form/'.$page->id); ?>"><i class="icon-pencil"></i> <?php echo lang('edit');?></a>
                            <a class="btn" href="<?php echo site_url('page/'.$page->slug); ?>" target="_blank"><i class="icon-play-circle"></i> <?php echo lang('go_to_page');?></a>
                        <?php endif; ?>
                        <a class="btn btn-danger" href="<?php echo site_url(ADMIN_FOLDER.'/pages/delete/'.$page->id); ?>" onclick="return areyousure();"><i class="icon-trash icon-white"></i> <?php echo lang('delete');?></a>
                    </div>
                </td>
            </tr>
            <?php
            if (isset($pages[$page->id]) && sizeof($pages[$page->id]) > 0)
            {
                $sub2 = str_replace('&rarr;&nbsp;', '&nbsp;', $sub);
                    $sub2 .=  '&nbsp;&nbsp;&nbsp;&rarr;&nbsp;';
                list_pages($page->id, $pages, $sub2);
            }
            endforeach;
        }
        
        if(isset($pages[-1]))
        {
            list_pages(-1, $pages);
        }

        if(isset($pages[0]))
        {
            list_pages(0, $pages);
        }


        
        ?>
    </tbody>
    <?php endif;?>
</table>
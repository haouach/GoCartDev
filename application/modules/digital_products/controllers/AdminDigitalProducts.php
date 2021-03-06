<?php namespace GoCart\Controller;
/**
 * AdminDigitalProducts Class
 *
 * @package     GoCart
 * @subpackage  Controllers
 * @category    AdminDigitalProducts
 * @author      Clear Sky Designs
 * @link        http://gocartdv.com
 */

Class AdminDigitalProducts extends Admin {

    public function __construct()
    {
        parent::__construct();
        \CI::lang()->load('digital_product');
        \CI::load()->model('Digital_Products');
    }
    
    public function index()
    {
        $data['page_title'] = lang('dgtl_pr_header');
        $data['file_list']  = \CI::Digital_Products()->get_list();
        
        $this->view('digital_products', $data);
    }
    
    public function form($id=0)
    {
        \CI::load()->helper('form_helper');
        \CI::load()->library('form_validation');
        \CI::form_validation()->set_error_delimiters('<div class="error">', '</div>');
        
        $data   = [
                    'id' =>'',
                    'filename' =>'',
                    'max_downloads' =>'',
                    'title' =>'',
                    'size' =>''
                  ];
        if($id)
        {
            $data = array_merge($data, (array)\CI::Digital_Products()->get_file_info($id));
        }
        
        $data['page_title'] = lang('digital_products_form');
        
        \CI::form_validation()->set_rules('max_downloads', 'lang:max_downloads', 'numeric');
        \CI::form_validation()->set_rules('title', 'lang:title', 'trim|required');

        
        if (\CI::form_validation()->run() == FALSE)
        {
            $this->view('digital_product_form', $data);
        }
        else
        {
            if($id==0)
            {
                $data['file_name'] = false;
                $data['error']  = false;
                
                $config['allowed_types'] = '*';
                $config['upload_path'] = 'uploads/digital_uploads';//config_item('digital_products_path');
                $config['remove_spaces'] = true;
        
                \CI::load()->library('upload', $config);
                
                if(\CI::upload()->do_upload())
                {
                    $upload_data = \CI::upload()->data();
                } else {
                    $data['error'] = \CI::upload()->display_errors();
                    $this->view('digital_product_form', $data);
                    return;
                }
                
                $save['filename'] = $upload_data['file_name'];
                $save['size'] = $upload_data['file_size'];
            } else {
                $save['id'] = $id;
            }
            
            $save['max_downloads']  = set_value('max_downloads');               
            $save['title']          = set_value('title');
            
            \CI::Digital_Products()->save($save);
            
            redirect('admin/digital_products');
        }
    }
    
    public function delete($id)
    {
        \CI::Digital_Products()->delete($id);
        
        \CI::session()->set_flashdata('message', lang('message_deleted_file'));
        redirect('admin/digital_products');
    }

}
<?php

Class DigitalProducts extends CI_Model {
    
    // Return blank record array
    public function new_file()
    {
        return [
                'id'=>'',
                'filename'=>'',
                'max_downloads'=>'',
                'title'=>'',
                'description'=>'',
                'size'=>''
                ];
    }
    
    // Get files list
    public function get_list()
    {
        $list = CI::db()->get('digital_products')->result();
        
        foreach($list as &$file)
        {
            // identify if the record is missing it's file content
            $file->verified = $this->verify_content($file->filename);
        }
        
        return $list;
    }
    
    // Get file record
    public function get_file_info($id)
    {
        return CI::db()->where('id', $id)->get('digital_products')->row();
    }
    
    // Verify upload path
    public function verify_file_path()
    {
        return is_writable('uploads/digital_products');
    }
    
    // Verify file content
    public function verify_content($filename)
    {
        return file_exists('uploads/digital_products'.'/'.$filename);
    }
    
    // Save/Update file record
    public function save($data)
    {
        if(isset($data['id']))
        {
            CI::db()->where('id', $data['id'])->update('digital_products', $data);
            return $data['id'];
        } else {
            CI::db()->insert('digital_products', $data);
            return CI::db()->insert_id();
        }
    }
    
    // Add product association
    public function associate($file_id, $product_id)
    {
        CI::db()->insert('products_files', ['product_id'=>$product_id, 'file_id'=>$file_id]);
    }
    
    // Remove product association (all or by product)
    public function disassociate($file_id, $product_id=false)
    {
        
        if($product_id)
        {
            $data['product_id'] = $product_id;
        }
        if($file_id)
        {
            $data['file_id']    = $file_id;
        }
        CI::db()->where($data)->delete('products_files');
    }
    
    public function get_associations_by_file($id)
    {
        return CI::db()->where('file_id', $id)->get('products_files')->result();
    }
    
    public function get_associations_by_product($product_id)
    {
        return CI::db()->where('product_id', $product_id)->get('products_files')->result();
    }
    
    // Delete file record & content
    public function delete($id)
    {
        CI::load()->model('Products');
        
        $info = $this->get_file_info($id);
        
        if(!$info)
        {
            return false;
        }
        
        // remove file
        if($this->verify_content($info->filename))
        {
            unlink('uploads/digital_products/'.$info->filename);
        }
        
        // Disable products that are associated with this file
        //  to prevent users purchasing a product with deleted media
        $product_associations  = $this->get_associations_by_file($id);
        foreach($product_associations as $p)
        {
            $save['id']         = $p->product_id;
            $save['enabled']    = 0;
            CI::Products()->save($save);
        }
        
        // Remove db associations
        CI::db()->where('id', $id)->delete('digital_products');
        $this->disassociate($id);
    }
    
    // Accepts an array of file lists for products purchased
    //  and sets up the list of available downloads for the customer
    //  uses customer id if available, also creates a package code
    //  that can be sent to non registered customers
    public function add_download_package($package, $order_id)
    {
        // get customer stuff
        $customer = $this->go_cart->customer();
        if(!empty($customer['id']))
        {
            $new_package['customer_id'] = $customer['id'];
        } else {
            $new_package['customer_id'] = 0;
        }
        
        $new_package['order_id'] = $order_id;
        $new_package['code']    = generate_code();
        
        // save master package record
        CI::db()->insert('download_packages',$new_package);
        
        $package_id = CI::db()->insert_id();
        
        // save the db data here
        $files_list = [];
        
        // use this to prevent inserting duplicates
        // in case a file is shared across products
        $ids = [];
        
        // build files records list
        foreach($package as $product_list)
        {
            foreach($product_list as $f)
            {
                if(!isset($ids[$f->file_id]))
                {
                    $file['package_id'] = $package_id;
                    $file['file_id'] = $f->file_id;
                    $file['link'] = md5($f->file_id . time() . $new_package['customer_id']); // create a unique download key for each file
                    
                    $files_list[] = $file;
                }
            }
        }
        
        CI::db()->insert_batch('download_package_files', $files_list);
        
        // save the master record to include links in the order email
        $this->go_cart->saveOrder_downloads($new_package);
    }
    
    // Retrieve user's download packages
    //  send back an array indexed by order number
    public function get_user_downloads($customer_id)
    {
        $result = CI::db()->where('customer_id', $customer_id)->get('download_packages')->result();
        
        $downloads = [];
        foreach($result as $r)
        {
            $downloads[$r->order_id] = $this->get_package_files($r->id); 
        }
        
        return $downloads;
    }
    
    // Retrieve non-member download by code
    //   format array exactly as by user
    public function get_downloads_by_code($code)
    {
        $row =  CI::db()->where('code', $code)->get('download_packages')->row();
        
        if($row)
        {
            return [$row->order_id => $this->get_package_files($row->id)];
        }
    }
    
    // get the files in a package
    public function get_package_files($package_id)
    {
        
        return CI::db()->select('*')
                        ->from('download_package_files as a')
                        ->join('digital_products as b', 'a.file_id=b.id')
                        ->where('package_id', $package_id)
                        ->get()
                        ->result();
    }
    
    // get file info for download by the link code
    //  increment the download counter
    public function get_file_info_by_link($link)
    {
        
        $record = CI::db()->from('digital_products as a')
                        ->join('download_package_files as b', 'a.id=b.file_id')
                        ->where('link', $link)
                        ->get()
                        ->row();
                        
        return $record;
        
    }
    
    
    public function touch_download($link)
    {
        CI::db()->where('link', $link)->set('downloads','downloads+1', false)->update('download_package_files');
    }
}
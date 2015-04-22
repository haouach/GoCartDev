<?php
Class Customers extends CI_Model
{
    public function createGuest()
    {
        return $this->save([
            'id'=>false,
            'firstname'=>'',
            'lastname'=>'',
            'email'=>'',
            'email_subscribe'=>1,
            'phone'=>'',
            'company'=>'',
            'password'=>'',
            'active'=>1,
            'group_id'=>1,
            'confirmed'=>0,
            'is_guest'=>1,
        ]);
    }

    public function get_customers($limit=0, $offset=0, $order_by='id', $direction='DESC')
    {
        CI::db()->where('is_guest', 0)->order_by($order_by, $direction);
        if($limit>0)
        {
            CI::db()->limit($limit, $offset);
        }

        $result = CI::db()->get('customers');
        return $result->result();
    }
    
    public function count_customers()
    {
        return CI::db()->where('is_guest', 0)->count_all_results('customers');
    }
    
    public function get_customer($id)
    {
        
        $result = CI::db()->get_where('customers', array('id'=>$id));
        return $result->row();
    }
    
    public function get_subscribers()
    {
        CI::db()->where('email_subscribe','1');
        $res = CI::db()->where('is_guest', 0)->get('customers');
        return $res->result_array();
    }
    
    public function get_address_list($id)
    {
        return CI::db()->where('customer_id', $id)->get('customers_address_bank')->result_array();
    }

    public function count_addresses($id)
    {
        return CI::db()->where('customer_id', $id)->from('customers_address_bank')->count_all_results();
    }
    
    public function get_address($address_id)
    {
        return CI::db()->where('id', $address_id)->get('customers_address_bank')->row_array();
    }
    
    public function save_address($data)
    {
        if(!empty($data['id']))
        {
            CI::db()->where('id', $data['id']);
            CI::db()->update('customers_address_bank', $data);
            return $data['id'];
        } else {
            CI::db()->insert('customers_address_bank', $data);
            return CI::db()->insert_id();
        }
    }
    
    public function delete_address($id, $customer_id)
    {
        CI::db()->where(array('id'=>$id, 'customer_id'=>$customer_id))->delete('customers_address_bank');
        return $id;
    }
    
    public function save($customer)
    {
        if($customer['id'])
        {
            CI::db()->where('id', $customer['id']);
            CI::db()->update('customers', $customer);
            return $customer['id'];
        }
        else
        {
            CI::db()->insert('customers', $customer);
            return CI::db()->insert_id();
        }
    }
    
    public function deactivate($id)
    {
        $customer = array('id'=>$id, 'active'=>0);
        $this->save_customer($customer);
    }
    
    public function delete($id)
    {
        /*
        deleting a customer will remove all their orders from the system
        this will alter any report numbers that reflect total sales
        deleting a customer is not recommended, deactivation is preferred
        */
        
        //this deletes the customers record
        CI::db()->where('id', $id);
        CI::db()->delete('customers');
        
        // Delete Address records
        CI::db()->where('customer_id', $id);
        CI::db()->delete('customers_address_bank');
        
        //get all the orders the customer has made and delete the items from them
        CI::db()->select('id');
        $result = CI::db()->get_where('orders', array('customer_id'=>$id));
        $result = $result->result();
        foreach ($result as $order)
        {
            CI::db()->where('order_id', $order->id);
            CI::db()->delete('order_items');
        }
        
        //delete the orders after the items have already been deleted
        CI::db()->where('customer_id', $id);
        CI::db()->delete('orders');
    }
    
    public function check_email($str, $id=false)
    {
        CI::db()->select('email');
        CI::db()->from('customers');
        CI::db()->where('is_guest', 0)->where('email', $str);
        if ($id)
        {
            CI::db()->where('id !=', $id);
        }
        $count = CI::db()->count_all_results();
        
        if ($count > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
 
    public function reset_password($email)
    {
        CI::load()->library('encrypt');
        $customer = $this->get_customer_by_email($email);
        if ($customer)
        {
            CI::load()->helper('string');
            CI::load()->library('email');
            
            $new_password       = random_string('alnum', 8);
            $customer['password']   = sha1($new_password);
            $this->save($customer);
            
            $this->email->from(config_item('email'), config_item('site_name'));
            $this->email->to($email);
            $this->email->subject(config_item('site_name').': Password Reset');
            $this->email->message('Your password has been reset to <strong>'. $new_password .'</strong>.');
            $this->email->send();
            
            return true;
        }
        else
        {
            return false;
        }
    }
    
    public function get_customer_by_email($email)
    {
        $result = CI::db()->get_where('customers', array('email'=>$email));
        return $result->row_array();
    }

    // Customer groups public functions
    public function get_groups()
    {
        return CI::db()->get('customer_groups')->result();     
    }
    
    public function get_group($id)
    {
        return CI::db()->where('id', $id)->get('customer_groups')->row();      
    }
    
    public function delete_group($id)
    {
        CI::db()->where('id', $id);
        CI::db()->delete('customer_groups');
    }
    
    public function save_group($data)
    {
        if(!empty($data['id'])) 
        {
            CI::db()->where('id', $data['id'])->update('customer_groups', $data);
            return $data['id'];
        } else {
            CI::db()->insert('customer_groups', $data);
            return CI::db()->insert_id();
        }
    }
}
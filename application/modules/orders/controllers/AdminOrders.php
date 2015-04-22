<?php namespace GoCart\Controller;
/**
 * AdminOrders Class
 *
 * @package     GoCart
 * @subpackage  Controllers
 * @category    AdminOrders
 * @author      Clear Sky Designs
 * @link        http://gocartdv.com
 */

class AdminOrders extends Admin { 
    
    public function __construct()
    {       
        parent::__construct();

        \CI::load()->model('Orders');
        \CI::load()->model('Search');
        \CI::load()->model('Locations');
        \CI::load()->helper(array('formatting'));
        \CI::lang()->load('order');
    }
    
    public function index($sort_by='order_number',$sort_order='desc', $code=0, $page=0, $rows=15)
    {
        
        //if they submitted an export form do the export
        if(\CI::input()->post('submit') == 'export')
        {
            \CI::load()->model('Customers');
            \CI::load()->helper('download_helper');
            $post = \CI::input()->post(null, false);
            $term = (object)$post;

            $data['orders'] = \CI::Orders()->getOrders($term);

            foreach($data['orders'] as &$o)
            {
                $o->items = \CI::Orders()->getOrderItems($o->id);
            }

            force_download_content('orders.xml', $this->view('orders_xml', $data, true));
            
            //kill the script from here
            die;
        }
        
        \CI::load()->helper('form');
        \CI::load()->helper('date');
        $data['message'] = \CI::session()->flashdata('message');
        $data['page_title'] = lang('orders');
        $data['code'] = $code;
        $term = false;
        
        $post = \CI::input()->post(null, false);
        if($post)
        {
            //if the term is in post, save it to the db and give me a reference
            $term = json_encode($post);
            $code = \CI::Search()->record_term($term);
            $data['code'] = $code;
            //reset the term to an object for use
            $term   = (object)$post;
        }
        elseif ($code)
        {
            $term = \CI::Search()->get_term($code);
            $term = json_decode($term);
        } 
        
        $data['term'] = $term;
        $data['orders'] = \CI::Orders()->getOrders($term, $sort_by, $sort_order, $rows, $page);
        $data['total'] = \CI::Orders()->getOrderCount($term);
        
        \CI::load()->library('pagination');
        
        $config['base_url'] = site_url('admin/orders/index/'.$sort_by.'/'.$sort_order.'/'.$code.'/');
        $config['total_rows'] = $data['total'];
        $config['per_page'] = $rows;
        $config['uri_segment'] = 7;
        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $config['full_tag_open'] = '<div class="pagination"><ul>';
        $config['full_tag_close'] = '</ul></div>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        
        $config['prev_link'] = '&laquo;';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';

        $config['next_link'] = '&raquo;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        
        \CI::pagination()->initialize($config);
    
        $data['sort_by'] = $sort_by;
        $data['sort_order'] = $sort_order;
                
        $this->view('orders', $data);
    }
    
    public function export()
    {
        \CI::load()->model('Customers');
        \CI::load()->helper('download_helper');
        $post = \CI::input()->post(null, false);
        $term = (object)$post;
        
        $data['orders'] = \CI::Orders()->getOrders($term);     

        foreach($data['orders'] as &$o)
        {
            $o->items = \CI::Orders()->getOrderItems($o->id);
        }

        force_download_content('orders.xml', $this->view('orders_xml', $data, true));
    }
    
    public function order($id)
    {
        \CI::load()->helper(array('form', 'date'));
        \CI::load()->library('form_validation');
        \CI::load()->model('Gift_card_model');
            
        \CI::form_validation()->set_rules('notes', 'lang:notes');
        \CI::form_validation()->set_rules('status', 'lang:status', 'required');
    
        $message = \CI::session()->flashdata('message');
        
    
        if (\CI::form_validation()->run() == TRUE)
        {
            $save = ['id' => $id,
                     'notes' => \CI::input()->post('notes'),
                     'status' => \CI::input()->post('status')
                    ];
            $data['message'] = lang('message_order_updated');
            
            \CI::Orders()->saveOrder($save);
        }
        //get the order information, this way if something was posted before the new one gets queried here
        $data['page_title'] = lang('view_order');
        $data['order'] = \CI::Orders()->getOrder($id);
        
        /*****************************
        * Order Notification details *
        ******************************/
        // get the list of canned messages (order)
        \CI::load()->model('Messages');
        $msg_templates = \CI::Messages()->get_list('order');
        
        // replace template variables
        foreach($msg_templates as $msg)
        {
            // fix html
            $msg['content'] = str_replace("\n", '', html_entity_decode($msg['content']));
            
            // {order_number}
            $msg['subject'] = str_replace('{order_number}', $data['order']->order_number, $msg['subject']);
            $msg['content'] = str_replace('{order_number}', $data['order']->order_number, $msg['content']);
            
            // {url}
            $msg['subject'] = str_replace('{url}', config_item('base_url'), $msg['subject']);
            $msg['content'] = str_replace('{url}', config_item('base_url'), $msg['content']);
            
            // {site_name}
            $msg['subject'] = str_replace('{site_name}', config_item('company_name'), $msg['subject']);
            $msg['content'] = str_replace('{site_name}', config_item('company_name'), $msg['content']);
            
            $data['msg_templates'][] = $msg;
        }

        // we need to see if any items are gift cards, so we can generate an activation link
        foreach($data['order']->contents as $orderkey=>$product)
        {
            if(isset($product['is_gc']) && (bool)$product['is_gc'])
            {
                if(\CI::GiftCards()->isActive($product['sku']))
                {
                    $data['order']->contents[$orderkey]['gc_status'] = '[ '.lang('gift_card_isActive').' ]';
                } else {
                    $data['order']->contents[$orderkey]['gc_status'] = ' [ <a href="'. base_url() . 'admin/gift-cards/activate/'. $product['code'].'">'.lang('activate').'</a> ]';
                }
            }
        }
        
        $this->view('order', $data);
        
    }
    
    public function packing_slip($order_id)
    {
        \CI::load()->helper('date');
        $data['order'] = \CI::Orders()->getOrder($order_id);
        
        $this->view('packing_slip.php', $data);
    }
    
    public function edit_status()
    {
        \CI::auth()->isLoggedIn();
        $order['id'] = \CI::input()->post('id');
        $order['status'] = \CI::input()->post('status');
        
        \CI::Orders()->saveOrder($order);
        
        echo url_title($order['status']);
    }
    
    public function sendNotification($order_id='')
    {
        // send the message
        $config['mailtype'] = 'html';
        \CI::load()->library('email');
        \CI::email()->initialize($config);
        \CI::email()->from(config_item('email'), config_item('company_name'));
        \CI::email()->to(\CI::input()->post('recipient'));
        \CI::email()->subject(\CI::input()->post('subject'));
        \CI::email()->message(html_entity_decode(\CI::input()->post('content')));
        \CI::email()->send();

        \CI::session()->set_flashdata('message', lang('sent_notification_message'));
        redirect('admin/orders/order/'.$order_id);
    }
    
    public function bulk_delete()
    {
        $orders = \CI::input()->post('order');
        
        if($orders)
        {
            foreach($orders as $order)
            {
                \CI::Orders()->delete($order);
            }
            \CI::session()->set_flashdata('message', lang('message_orders_deleted'));
        }
        else
        {
            \CI::session()->set_flashdata('error', lang('error_no_orders_selected'));
        }
        //redirect as to change the url
        redirect('admin/orders');   
    }
}
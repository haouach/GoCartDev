<?php
Class Orders extends CI_Model
{
    public function getGrossMonthlySales($year)
    {
        CI::db()->select('SUM(coupon_discount) as coupon_discounts');
        CI::db()->select('SUM(gift_card_discount) as gift_card_discounts');
        CI::db()->select('SUM(subtotal) as product_totals');
        CI::db()->select('SUM(shipping) as shipping');
        CI::db()->select('SUM(tax) as tax');
        CI::db()->select('SUM(total) as total');
        CI::db()->select('YEAR(ordered_on) as year');
        CI::db()->select('MONTH(ordered_on) as month');
        CI::db()->group_by(['MONTH(ordered_on)']);
        CI::db()->order_by("ordered_on", "desc");
        CI::db()->where('YEAR(ordered_on)', $year);
        
        return CI::db()->get('orders')->result();
    }
    
    public function getSalesYears()
    {
        CI::db()->order_by("ordered_on", "desc");
        CI::db()->select('YEAR(ordered_on) as year');
        CI::db()->group_by('YEAR(ordered_on)');
        $records = CI::db()->get('orders')->result();
        $years = [];
        foreach($records as $r)
        {
            $years[] = $r->year;
        }
        return $years;
    }
    
    public function getOrders($search=false, $sort_by='', $sort_order='DESC', $limit=0, $offset=0)
    {           
        if ($search)
        {
            if(!empty($search->term))
            {
                //support multiple words
                $term = explode(' ', $search->term);

                foreach($term as $t)
                {
                    $not = '';
                    $operator = 'OR';
                    if(substr($t,0,1) == '-')
                    {
                        $not = 'NOT ';
                        $operator = 'AND';
                        //trim the - sign off
                        $t = substr($t,1,strlen($t));
                    }

                    $like = '';
                    $like .= "( `order_number` ".$not."LIKE '%".$t."%' " ;
                    $like .= $operator." `bill_firstname` ".$not."LIKE '%".$t."%'  ";
                    $like .= $operator." `bill_lastname` ".$not."LIKE '%".$t."%'  ";
                    $like .= $operator." `ship_firstname` ".$not."LIKE '%".$t."%'  ";
                    $like .= $operator." `ship_lastname` ".$not."LIKE '%".$t."%'  ";
                    $like .= $operator." `status` ".$not."LIKE '%".$t."%' ";
                    $like .= $operator." `notes` ".$not."LIKE '%".$t."%' )";

                    CI::db()->where($like);
                }
            }
            if(!empty($search->start_date))
            {
                CI::db()->where('ordered_on >=',$search->start_date);
            }
            if(!empty($search->end_date))
            {
                //increase by 1 day to make this include the final day
                //I tried <= but it did not public function. Any ideas why?
                $search->end_date = date('Y-m-d', strtotime($search->end_date)+86400);
                CI::db()->where('ordered_on <',$search->end_date);
            }
        }
        
        if($limit>0)
        {
            CI::db()->limit($limit, $offset);
        }
        if(!empty($sort_by))
        {
            CI::db()->order_by($sort_by, $sort_order);
        }
        
        return CI::db()->get('orders')->result();
    }
    
    public function getOrderCount($search=false)
    {           
        if ($search)
        {
            if(!empty($search->term))
            {
                //support multiple words
                $term = explode(' ', $search->term);

                foreach($term as $t)
                {
                    $not = '';
                    $operator = 'OR';
                    if(substr($t,0,1) == '-')
                    {
                        $not = 'NOT ';
                        $operator = 'AND';
                        //trim the - sign off
                        $t = substr($t,1,strlen($t));
                    }

                    $like = '';
                    $like .= "( `order_number` ".$not."LIKE '%".$t."%' " ;
                    $like .= $operator." `bill_firstname` ".$not."LIKE '%".$t."%'  ";
                    $like .= $operator." `bill_lastname` ".$not."LIKE '%".$t."%'  ";
                    $like .= $operator." `ship_firstname` ".$not."LIKE '%".$t."%'  ";
                    $like .= $operator." `ship_lastname` ".$not."LIKE '%".$t."%'  ";
                    $like .= $operator." `status` ".$not."LIKE '%".$t."%' ";
                    $like .= $operator." `notes` ".$not."LIKE '%".$t."%' )";

                    CI::db()->where($like);
                }   
            }
            if(!empty($search->start_date))
            {
                CI::db()->where('ordered_on >=',$search->start_date);
            }
            if(!empty($search->end_date))
            {
                CI::db()->where('ordered_on <',$search->end_date);
            }
            
        }
        
        return CI::db()->count_all_results('orders');
    }

    //get an individual customers orders
    public function getCustomerOrders($id, $offset=0)
    {
        CI::db()->join('order_items', 'orders.id = order_items.order_id');
        CI::db()->order_by('ordered_on', 'DESC');
        return CI::db()->get_where('orders', ['customer_id' => $id], 15, $offset)->result();
    }

    public function getCustomerCart($customerID)
    {
        CI::db()->where('status', 'cart');
        CI::db()->where('customer_id', $customerID);
        return CI::db()->get('orders')->row();
    }
    
    public function countCustomerOrders($id)
    {
        CI::db()->where(['customer_id' => $id]);
        return CI::db()->count_all_results('orders');
    }

    public function getOrder($id)
    {
        CI::db()->where('id', $id);
        $result = CI::db()->get('orders');
        
        $order = $result->row();
        $order->contents = $this->getOrderItems($order->id);

        return $order;
    }
    
    public function getItems($id)
    {
        $this->db->where('order_id', $id)->order_by('type', 'ASC')->order_by('id', 'ASC');
        $items = $this->db->get('order_items')->result();

        return $items;
    }

    public function getItemOptions($order_id)
    {
        $optionValues = $this->db->where('order_id', $order_id)->get('order_item_options')->result();

        $return =[];

        foreach($optionValues as $optionValue)
        {
            if(!isset($return[$optionValue->order_item_id]))
            {
                $return[$optionValue->order_item_id] = [];
            }
            $return[$optionValue->order_item_id][] = $optionValue;
        }

        return $return;
    }

    public function removeItem($order_id, $id)
    {
        $this->db->where('order_id', $order_id)->where('id', $id)->delete('order_items');
        $this->db->where('order_item_id', $id)->delete('order_item_files');
        $this->db->where('order_item_id', $id)->delete('order_item_options');
    }

    public function getOrderItems($id)
    {
        CI::db()->select('order_id, contents');
        CI::db()->where('order_id', $id);
        CI::DB()->order_by('type', 'ASC');
        CI::DB()->order_by('id', 'ASC');
        $result = CI::db()->get('order_items');
        
        $items = $result->row_array();
        
        $return = [];
        $count = 0;
        foreach($items as $item)
        {

            $item_content = unserialize($item['contents']);
            
            //remove contents from the item array
            unset($item['contents']);
            $return[$count] = $item;
            
            //merge the unserialized contents with the item array
            $return[$count] = array_merge($return[$count], $item_content);
            
            $count++;
        }
        return $return;
    }
    
    public function delete($id)
    {
        CI::db()->where('id', $id);
        CI::db()->delete('orders');
        
        //now delete the order items
        CI::db()->where('order_id', $id);
        CI::db()->delete('order_items');

        CI::db()->where('order_id', $id);
        CI::db()->delete('order_item_options');

        CI::db()->where('order_id', $id);
        CI::db()->delete('order_item_files');
    }
    
    public function saveItem($data)
    {
        if (isset($data['id']))
        {
            CI::db()->where('id', $data['id']);
            CI::db()->update('order_items', $data);
            return $data['id'];
        }
        else
        {
            CI::db()->insert('order_items', $data);
            return CI::db()->insert_id();
        }
    }

    public function saveItemOption($data)
    {
        if (isset($data['id']))
        {
            CI::db()->where('id', $data['id']);
            CI::db()->update('order_item_options', $data);
            return $data['id'];
        }
        else
        {
            CI::db()->insert('order_item_options', $data);
            return CI::db()->insert_id();
        }
    }

    public function saveOrder($data, $contents = false)
    {
        if (isset($data['id']))
        {
            CI::db()->where('id', $data['id']);
            CI::db()->update('orders', $data);
            $id = $data['id'];
        }
        else
        {
            CI::db()->insert('orders', $data);
            $id = CI::db()->insert_id();
        }
        
        //if there are items being submitted with this order add them now
        if($contents)
        {
            // clear existing order items
            CI::db()->where('order_id', $id)->delete('order_items');
            // update order items
            foreach($contents as $item)
            {
                $save = [];
                $save['contents'] = $item;
                
                $item = unserialize($item);
                $save['product_id'] = $item['id'];
                $save['quantity'] = $item['quantity'];
                $save['order_id'] = $id;
                CI::db()->insert('order_items', $save);
            }
        }
        return $id;
    }
    
    public function getBestSellers($start, $end)
    {
        if(!empty($start))
        {
            CI::db()->where('ordered_on >=', $start);
        }
        if(!empty($end))
        {
            CI::db()->where('ordered_on <',  $end);
        }

        // just fetch a list of order id's
        $orders = CI::db()->select('id')->get('orders')->result();
        
        $items = [];
        foreach($orders as $order)
        {
            // get a list of product id's and quantities for each
            $order_items = CI::db()->select('product_id, quantity')->where('order_id', $order->id)->get('order_items')->row_array();
            
            foreach($order_items as $i)
            {
                if(isset($items[$i['product_id']]))
                {
                    $items[$i['product_id']] += $i['quantity'];
                }
                else
                {
                    $items[$i['product_id']] = $i['quantity'];
                }
            }
        }
        arsort($items);
        
        // don't need this anymore
        unset($orders);
        
        $return = [];
        foreach($items as $key=>$quantity)
        {
            $product = CI::db()->where('id', $key)->get('products')->row();
            if($product)
            {
                $product->quantity_sold = $quantity;
            }
            else
            {
                $product = (object)['sku'=>'Deleted', 'name'=>'Deleted', 'quantity_sold'=>$quantity];
            }
            
            $return[] = $product;
        }
        
        return $return;
    }
    
}
<?php
/**
 * Products Class
 *
 * @package     GoCart
 * @subpackage  Models
 * @category    Products
 * @author      Clear Sky Designs
 * @link        http://gocartdv.com
 */

Class Products extends CI_Model
{
    public function getProduct($id)
    {
        return CI::db()->where('id', $id)->get('products')->row();
    }

    public function product_autocomplete($name, $limit)
    {
        return  CI::db()->like('name', $name)->get('products', $limit)->result();
    }
    
    public function products($data=[], $return_count=false)
    {
        if(empty($data))
        {
            //if nothing is provided return the whole shabang
            $this->get_all_products();
        }
        else
        {
            //grab the limit
            if(!empty($data['rows']))
            {
                CI::db()->limit($data['rows']);
            }
            
            //grab the offset
            if(!empty($data['page']))
            {
                CI::db()->offset($data['page']);
            }
            
            //do we order by something other than category_id?
            if(!empty($data['order_by']))
            {
                //if we have an order_by then we must have a direction otherwise KABOOM
                CI::db()->order_by($data['order_by'], $data['sort_order']);
            }
            
            //do we have a search submitted?
            if(!empty($data['term']))
            {
                $search = json_decode($data['term']);
                //if we are searching dig through some basic fields
                if(!empty($search->term))
                {
                    CI::db()->like('name', $search->term);
                    CI::db()->or_like('description', $search->term);
                    CI::db()->or_like('excerpt', $search->term);
                    CI::db()->or_like('sku', $search->term);
                }
                
                if(!empty($search->category_id))
                {
                    //lets do some joins to get the proper category products
                    CI::db()->join('category_products', 'category_products.product_id=products.id', 'right');
                    CI::db()->where('category_products.category_id', $search->category_id);
                    CI::db()->order_by('sequence', 'ASC');
                }
            }
            
            if($return_count)
            {
                return CI::db()->count_all_results('products');
            }
            else
            {
                return CI::db()->get('products')->result();
            }
            
        }
    }
    
    public function get_all_products()
    {
        //sort by alphabetically by default
        CI::db()->order_by('name', 'ASC');
        $result = CI::db()->get('products');

        return $result->result();
    }
    
    public function get_filtered_products($product_ids, $limit = false, $offset = false)
    {
        
        if(count($product_ids)==0)
        {
            return [];
        }
        
        CI::db()->select('id, LEAST(IFNULL(NULLIF(saleprice, 0), price), price) as sort_price', false)->from('products');
        
        if(count($product_ids)>1)
        {
            $querystr = '';
            foreach($product_ids as $id)
            {
                $querystr .= 'id=\''.$id.'\' OR ';
            }
        
            $querystr = substr($querystr, 0, -3);
            
            CI::db()->where($querystr, null, false);
            
        } else {
            CI::db()->where('id', $product_ids[0]);
        }
        
        $result = CI::db()->limit($limit)->offset($offset)->get()->result();

        //die(CI::db()->last_query());

        $contents   = [];
        $count      = 0;
        foreach ($result as $product)
        {

            $contents[$count]   = $this->get_product($product->id);
            $count++;
        }

        return $contents;
        
    }
    
    public function getProducts($category_id = false, $limit = false, $offset = false, $by=false, $sort=false)
    {
        //if we are provided a category_id, then get products according to category
        if ($category_id)
        {
            CI::db()->select('category_products.*, products.*, LEAST(IFNULL(NULLIF(saleprice, 0), price), price) as sort_price', false)->from('category_products')->join('products', 'category_products.product_id=products.id')->where(array('category_id'=>$category_id, 'enabled'=>1));

            CI::db()->order_by($by, $sort);
            
            $result = CI::db()->limit($limit)->offset($offset)->get()->result();
            
            return $result;
        }
        else
        {
            //sort by alphabetically by default
            return CI::db()->order_by('name', 'ASC')->get('products')->result();
        }
    }
    
    public function count_all_products()
    {
        return CI::db()->count_all_results('products');
    }
    
    public function count_products($id)
    {
        return CI::db()->select('product_id')->from('category_products')->join('products', 'category_products.product_id=products.id')->where(array('category_id'=>$id, 'enabled'=>1))->count_all_results();
    }

    public function slug($slug, $related=true)
    {
      $result = CI::db()->get_where('products', array('slug'=>$slug))->row();
      
      if(!$result)
        {
            return false;
        }

        $related    = json_decode($result->related_products);
        
        if(!empty($related))
        {
            //build the where
            $where = [];
            foreach($related as $r)
            {
                $where[] = '`id` = '.$r;
            }

            CI::db()->where('('.implode(' OR ', $where).')', null);
            CI::db()->where('enabled', 1);

            $result->related_products   = CI::db()->get('products')->result();
        }
        else
        {
            $result->related_products   = [];
        }
        $result->categories         = $this->get_product_categories($result->id);

        return $result;
    }
    
    public function find($id, $related=true)
    {
        $result = CI::db()->get_where('products', array('id'=>$id))->row();
        if(!$result)
        {
            return false;
        }

        if($related)
        {
            $relatedItems = json_decode($result->related_products);
            if(!empty($relatedItems))
            {
                //build the where
                $where = [];
                foreach($relatedItems as $r)
                {
                    $where[] = '`id` = '.$r;
                }

                CI::db()->where('('.implode(' OR ', $where).')', null);
                CI::db()->where('enabled', 1);

                $result->related_products   = CI::db()->get('products')->result();
            }
            else
            {
                $result->related_products   = [];
            }
        }

        $result->categories = $this->get_product_categories($result->id);

        return $result;
    }

    public function get_product_categories($id)
    {
        return CI::db()->where('product_id', $id)->join('categories', 'category_id = categories.id')->get('category_products')->result();
    }

    public function get_slug($id)
    {
        return CI::db()->get_where('products', array('id'=>$id))->row()->slug;
    }

    public function save($product, $options=false, $categories=false)
    {
        if ($product['id'])
        {
            CI::db()->where('id', $product['id']);
            CI::db()->update('products', $product);

            $id = $product['id'];
        }
        else
        {
            CI::db()->insert('products', $product);
            $id = CI::db()->insert_id();
        }

        //loop through the product options and add them to the db
        if($options !== false)
        {

            // wipe the slate
            CI::ProductOptions()->clearOptions($id);

            // save edited values
            $count = 1;
            foreach ($options as $option)
            {
                $values = $option['values'];
                unset($option['values']);
                $option['product_id'] = $id;
                $option['sequence'] = $count;

                CI::ProductOptions()->saveOption($option, $values);
                $count++;
            }
        }
        
        if($categories !== false)
        {
            if($product['id'])
            {
                //get all the categories that the product is in
                $cats   = $this->get_product_categories($id);
                
                //generate cat_id array
                $ids    = [];
                foreach($cats as $c)
                {
                    $ids[]  = $c->id;
                }

                //eliminate categories that products are no longer in
                foreach($ids as $c)
                {
                    if(!in_array($c, $categories))
                    {
                        CI::db()->delete('category_products', array('product_id'=>$id,'category_id'=>$c));
                    }
                }
                
                //add products to new categories
                foreach($categories as $c)
                {
                    if(!in_array($c, $ids))
                    {
                        CI::db()->insert('category_products', array('product_id'=>$id,'category_id'=>$c));
                    }
                }
            }
            else
            {
                //new product add them all
                foreach($categories as $c)
                {
                    CI::db()->insert('category_products', array('product_id'=>$id,'category_id'=>$c));
                }
            }
        }
        
        
        //return the product id
        return $id;
    }
    
    public function delete_product($id)
    {
        // delete product 
        CI::db()->where('id', $id);
        CI::db()->delete('products');

        //delete references in the product to category table
        CI::db()->where('product_id', $id);
        CI::db()->delete('category_products');
        
        // delete coupon reference
        CI::db()->where('product_id', $id);
        CI::db()->delete('coupons_products');

    }

    public function addProduct_to_category($product_id, $optionlist_id, $sequence)
    {
        CI::db()->insert('product_categories', array('product_id'=>$product_id, 'category_id'=>$category_id, 'sequence'=>$sequence));
    }

    public function search_products($term, $limit=false, $offset=false, $by=false, $sort=false)
    {
        $results = [];
        
        CI::db()->select('*, LEAST(IFNULL(NULLIF(saleprice, 0), price), price) as sort_price', false);
        //this one counts the total number for our pagination
        CI::db()->where('enabled', 1);
        CI::db()->where('(name LIKE "%'.$term.'%" OR description LIKE "%'.$term.'%" OR excerpt LIKE "%'.$term.'%" OR sku LIKE "%'.$term.'%")');
        $results['count']   = CI::db()->count_all_results('products');


        CI::db()->select('*, LEAST(IFNULL(NULLIF(saleprice, 0), price), price) as sort_price', false);
        //this one gets just the ones we need.
        CI::db()->where('enabled', 1);
        CI::db()->where('(name LIKE "%'.$term.'%" OR description LIKE "%'.$term.'%" OR excerpt LIKE "%'.$term.'%" OR sku LIKE "%'.$term.'%")');
        
        if($by && $sort)
        {
            CI::db()->order_by($by, $sort);
        }
        
        $results['products'] = CI::db()->get('products', $limit, $offset)->result();
        
        return $results;
    }

    // Build a cart-ready product array
    public function getCartReadyProduct($id, $quantity=false)
    {
        $product = CI::db()->get_where('products', array('id'=>$id))->row();
        
        //unset some of the additional fields we don't need to keep
        if(!$product)
        {
            return false;
        }
        
        $product->base_price = $product->price;
        
        if ($product->saleprice != 0.00)
        { 
            $product->price = $product->saleprice;
        }       
        
        // Some products have n/a quantity, such as downloadables
        //overwrite quantity of the product with quantity requested
        if (!$quantity || $quantity <= 0 || $product->fixed_quantity==1)
        {
            $product->quantity = 1;
        }
        else
        {
            $product->quantity = $quantity;
        }
        
        // attach list of associated downloadables
        $product->file_list = $this->DigitalProducts->get_associations_by_product($id);
        
        return (array)$product;
    }

    public function validate_slug($slug, $id=false, $counter=false)
    {
        CI::db()->select('slug');
        CI::db()->from('products');
        CI::db()->where('slug', $slug.$counter);
        if ($id)
        {
            CI::db()->where('id !=', $id);
        }
        $count = CI::db()->count_all_results();

        if ($count > 0)
        {
            if(!$counter)
            {
                $counter = 1;
            }
            else
            {
                $counter++;
            }
            return $this->validate_slug($slug, $id, $counter);
        }
        else
        {
             return $slug.$counter;
        }
    }

}
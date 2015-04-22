<?php
/**
 * Categories Class
 *
 * @package     GoCart
 * @subpackage  Models
 * @category    Categories
 * @author      Clear Sky Designs
 * @link        http://gocartdv.com
 */

Class Categories
{

    var $tiered;

    public function __construct()
    {
        $this->tiered = [];
        $this->get_categories_tiered();
    }

    public function tier($parent_id)
    {
        if(isset($this->categories[$parent_id]))
        {
            return $this->categories[$parent_id];
        }
        else
        {
            return false;
        }
    }

    public function getBySlug($slug)
    {
        foreach($this->tiered['all'] as $c)
        {
            if($c->slug == $slug)
            {
                return $c;
                break;
            }
        }
        return false;
    }

    public function get($slug, $sort, $direction, $page, $products_per_page)
    {
        //get the category by slug
        $category = $this->getBySlug($slug);

        //if the category does not exist return false
        if(!$category || !$category->enabled)
        {
            return false;
        }

        //create view variable
        $data['page_title'] = $category->name;
        $data['meta'] = $category->meta;
        $data['seo_title'] = (!empty($category->seo_title))?$category->seo_title:$category->name;
        $data['category'] = $category;

        $data['total_products'] = CI::Products()->count_products($category->id);
        $data['products'] = CI::Products()->getProducts($category->id, $products_per_page, $page, $sort, $direction);

        foreach ($data['products'] as &$p)
        {
            $p->images  = json_decode($p->images, true);
        }

        return $data;
    }

    public function get_categories($parent = false)
    {
        if ($parent !== false)
        {
            CI::db()->where('parent_id', $parent);
        }
        CI::db()->select('id');
        CI::db()->order_by('categories.sequence', 'ASC');
        
        //this will alphabetize them if there is no sequence
        CI::db()->order_by('name', 'ASC');
        $result = CI::db()->get('categories');
        
        $categories = [];
        foreach($result->result() as $cat)
        {
            $categories[]   = $this->find($cat->id);
        }
        
        return $categories;
    }
    
    public function get_categories_tiered($admin = false)
    {
        if(!$admin && !empty($this->tiered))
        {
            return $this->tiered;
        }

        if(!$admin) CI::db()->where('enabled', 1);
        
        CI::db()->order_by('sequence');
        CI::db()->order_by('name', 'ASC');
        $categories = CI::db()->get('categories')->result();
        
        $results    = [];
        $results['all'] = [];
        foreach($categories as $category) {

            // Set a class to active, so we can highlight our current category
            if(CI::uri()->segment(2) == $category->slug && CI::uri()->segment(1) == 'category') {
                $category->active = true;
            } else {
                $category->active = false;
            }
            $results['all'][$category->id] = $category;
            $results[$category->parent_id][$category->id] = $category;
        }
        
        if(!$admin)
        {
            $this->tiered = $results;
        }

        return $results;
    }
    
    public function slug($slug)
    {
        return CI::db()->get_where('categories', array('slug'=>$slug))->row();
    }

    public function find($id)
    {
        return CI::db()->get_where('categories', array('id'=>$id))->row();
    }
    
    public function get_category_products_admin($id)
    {
        CI::db()->order_by('sequence', 'ASC');
        $result = CI::db()->get_where('category_products', array('category_id'=>$id));
        $result = $result->result();
        
        $contents   = [];
        foreach ($result as $product)
        {
            $result2    = CI::db()->get_where('products', array('id'=>$product->product_id));
            $result2    = $result2->row();
            
            $contents[] = $result2; 
        }
        
        return $contents;
    }
    
    public function get_category_products($id, $limit, $offset)
    {
        CI::db()->order_by('sequence', 'ASC');
        $result = CI::db()->get_where('category_products', array('category_id'=>$id), $limit, $offset);
        $result = $result->result();
        
        $contents   = [];
        $count      = 1;
        foreach ($result as $product)
        {
            $result2    = CI::db()->get_where('products', array('id'=>$product->product_id));
            $result2    = $result2->row();
            
            $contents[$count]   = $result2;
            $count++;
        }
        
        return $contents;
    }
    
    public function organize_contents($id, $products)
    {
        //first clear out the contents of the category
        CI::db()->where('category_id', $id);
        CI::db()->delete('category_products');
        
        //now loop through the products we have and add them in
        $sequence = 0;
        foreach ($products as $product)
        {
            CI::db()->insert('category_products', array('category_id'=>$id, 'product_id'=>$product, 'sequence'=>$sequence));
            $sequence++;
        }
    }
    
    public function save($category)
    {
        if ($category['id'])
        {
            CI::db()->where('id', $category['id']);
            CI::db()->update('categories', $category);
            
            return $category['id'];
        }
        else
        {
            CI::db()->insert('categories', $category);
            return CI::db()->insert_id();
        }
    }
    
    public function delete($id)
    {
        CI::db()->where('id', $id);
        CI::db()->delete('categories');
        
        //delete references to this category in the product to category table
        CI::db()->where('category_id', $id);
        CI::db()->delete('category_products');
    }

    /*
     * check if slug already exists
     */

   public function validate_slug($slug, $id=false, $counter=false)
    {
		CI::db()->select('slug');
		CI::db()->from('categories');
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
                $counter	= 1;
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
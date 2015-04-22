<?php namespace GoCart\Controller;
/**
 * Product Class
 *
 * @package     GoCart
 * @subpackage  Controllers
 * @category    Product
 * @author      Clear Sky Designs
 * @link        http://gocartdv.com
 */

class Product extends Front {

    public function index($slug) {
        $product = \CI::Products()->slug($slug);

        if(!$product)
        {
            show_404();
        }
        else
        {
            //json decode the product images
            if($product->images == 'false')
            {
                $product->images = [];
            }
            else
            {
                $product->images = array_values(json_decode($product->images, true));
            }

            //set product options
            $data['options'] = \CI::ProductOptions()->getProductOptions($product->id);

            $data['posted_options'] = \CI::session()->flashdata('option_values');

            //get related items
            $data['related'] = $product->related_products;

            //create view variable
            $data['page_title'] = $product->name;
            $data['meta'] = $product->meta;
            $data['seo_title'] = (!empty($product->seo_title))?$product->seo_title:$product->name;
            $data['product'] = $product;

            //load the view
            $this->view('product', $data);
        }
    }

}


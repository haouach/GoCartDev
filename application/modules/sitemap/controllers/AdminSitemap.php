<?php namespace GoCart\Controller;
/**
 * AdminSitemap Class
 *
 * @package     GoCart
 * @subpackage  Controllers
 * @category    AdminSitemap
 * @author      Clear Sky Designs
 * @link        http://gocartdv.com
 */

class AdminSitemap extends Admin {

    public function __construct()
    {
        parent::__construct();

        \CI::auth()->check_access('Admin', true);  
        \CI::load()->model(['Categories', 'Products', 'Pages']);
        \CI::lang()->load('sitemap');
    }

    public function index()
    {
        $data['page_title'] = lang('sitemap_page_title');
        $data['ProductsCount'] = count(\CI::Products()->getProducts());
        $this->view('sitemap', $data);
    }

    public function generateProductsFeed()
    {
        $data = [];
        $data['page_title'] = 'Google product feed';
        $data['ProductsCount'] = count(\CI::Products()->getProducts());

        $this->view('product_feed', $data);
    }

    public function generateXMLGoogleFeed()
    {
        \CI::load()->helper('formatting');
        $products   = \CI::Products()->products(['rows' =>  \CI::input()->post('limit'), 'page' => \CI::input()->post('offset')] );
        $xml_feed = '';
        
        if(\CI::input()->post('offset') == 0)
        {
          $xml_feed .= '<?xml version="1.0" encoding="utf-8"?><rss version="2.0" xmlns:g="http://base.google.com/ns/1.0"><channel>';
        }
        
        foreach($products as $TmpProduct)
        {
            $googleFeedItem = (array)json_decode($TmpProduct->google_feed); 
            $xml_feed .= '<item>';
            $xml_feed .= '<title>'.$TmpProduct->name.'</title>';
            $xml_feed .= '<link>'.site_url('product/'.$TmpProduct->slug).'</link>';
            $xml_feed .= '<description>'.$TmpProduct->description.'</description>';
            $xml_feed .= '<g:id>'.$TmpProduct->id.'</g:id>';
            ($TmpProduct->sku) ? $xml_feed .= '<g:item_group_id>'.$TmpProduct->sku.'</g:item_group_id>' : '';
            $xml_feed .= ((bool)$TmpProduct->track_stock && $TmpProduct->quantity < 1 && config_item('inventory_enabled')) ? '<g:availability>out of stock</g:availability>' : '<g:availability>in stock</g:availability>';
            $photo  = theme_img('no_picture.png', lang('no_image_available'));  
            
            if($TmpProduct->images == 'null')
            {
                $TmpProduct->images = [];
            }
            else
            {
                $TmpProduct->images = array_values((array)json_decode($TmpProduct->images));
            }
            
            if(!empty($TmpProduct->images[0]))
                {
                    $primary    = $TmpProduct->images[0];
                    foreach($TmpProduct->images as $photo)
                    {
                        if(isset($photo->primary))
                        {
                            $primary    = $photo;
                        }
                    }

                    $xml_feed .= '<g:image_link>'.base_url('uploads/images/medium/'.$primary->filename).'</g:image_link>';
                }       
            
            if(count($TmpProduct->images) > 1)
            {
                foreach($TmpProduct->images as $image)
                {
                    $xml_feed .= '<g:additional_image_link>'.base_url('uploads/images/medium/'.$image->filename).'</g:additional_image_link>';
                }
            }

            if($googleFeedItem):
                (isset($googleFeedItem->category)) ? $xml_feed .= '<g:google_product_category>'.$googleFeedItem->category.'</g:google_product_category>' : '';
                (isset($googleFeedItem->category)) ? $xml_feed .= '<g:product_type>'.$googleFeedItem->category.'</g:product_type>' : '';
                (isset($googleFeedItem->condition)) ? $xml_feed .= '<g:condition>'.$googleFeedItem->condition .'</g:condition>' : ''; // new/ used/ refurbished
                (isset($googleFeedItem->availability_date)) ? $xml_feed .= '<g:availability_date>'.$googleFeedItem->availability_date .'</g:availability_date>' : '';
                $xml_feed .= '<g:price>'.format_currency($TmpProduct->price).'</g:price>';
                $xml_feed .= '<g:sale_price>'.format_currency($TmpProduct->saleprice).'</g:sale_price>';
                (isset($googleFeedItem->sale_price_effective_date_start) && isset($googleFeedItem->sale_price_effective_date_end)) ? $xml_feed .= '<g:sale_price_effective_date>'.$googleFeedItem->sale_price_effective_date_start .'/'.$googleFeedItem->sale_price_effective_date_end .'</g:sale_price_effective_date>' : '';
                (isset($googleFeedItem->brand)) ? $xml_feed .= '<g:brand>'.$googleFeedItem->brand .'</g:brand>' : '';
                (isset($googleFeedItem->gtin)) ?  $xml_feed .= '<g:gtin>'.$googleFeedItem->gtin .'</g:gtin>' : '';
                (isset($googleFeedItem->mpn)) ?  $xml_feed .= '<g:mpn>'.$googleFeedItem->mpn .'</g:mpn>' : '';
                (isset($googleFeedItem->identifier_exists)) ? $xml_feed .= '<g:identifier_exists>'.$googleFeedItem->identifier_exists .'</g:identifier_exists>' : '';
                (isset($googleFeedItem->gender)) ? $xml_feed .= '<g:gender>'.$googleFeedItem->gender .'</g:gender>' : '';
                (isset($googleFeedItem->age_group)) ? $xml_feed .= '<g:age_group>'.$googleFeedItem->age_group .'</g:age_group>': '';
                (isset($googleFeedItem->color)) ? $xml_feed .= '<g:color>'.$googleFeedItem->color .'</g:color>' : '';
                (isset($googleFeedItem->size)) ? $xml_feed .= '<g:size>'.$googleFeedItem->size .'</g:size>' : '';
                (isset($googleFeedItem->size_type)) ? $xml_feed .= '<g:size_type>'.$googleFeedItem->size_type .'</g:size_type>' : ''; // Regular/ Petite/ Plus/ Big and Tall/ Maternity
                (isset($googleFeedItem->size_system)) ? $xml_feed .= '<g:size_system>'.$googleFeedItem->size_system .'</g:size_system>' : ''; //US/UK/EU/DE/FR/JP/CN (China)/IT/BR/MEX/AU
                (isset($googleFeedItem->material)) ? $xml_feed .= '<g:material>'.$googleFeedItem->material .'</g:material>' : '';
                (isset($googleFeedItem->pattern)) ? $xml_feed .= '<g:pattern>'.$googleFeedItem->pattern .'</g:pattern>' : '';
                (isset($googleFeedItem->shipping_weight_number) && isset($googleFeedItem->shipping_weight_unit)) ? $xml_feed .= '<g:shipping_weight>'.$googleFeedItem->shipping_weight_number .' '.$googleFeedItem->shipping_weight_unit .'</g:shipping_weight>' : ''; // lb, oz, g, kg.
                (isset($googleFeedItem->multipack)) ? $xml_feed .= '<g:multipack>'.$googleFeedItem->multipack .'</g:multipack>' : ''; 
                (isset($googleFeedItem->is_bundle)) ? $xml_feed .= '<g:is_bundle>'.$googleFeedItem->is_bundle .'</g:is_bundle>' : ''; //true false
                (isset($googleFeedItem->adult)) ? $xml_feed .= '<g:adult>'.$googleFeedItem->adult .'</g:adult>' : '';
                (isset($googleFeedItem->adwords_grouping)) ? $xml_feed .= '<g:adwords_grouping>'.$googleFeedItem->adwords_grouping .'</g:adwords_grouping>' : '';
                (isset($googleFeedItem->adwords_labels)) ? $xml_feed .= '<g:adwords_labels>'.$googleFeedItem->adwords_labels .'</g:adwords_labels>' : '';
                (isset($googleFeedItem->adwords_redirect)) ? $xml_feed .= '<g:adwords_redirect>'.$googleFeedItem->adwords_redirect .'</g:adwords_redirect>' : '';
                (isset($googleFeedItem->custom_label_0)) ? $xml_feed .= '<g:custom_label_0>'.$googleFeedItem->custom_label_0 .'</g:custom_label_0>' : '';
                (isset($googleFeedItem->custom_label_1)) ? $xml_feed .= '<g:custom_label_1>'.$googleFeedItem->custom_label_1 .'</g:custom_label_1>' : '';
                (isset($googleFeedItem->custom_label_2)) ? $xml_feed .= '<g:custom_label_2>'.$googleFeedItem->custom_label_2 .'</g:custom_label_2>' : '';
                (isset($googleFeedItem->custom_label_3)) ? $xml_feed .= '<g:custom_label_3>'.$googleFeedItem->custom_label_3 .'</g:custom_label_3>' : '';
                (isset($googleFeedItem->custom_label_4)) ? $xml_feed .= '<g:custom_label_4>'.$googleFeedItem->custom_label_4 .'</g:custom_label_4>' : '';
                (isset($googleFeedItem->expiration_date)) ? $xml_feed .= '<g:expiration_date>'.$googleFeedItem->expiration_date .'</g:expiration_date>' : '';
                //EU ONLY
                (isset($googleFeedItem->unit_pricing_measure)) ? $xml_feed .= '<g:unit_pricing_measure>'.$googleFeedItem->unit_pricing_measure_number.$googleFeedItem->unit_pricing_measure_unit .'</g:unit_pricing_measure>' : '';  // Weight (mg, g, kg), volume (ml, cl, l, cbm), length (cm, m), and area (sqm)
                (isset($googleFeedItem->unit_pricing_base_measure)) ? $xml_feed .= '<g:unit_pricing_base_measure>'.$googleFeedItem->unit_pricing_base_measure_number.$googleFeedItem->unit_pricing_base_measure_unit .'</g:unit_pricing_base_measure>' : '';
                (isset($googleFeedItem->energy_efficiency_class)) ? $xml_feed .= '<g:energy_efficiency_class>'.$googleFeedItem->energy_efficiency_class .'</g:energy_efficiency_class>' : '';
                //Japan ONLY
                (isset($googleFeedItem->loyalty_points)) ? $xml_feed .= '<g:loyalty_points><g:name>'.$googleFeedItem->loyalty_points_program .'</g:name><g:points_value>'.$googleFeedItem->loyalty_points_program_points .'</g:points_value><g:ratio>'.$googleFeedItem->loyalty_points_ratio .'</g:ratio> </g:loyalty_points>' : '';
                //Brazil Only
                (isset($googleFeedItem->installment)) ? $xml_feed .= '<g:installment><g:months>'.$googleFeedItem->installment_months .'</g:months><g:amount>'.$googleFeedItem->installment_amount .' BRL</g:amount></g:installment>' : '';

            endif;
            $xml_feed .= '</item>';
        }

        if(\CI::input()->post('limit') >= count(\CI::Products()->getProducts()))
        {
            $xml_feed .= ' </channel> </rss>';
        }

        $path = $_SERVER['DOCUMENT_ROOT'] . '/google_feed-'.date('m-d-Y').'.xml';   
        $file = fopen('google_feed-'.date('m-d-Y').'.xml', 'a+');
        fwrite($file, $xml_feed);
        fclose($file);
    }

    public function newSitemap()
    {
        $path = $_SERVER['DOCUMENT_ROOT'] . '/sitemap.xml'; 
        $file = fopen('sitemap.xml', 'w');
        $xml = $this->partial('sitemap_xml_head', [], true);
        echo $xml;
        fwrite($file, $xml);
        fclose($file);
    }

    public function generateProducts()
    {
        $limit = \CI::input()->post('limit');
        $offset = \CI::input()->post('offset');

        $products = \CI::Products()->products(['rows'=>$limit, 'page'=>$offset]);

        $xml = $this->partial('product_xml', ['products'=>$products], true);
        echo $xml;
        $path = $_SERVER['DOCUMENT_ROOT'] . '/sitemap.xml'; 
        $file = fopen('sitemap.xml', 'a');
        fwrite($file, $xml);
        fclose($file);
    }

    public function generateCategories()
    {
        $categories = \CI::Categories()->get_categories_tiered();
        
        $xml = $this->partial('category_xml', ['categories'=>$categories['all']], true);
        echo $xml;
        $path = $_SERVER['DOCUMENT_ROOT'] . '/sitemap.xml'; 
        $file = fopen('sitemap.xml', 'a');
        fwrite($file, $xml);
        fclose($file);
    }

    public function generatePages()
    {
        $pages = \CI::Pages()->get_pages_tiered();
        
        $xml = $this->partial('page_xml', ['pages'=>$pages['all']], true);
        echo $xml;
        $path = $_SERVER['DOCUMENT_ROOT'] . '/sitemap.xml'; 
        $file = fopen('sitemap.xml', 'a');
        fwrite($file, $xml);
        fclose($file);
    }

    public function completeSitemap()
    {
        $xml = $this->partial('sitemap_xml_foot', [], true);

        echo $xml;

        $path = $_SERVER['DOCUMENT_ROOT'] . '/sitemap.xml'; 
        $file = fopen('sitemap.xml', 'a');
        fwrite($file, $xml);
        fclose($file);

        \CI::session()->set_flashdata('message',  lang('success_sitemap_generate'). ' File location '.site_url('sitemap.xml'));
        redirect('admin/sitemap');
    }
    /* Sitemap Ping Feature to come
    public function pingSearchEngines()
    {
        $sitemap_url = urlencode(site_url('sitemap.xml'));

        $searchEngines = [
            'http://www.google.com/webmasters/sitemaps/ping?sitemap=' => 'GET',
            'http://www.bing.com/webmaster/ping.aspx?sitemap=' => 'GET',
            'http://submissions.ask.com/ping?sitemap=' => 'GET',
            'https://blogs.yandex.ru/pings/?status=success&url=' => 'GET',
        ];

        foreach($searchEngines as $url=>$method)
        {
            if($method == 'GET')
            {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,2);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                curl_setopt ($ch, CURLOPT_URL, $google_url);
            }
        }
    }*/
}
/* End of file AdminSitemap.php */
/* Location: ./gocart/modules/controllers/AdminSitemap.php */  
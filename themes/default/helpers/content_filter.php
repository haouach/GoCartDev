<?php

Class content_filter
{
    public $content_filters = [
                                'banners'=>'banner_filter',
                                'category'=>'category_filter',
                                'col'=>'col_filter',
                                '/col'=>'col_close_filter',
                                'element'=>'element_filter',
                                '/element'=>'element_close_filter',
                                'markdown'=>'markdown_filter'
                            ];
    public $content = '';
    
    function __construct($content)
    {
        $this->CI = get_instance();

        //set the content appropriately
        $this->content = $content;

        preg_match_all('/{(.*?)}/s', $content, $matches);

        $caught = false;
        foreach ($matches[1] as $a )
        {
            //trim all the values
            $vars = array_map('trim', explode('|', $a));

            //look for an array key
            if(array_key_exists($vars[0], $this->content_filters))
            {
                $caught = true; //we found an actual filter
                //get the key
                $key = array_shift($vars);

                //define the method
                $method = $this->content_filters[$key];
                
                //run the method with the remaining vars
                $return = $this->$method($vars);

                if($return)
                {
                    $this->content = str_replace('{'.$a.'}', $return, $this->content);
                }
            }
        }

        //if there were no filters, automatically run the whole thing through parsedown
        if(!$caught)
        {
            $this->content = $this->markdown_filter([$content]);
        }
    }

    function markdown_filter($vars)
    {
        if(isset($vars[0]))
        {
            $parse = new Parsedown();
            return $parse->text($vars[0]);
        }
        else
        {
            return '';
        }
    }
    function display()
    {
       return $this->content;
    }

    function banner_filter($vars)
    {
        //set defaults
        $collection = false;
        $quantity = 5;
        $template = 'default';
        
        if(isset($vars[0]))
        {
            //collection ID
            $collection = $vars[0];
        }
        else
        {
            return false; // there is nothing to display
        }

        //set quantity
        if(isset($vars[1]))
        {
            $quantity = $vars[1];
        }

        //set tempalte
        if(isset($vars[2]))
        {
            $template = $vars[2];
        }
        ob_start();
        $this->CI->banners->show_collection($collection, $quantity, $template);
        $banners = ob_get_contents();
        ob_end_clean();

        return $banners;
    }

    function category_filter($vars)
    {
        //set defaults
        $slug = false;
        $sort = 'sequence';
        $direction = 'ASC';
        $page = 0;
        $products_per_page = config_item('products_per_page');
        
        if(isset($vars[0]))
        {
            $slug = $vars[0];
        }
        else
        {
            return false; // there is nothing to display
        }

        if(isset($vars[1]))
        {
            $sort = $vars[1];
        }

        if(isset($vars[2]))
        {
            $direction = $vars[2];
        }

        if(isset($vars[3]))
        {
            $page = $vars[3];
        }

        if(isset($vars[4]))
        {
            $products_per_page = $vars[4];
        }

        $categories = $this->CI->categories->get($slug, $sort, $direction, $page, $products_per_page);

        return $this->CI->load->view('categories/index', $categories, true);
    }

    function col_filter($vars)
    {
        //defaults
        $col_l = '1';
        $col_m = '1';
        $col_s = '1';
        
        if(isset($vars[0]))
        {
            $col_l = $vars[0];
        }

        if(isset($vars[1]))
        {
            $col_m = $vars[1];
        }

        if(isset($vars[2]))
        {
            $col_s = $vars[2];
        }

        $container =  '<div class="col" data-l-cols="'.$col_l.'" data-m-cols="'.$col_m.'" data-s-cols="'.$col_s.'"';
        
        $container .='>';

        return $container;
    }

    function col_close_filter($vars)
    {

        return '</div>';
    }

    function element_filter($vars)
    {
        if(isset($vars[0]))
        {
            return '<div class="element" '.$vars[0].'>';
        }
        else
        {
            return '<div class="element">';
        }
    }

    function element_close_filter($vars)
    {
        return '</div>';
    }
}
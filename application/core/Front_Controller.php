<?php namespace GoCart\Controller;

class Front extends \GoCart\Controller {

    public function __construct()
    {
        parent::__construct();

        //load in some base information
        \CI::load()->model('Pages');
        \CI::load()->helper('theme');
        \CI::lang()->load('common');
        $this->pages = \CI::Pages()->get_pages_tiered();

        //see if the customer is logged in.
        //if the customer is not logged in, then we'll have a temporary guest customer created.
        $this->isLoggedIn = \CI::Login()->isLoggedIn();
    }

    public function view($view, $vars = [], $string=false)
    {
        //pass in the controller so we can access the controllers variables
        //$vars['this'] = $this;

        if($string)
        {
            $result  = $this->views->get('header', $vars);
            $result .= $this->views->get($view, $vars);
            $result .= $this->views->get('footer', $vars);
            
            return $result;
        }
        else
        {
            $this->views->show('header', $vars);
            $this->views->show($view, $vars);
            $this->views->show('footer', $vars);
        }
    }

    public function partial($view, $vars = [], $string=false)
    {
        //$vars['this'] = $this;

        if($string)
        {
            return $this->views->show($view, $vars);
        }
        else
        {
            $this->views->show($view, $vars);
        }
    }

}
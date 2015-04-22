<?php namespace GoCart\Controller;
/**
 * AdminReports Class
 *
 * @package     GoCart
 * @subpackage  Controllers
 * @category    AdminReports
 * @author      Clear Sky Designs
 * @link        http://gocartdv.com
 */

class AdminReports extends Admin {

    var $customer_id    = false;    

    function __construct()
    {       
        parent::__construct();
        \CI::auth()->check_access('Admin', true);
        \CI::load()->model(['Orders', 'Search']);
        \CI::load()->helper(array('formatting'));
        \CI::lang()->load('report');
    }
    
    function index()
    {
        $data['page_title'] = lang('reports');
        $data['years'] = \CI::Orders()->getSalesYears();
        $this->view('reports', $data);
    }
    
    function best_sellers()
    {
        $start = \CI::input()->post('start');
        $end = \CI::input()->post('end');
        $data['best_sellers'] = \CI::Orders()->getBestSellers($start, $end);
        
        $this->view('reports/best_sellers', $data);    
    }
    
    function sales()
    {
        $year = \CI::input()->post('year');
        $data['orders'] = \CI::Orders()->getGrossMonthlySales($year);
        $this->view('reports/sales', $data);   
    }
}
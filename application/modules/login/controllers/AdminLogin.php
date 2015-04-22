<?php namespace GoCart\Controller;
/**
 * AdminLogin Class
 *
 * @package     GoCart
 * @subpackage  Controllers
 * @category    AdminLogin
 * @author      Clear Sky Designs
 * @link        http://gocartdv.com
 */

class AdminLogin extends \GoCart\Controller {

    public function __construct()
    {
        parent::__construct();
        \CI::lang()->load('login');
    }

    public function login()
    {
        $redirect   = \CI::auth()->isLoggedIn(false, false);
        if ($redirect)
        {
            redirect('admin/dashboard');
        }
        
        \CI::load()->helper('form');
        $data['redirect']   = \CI::session()->flashdata('redirect');
        $submitted          = \CI::input()->post('submitted');
        if ($submitted)
        {
            $username   = \CI::input()->post('username');
            $password   = \CI::input()->post('password');
            $remember   = \CI::input()->post('remember');
            $redirect   = \CI::input()->post('redirect');
            $login      = \CI::auth()->login_admin($username, $password, $remember);
            if ($login)
            {
                if ($redirect == '')
                {
                    $redirect = 'admin/dashboard';
                }
                redirect($redirect);
            }
            else
            {
                //this adds the redirect back to flash data if they provide an incorrect credentials
                \CI::session()->set_flashdata('redirect', $redirect);
                \CI::session()->set_flashdata('error', lang('error_authentication_failed'));
                redirect('admin/login');
            }
        }
        $this->views->show('admin/header', $data);
        $this->views->show('admin/login', $data);
        $this->views->show('admin/footer', $data);
    }
    
    public function logout()
    {
        \CI::auth()->logout();
        
        //when someone logs out, automatically redirect them to the login page.
        \CI::session()->set_flashdata('message', lang('message_logged_out'));
        redirect('admin/login');
    }

}

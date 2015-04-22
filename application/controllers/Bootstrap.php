<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bootstrap extends CI_Controller {

	public function init()
	{
		//load in the database.
		$this->load->database();

		//set up routing...
		$router = new \AltoRouter();

		//set the homepage route
		$router->map('GET|POST', '/', 'GoCart\Controller\Page#homepage');

		//Load in the module routes
		$GLOBALS['modules'] = [];
		$paymentModules = [];
		$shippingModules = [];
		foreach(array_diff( scandir(APPPATH.'modules'), ['..', '.']) as $availableModule)
		{
			$path = APPPATH.'modules/'.$availableModule;

			//is the path a file or a directory?
			if(is_dir($path))
			{

				//load in the routes for the module if they exist
				if(file_exists($path.'/routes.php'))
				{
					include($path.'/routes.php');
				}

				//add the package path to CodeIgniter
				$this->load->add_package_path($path);
				$GLOBALS['modules'][] = $path;
			}
		}
		//set payment and shipping modules as global
		$GLOBALS['paymentModules'] = $paymentModules;
		$GLOBALS['shippingModules'] = $shippingModules;

		//autoload some libraries here.
		$this->load->library(array('session', 'auth', 'form_validation'));
		$this->load->model(array('Customers', 'Categories', 'Locations', 'Settings'));
		$this->load->helper(array('url', 'file', 'string', 'html', 'language', 'form', 'formatting'));

		//get settings from the DB
		$settings = $this->Settings->get_settings('gocart');

		//get gift card settings from the DB
		$giftcards = $this->Settings->get_settings('gift_cards');

		//set giftcard availability in the config library
		$this->config->set_item('gift_cards_enabled', $giftcards['enabled']);

		//loop through the settings and set them in the config library
		foreach($settings as $key=>$setting)
		{
			//special for the order status settings
			if($key == 'order_statuses')
			{
				$setting = json_decode($setting, true);
			}

			//other config items get set directly to the config class
			$this->config->set_item($key, $setting);
		}

		//do we have a dev username & password in place?
		//if there is a username and password for dev, require them
		if(config_item('stage_username') != '' && config_item('stage_password') != '')
		{
			if (!isset($_SERVER['PHP_AUTH_USER'])) {
				header('WWW-Authenticate: Basic realm="Login to restricted area"');
				header('HTTP/1.0 401 Unauthorized');
				echo config_item('company_name').' Restricted Location';
				exit;
			} else {
				if(config_item('stage_username') != $_SERVER['PHP_AUTH_USER'] || config_item('stage_password') != $_SERVER['PHP_AUTH_PW'])
				{
					header('WWW-Authenticate: Basic realm="Login to restricted area"');
					header('HTTP/1.0 401 Unauthorized');
					echo 'Restricted Location';
					exit;
				}
			}
		}
		
		//add the theme to the packages path
		$this->load->add_package_path(FCPATH.'themes/'.config_item('theme').'/');

		// lets run the routes
		$match = $router->match();

		// call a closure
		if( $match && is_callable( $match['target'] ) ) {
			call_user_func_array( $match['target'], $match['params'] ); 
		}

		// parse a string and call it
		elseif($match && is_string($match['target']))
		{
			$target = explode('#', $match['target']);
			if(!class_exists( $target[0] ))
			{
				throw_404();
			}
			else
			{
				$class = new $target[0];
				call_user_func_array([$class, $target[1]], $match['params']);
			}
		}

		// throw a 404 error
		else
		{
			throw_404();
		}
	}
}

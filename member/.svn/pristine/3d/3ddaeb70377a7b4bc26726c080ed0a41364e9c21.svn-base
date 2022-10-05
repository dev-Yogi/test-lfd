<?php

	#
	#
	# How to use Google Recaptcha:
	#
	# 1) Add  '//www.google.com/recaptcha/api.js to the $viewCfg['js] array
	#
	# 2) Add this div '<div class='g-recaptcha' data-sitekey='6LcGkhQTAAAAANzuHdzKcI9e_nbPQPxtT0kRcRVu"></div>' 
	#    where you wish the captcha to appear on the page
	#
	# 3) Validate captcha:  
        #    if ( $this->recaptcha_model->validate($this->input->post('g-recaptcha-response') ) )
	#       - the 'g-recaptcha-response' is the default _POST variable name;
	#

	class Recaptcha_model extends CI_Model
	{

		var $site_key      = '6LcGkhQTAAAAANzuHdzKcI9e_nbPQPxtT0kRcRVu'; 
		var $recaptcha_api = 'https://www.google.com/recaptcha/api/siteverify';
		var $secret_key    = '6LcGkhQTAAAAAHcJTHxB-c4HuBlkSzoskJd7rQPY';

		public function __construct() // ------------------------------- __construct
    	{
        	parent::__construct();
    	}

		#
		# Send a validation request to the Google recaptcha API
		#

		public function validate($gcode=null)
		{

			if ( empty($gcode) ) return false;

			$data['secret']   = $this->secret_key;
			$data['response'] = $gcode;
			$data['remoteip'] = $_SERVER['REMOTE_ADDR'];

			$options = array(
				'http'    => array(
        			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        		    'method'  => 'POST',
        		    'content' => http_build_query($data),
    			),
			);

			$context  = stream_context_create($options);

			$result = file_get_contents($this->recaptcha_api, false, $context);

			if ($result === FALSE) return false; 

			$json = json_decode($result);

			if ( $json->success === TRUE ) return true;

			return false;

		}

		public function get_recaptcha_div()
		{
			return "<div class='g-recaptcha' data-sitekey='$this->site_key'></div>"; 
		}

	}

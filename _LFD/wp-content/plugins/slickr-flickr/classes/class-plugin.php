<?php
class Slickr_Flickr_Plugin {

	const CACHE_EXPIRY = 43200;	

 	private $help = SLICKR_FLICKR_HELP;
  	private $home = SLICKR_FLICKR_HOME;	
 	private $icon = SLICKR_FLICKR_ICON;
 	private $name = SLICKR_FLICKR_PLUGIN_NAME;
	private $newsfeeds = array(SLICKR_FLICKR_NEWS,DIYWEBMASTERY_NEWS);
 	private $path = SLICKR_FLICKR_PATH;
 	private $slug = SLICKR_FLICKR_SLUG;
 	private $version = SLICKR_FLICKR_VERSION;
	
	private $defaults = array(	    
		'id' => '',
	    'group' => 'n',
	    'use_key' => '',
	    'api_key' => '',
	    'use_rss' => '',  
	    'search' => 'photos',
 		'photo_id' => '',
	    'tag' => '',
	    'tagmode' => '',
	    'set' => '',
	    'gallery' => '',
	    'license' => '',
	    'date_type' => '',
	    'date' => '',
	    'before' => '',
	    'after' => '',
	    'text' => '',
	    'cache' => 'on',
	    'cache_expiry' => self::CACHE_EXPIRY,
	    'items' => '20',
	    'type' => 'gallery',
	    'captions' => 'on',
	    'lightbox' => 'sf-lightbox',
	    'galleria' => 'galleria-latest',
	    'galleria_theme' => 'classic',
	    'galleria_theme_loading' => 'static',
    	'galleria_themes_folder' => 'galleria/themes',
    	'galleria_options' => '',
    	'options' => '',
    	'delay' => '5',
    	'transition' => '0.5',
    	'start' => '1',
    	'autoplay' => 'on',
    	'pause' => '',
    	'orientation' => 'landscape',
    	'size' => 'medium',
    	'responsive' => '',
 	    'width' => '',
	    'height' => '',
	    'private' => '',
	    'random' => '',
    	'bottom' => '',
    	'thumbnail_size' => '',
    	'thumbnail_scale' => '',
    	'thumbnail_captions' => '',
    	'thumbnail_border' => '',
    	'photos_per_row' => '',
		'class' => '',
    	'align' => '',
    	'border' => '',
    	'descriptions' => '',
    	'ptags' => '',
    	'flickr_link' => '',
    	'flickr_link_title' => 'Click to see the photo on Flickr',
    	'flickr_link_target' => '',
    	'link' => '',
    	'target' => '_self',
    	'attribution' => '',
    	'nav' => '',
    	'sort' => '',
    	'direction' => '',
    	'album_order' => false,
    	'per_page' => 50,
    	'page' => 1,
    	'pagination'=> '',
        'element_id' => '',
    	'restrict' => '',
    	'scripts_in_footer' => false,
    	'silent' => false,
    	'message' => '' 	
	); 

  	private $news;
 	private $options;
  	private $public;
	private $tooltips;
   	private $utils;

	public function init() {
        $d = dirname(__FILE__) . '/';
        require_once($d . 'class-options.php');
        require_once($d . 'class-utils.php');
        require_once($d . 'class-tooltip.php');
        require_once($d . 'class-module.php');
        require_once($d . 'class-cache.php');
        $this->utils = new Slickr_Flickr_Utils();
        $this->tooltips = new Slickr_Flickr_Tooltip();
        $this->options = new Slickr_Flickr_Options( 'slickr_flickr_options', $this->defaults);
        if (!class_exists('SimplePie')) require_once($d . 'simplepie/autoloader.php'); //SimplePie 1.5.4
        if (is_admin()) 
            $this->admin_init(); 
        else 
            $this->public_init();
	}

	public function public_init() {
		$d = dirname(__FILE__) . '/';
		require_once($d . 'class-feed-photo.php');
		require_once($d . 'class-api-photo.php');
		require_once($d . 'class-feed.php');
		require_once($d . 'class-fetcher.php');
		require_once($d . 'class-display.php');
		require_once($d . 'class-public.php');		
		$this->public = new Slickr_Flickr_Public();
	}

	public function admin_init() {
		$d = dirname(__FILE__) . '/';	
		require_once($d . 'class-news.php');
		require_once($d . 'class-admin.php');
		require_once($d . 'class-dashboard.php');
		$this->newsfeeds = apply_filters('slickr_flickr_news',$this->newsfeeds);
		$this->news = new Slickr_Flickr_News($this->version);
		new Slickr_Flickr_Dashboard($this);
 		if ($this->get_activation_key()) add_action('admin_init',array($this, 'upgrade')); 
	}
  	static function get_instance() {
        static $instance = null;
        if (null === $instance) {
            $instance = new self(); 
            register_activation_hook($instance->path, array($instance, 'activate'));            
            add_action('init', array($instance, 'init'),0);              
        }
        return $instance;
  	}
   
  	protected function __construct() {}

  	private function __clone() {}

  	private function __wakeup() {}

	public function get_help(){
		return $this->help;
	}
	
	public function get_home(){
		return $this->home;
	}
	
	public function get_icon(){
		return $this->icon;
	}

	public function get_name(){
		return $this->name;
	}

	public function get_news(){
		return $this->news;
	}		

	public function get_newsfeeds(){
		return $this->newsfeeds;
	}
	
	public function get_options(){
		return $this->options;
	}

	public function get_path(){
		return $this->path;
	}

	public function get_prefix(){
        return sprintf('_%1$s_', $this->slug);
	}

	public function get_public(){
		return $this->public;
	}
	
 	public function get_slug(){
		return $this->slug;
	}


	public function get_tooltips(){
		return $this->tooltips;
	}

	public function get_utils(){
		return $this->utils;
	}

	public function get_version(){
		return $this->version;
	}

    public function activate() { //called on plugin activation
        $this->set_activation_key();
    }
	
    private function deactivate($path ='') {
        if (empty($path)) $path = $this->path;
        if (is_plugin_active($path)) deactivate_plugins( $path );
    }

    private function get_activation_key() { 
        return get_option($this->activation_key_name()); 
    }

    private function set_activation_key() { 
        return update_option($this->activation_key_name(), true); 
    }

    private function unset_activation_key() { 
        return delete_option($this->activation_key_name(), true); 
    }

    private function activation_key_name() { 
        return strtolower(__CLASS__) . '_activation'; 
    }

    public function upgrade() { 
        $this->options->upgrade_options(); //save any new options on plugin update
        $this->unset_activation_key(); //delete key so upgrade runs only on activation		
        $cache = new Slickr_Flickr_Cache();
        $cache->clear_all(); //clear out the cache
    }

}

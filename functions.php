<?php

	
	include('ertabs.inc.php');
	//FOR QUICK DEBUGGING
	if(!function_exists('pre')){
		function pre($data){
			echo '<pre>';
			print_r($data);
			echo '</pre>';	
		}	 
	}

	function register_ertabs_scripts($hook_suffix) {
		
		
		
		wp_register_style( 'ertab-style', plugins_url('css/style.css', __FILE__) );
		
		
		wp_enqueue_script('jquery');	
		
		if(is_admin()){
			wp_enqueue_style( 'ertab-style' );
			
			
				
			wp_enqueue_script(
				'ertab-scripts',
				plugins_url('js/scripts.js', __FILE__)
			);		
						
		}else{
			wp_register_style( 'ertab-responsive-tabs', plugins_url('css/easy-responsive-tabs.css', __FILE__) );
			wp_enqueue_style( 'ertab-responsive-tabs' );
			
			wp_enqueue_script(
				'ertab-easyResponsiveTabs',
				plugins_url('js/easyResponsiveTabs.js', __FILE__)
			);		
		}
		
	}	
		
	function get_include_contents($filename) {
		$filename =  plugin_dir_path(__FILE__).$filename;
		if (is_file($filename)) {
			ob_start();
			include $filename;
			return ob_get_clean();
		}
		return false;
	}	
	
		
	function ertab_plugin_links($links) { 
	  $settings_link = '<a href="admin.php?page=woocommerce_umf">Settings</a>'; 
	  $premium_link = '<a href="http://shop.androidbubbles.com/product/easy-responsive-tabs-accordion/" title="Buy Pro" target=_blank>Buy Pro</a>'; 
	  array_unshift($links, $settings_link,$premium_link); 
	  return $links; 
	}
	
	
?>
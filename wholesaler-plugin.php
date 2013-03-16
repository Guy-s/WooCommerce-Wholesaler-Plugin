<?php
  /*
    Plugin Name: WooCommerce Wholesale Plugin
    Plugin URI: http://seravo.fi
    Description: Experimental WooCommerce plugin for checking wholesaler stocks without a proper API
    Version: 1.0
    Author: Tomi Toivio
    Author URI: http://seravo.fi
    License: GPL2
 */
  /*  Copyright 2012 Tomi Toivio (email: tomi@seravo.fi)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
 



function wh_check_wholesaler_stock () {
	 	global $post, $woocommerce, $product, $woocommerce_loop, $wpdb;

        	$curlurl = get_post_meta($post->ID, 'curlurl', true);
    		$curlpattern = get_post_meta($post->ID, 'curlpattern', true);
        	$curlpatterntwo = get_post_meta($post->ID, 'curlpatterntwo', true);
    		$curlpatternthree = get_post_meta($post->ID, 'curlpatternthree', true);  
    		$curltimestamp = get_post_meta($post->ID, 'curltimestamp', true);
    		$curlresult = get_post_meta($post->ID, 'curlresult', true);


		if (!empty($curlurl) && !empty($curlpattern))
		{
			if ((time() - $curltimestamp) < 3600) 
				{      
				if (preg_match('/' . $curlpattern . '/', $curlresult))
				{  update_post_meta($post->ID, '_stock_status', 'instock'); }
				elseif (preg_match('/' . $curlpatterntwo . '/', $curlresult))
				{  update_post_meta($post->ID, '_stock_status', 'instock'); }
				elseif (preg_match('/' . $curlpatternthree . '/', $curlresult))
				 {  update_post_meta($post->ID, '_stock_status', 'instock'); }
				 else
				{ update_post_meta($post->ID, '_stock_status', 'outofstock'); }
				} else {
			$newcurltime = time();
			update_post_meta($post->ID, 'curltimestamp', $newcurltime);
                	$ch = curl_init();
                	curl_setopt($ch, CURLOPT_URL, $curlurl);
                	curl_setopt($ch, CURLOPT_HEADER, FALSE);
                	curl_setopt($ch, CURLOPT_NOBODY, FALSE);
                	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                	$curlhtml = curl_exec($ch);
                	curl_close($ch);
			$curlsubject = $curlhtml;
			update_post_meta($post->ID, 'curlresult', $curlhtml);
			if (preg_match('/' . $curlpattern . '/', $curlsubject))
			   { update_post_meta($post->ID, '_stock_status', 'instock' );
			   }
			elseif (preg_match('/' . $curlpatterntwo . '/', $curlsubject))
			   { update_post_meta($post->ID, '_stock_status', 'instock' );
			   }
			elseif (preg_match('/' . $curlpatternthree . '/', $curlsubject))
			   { update_post_meta($post->ID, '_stock_status', 'instock' );
			   }
			else
			   { update_post_meta($post->ID, '_stock_status', 'outofstock' );
			   }
			   }
			   }
			   }
			   
add_action( 'woocommerce_before_single_product', 'wh_check_wholesaler_stock' );

?>

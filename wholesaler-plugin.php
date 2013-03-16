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
 
global $post;

function wh_check_wholesaler_stock () {
        	$curlurl = get_post_meta($this->id, 'curlurl', true);
    		$curlpattern = get_post_meta($this->id, 'curlpattern', true);
        	$curlpatterntwo = get_post_meta($this->id, 'curlpatterntwo', true);
    		$curlpatternthree = get_post_meta($this->id, 'curlpatternthree', true);  
    		$curltimestamp = get_post_meta($this->id, 'curltimestamp', true);
    		$curlresult = get_post_meta($this->id, 'curlresult', true);


		if (!empty($curlurl) && !empty($curlpattern))
		{
			if ((time() - $curltimestamp) < 3600) 
				{      
				if ((preg_match($curlpattern, $curlresult)))
				{ return true; }
				elseif ((preg_match($curlpatterntwo, $curlresult)))
				{ return true; }
				elseif ((preg_match($curlpatternthree, $curlresult)))
				 { return true; }
				 else
				{ return false; }
				} else {
			$newcurltime = time();
			update_post_meta($this->id, 'curltimestamp', $newcurltime);
                	$ch = curl_init();
                	curl_setopt($ch, CURLOPT_URL, $curlurl);
                	curl_setopt($ch, CURLOPT_HEADER, FALSE);
                	curl_setopt($ch, CURLOPT_NOBODY, FALSE);
                	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                	$curlhtml = curl_exec($ch);
                	curl_close($ch);
			$curlsubject = $curlhtml;
			update_post_meta($this->id, 'curlresult', $curlhtml);
			if (preg_match($curlpattern, $curlsubject))
			   { return true; 
				update_post_meta( $this->id, '_stock_status', 'instock' );
			   }
			elseif ((preg_match($curlpatterntwo, $curlsubject)))
			   { return true; 
			   	update_post_meta( $this->id, '_stock_status', 'instock' );
			   }
			elseif ((preg_match($curlpatternthree, $curlsubject)))
			   { return true; 
			   	update_post_meta( $this->id, '_stock_status', 'instock' );
			   }
			else
			   { return false; 
			   	update_post_meta( $this->id, '_stock_status', 'outofstock' );
			   }
}
add_action( 'woocommerce_product_set_stock_status', 'wh_check_wholesaler_stock' );

?>

<?php
/**
 * Plugin Name: Remote Comments (FeedWordPress Addon)
 * Plugin URI: http://projects.marthaburtis.net/plugins/remote-comments
 * Description: This plugin works with FeedWordPress to display a comment count for syndicated content based on comments on the "host" content provider.
 * Version: 1.0	
 * Author: Martha Burtis/UMW DTLT
 * Author URI: http://marthaburtis.net
 * License: GPL2
 */

add_filter('get_comments_number', 'rc_comment_count', 10, 2);

function rc_comment_count($count){
	global $id;
	if (is_syndicated()) { 
		
		if (get_post_meta($id, 'wfw:commentRSS')) { 
			$count = get_post_meta($id, 'rc_comment_count', true);
			$rc_last_count_stamp = get_post_meta($id, 'rc_comment_count_stamp', true);
			$current_stamp = date('U');
			$stamp_difference = $current_stamp - $rc_last_count_stamp[0];		
			if ($stamp_difference > '600'){
				$comments_url = get_post_meta($id, 'wfw:commentRSS', true);  
				$comments_rss = fetch_feed($comments_url); 				
					if (!is_wp_error($comments_rss)) { 
					 $count =  $comments_rss->get_item_quantity();	
					 update_post_meta($id, 'rc_comment_count', $count);
			         update_post_meta($id, 'rc_comment_count_stamp', date('U'));
					}
		    } 
		
		}
	  } 
 return $count;
}



?>

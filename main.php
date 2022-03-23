<?php
/*
 * Plugin Name: YouTube Hub add-on - embed videos in post content as URL
 * Plugin URI: https://wpythub.com
 * Description: Import YouTube videos into post and embed them by directly placing the video URL into the post content.
 * Author: CodeFlavors
 * Version: 1.0
 * Author URI: https://wpythub.com
 */

namespace WPYTHub_URL_Embed;

class Plugin{

	/**
	 * Constructor, initializes all actions and filters.
	 */
	public function __construct(){

		add_action(
			'cbc_video_post_content',
			[$this, 'add_url_to_post_content'],
			10, 3
		);

		add_filter( 'ccb_embed_videos', '__return_false' );
	}

	/**
	 * Filter the post content to be inserted into the database.
	 *
	 * @param string        $post_content   The post content.
	 * @param array         $video          The video details.
	 * @param null|array    $theme_import   Theme import details.
	 *
	 * @return string
	 */
	public function add_url_to_post_content( $post_content, $video, $theme_import ){
		if( !$theme_import ){
			$post_content = sprintf(
				'https://www.youtube.com/watch?v=%s',
				$video['video_id']
			) . "\n\n" . $post_content;

			global $wp_embed;
			$post_content = $wp_embed->run_shortcode( $post_content );
			$post_content = $wp_embed->autoembed( $post_content );
			$post_content = do_blocks( $post_content );
			$post_content = wptexturize( $post_content );
			$post_content = convert_smilies( $post_content );
			$post_content = shortcode_unautop( $post_content );
			$post_content = wp_filter_content_tags( $post_content );
			$post_content = do_shortcode( $post_content );
			$post_content = str_replace( ']]>', ']]&gt;', $post_content );
		}

		return $post_content;
	}

}

new Plugin();
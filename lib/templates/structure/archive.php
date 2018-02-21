<?php

add_action( 'wp', 'pp_set_up_archive_structure' );
/**
 * Set up archive structure
 *
 * @since 1.0.0
 *
 * @return void
 */
function pp_set_up_archive_structure() {
	if(is_home() || is_archive()){
		//featured image on top of posts on blog page

		/// add_action( 'beans_post_prepend_markup', 'beans_post_image' );

		beans_modify_action_callback('beans_post_content','pp_modify_beans_post_content');
		/**
		 * Replace the content by the excerpt and the more link.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		function pp_modify_beans_post_content(){
			global $post;

			beans_open_markup_e( 'beans_post_content', 'div', array(
				'class'    => 'tm-article-content',
				'itemprop' => 'text',
			) );

			the_excerpt();
			echo beans_post_more_link( );

			if ( is_singular() && 'open' === get_option( 'default_ping_status' ) && post_type_supports( $post->post_type, 'trackbacks' ) ) {
				echo '<!--';
				trackback_rdf();
				echo '-->' . "\n";
			}

			beans_close_markup_e( 'beans_post_content', 'div' );
		}
	}
}

add_filter( 'beans_post_more_link_text_output', 'pp_modify_more_link_text' );
/**
 * Modify more link text.
 *
 * @since 1.0.0
 *
 * @return string
 */
function pp_modify_more_link_text(){
	return 'Read More';
}

add_filter( 'excerpt_length', 'pp_modify_excerpt_length' );
/**
 * Filter the except length.
 *
 * @param int $length Excerpt length.
 * @return int (Maybe) modified excerpt length.
 */
function pp_modify_excerpt_length($length){
	return 60;
}

add_filter( 'excerpt_more', 'pp_modify_excerpt_more' );
/**
 * Filter the excerpt "Read More" string.
 *
 * @param string $more "Read more" excerpt string.
 * @return string (Maybe) modified "Read More" excerpt string.
 */
function pp_modify_excerpt_more( $more ) {
	return ' [....]';
}

// Display Read More as a button and align it to the right.
beans_add_attribute('beans_post_more_link','class','uk-button uk-button-primary uk-align-right ');

// Wrap meta tags and meta categories together and align them to the left.
beans_wrap_markup('beans_post_meta_categories','beans_meta_wrap','div', array('class' => 'uk-align-left'));
beans_modify_action_hook('beans_post_meta_tags','beans_meta_wrap_append_markup');

<?php

class Page_Generation {
	/**
	 * Static property to hold our singleton instance
	 */
	public static $instance = false;

	/**
	 * This is our constructor
	 *
	 * @return void
	 */
	private function __construct() {
		add_action( 'init', [ $this, 'generate_page' ] );
	}

	/**
	 * If an instance exists, this returns it.  If not, it creates one and
	 * retuns it.
	 *
	 * @return Page_Generation
	 */
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Listen for the $_POST request that signals our button was clicked and 
	 * kick off the page generation process if it was.
	 */
	public function generate_page() {
		if( isset( $_POST['generate_kitchen_sink'] ) ) {
			$kitchen_sink_page = get_page_by_path( 'kitchen-sink' );

			if( empty( $kitchen_sink_page ) ) {
				wp_insert_post( [
					'post_title'   => 'Kitchen Sink',
					'post_status'  => 'private',
					'post_content' => '',
					'post_name'    => 'kitchen-sink',
					'post_type'    => 'page',
				], false, true );
			}

			
			// TO-DO: Hardcode h1-h6 and p blocks in here
			$blocks = [

			];

			// Grab the IDs for every page
			$pages = new WP_Query([
				'post_type'      => 'page',
				'posts_per_page' => -1,
				'post__not_in'   => [ $kitchen_sink_page->ID ],
				'fields'         => 'ids'
			]);

			foreach( $pages->posts as $page_id ) {
				$page = get_post( $page_id );
			
				$page_blocks      = parse_blocks( $page->post_content );
				$page_block_names = wp_list_pluck( $page_blocks, 'blockName', null );

				$page_block_names = array_unique( array_filter( $page_block_names ) );

				foreach( $page_block_names as $page_block_name ) {
					$existing_blocks = array_unique( array_filter( wp_list_pluck( $blocks, 'name', null ) ) );

					if( in_array( $page_block_name, $existing_blocks ) ) {
						$block_index = array_search($page_block_name, array_column($blocks, 'name'));
						$blocks[$block_index]['other_pages'][] = $page_id;
						continue;
					}

					$block_index = array_search($page_block_name, array_column($page_blocks, 'blockName'));
					$block_instance = $page_blocks[$block_index];
					$block_id = $block_instance['attrs']['id'];

					// Since serialize_block wasn't working for us, we need
					// to regex into the post content and grab all blocks of
					// the type we're looking for
					preg_match_all("/(<!-- wp:" . str_replace("/", "\/", $block_instance['blockName']) . " .*?\/-->)/s", $page->post_content, $matches);

					$block_markup = '';

					// To make sure we only get the markup of the particular block
					// instance that we're looking for, check to see if that ID is
					// found in the block
					foreach( $matches as $match ) {
						if( false !== strpos($match, $block_id ) ) {
							$block_markup = $match[0];
						}
					}

					$blocks[] = [
						'name'        => $page_block_name,
						'markup'      => $block_markup,
						'page'        => $page_id,
						'other_pages' => []
					];
				}
			}

			$all_blocks_markup = implode(' ', wp_list_pluck( $blocks, 'markup', null ) );

			global $wpdb;
			$wpdb->update(
				'wp_posts',
				[
        			'post_content' => $all_blocks_markup
    			],
    			[
    				'ID' => $kitchen_sink_page->ID
    			]
    		);
    	}
	}
}
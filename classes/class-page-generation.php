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
			
			$core_blocks = [
				[
					'name'        => 'core/heading',
					'markup'      => '<!-- wp:heading --><h1 style="text-decoration: underline;">Core Blocks</h1><!-- /wp:heading -->',
					'page'        => -1,
					'other_pages' => []
				],
				[
					'name'        => 'core/heading',
					'markup'      => '<!-- wp:heading --><h1>This is an example of an h1 heading.</h1><!-- /wp:heading -->',
					'page'        => -1,
					'other_pages' => []
				],
				[
					'name'        => 'core/heading',
					'markup'      => '<!-- wp:heading --><h2>This is an example of an h2 heading.</h2><!-- /wp:heading -->',
					'page'        => -1,
					'other_pages' => []
				],
				[
					'name'        => 'core/heading',
					'markup'      => '<!-- wp:heading --><h3>This is an example of an h3 heading.</h3><!-- /wp:heading -->',
					'page'        => -1,
					'other_pages' => []
				],
				[
					'name'        => 'core/heading',
					'markup'      => '<!-- wp:heading --><h4>This is an example of an h4 heading.</h4><!-- /wp:heading -->',
					'page'        => -1,
					'other_pages' => []
				],
				[
					'name'        => 'core/heading',
					'markup'      => '<!-- wp:heading --><h5>This is an example of an h5 heading.</h5><!-- /wp:heading -->',
					'page'        => -1,
					'other_pages' => []
				],
				[
					'name'        => 'core/heading',
					'markup'      => '<!-- wp:heading --><h6>This is an example of an h6 heading.</h6><!-- /wp:heading -->',
					'page'        => -1,
					'other_pages' => []
				],
				[
					'name'        => 'core/paragraph',
					'markup'      => '<!-- wp:paragraph --><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p><!-- /wp:paragraph -->',
					'page'        => -1,
					'other_pages' => []
				],
			];

			$custom_blocks = [];

			// Grab the IDs for every page
			$pages = new WP_Query([
				'post_type'      => 'page',
				'posts_per_page' => -1,
				'post__not_in'   => [ $kitchen_sink_page->ID ],
				'fields'         => 'ids',
				'post_status'    => 'publish',
			]);

			foreach( $pages->posts as $page_id ) {
				$page = get_post( $page_id );
			
				$page_blocks      = parse_blocks( $page->post_content );
				$page_block_names = wp_list_pluck( $page_blocks, 'blockName', null );

				$page_block_names = array_unique( array_filter( $page_block_names ) );

				foreach( $page_block_names as $page_block_name ) {
					$existing_blocks = array_unique( array_filter( wp_list_pluck( $custom_blocks, 'name', null ) ) );

					if( in_array( $page_block_name, $existing_blocks ) ) {
						$block_index = array_search($page_block_name, array_column($custom_blocks, 'name'));
						$custom_blocks[$block_index]['other_pages'][] = $page_id;
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

					$block_index = array_search($page_block_name, array_column($page_blocks, 'blockName'));
					$block_instance = $page_blocks[$block_index];

		            if( false !== strpos($page_block_name, 'core/' ) ) {
		            	$core_block_names = wp_list_pluck( $core_blocks, 'name', null );

						if( ! in_array($page_block_name, $core_block_names ) ) {
							$core_blocks[] = [
								'name'        => $page_block_name,
								'markup'      => $block_markup,
								'page'        => $page_id,
								'other_pages' => []
							];
			            }
			        } else {
		            	$custom_blocks[] = [
							'name'        => $page_block_name,
							'markup'      => $block_markup,
							'page'        => $page_id,
							'other_pages' => []
						];
		            }
				}
			}

			$core_blocks[] = [
				'name'        => 'core/heading',
				'markup'      => '<!-- wp:heading --><h1 style="text-decoration: underline;">Custom Blocks</h1><!-- /wp:heading -->',
				'page'        => -1,
				'other_pages' => []
			];

			$blocks = array_merge($core_blocks, $custom_blocks);

			$blocks = array_map(function($block){
				$page_block_title = str_replace('acf/','',$block['name']);
	            $page_block_title = str_replace('-',' ',$page_block_title);
	            $page_block_title = ucwords($page_block_title);
	            $page_block_title = "<!-- wp:heading --><h2>" . $page_block_title . "</h2><!-- /wp:heading -->";

	            $block_instance_links = $this->generate_block_instance_links($block);

	            $block['markup'] = $page_block_title . $block_instance_links . $block['markup'];

	            return $block;
			}, $blocks);

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

	public function generate_block_instance_links( $block ) {
		if( -1 === $block['page'] ) {
			return '';
		}

		$block_page_id = $block['page'];

		$link_markup = '<!-- wp:paragraph --><p>This block appears on the <a href="' . get_the_permalink( $block_page_id ) . '">' . get_the_title( $block_page_id ) . '</a> page';

		$expanded_markup = [];

		if( count( $block['other_pages'] ) > 0 ) {
			$link_markup .= ' and ' . count( $block['other_pages'] ) . ' others <span class="view-all-instances" style="cursor:pointer">(expand)</span>';

			foreach( $block['other_pages'] as $other_page ) {
				$expanded_markup[] = "<a href='" . get_the_permalink( $other_page ) . "'>" . get_the_title( $other_page )  . '</a>';
			}

			$expanded_markup = '<div style="display:none;" class="other-pages-list">' . implode(', ', $expanded_markup) . '</div>';
		}

		$link_markup .= '</p>';

		if( count( $block['other_pages'] ) > 0 ) {
			$link_markup .= $expanded_markup;
		}

		$link_markup .= '<!-- /wp:paragraph -->';

		return $link_markup;
	}
}
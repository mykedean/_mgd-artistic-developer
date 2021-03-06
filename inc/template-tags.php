<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Artistic_Developer
 */

if ( ! function_exists( '_mgd_artistic_developer_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function _mgd_artistic_developer_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			/* translators: %s: post date. */
			esc_html_x( 'Posted on %s', 'post date', '_mgd-artistic-developer' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;

if ( ! function_exists( '_mgd_artistic_developer_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function _mgd_artistic_developer_posted_by() {
		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x( 'by %s', 'post author', '_mgd-artistic-developer' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;

if ( ! function_exists( '_mgd_artistic_developer_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function _mgd_artistic_developer_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', '_mgd-artistic-developer' ) );
			if ( $categories_list ) {
				/* translators: 1: list of categories. */
				printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', '_mgd-artistic-developer' ) . '</span>', $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', '_mgd-artistic-developer' ) );
			if ( $tags_list ) {
				/* translators: 1: list of tags. */
				printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', '_mgd-artistic-developer' ) . '</span>', $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', '_mgd-artistic-developer' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post( get_the_title() )
				)
			);
			echo '</span>';
		}

		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Edit <span class="screen-reader-text">%s</span>', '_mgd-artistic-developer' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				wp_kses_post( get_the_title() )
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
endif;

if ( ! function_exists( '_mgd_artistic_developer_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function _mgd_artistic_developer_post_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :
			?>

			<div class="post-thumbnail">
				<?php the_post_thumbnail(); ?>
			</div><!-- .post-thumbnail -->

		<?php else : ?>

			<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php
					the_post_thumbnail(
						'post-thumbnail',
						array(
							'alt' => the_title_attribute(
								array(
									'echo' => false,
								)
							),
						)
					);
				?>
			</a>

			<?php
		endif; // End is_singular().
	}
endif;

if ( ! function_exists( 'wp_body_open' ) ) :
	/**
	 * Shim for sites older than 5.2.
	 *
	 * @link https://core.trac.wordpress.org/ticket/12563
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
endif;


if ( ! function_exists( '_mgd_artistic_developer_featured_posts' ) ) :
	/**
	 * Create a <section> to hold featured projects, then render the projects using _mgd_artistic_developer_featured_posts_from_category()
	 *
	 * @see _mgd_artistic_developer_featured_posts_from_category()
	 */
	function _mgd_artistic_developer_featured_posts( $category_name, $which_section, $number_of_posts_to_show = 3  ) {

		?>


		<section id="projects" class="featured-content-section" data-scroll-section data-scroll-sticky>

			<!-- section title -->
	    	<div class="scrolling-titles" data-scroll>
	    		<?php
		    		$category = get_category_by_slug( $category_name ); 
					$category_name = $category->name;
	    		?>
	    		<h2 data-scroll><?php echo $category->name;	?></h2>
	    	</div><!-- .scrolling-titles -->

	    	<!-- grid layout with posts -->
	    	<div id="featured-content-grid-wrapper" <?php echo $which_section;?> class="featured-content-grid-wrapper">

			<?php

			_mgd_artistic_developer_featured_posts_from_category( $category_name, $number_of_posts_to_show );

				?>
			</div><!-- .featured-content-grid-wrapper -->
				        
		</section>

		<?php

	}
	endif;

	if ( ! function_exists( '_mgd_artistic_developer_featured_posts_from_category' ) ) :
	/**
	 * Render the featured projects
	 */
	function _mgd_artistic_developer_featured_posts_from_category( $category_name, $number_of_posts_to_show ) {

		$post_number = 1;		//initialize loop counter to identify which post is being rendered in div id
		$scroll_speed = 1;		//initialize scroll speed - each grid column scrolls at a different speed


		/* get posts from the category for section 1 */
		// the query
        $the_query = new WP_Query(array(
            'category_name' => $category_name,
            'post_status' => 'publish',
            'posts_per_page' => $number_of_posts_to_show,
        ));

        if ($the_query->have_posts()) :
            while ($the_query->have_posts()) : $the_query->the_post();

            	/* Scroll speed - calculate speed of parallax based on even or odd grid column */
            	if( $post_number % 2 == 0 ) {
            		$scroll_speed = 2;
            	} else {
            		$scroll_speed = 1;
            	}

            	/* Create Grid Item <div> */
            	?>
				<div id=<?php echo "\"featured-content-grid-item-" . $post_number . "\"" ?> class="featured-content-grid-item" data-scroll data-scroll-speed=<?php echo "\"" . $scroll_speed*2  . "\"" ?>>

		    		<article class="featured-post">
					<?php

					/* Featured image */
	             	the_post_thumbnail();

	             	/* The title */
	             	?><h2><?php the_title(); ?> </h2> <?php

	             	/* Excerpt */
	            	the_excerpt();

	             	?>
					</article><!-- .featured-post -->
					
				</div><!-- .featured-content-grid-item -->
				<?php


				//increment the loop counter
				$post_number++;

            endwhile;
            
            wp_reset_postdata();

        else : ?>
            <p><?php __('No News'); ?></p>
        <?php 
    	endif;
		}
	endif;

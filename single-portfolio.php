<?php
/**
 * The template for displaying all single portfolio items
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage portfolio_plugin
 * @since 1.0
 * @version 1.0
 */

get_header(); ?>

<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="portfolio-site-main" role="main">

			<?php
			/* Start the Loop */
			while ( have_posts() ) :
				the_post(); ?>

			<article id="portfolio-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="wrap-top">
				
				<div class="portfolio-post-thumbnail">

				<?php
				if ( get_post_meta( $post->ID, 'add_file_id', true ) !== null ) {

					$add_file_link_id = get_post_meta( $post->ID, 'add_file_id', true );
					$add_file_link = wp_get_attachment_url($add_file_link_id);
					$file_format = get_post_mime_type($add_file_link_id);

				}
				?>

				<?php if ( get_post_meta( $post->ID, 'add_file_id', true ) == null ) : ?>
					<a href="<?php the_post_thumbnail_url( 'full' ); ?>" data-fancybox="gallery">
						<?php the_post_thumbnail( 'full' ); ?>
					</a>
				<?php elseif ( $file_format !== "audio/mpeg" ) : ?>
					<a href="<?php echo $add_file_link; ?>" data-fancybox="gallery">
						<?php the_post_thumbnail( 'full' ); ?>
					</a>
				<?php else : ?>
					<a href="#holder-<?php the_ID(); ?>" data-fancybox="gallery">
						<?php the_post_thumbnail( 'full' ); ?>
					</a>

					<div class="hide">
						<div id="holder-<?php the_ID(); ?>">
							<div class="item item1">
								<audio class="player" controls>
									<source src="<?php echo $add_file_link; ?>" type="audio/mp3">
								</audio>
							</div>
						</div>
					</div>
				<?php endif; ?>

				</div><!-- .post-thumbnail -->

					<header class="portfolio-entry-header">
						<?php
						if ( 'portfolio' === get_post_type() ) {
							echo '<div class="portfolio-entry-meta">';
							echo '<p>';
								print get_the_author();
							echo '</p>';
							echo '<p>';
								print get_the_date();
							echo '</p>';
							echo '</div><!-- .entry-meta -->';
						};
						?>
					</header><!-- .entry-header -->
				</div><!-- wrap-top -->
			
				<div class="portfolio-entry-content">
				<?php

				$terms = get_the_terms( $post->ID , 'portfolio_category' );
				// Loop over each item since it's an array
				if ( $terms != null ){
					echo '<div class="portfolio-categories">';

					foreach( $terms as $term ) {
					// Print the name method from $term which is an OBJECT
					echo '<a class="portfolio-categories-link" href="' . get_term_link($term) . '">';
						print $term->name;
					echo '</a>';
					// Get rid of the other data stored in the object, since it's not needed
					unset($term); }

					echo '</div>';
				}

				?>

					<?php
			
					if ( is_single() ) {
						the_title( '<h1 class="portfolio-entry-title">', '</h1>' );
					} elseif ( is_front_page() && is_home() ) {
						the_title( '<h3 class="portfolio-entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
					} else {
						the_title( '<h2 class="portfolio-entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
					}

					/* translators: %s: Name of current post */
					
					the_content();
			
					wp_link_pages(
						array(
							'before'      => '<div class="page-links">' . 'Pages:',
							'after'       => '</div>',
							'link_before' => '<span class="page-number">',
							'link_after'  => '</span>',
						)
					);
					?>
				</div><!-- .entry-content -->
			
			</article><!-- #post-## -->

				<?php // If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

				the_post_navigation(
					array(
						'prev_text' => '<span class="nav-button">' . '< Prev portfolio item' . '</span>',
						'next_text' => '<span class="nav-button">' . 'Next portfolio item >' . '</span>',
					)
				);

			endwhile; // End of the loop.
			?>

		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .wrap -->

<?php
get_footer();

<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Get theme options
$wr_nitro_options = WR_Nitro::get_options();

$shop_page_display = false;

$term = get_queried_object();
if ( ! is_shop() ) {
	$display_type = get_woocommerce_term_meta( $term->term_id, 'display_type', true );
}

if ( is_tax( 'product_cat' ) && $display_type ) {
	$display_type = get_woocommerce_term_meta( $term->term_id, 'display_type', true );
} else {
	$display_type = get_option( 'woocommerce_category_archive_display' );
}

if ( get_option( 'woocommerce_shop_page_display' ) || 'subcategories' == $display_type || 'both' == $display_type ) {
	$shop_page_display = true;
}

if ( $shop_page_display ) {
	// Get category style
	$cat_style = $wr_nitro_options['wc_categories_style'];
	if ( 'masonry' == $cat_style ) {
		$wr_attr    = ' data-masonry=\'{"selector":".cat-item", "columnWidth":".grid-sizer"}\'';
		$wr_classes = 'row categories wr-nitro-masonry';
	} else {
		$wr_attr = '';
		$wr_classes = 'row categories wr-nitro-grid';
	}

	// Category column
	$wr_cat_columns = $wr_nitro_options['wc_categories_layout_column'];
}

// Get product categories
$wc_categories = get_terms( 'product_cat');

get_header( 'shop' ); ?>

	<?php if ( $wr_nitro_options['wc_archive_page_title'] ) : ?>

		<?php WR_Nitro_Render::get_template( 'common/page', 'title' ); ?>

	<?php endif; ?>

	<?php if ( wp_is_mobile() && $wr_nitro_options['wc_archive_mobile_categories'] && ! empty( $wc_categories ) ) : ?>
		<div class="mobile-product-categories pr body_bg">
			<div>
				<?php
					echo '<a href="' . get_permalink( get_option( 'woocommerce_shop_page_id' ) ) . '">' . __( 'All items', 'wr-nitro' ) . '</a>';
					foreach ( $wc_categories as $category ) {
						if ( empty( $category->parent ) ) {
							echo '<a href="' . get_term_link( $category->term_id, 'product_cat' ) . '">' . esc_html( $category->name ) . '</a>';
						}
					}
				?>
			</div>
		</div>
	<?php endif; ?>

	<?php
		/**
		 * woocommerce_before_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		do_action( 'woocommerce_before_main_content' );
	?>

		<?php if ( have_posts() ) : ?>

			<?php do_action( 'woocommerce_before_product_list' ); ?>

			<div class="fc fcw jcfe aic shop-actions">
				<?php
					if( ! ( get_option( 'woocommerce_enable_ajax_add_to_cart' ) == 'no' || get_option( 'woocommerce_enable_ajax_add_to_cart_single' ) == 'no' ) ) {
						remove_action( 'woocommerce_before_shop_loop', 'wc_print_notices', 10 );
					}

					/**
					 * woocommerce_before_shop_loop hook
					 *
					 * @hooked woocommerce_result_count - 20
					 * @hooked woocommerce_catalog_ordering - 30
					 */
					do_action( 'woocommerce_before_shop_loop' );
				?>
			</div>

			<?php if ( $shop_page_display ) { ?>
				<div class="<?php echo esc_attr( $wr_classes ) . ( is_customize_preview() ? ' customizable customize-section-product_categories' : '' ); ?>"<?php echo wp_kses_post( $wr_attr ); ?>>
					<?php
						if ( $cat_style == 'masonry' ) {
							echo '<div class="grid-sizer cxs-12 cs-6 cm-' . (int) ( 12 / $wr_cat_columns ) . '"></div>';
						}
						woocommerce_product_subcategories();
					?>
				</div>
			<?php } ?>

			<?php woocommerce_product_loop_start(); ?>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php wc_get_template_part( 'content', 'product' ); ?>

				<?php endwhile; // end of the loop. ?>

			<?php woocommerce_product_loop_end(); ?>

			<?php
				/**
				 * woocommerce_after_shop_loop hook
				 *
				 * @hooked woocommerce_pagination - 10
				 */
				do_action( 'woocommerce_after_shop_loop' );
			?>

		<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

			<?php wc_get_template( 'loop/no-products-found.php' ); ?>

		<?php endif; ?>

	<?php
		/**
		 * woocommerce_after_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'woocommerce_after_main_content' );
	?>

	<?php
		/**
		 * woocommerce_sidebar hook
		 *
		 * @hooked woocommerce_get_sidebar - 10
		 */
		do_action( 'woocommerce_sidebar' );
	?>

<?php get_footer( 'shop' ); ?>

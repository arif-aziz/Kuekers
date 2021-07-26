<?php
/**
 * Grouped product add to cart
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $post;

$parent_product_post = $post;

// Get theme option
$wr_nitro_options = WR_Nitro::get_options();

// Catalog mode
$wr_catalog_mode = $wr_nitro_options['wc_archive_catalog_mode'];
if ( $wr_catalog_mode ) return;

// Get single style
$single_style = get_post_meta( get_the_ID(), 'single_style', true );
if ( $single_style == 0 ) {
	$single_style = $wr_nitro_options['wc_single_style'];
} else {
	$single_style = get_post_meta( get_the_ID(), 'single_style', true );
}

// Icon Set
$icons = $wr_nitro_options['wc_icon_set'];

do_action( 'woocommerce_before_add_to_cart_form' );
?>

<?php
	if ( $single_style != 1 ) {
		echo '<div class="p-single-action nitro-line btn-inline pdb20 fc aic aife">';
	}
?>
	<form class="cart" method="post" enctype='multipart/form-data'>
		<table cellspacing="0" class="group_table">
			<tbody>
				<?php
					foreach ( $grouped_products as $product_id ) :
						if ( ! $product = wc_get_product( $product_id ) ) {
							continue;
						}

						if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) && ! $product->is_in_stock() ) {
							continue;
						}

						$post    = $product->post;
						setup_postdata( $post );
						?>
						<tr>
							<td class="group-thumbnail">
								<?php
									if ( has_post_thumbnail() ) {
										echo apply_filters( 'woocommerce_group_product_image_thumbnail', sprintf( '<a href="%s">%s</a>', get_permalink(), get_the_post_thumbnail( $post->ID, 'shop_thumbnail' ) ) );
									}
								?>
							</td>

							<td class="group-title">
								<label class="db" for="product-<?php echo $product_id; ?>">
									<?php echo $product->is_visible() ? '<a href="' . esc_url( apply_filters( 'woocommerce_grouped_product_list_link', get_permalink(), $product_id ) ) . '">' . get_the_title() . '</a>' : get_the_title(); ?>
								</label>
								<?php
									echo $product->get_price_html();

									if ( $availability = $product->get_availability() ) {
										$availability_html = empty( $availability['availability'] ) ? '' : '<p class="stock ' . esc_attr( $availability['class'] ) . '">' . esc_html( $availability['availability'] ) . '</p>';
										echo apply_filters( 'woocommerce_stock_html', $availability_html, $availability['availability'], $product );
									}
								?>
							</td>

							<td class="group-quantity">
								<?php if ( $product->is_sold_individually() || ! $product->is_purchasable() ) : ?>
									<?php woocommerce_template_loop_add_to_cart(); ?>
								<?php else : ?>
									<?php
										$quantites_required = true;
										woocommerce_quantity_input( array(
											'input_name'  => 'quantity[' . $product_id . ']',
											'input_value' => ( isset( $_POST['quantity'][$product_id] ) ? wc_stock_amount( $_POST['quantity'][$product_id] ) : 0 ),
											'min_value'   => apply_filters( 'woocommerce_quantity_input_min', 0, $product ),
											'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->backorders_allowed() ? '' : $product->get_stock_quantity(), $product )
										) );
									?>
								<?php endif; ?>
							</td>
						</tr>
						<?php
					endforeach;

					// Reset to parent grouped product
					$post    = $parent_product_post;
					$product = wc_get_product( $parent_product_post->ID );
					setup_postdata( $parent_product_post );
				?>
			</tbody>
		</table>

		<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->id ); ?>" />

		<?php if ( $quantites_required ) : ?>

			<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

			<button type="submit" class="single_add_to_cart_button button alt <?php echo ( ! WR_Nitro_Helper::check_gravityforms( $post->ID ) ? 'wr_single_add_to_cart_ajax' : NULL ); ?>"><i class="nitro-icon-<?php echo esc_attr( $icons ); ?>-cart mgr10 mgt10"></i><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>

			<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

		<?php endif; ?>
	</form>

	<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
<?php
	if ( $single_style != 1 ) {
		echo '</div>';
	}
?>
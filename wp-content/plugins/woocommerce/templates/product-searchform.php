<?php
/**
 * The template for displaying product search form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/product-searchform.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	<form class="woocommerce-product-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<div class="input-group">
			<input type="search" class="search-field form-control pull-right" style="width: 370px; background-color: #e5e5e5;" placeholder="<?php echo esc_attr__( 'Search products&hellip;', 'woocommerce' ); ?>" 
				   id="woocommerce-product-search-field-<?php echo isset( $index ) ? absint( $index ) : 0; ?>" value="<?php echo get_search_query(); ?>" name="s">
			<span class="input-group-btn">
				<button type="reset" class="btn btn-default">
					<span class="glyphicon glyphicon-remove">
						<span class="sr-only">Close</span>
					</span>
				</button>
				<button type="submit" class="btn btn-default" value="<?php echo esc_attr_x( 'Search', 'submit button', 'woocommerce' ); ?>">
					<span class="glyphicon glyphicon-search">
						<span class="sr-only">Search</span>
					</span>
				</button>
			</span>
			<input type="hidden" name="post_type" value="product" />
		</div>
	</form>
</div>
<?php
/**
 * @version    1.0
 * @package    WR_Share_For_Discounts
 * @author     WooRockets Team <support@woorockets.com>
 * @copyright  Copyright (C) 2014 WooRockets.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.woorockets.com
 */
?>
<ul class="subsubsub">
	<li>
		<a class="<?php if ( 'sharing' == $section ) echo 'current'; ?>" href="<?php echo '' . $url . '&section=sharing'; ?>">
			<?php _e( 'Sharing', 'wr-share-for-discounts' ); ?>
		</a>
		|
	</li>
	<li>
		<a class="<?php if ( 'discount' == $section ) echo 'current'; ?>" href="<?php echo '' . $url . '&section=discount'; ?>">
			<?php _e( 'Discount', 'wr-share-for-discounts' ); ?>
		</a>
	</li>
</ul>
<br class="clear">
<?php
// Load template file for current section.
include_once WR_S4D_PATH . "templates/settings/{$section}.php";

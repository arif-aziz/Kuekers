<?php
/**
 * Class to create a custom max-width layout control
 */
class Storefront_Designer_Content_Frame extends WP_Customize_Control {

	/**
	* Render the content on the theme customizer page
	*/
	public function render_content() {
		?>
		<div style="overflow: hidden; zoom: 1;">
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>

			<p><?php echo esc_html( $this->description ); ?></p>

			<label style="width: 48%; float: left; margin-right: 3.8%; text-align: center;">
				<img src="<?php echo plugins_url( '../assets/img/admin/frame-off.png', __FILE__ ); ?>" alt="Frame off" style="display: block; width: 100%; margin-bottom: .618em;" />
				<input type="radio" value="false" style="margin: 5px 0 0 0;"name="<?php echo esc_attr( $this->id ); ?>" <?php $this->link(); checked( $this->value(), 'false' ); ?> />
				<br/>
			</label>
			<label style="width: 48%; float: right; text-align: center;">
				<img src="<?php echo plugins_url( '../assets/img/admin/frame-on.png', __FILE__ ); ?>" alt="Frame on" style="display: block; width: 100%; margin-bottom: .618em;" />
				<input type="radio" value="true" style="margin: 5px 0 0 0;"name="<?php echo esc_attr( $this->id ); ?>" <?php $this->link(); checked( $this->value(), 'true' ); ?> />
				<br/>
			</label>
		</div>
		<?php
	}
}
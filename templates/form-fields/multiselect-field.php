<?php wp_enqueue_script( 'wp-restaurant-listings-multiselect' ); ?>

<select multiple="multiple" name="<?php echo esc_attr( isset( $field['name'] ) ? $field['name'] : $key ); ?>[]" id="<?php echo esc_attr( $key ); ?>" class="restaurant-listings-multiselect" <?php if ( ! empty( $field['required'] ) ) echo 'required'; ?> data-no_results_text="<?php _e( 'No results match', 'wp-restaurant-listings' ); ?>" data-multiple_text="<?php _e( 'Select Some Options', 'wp-restaurant-listings' ); ?>">
	<?php foreach ( $field['options'] as $key => $value ) : ?>
		<option value="<?php echo esc_attr( $key ); ?>" <?php if ( ! empty( $field['value'] ) && is_array( $field['value'] ) ) selected( in_array( $key, $field['value'] ), true ); ?>><?php echo esc_html( $value ); ?></option>
	<?php endforeach; ?>
</select>
<?php if ( ! empty( $field['description'] ) ) : ?><small class="description"><?php echo $field['description']; ?></small><?php endif; ?>

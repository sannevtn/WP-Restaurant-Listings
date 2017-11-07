<?php
global $wp_post_types;

switch ( $restaurant->post_status ) :
	case 'publish' :
		printf( __( '%s listed successfully. To view your listings <a href="%s">click here</a>.', 'wp-restaurant-listings' ), $wp_post_types['restaurant_listings']->labels->singular_name, get_permalink( $restaurant->ID ) );
	break;
	case 'pending' :
		printf( __( '%s submitted successfully. Your listings will be visible once approved.', 'wp-restaurant-listings' ), $wp_post_types['restaurant_listings']->labels->singular_name, get_permalink( $restaurant->ID ) );
	break;
	default :
		do_action( 'restaurant_listings_restaurant_submitted_content_' . str_replace( '-', '_', sanitize_title( $restaurant->post_status ) ), $restaurant );
	break;
endswitch;

do_action( 'restaurant_listings_restaurant_submitted_content_after', sanitize_title( $restaurant->post_status ), $restaurant );
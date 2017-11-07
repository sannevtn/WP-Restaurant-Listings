<?php
/**
 * The REST API Initializer
 *
 * @package WPRL/REST
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WP_Restaurant_Listings_REST_API
 */
class WP_Restaurant_Listings_REST_API {

	/**
	 * Is the api enabled?
	 *
	 * @var bool
	 */
	private $is_rest_api_enabled;
	/**
	 * Our bootstrap
	 *
	 * @var WP_Restaurant_Listings_REST_Bootstrap
	 */
	private $wprl_rest_api;
	/**
	 * The plugin base dir
	 *
	 * @var string
	 */
	private $base_dir;

	/**
	 * WP_Restaurant_Listings_REST_API constructor.
	 *
	 * @param string $base_dir The base dir.
	 */
	public function __construct( $base_dir ) {
		$this->base_dir = trailingslashit( $base_dir );
		$this->is_rest_api_enabled = defined( 'WPRL_REST_API_ENABLED' ) && ( true === constant( 'WPRL_REST_API_ENABLED' ) );
	}

	/**
	 * Bootstrap our REST Api
	 */
	private function bootstrap() {
		$file = $this->base_dir . 'lib/wprl_rest/class-wp-restaurant-listings-rest-bootstrap.php';
		if ( ! file_exists( $file ) ) {
			return new WP_Error( 'mixtape-missing' );
		}

		include_once $file;

		$this->wprl_rest_api = WP_Restaurant_Listings_REST_Bootstrap::create();
		if ( empty( $this->wprl_rest_api ) ) {
			return new WP_Error( 'rest-api-bootstrap-failed' );
		}
		$this->wprl_rest_api->load();

		include_once 'class-wp-restaurant-listings-models-settings.php';
		include_once 'class-wp-restaurant-listings-models-status.php';
		include_once 'class-wp-restaurant-listings-filters-status.php';
		include_once 'class-wp-restaurant-listings-data-stores-status.php';
		include_once 'class-wp-restaurant-listings-controllers-status.php';
	}

	/**
	 * Get WP_Restaurant_Listings_REST_Bootstrap
	 *
	 * @return WP_Restaurant_Listings_REST_Bootstrap
	 */
	public function get_bootstrap() {
		return $this->wprl_rest_api;
	}

	/**
	 * Initialize the REST API
	 *
	 * @return WP_Restaurant_Listings_REST_API $this
	 */
	public function init() {
		if ( ! $this->is_rest_api_enabled ) {
			return $this;
		}
		$err = $this->bootstrap();
		if ( is_wp_error( $err ) ) {
			// Silently don't initialize the rest api if we get a wp_error.
			return $this;
		}
		$this->define_api( $this->wprl_rest_api->environment() );
		$this->wprl_rest_api->environment()
			->start();
		return $this;
	}

	/**
	 * Define our REST API Models and Controllers
	 *
	 * @param WP_Restaurant_Listings_REST_Environment $env The Environment.
	 */
	public function define_api( $env ) {
		// Models.
		$env->define_model( 'WP_Restaurant_Listings_Models_Settings' )
			->with_data_store( new WP_Restaurant_Listings_REST_Data_Store_Option( $env->model( 'WP_Restaurant_Listings_Models_Settings' ) ) );
		$env->define_model( 'WP_Restaurant_Listings_Models_Status' )
			->with_data_store( new WP_Restaurant_Listings_Data_Stores_Status( $env->model( 'WP_Restaurant_Listings_Models_Status' ) ) );
		$env->define_model( 'WP_Restaurant_Listings_Filters_Status' );

		// Endpoints.
		$env->rest_api( 'wprl/v1' )
			->add_endpoint( new WP_Restaurant_Listings_REST_Controller_Settings( '/settings', 'WP_Restaurant_Listings_Models_Settings' ) )
			->add_endpoint( new WP_Restaurant_Listings_Controllers_Status( '/status', 'WP_Restaurant_Listings_Models_Status' ) );
	}
}


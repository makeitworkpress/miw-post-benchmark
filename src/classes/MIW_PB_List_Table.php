<?php
/**
 * Exposing the private class for a table with custom options
 */
defined( 'ABSPATH' ) or die('Nope...');

// Requires the WP_List_Table Class
if ( ! class_exists( 'WP_List_Table' ) ) {
  require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

$benchmarks = get_option('miw_pb_benchmarks');

/**
 * The class returning the previous tests in a nicely formatted table
 */
class MIW_PB_List_Table extends WP_List_Table {

	/** 
   * Class constructor 
   */
	public function __construct() {

		parent::__construct( [
			'singular' => __('Benchmark'),
			'plural'   => __('Benchmarks'),
			'ajax'     => false 
		] );

	}
  
	/**
	 * Default column handler.
	 */
  public function column_default( $item, $column_name ) {
    switch( $column_name ) { 
      case 'insert_time':
      case 'query_time':
      case 'delete_time':
        return $item[$column_name] . ' <i>' . __('seconds') . '</i>';
      default:
        return print_r($item, true);
    }
  }

	/**
	 * Associative array of columns
	 *
	 * @return array
	 */
	public function get_columns(): array {
		$columns = [
			'id'  				=> __('ID'),
			'number'  		=> __('Number of Posts'),
			'insert_time' => __('Insert Time'),
			'query_time'  => __('Query Time'),
			'delete_time' => __('Deletion Time'),
			'score' 			=> __('Score')
		];

		return $columns;
	}  

	/**
	 * Default primary column.
	 */
	protected function get_default_primary_column_name() {
		return 'number';
	}   

	/**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() {

    $this->_column_headers = [$this->get_columns(), [], []];
    $this->items  = get_option('miw_pb_benchmarks') ? get_option('miw_pb_benchmarks') : [];
		$this->set_pagination_args( [
			'total_items' => count($this->items), 
			'per_page'    => 100 
		] );

	}

	/** 
   * Text displayed when no customer data is available 
   */
	public function no_items() {
		_e('No benchmark results.');
	}
  
	/**
	 * Method for number column
	 *
	 * @param array $item an array of DB data
	 *
	 * @return string
	 */
	function column_number( array $item ): string {
		return '<strong>' . $item['number'] . '</strong>';
	}
	
	/**
	 * Method for id column
	 *
	 * @param array $item an array of DB data
	 *
	 * @return string
	 */
	function column_id( array $item ): string {
		return '#' . $item['id'];
	}
	
	/**
	 * Method for id column
	 *
	 * @param array $item an array of DB data
	 *
	 * @return string
	 */
	function column_score( array $item ): string {

		if( $item['score'] >= 8 ) {
			$color = '#1ed14b';
		} else if( $item['score'] >= 6 ) {
			$color = '#f0c33c';
		} else if( $item['score'] >= 4 ) {
			$color = '#bd8600';
		}	else if( $item['score'] >= 2 ) {
			$color = '#e65054';
		}	else if( $item['score'] >= 0 ) {
			$color = '#d63638';
		}				

		return '<b style="background-color: ' . $color . '; border-radius: 5px; color: #fff; padding: 0.5em; ">' . $item['score'] . '</b>';
	} 	

}

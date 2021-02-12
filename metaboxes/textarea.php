<?php
new Meta_custom_param_pyscrpt;

class Meta_custom_param_pyscrpt{
	public $post_type = 'pyscrpt';
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_metabox' ) );
		add_action( 'save_post_' . $this->post_type, array( $this, 'save_metabox' ) );
	}
	## Добавляет мeтабоксы
	public function add_metabox() {
		add_meta_box( 'box_slides_custom_param', 'Python', array( $this, 'render_metabox' ), $this->post_type, 'side', 'low' );
	}
	
	public function render_metabox( $post ) {
		$script = get_post_meta($post->ID, 'script', 1);
			?>
<textarea id="pycode" rows="10" cols="88" name="script"><?php echo $script; ?></textarea>
		
			<?php
	}

	public function save_metabox( $post_id ) {
		if ( wp_is_post_autosave( $post_id ) ){
			return;
			}
		if (isset($_POST['script'])){
			$script = $_POST['script'];
			if( $script ){
				update_post_meta( $post_id, 'script', $script );
			}
			else{
				delete_post_meta( $post_id, 'script', $script );
				}
		}
	}
}

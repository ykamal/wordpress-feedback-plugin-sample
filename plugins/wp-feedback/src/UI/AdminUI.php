<?php
namespace WPFB\UI;

use WPFB\DB\Feedback;

class AdminUI {

	/**
	 * Hook into the appropriate actions when the class is constructed.
	 */
	public function __construct() {
		\add_action( 'admin_enqueue_scripts', [$this, 'add_admin_styles']);
        \add_action( 'add_meta_boxes', [$this, 'add_meta_box'] );

	}

    public function add_admin_styles()
    {
        wp_enqueue_style('wpfb-metabox', WPFB_PATH . '/assets/css/admin.css');
    }

	/**
	 * Adds the meta box container.
	 */
	public function add_meta_box( $post_type ) {
		$post_types = array( 'post', 'page' );

		if ( in_array( $post_type, $post_types ) ) {
			add_meta_box(
				'wpfb_meta_box',
				__( 'Feedback Summary', 'textdomain' ), // todo
				[$this, 'render_meta_box_content'],
				$post_type,
				'advanced',
				'high'
			);
		}
	}

	
	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_meta_box_content( $post ) {

        $feedback = (new Feedback())->get_feedback($post->ID);

		?>
		<!-- TODO: move to ui/templates/metaboxes -->
        <table class="wpfb_meta_box_table">
            <tbody>
                <tr>
                    <td>Total Votes</td>
                    <td><?= $feedback['total']; ?></td>
                </tr>
                <tr>
                    <td>Helpful</td>
                    <td><?= $feedback['helpful']; ?>%</td>
                </tr>
                <tr>
                    <td>Unhelpful</td>
                    <td><?= $feedback['unhelpful']; ?>%</td>
                </tr>
            </tbody>
        </table>
		
		<?php
	}
}
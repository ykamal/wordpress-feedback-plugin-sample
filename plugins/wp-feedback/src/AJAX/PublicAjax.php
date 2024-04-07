<?php
namespace WPFB\AJAX;

use WPFB\DB\Feedback;

class PublicAjax {

    private $feedback;

    public function __construct() {
        $this->feedback = new Feedback();
        $this->register_hooks();
        add_action('wp_enqueue_scripts', [$this, 'register_assets']);
    }

    public function register_hooks()
    {
        $action = 'wpfb_add_feedback';
        add_action("wp_ajax_{$action}", [$this, 'handle_public_ajax']);
        add_action("wp_ajax_nopriv_{$action}", [$this, 'handle_public_ajax']);
    }

    public function register_assets()
    {
        wp_enqueue_script('wpfb-js', WPFB_PATH . '/assets/js/wpfb.js', ['jquery'], '1.0', true);
        wp_localize_script('wpfb-js', 'wpfb_params', ['ajax_url' => admin_url('admin-ajax.php')]);
    }

    public function handle_public_ajax()
    {

        if($_SERVER['REQUEST_METHOD'] !== 'POST') wp_send_json_error('Invalid request type');

        if(!isset($_POST['data'])) wp_send_json_error('Invalid post body');

        $data = $_POST['data'];

        if(!isset($data['isHelpful'])) wp_send_json_error('Invalid post body');

        $is_helpful = $data['isHelpful'] === "true";

        if(!isset($data['postId'])) wp_send_json_error('Invalid post body');

        $post_id = absint($data['postId']);

        $ip = $_SERVER['REMOTE_ADDR'];

        $has_already_voted = $this->feedback->has_voted($post_id, $ip);

        if($has_already_voted) wp_send_json_error('You have already voted');  

        // finally, save it
        $result = $this->feedback->add_feedback($post_id, $ip, $is_helpful);

        if(!$result) {
            wp_send_json_error('Could not submit feedback');
        } else {
            wp_send_json_success([
                'feedback' => $this->feedback->get_feedback($post_id)
            ]);
        }

        wp_die();
    }
}

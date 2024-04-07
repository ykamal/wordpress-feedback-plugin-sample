<?php

namespace WPFB\UI;

use WPFB\DB\Feedback;

/**
 * Helps render the public front-end components.
 */
class PublicUI
{
    public function __construct()
    {
        \add_action('wp_enqueue_scripts', [$this, 'register_css']);
        $this->add_display_hook();
    }

    /**
     * Registers CSS for the public front-end components.
     */
    public function register_css()
    {
        if (is_single()) {
            \wp_enqueue_style('wpfb-fe-css', WPFB_PATH.'/assets/css/public.css');
            // sorry, but this is a sortcut I must take
            \wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css');
        }
    }

    /**
     * Adds display hook to render feedback components on the content.
     */
    public function add_display_hook()
    {
        add_action('the_content', function ($content) {
            if (is_single()) {
                $feedback_class = new Feedback();

                $content_id = get_the_ID();
                $ip = $_SERVER['REMOTE_ADDR'];
                $feedback = $feedback_class->get_feedback($content_id);
                $has_voted = $feedback_class->has_voted($content_id, $ip);
                $the_vote = null;

                if ($has_voted) {
                    $the_vote = $feedback_class->get_vote_by_ip($content_id, $ip);
                }

                $content .= $this->render_frontend($has_voted, $feedback, $the_vote);
            }

            return $content;
        });
    }

    /**
     * Renders the frontend components.
     *
     * @param bool   $has_voted Flag indicating whether the user has voted.
     * @param array  $feedback  An array containing feedback statistics.
     * @param object $the_vote  (Optional) The user's vote record.
     *
     * @return string The rendered frontend HTML.
     */
    public function render_frontend(bool $has_voted, $feedback, $the_vote = null)
    {
        ob_start();
        include __DIR__.'/templates/public.php';
        $template = ob_get_clean();

        return $template;
    }
}

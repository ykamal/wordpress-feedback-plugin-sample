<?php
namespace WPFB;

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

use WPFB\AJAX\PublicAjax;
use WPFB\DB\Manager;
use WPFB\UI\PublicUI;
use WPFB\UI\AdminUI;

class Main {
    public static function init() 
    {
        // register table management hooks
        // !! THESE ARE STATIC FOR SAFETY PURPOSES !!
        register_activation_hook(WPFB_PLUGIN_FILE, [Manager::class, 'create_tables']);
        register_uninstall_hook(WPFB_PLUGIN_FILE, [Manager::class, 'delete_tables']);

        // AJAX
        new PublicAjax();

        // UI
        new PublicUI();
        // admin meta boxes
        if(\is_admin()) {
            \add_action('load-post.php', function() {
                new AdminUI();
            });
        }
    }
}
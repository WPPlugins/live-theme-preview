<?php
/**
 * WP_LiveAdmin extension: Live Screen Options
 */

// Exit if accessed directly
if ( !defined ( 'ABSPATH' ) )
    exit;

/**
 * PATHs and URLs
 */
define ( 'LIVE_SCREEN_OPTIONS_DIR', plugin_dir_path ( __FILE__ ) );
define ( 'LIVE_SCREEN_OPTIONS_URL', plugin_dir_url ( __FILE__ ) );
define ( 'LIVE_SCREEN_OPTIONS_INC_URL', LIVE_SCREEN_OPTIONS_URL . '_inc/' );

/**
 * Live Screen Options is pluggable
 */
if ( !class_exists ( 'WP_LiveAdmin_LiveScreenOptions' ) ) :

    class WP_LiveAdmin_LiveScreenOptions
    {

        /**
         * Creates an instance of the LiveAdmin_Menu class
         *
         * @return LiveMenu object
         * @since 0.1
         * @static
        */
        public static function &init() {
            static $instance = false;

            if ( !$instance ) {
                $instance = new WP_LiveAdmin_LiveScreenOptions;
            }

            return $instance;
        }

        public function __construct() {
            wp_enqueue_script ( 'jquery-hotkeys' );
            wp_enqueue_script('live-screen-options', LIVE_SCREEN_OPTIONS_INC_URL . 'js/live-screen-options.js', array ('jquery', 'jquery-hotkeys', 'common'), LIVE_ADMIN_VERSION, true );
            wp_dequeue_script('postbox');

            wp_enqueue_style('live-screen-options', LIVE_SCREEN_OPTIONS_INC_URL . 'css/live-screen-options.css', array('customize-controls'), LIVE_ADMIN_VERSION );

            add_action ( 'live_admin_before_wpwrap', array ( &$this, 'load_screen_options' ) );

            add_action ( 'live_admin_controls', array ( &$this, 'show_toggle' ) );

        }

            public function wp_liveadmin_livescreenoptions() {
                $this->_construct();
            }

        public function show_toggle() {
            ?>
            <div class="show-screen-options hide-if-no-js toggle-screen-options">
                <div id="collapse-button" style=''><div style='background-position: 0 -108px;'></div></div><span>Show screen options</span>
            </div>
            <?php
        }

        protected function show_hide_toggle() {
            ?>
            <div id="" class="hide-screen-options hide-if-no-js toggle-screen-options">
                    <div id="collapse-button"><div></div></div><span>Hide screen options</span>
            </div>
            <?php
        }

        public function load_screen_options() {
            $screen = get_current_screen();
            if ( is_string( $screen ) )
            $screen = convert_to_screen( $screen );

            if ( ! $screen ) return;

            ?>
            <div id="live-admin-screen-options" class="collapsed">

                <?php
                $this->show_hide_toggle();

                $screen->render_screen_options();
                wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false );
                wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );

                $this->show_hide_toggle();
                ?>

            </div>
            <?php

        }



    }
    add_action ( 'live_admin_start', array ( 'WP_LiveAdmin_LiveScreenOptions', 'init' ) );
endif;
<?php

/**
 * @package Clustdoc_Client_Portal
 * @version 4.1.0
 */
/*
Plugin Name: Clustdoc Client Portal
Plugin URI:  https://clustdoc.com
Description: A secure online application system for your Wordpress site. Start receiving client applications easily from your pages.
Version:     4.1.0
Author:      Clustdoc
Author URI:  https://clustdoc.com
Text Domain: clustdoc-client-portal
*/

defined('ABSPATH') or die();

// Inclure le fichier de style
wp_enqueue_style('clustdoc-client-portal-style', plugin_dir_url(__FILE__) . 'css/style.css');

// Inclure le fichier de configuration
require_once plugin_dir_path(__FILE__) . 'conf/env.php';

if (!class_exists('clust_client_portal')) {
    class clust_client_portal
    {
        private $options = null;
        private $modal_loaded = false;
        private $api_error_message = null;
        private $api_success_message = null;
        private $plugin = null;

        public static function getInstance()
        {
            if (self::$instance == null) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        private static $instance = null;

        // Méthode pour empêcher le clonage d'objets
        private function __clone()
        {
        }

        // Méthode pour gérer la réinitialisation d'objets
        public function __wakeup()
        {
        }

        private function __construct()
        {
            // Properties
            $this->plugin = plugin_basename(__FILE__);

            // Hooks
            add_action('admin_init', array($this, 'register_settings'));
            add_action('admin_init', array($this, 'load_options'));
            add_action('admin_menu', array($this, 'add_options_menu'));
            add_action('enqueue_block_editor_assets', array($this, 'loadMyBlock'));
            add_shortcode('clust_client_portal', array($this, 'output_shortcode'));
            add_filter("plugin_action_links_$this->plugin", array($this, 'settings_link'));

            //register_activation_hook( __FILE__, array($this,'plugin_activation' ) );
            register_deactivation_hook(__FILE__, array($this, 'plugin_deactivation'));

            //$this->options['api_token'] = ' ';
            $this->get_option('api_token', '');

            //Loading Cookie for the block
            $this->get_portals_j();
        }

        public function plugin_deactivation()
        {
            $this->delete_cookie();
        }

        // function for the options menu
        public function register_settings()
        {
            register_setting('clustdoc_client_portal_optsgroup', 'clustdoc_client_portal_options');
        }

        //function to have a link to go directly to the configuration after having activated the plugin
        public function settings_link($links)
        {
            $settings_link = '<a href="admin.php?page=clustdoc-client-portal">' . __('Settings', 'clustdoc-client-portal') . '</a>';
            array_push($links, $settings_link);
            return $links;
        }

        public function load_options()
        {
            $this->options['api_token'] = '';
            $options = get_option('clustdoc_client_portal_options');
            $this->options = is_array($options) ? $options : array();
        }

        public function add_options_menu()
        {
            add_menu_page(
                __('Clustdoc Client Portal', 'clustdoc-client-portal'),
                __('Clustdoc Client Portal', 'clustdoc-client-portal'),
                'manage_options',
                'clustdoc-client-portal',
                array($this, 'add_options_page'),
                plugin_dir_url(__FILE__) . 'images/menu-icon.png'
            );
        }

        //load block
        public function add_options_page()
        {
            include(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'options.php');
        }

        // Load the scrpit of the Block
        public function loadMyBlock()
        {
            wp_enqueue_script(
                'my-new-block',
                plugin_dir_url(__FILE__) . 'clustdoc-block.js',
                array('wp-blocks', 'wp-editor'),
                true
            );
        }

        // Retriving portals send them in html format for the options menu
        private function get_portals()
        {
            $api_token = $this->get_option('api_token', '');

            $response = wp_remote_get('https://clustdoc.com/api/portals?api_token=' . $api_token);
            $options_obj = json_decode($response['body']);

            if (!count($options_obj->data)) {
                $this->api_error_message = __('No portal were found. Please create at least one template in your Clustdoc account.', 'clustdoc-client-portal');
                return '<option value=0>' . __('No portal were found', 'clustdoc-client-portal') . '</option>';
            }

            $html = '';
            $i = 0;
            foreach ($options_obj->data as $element) {
                $html .= '<option value=' . $element->public_url . '>' . $element->title . '</option>';
            }
            return $html;
        }

        // Retrieving portals for the select in option.php and load the API token into Cookie
        private function get_portals_j()
        {
            $api_token = $this->get_option('api_token', '');
            if ($api_token == '') {
                $this->api_error_message = __('Oops: It seems like you haven\'t set up your API Token yet.', 'clustdoc-client-portal');
                $this->delete_cookie();
                return '<option>' . __('Error: No portal were found', 'clustdoc-client-portal') . '</option>';
            }
            $response = wp_remote_get('https://clustdoc.com/api/portals?api_token=' . $api_token);
            if (is_wp_error($response)) {
                $this->api_error_message = __('Oops: It seems like you have entered a wrong API Token. Please check your API key.', 'clustdoc-client-portal');
                return '<option>' . __('Error: No portal were found', 'clustdoc-client-portal') . '</option>';
            }
            $options_obj = json_decode($response['body']);
            if (!$options_obj || !property_exists($options_obj, 'data')) {
                $this->api_error_message = __('Oops: It seems like you have entered a wrong API Token. Please check your API key.', 'clustdoc-client-portal');
                return '<option>' . __('Error: No portal were found', 'clustdoc-client-portal') . '</option>';
            }

            setcookie("CL-apiToken", $api_token);

            $this->api_success_message = __('You\'re connected', 'clustdoc-client-portal');

            return $options_obj;
        }

        // Retrieving the API token
        private function get_option($option_name, $default = '')
        {
            if (is_null($this->options)) {
                $this->load_options();
            }
            return isset($this->options[$option_name]) ? trim($this->options[$option_name]) : $default;
        }

        // function delete_cookie 
        public function delete_cookie()
        {
            if (isset($_COOKIE['CL-apiToken'])) {
                setcookie('CL-apiToken', "", -360);
                unset($_COOKIE['CL-apiToken']);
            }
        }
    }
}
clust_client_portal::getInstance();

<?php
/**
 * Plugin Name: Two Column Print
 * Plugin URI: https://example.com/plugins/two-column-print
 * Description: A WordPress plugin that allows printing specific elements in a two-column layout
 * Version: 1.0.0
 * Author: Gedeon Nzemba
 * Author URI: https://example.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: two-column-print
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class TwoColumnPrint {
    private static $instance = null;
    private $options;

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('init', array($this, 'init'));
        add_action('admin_menu', array($this, 'addAdminMenu'));
        add_action('admin_init', array($this, 'registerSettings'));
        add_action('wp_enqueue_scripts', array($this, 'enqueueScripts'));
        add_action('admin_enqueue_scripts', array($this, 'enqueueAdminScripts'));
        add_shortcode('print_button', array($this, 'printButtonShortcode'));
    }

    public function init() {
        $this->options = get_option('tcp_options', array(
            'left_width' => '20',
            'right_width' => '70',
            'middle_space' => '10',
            'left_selectors' => '',
            'right_selectors' => ''
        ));
    }

    public function enqueueScripts() {
        wp_enqueue_style(
            'tcp-styles',
            plugins_url('assets/css/print-styles.css', __FILE__),
            array(),
            '1.0.0'
        );

        wp_enqueue_script(
            'tcp-script',
            plugins_url('assets/js/print-script.js', __FILE__),
            array('jquery'),
            '1.0.0',
            true
        );

        wp_localize_script('tcp-script', 'tcpOptions', $this->options);
    }

    public function enqueueAdminScripts($hook) {
        if ('settings_page_two-column-print' !== $hook) {
            return;
        }

        wp_enqueue_style(
            'tcp-admin-styles',
            plugins_url('assets/css/admin-styles.css', __FILE__),
            array(),
            '1.0.0'
        );
    }

    public function addAdminMenu() {
        add_options_page(
            'Two Column Print Settings',
            'Two Column Print',
            'manage_options',
            'two-column-print',
            array($this, 'renderSettingsPage')
        );
    }

    public function registerSettings() {
        register_setting('tcp_options', 'tcp_options', array($this, 'validateOptions'));

        add_settings_section(
            'tcp_main_section',
            'Layout Settings',
            array($this, 'sectionCallback'),
            'two-column-print'
        );

        add_settings_field(
            'left_width',
            'Left Column Width (%)',
            array($this, 'numberFieldCallback'),
            'two-column-print',
            'tcp_main_section',
            array('field' => 'left_width')
        );

        add_settings_field(
            'right_width',
            'Right Column Width (%)',
            array($this, 'numberFieldCallback'),
            'two-column-print',
            'tcp_main_section',
            array('field' => 'right_width')
        );

        add_settings_field(
            'middle_space',
            'Middle Space Width (%)',
            array($this, 'numberFieldCallback'),
            'two-column-print',
            'tcp_main_section',
            array('field' => 'middle_space')
        );

        add_settings_field(
            'left_selectors',
            'Left Column Selectors',
            array($this, 'textFieldCallback'),
            'two-column-print',
            'tcp_main_section',
            array('field' => 'left_selectors')
        );

        add_settings_field(
            'right_selectors',
            'Right Column Selectors',
            array($this, 'textFieldCallback'),
            'two-column-print',
            'tcp_main_section',
            array('field' => 'right_selectors')
        );
    }

    public function validateOptions($input) {
        $new_input = array();
        
        $new_input['left_width'] = absint($input['left_width']);
        $new_input['right_width'] = absint($input['right_width']);
        $new_input['middle_space'] = absint($input['middle_space']);
        
        // Ensure percentages add up to 100
        $total = $new_input['left_width'] + $new_input['right_width'] + $new_input['middle_space'];
        if ($total !== 100) {
            add_settings_error(
                'tcp_options',
                'tcp_width_error',
                'The sum of widths must equal 100%',
                'error'
            );
            return $this->options;
        }

        $new_input['left_selectors'] = sanitize_text_field($input['left_selectors']);
        $new_input['right_selectors'] = sanitize_text_field($input['right_selectors']);

        return $new_input;
    }

    public function sectionCallback() {
        echo '<p>Configure the layout settings for the print view. Use CSS selectors to specify which elements should appear in each column.</p>';
    }

    public function numberFieldCallback($args) {
        $field = $args['field'];
        printf(
            '<input type="number" min="0" max="100" id="%s" name="tcp_options[%s]" value="%s" />',
            esc_attr($field),
            esc_attr($field),
            esc_attr($this->options[$field])
        );
    }

    public function textFieldCallback($args) {
        $field = $args['field'];
        printf(
            '<input type="text" id="%s" name="tcp_options[%s]" value="%s" class="regular-text" /><br/>
            <span class="description">Enter CSS selectors separated by commas (e.g., .content, #main-content)</span>',
            esc_attr($field),
            esc_attr($field),
            esc_attr($this->options[$field])
        );
    }

    public function renderSettingsPage() {
        if (!current_user_can('manage_options')) {
            return;
        }
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields('tcp_options');
                do_settings_sections('two-column-print');
                submit_button('Save Settings');
                ?>
            </form>
        </div>
        <?php
    }

    public function printButtonShortcode($atts) {
        $atts = shortcode_atts(array(
            'text' => 'Print Page',
            'class' => 'tcp-print-button',
        ), $atts);

        return sprintf(
            '<button class="%s" onclick="TCP.print()">%s</button>',
            esc_attr($atts['class']),
            esc_html($atts['text'])
        );
    }
}

// Initialize the plugin
TwoColumnPrint::getInstance();

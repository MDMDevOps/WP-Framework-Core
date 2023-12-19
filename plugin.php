<?php
/**
 * Plugin bootstrap file
 *
 * PHP Version 8.0.28
 *
 * @package MWF_WP_Framework_Core
 * @author  Bob Moore <bob@bobmoore.dev>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    https://github.com/MDMDevOps/WP-Framework-Core
 * @since   1.0.0
 *
 * @wordpress-plugin
 * Plugin Name: Mid-West Family WP Framework Core
 * Plugin URI:  https://github.com/MDMDevOps/WP-Framework-Core
 * Description: Custom Description
 * Version:     1.0.0
 * Author:      Bob Moore
 * Author URI:  https://www.bobmoore.dev
 * Tags: framework, sample
 * Requires at least: 6.0
 * Tested up to: 6.3
 * Requires PHP: 8.0.28
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: mwf_wp_core
 */

namespace Mwf\WPCore;

defined( 'ABSPATH' ) || exit;

require_once trailingslashit( plugin_dir_path( __FILE__ ) ) . 'vendor/autoload.php';

Main::mount( [
    'package' => 'mwf_wp_core',
    'type'    => 'plugin',
    'assets'  => [ 'dir' => 'dist' ],
    'views'   => [ 'dir' => 'views' ]
] );
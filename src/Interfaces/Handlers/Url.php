<?php
/**
 * URL Handler interface definition
 *
 * PHP Version 8.0.28
 *
 * @package WP_Framework_Core
 * @author  Bob Moore <bob@bobmoore.dev>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    https://github.com/MDMDevOps/WP-Framework-Core
 * @since   1.0.0
 */

namespace Mwf\WPCore\Interfaces\Handlers;

/**
 * Handlers\Url interface
 *
 * Used to type hint against Mwf\WPCore\Interfaces\Handlers\Url.
 *
 * @subpackage Interfaces
 */
interface Url
{
	/**
	 * Set the base URL
	 * Can include an additional string for appending to the URL of the plugin
	 *
	 * @param string $url : root url to use.
	 * @param string $append : string to append to the directory path.
	 *
	 * @return void
	 */
	public function setUrl( string $url, string $append = '' ): void;
	/**
	 * Get the url with string appended
	 *
	 * @param string $append : string to append to the URL.
	 *
	 * @return string complete url
	 */
	public function url( string $append = '' ): string;
}

<?php
/**
 * Handler Controller
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

use Timber\Menu;

/**
 * Controls the registration and execution of services
 *
 * @subpackage Controllers
 */
interface Menus
{
	/**
	 * Setter for menus field.
	 *
	 * @param array<string, mixed> $menus : the menu definitions to set.
	 *
	 * @return void
	 */
	public function setMenus( array $menus ): void;
	/**
	 * Register navigation menus with WordPress.
	 *
	 * @return void
	 */
	public function registerNavMenus(): void;
	/**
	 * Get a menu by name or ID
	 *
	 * @param string               $name_or_id : name or ID of menu to retrieve.
	 * @param array<string, mixed> $args : arguments to pass to Timber::get_menu().
	 *
	 * @return Menu|null
	 */
	public function getMenu( string $name_or_id, array $args = [] ): ?Menu;
	/**
	 * Get menu with all pages.
	 *
	 * @return Menu
	 */
	public function pagesMenu(): Menu;
}

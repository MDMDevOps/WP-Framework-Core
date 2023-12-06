<?php
/**
 * Loadable interface definition
 *
 * PHP Version 8.1
 *
 * @package WP Framework
 * @author  Bob Moore <bob@bobmoore.dev>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    https://github.com/bob-moore/WP-Plugin-Skeleton
 * @since   1.0.0
 */

namespace Mwf\Wp\Framework\Interfaces;

/**
 * Loadable interface requirements
 *
 * Used to type hint against Mwf\Wp\Framework\Interfaces\Loadable.
 *
 * @subpackage Interfaces
 */
interface Mountable
{
	/**
	 * Check if loading action has already fired
	 *
	 * @return int
	 */
	public function hasMounted(): int;
	/**
	 * Fire Mounted action on mount
	 *
	 * @return void
	 */
	public function onMount(): void;
}

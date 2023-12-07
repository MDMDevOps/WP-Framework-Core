<?php
/**
 * Frontend Route Definition
 *
 * PHP Version 8.1
 *
 * @package WP Framework
 * @author  Bob Moore <bob@bobmoore.dev>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    https://github.com/bob-moore/WP-Plugin-Skeleton
 * @since   1.0.0
 */

namespace Mwf\Wp\Framework\Core\Routes;

use Mwf\Wp\Framework\Core\Abstracts,
	Mwf\Wp\Framework\Core\Traits,
	Mwf\Wp\Framework\Core\Interfaces,
	Mwf\Wp\Framework\Core\DI\OnMount;

/**
 * Frontend router class
 *
 * @subpackage Route
 */
class Frontend extends Abstracts\Mountable implements Interfaces\Uses\ScriptDispatcher, Interfaces\Uses\StyleDispatcher
{
	use Traits\Uses\ScriptDispatcher;
	use Traits\Uses\StyleDispatcher;

	/**
	 * Load actions and filters, and other setup requirements
	 *
	 * @return void
	 */
	#[OnMount]
	public function mount(): void
	{
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueueAssets' ] );
	}
	/**
	 * Enqueue admin styles and JS bundles
	 *
	 * @return void
	 */
	public function enqueueAssets(): void
	{
		$this->enqueueScript(
			'frontend',
			'frontend/bundle.js'
		);
		$this->enqueueStyle(
			'frontend',
			'frontend/bundle.css'
		);
	}
}

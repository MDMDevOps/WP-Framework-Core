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

namespace Mwf\WPCore\Handlers;

use Mwf\WPCore\Traits,
	Mwf\WPCore\Interfaces,
	Mwf\WPCore\Abstracts;

/**
 * Controls the registration and execution of services
 *
 * @subpackage Controllers
 */
class Blocks extends Abstracts\Mountable implements Interfaces\Handlers\Blocks
{
	use Traits\Handlers\Directory;

	/**
	 * Collection of blocks to register
	 *
	 * @var array<string, string>
	 */
	protected array $blocks = [];
	/**
	 * Blocks setter
	 *
	 * @param string ...$blocks : paths to block.json file(s).
	 *
	 * @return void
	 */
	public function setBlocks( string ...$blocks ): void
	{
		$this->blocks = array_merge( $this->blocks, $blocks );
	}
	/**
	 * Register custom blocks
	 *
	 * @see https://developer.wordpress.org/reference/functions/register_block_type/
	 *
	 * @return void
	 */
	public function registerBlocks(): void
	{
		$blocks = apply_filters( "{$this->package}_blocks", $this->blocks );

		foreach ( $blocks as $block_file ) {
			if ( is_string( $block_file ) && is_file( $block_file ) ) {
				register_block_type( $block_file );
			}
		}
	}
}

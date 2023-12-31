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

use Mwf\WPCore\Helpers,
	Mwf\WPCore\Interfaces,
	Mwf\WPCore\Abstracts;

/**
 * Controls the registration and execution of services
 *
 * @subpackage Controllers
 */
class Terms extends Abstracts\Mountable implements Interfaces\Handlers\Terms
{
	/**
	 * Array of custom taxonomies
	 *
	 * @var array<Interfaces\Entities\Taxonomy>
	 */
	protected array $taxonomies = [];
	/**
	 * Taxonomies setter
	 *
	 * @param Interfaces\Entities\Taxonomy ...$taxonomies : array of taxonomy objects.
	 *
	 * @return void
	 */
	public function setTaxonomies( Interfaces\Entities\Taxonomy ...$taxonomies ): void
	{
		$this->taxonomies = array_merge( $this->taxonomies, $taxonomies );
	}
	/**
	 * Register custom taxonomies
	 *
	 * @return void
	 */
	public function registerTaxonomies(): void
	{
		$taxonomies = apply_filters( "{$this->package}_taxonomies", $this->taxonomies );

		foreach ( $taxonomies as $taxonomy ) {
			if ( ! Helpers::implements( $taxonomy, Interfaces\Entities\Taxonomy::class ) ) {
				continue;
			}
			register_taxonomy(
				$taxonomy->getName(),
				$taxonomy->getPostTypes(),
				$taxonomy->getDefinition()
			);

			foreach ( $taxonomy->getPostTypes() as $post_type ) {
				register_taxonomy_for_object_type( $taxonomy->getName(), $post_type );
			}
		}
	}
}

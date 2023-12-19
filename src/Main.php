<?php
/**
 * Main app file
 *
 * PHP Version 8.0.28
 *
 * @package WP_Framework_Core
 * @author  Bob Moore <bob@bobmoore.dev>
 * @license GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link    https://github.com/MDMDevOps/WP-Framework-Core
 * @since   1.0.0
 */

namespace Mwf\WPCore;

use Mwf\WPCore\DI\ContainerBuilder;

use Psr\Container\ContainerInterface;

use ValueError;

/**
 * Main App Class
 *
 * Used as the base for a plugin/theme
 *
 * @subpackage Traits
 */
class Main extends Abstracts\Mountable implements Interfaces\Main, Interfaces\Controller
{
	/**
	 * The service container for dependency injections, and locating service
	 * instances
	 *
	 * @var ContainerInterface
	 */
	protected ContainerInterface $service_container;
	/**
	 * The optional package name.
	 *
	 * @var string
	 */
	protected const PACKAGE = '';
	/**
	 * Configuration array
	 *
	 * @var array<string, mixed>
	 */
	protected array $config = [];
	/**
	 * Default constructor
	 *
	 * @param array<string, mixed> $config : configuration array to merge with defaults.
	 *
	 * @throws ValueError : throw exception if package is empty.
	 */
	public function __construct( array $config = [] )
	{
		$this->setConfig( $config );

		$this->setPackage( $this->config['package'] );

		if ( empty( $this->package ) ) {
			throw new ValueError( 'Package name is required' );
		}

		parent::__construct();
	}
	/**
	 * Setter for config field.
	 *
	 * Ensures default values are set for package, dir, and url.
	 *
	 * @param array<string, mixed> $config : configuration array.
	 *
	 * @return void
	 */
	public function setConfig( array $config ): void
	{
		$package = $config['package'] ?? static::PACKAGE;
		$app_dir = untrailingslashit( $config['dir'] ?? Helpers::getDefaultDir() );
		$app_url = untrailingslashit( $config['url'] ?? Helpers::getDefaultUrl( $app_dir ) );
		$assets = [
			'dir' => untrailingslashit( ltrim( $config['assets']['dir'] ?? $config['assets'] ?? 'dist', '/' ) ),
			'url' => ContainerBuilder::string( '{config.url}/{config.assets.dir}' ),
		];
		$views = [
			'dir' => untrailingslashit( ltrim( $config['views']['dir'] ?? 'views', '/' ) ),
		];

		$this->config = array_merge(
			$config,
			[
				'package' => $package,
				'dir'     => $app_dir,
				'url'     => $app_url,
				'assets'  => $assets,
				'views'   => $views,
			]
		);
	}
	/**
	 * Set individual configuration items
	 *
	 * @param string $key : which config item to set.
	 * @param mixed  $value : config value to set.
	 *
	 * @return void
	 */
	public function configure( string $key, mixed $value ): void
	{
		$this->config[ $key ] = $value;
	}
	/**
	 * Helper function to mount new instance of class
	 *
	 * @param array<string, mixed> $config : configuration array to merge with defaults.
	 *
	 * @return static
	 */
	public static function mount( array $config = [] ): static
	{
		$instance = new static( $config );

		$instance->setContainer(
			$instance->getContainer() ?? $instance->buildContainer()
		);

		$instance->onMount();

		return $instance;
	}
	/**
	 * Setter for service container
	 *
	 * @param ContainerInterface $container : instance of service container.
	 *
	 * @return void
	 */
	public function setContainer( ContainerInterface $container ): void
	{
		$this->service_container = $container;

		ContainerBuilder::cacheContainer( $this->package, $this->service_container );
	}
	/**
	 * Build the service container
	 * - Instantiate a new container builder
	 * - Add plugin specific definitions
	 * - Get service definitions from controllers
	 *
	 * @return ContainerInterface
	 */
	protected function buildContainer(): ContainerInterface
	{
		$container_builder = new ContainerBuilder();

		$container_builder->useAttributes( true );

		$container_builder->addDefinitions( [ 'config' => $this->config ] );

		$container_builder->addDefinitions( self::getServiceDefinitions() );

		$container = $container_builder->build();

		return $container;
	}
	/**
	 * Get container from the cache if available, else build a new one
	 *
	 * Cache is used to give access to the container instead of a singleton
	 *
	 * @return ContainerInterface|null
	 */
	protected function getContainer(): ?ContainerInterface
	{
		return $this->service_container ?? ContainerBuilder::locateContainer( $this->package );
	}
	/**
	 * Get service definitions to add to service container
	 *
	 * @return array<string, mixed>
	 */
	public static function getServiceDefinitions(): array
	{
		return [
			Controllers\Handlers::class => ContainerBuilder::autowire(),
			Controllers\Routes::class   => ContainerBuilder::autowire(),
			Controllers\Services::class => ContainerBuilder::autowire(),
		];
	}
	/**
	 * Fire Mounted action on mount
	 *
	 * @return void
	 */
	public function onMount(): void
	{
		$this->mountActions();

		$this->mountControllers();

		parent::onMount();
	}
	/**
	 * Mount the Actions
	 *
	 * @return void
	 */
	protected function mountActions(): void
	{
		add_filter( "{$this->package}_has_route", [ $this, 'hasRoute' ], 5, 2 );
		add_action( "{$this->package}_load_route", [ $this, 'loadRoute' ], 5 );
	}
	/**
	 * Mount the controller classes
	 *
	 * @return void
	 */
	protected function mountControllers(): void
	{
		foreach ( self::getServiceDefinitions() as $key => $value ) {
			$this->service_container->get( $key );
		}
	}
	/**
	 * Check if a particular route exists
	 *
	 * @param boolean $has_route : default value.
	 * @param string  $route_alias : name of route to find.
	 *
	 * @return boolean
	 */
	public function hasRoute( bool $has_route, string $route_alias ): bool
	{
		return false === $has_route ? $this->service_container->has( $route_alias ) : $has_route;
	}
	/**
	 * Load a route from the service container
	 *
	 * @param string $route_alias : name of route to load.
	 *
	 * @return void
	 */
	public function loadRoute( string $route_alias ): void
	{
		$this->service_container->get( $route_alias );
	}
	/**
	 * Locate a specific service
	 *
	 * Use primarily by 3rd party interactions to remove actions/filters
	 *
	 * @param string $service : name of service to locate.
	 * @param string $package : package id of container to search in.
	 *
	 * @return mixed
	 */
	public static function locateService( string $service, string $package = '' ): mixed
	{
		$container = ContainerBuilder::locateContainer( ! empty( $package ) ? $package : static::PACKAGE );

		if ( $container ) {
			return $container->get( $service );
		} else {
			return null;
		}
	}
}

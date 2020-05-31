<?php

namespace Drupal\user_routes\Routing;

use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Alter user route paths.
 */
class RouteSubscriber extends RouteSubscriberBase {

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected $configFactory;

  /**
   * Constructs a new RouteSubscriber object.
   *
   * @param \Drupal\Core\Config\ConfigFactory $config_factory
   *   The config factory.
   */
  public function __construct(ConfigFactory $config_factory) {
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    $user_routes_config = $this->configFactory
      ->get('user_routes.settings')
      ->get();

    foreach ($user_routes_config as $route_name => $route_path) {
      if (!$route_path) {
        continue;
      }

      $route_name = str_replace('_', '.', $route_name);
      if ($route = $collection->get($route_name)) {
        $route->setPath($route_path);
      }
    }
  }

}

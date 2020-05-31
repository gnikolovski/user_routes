<?php

namespace Drupal\user_routes\Form;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Routing\RouteBuilderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines a form that configures User Routes settings.
 */
class SettingsForm extends ConfigFormBase implements ContainerInjectionInterface {

  /**
   * The route builder.
   *
   * @var \Drupal\Core\Routing\RouteBuilderInterface
   */
  protected $routerBuilder;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->setRouteBuilder($container->get('router.builder'));
    return $instance;
  }

  /**
   * Sets route builder.
   *
   * @param \Drupal\Core\Routing\RouteBuilderInterface $router_builder
   *   The route builder.
   */
  public function setRouteBuilder(RouteBuilderInterface $router_builder) {
    $this->routerBuilder = $router_builder;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'user_routes_admin_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'user_routes.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $user_routes_config = $this->config('user_routes.settings');

    $form['user_login'] = [
      '#type' => 'textfield',
      '#title' => t('User login'),
      '#default_value' => $user_routes_config->get('user_login'),
      '#description' => t('Default route path: /user/login'),
    ];

    $form['user_register'] = [
      '#type' => 'textfield',
      '#title' => t('User register'),
      '#default_value' => $user_routes_config->get('user_register'),
      '#description' => t('Default route path: /user/register'),
    ];

    $form['user_logout'] = [
      '#type' => 'textfield',
      '#title' => t('User logout'),
      '#default_value' => $user_routes_config->get('user_logout'),
      '#description' => t('Default route path: /user/logout'),
    ];

    $form['user_pass'] = [
      '#type' => 'textfield',
      '#title' => t('User reset password'),
      '#default_value' => $user_routes_config->get('user_pass'),
      '#description' => t('Default route path: /user/password'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $this->config('user_routes.settings')
         ->set('user_login', $values['user_login'])
         ->set('user_register', $values['user_register'])
         ->set('user_logout', $values['user_logout'])
         ->set('user_pass', $values['user_pass'])
         ->save();

    $this->routerBuilder->rebuild();

    parent::submitForm($form, $form_state);
  }

}

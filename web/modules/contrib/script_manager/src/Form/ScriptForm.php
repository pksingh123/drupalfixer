<?php

namespace Drupal\script_manager\Form;

use Drupal\Component\Plugin\ContextAwarePluginInterface;
use Drupal\Core\Config\ImmutableConfig;
use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Executable\ExecutableManagerInterface;
use Drupal\Core\Form\FormState;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\Context\ContextRepositoryInterface;
use Drupal\script_manager\Entity\Script;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * The script entity add form.
 */
class ScriptForm extends EntityForm {

  /**
   * The condition plugin manager.
   *
   * @var \Drupal\Core\Executable\ExecutableManagerInterface
   */
  protected $conditionManager;

  /**
   * The script entity object.
   *
   * @var \Drupal\script_manager\Entity\ScriptInterface
   */
  protected $entity;

  /**
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  protected $configuration;

  /**
   * The context repository.
   *
   * @var \Drupal\Core\Plugin\Context\ContextRepositoryInterface
   */
  protected $contextRepository;

  /**
   * Constructs the ScriptForm class.
   */
  public function __construct(ExecutableManagerInterface $manager, ImmutableConfig $configuration, ContextRepositoryInterface $contextRepository) {
    $this->conditionManager = $manager;
    $this->configuration = $configuration;
    $this->contextRepository = $contextRepository;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.condition'),
      $container->get('config.factory')->get('script_manager.settings'),
      $container->get('context.repository')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    // See \Drupal\Core\Condition\ConditionPluginBase::buildConfigurationForm.
    $form_state->setTemporaryValue('gathered_contexts', $this->contextRepository->getAvailableContexts());

    $form['#tree'] = TRUE;

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Script Name'),
      '#default_value' => $this->entity->label(),
      '#size' => 30,
      '#required' => TRUE,
      '#maxlength' => 64,
      '#description' => $this->t('A human readable name to easily identify the script, eg "Google analytics".'),
    ];
    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $this->entity->id(),
      '#required' => TRUE,
      '#disabled' => !$this->entity->isNew(),
      '#size' => 30,
      '#maxlength' => 64,
      '#machine_name' => [
        'exists' => ['\Drupal\script_manager\Entity\Script', 'load'],
      ],
    ];
    $form['position'] = [
      '#type' => 'select',
      '#title' => $this->t('Script Position'),
      '#required' => TRUE,
      '#options' => [
        'top' => $this->t('Top'),
        'bottom' => $this->t('Bottom'),
        'hidden' => $this->t('Not shown'),
      ],
      '#default_value' => $this->entity->getPosition(),
    ];
    $form['snippet'] = [
      '#type' => 'textarea',
      '#title' => $this->t('HTML Snippet'),
      '#description' => $this->t('The snippet of JavaScript to display on the page.'),
      '#required' => TRUE,
      '#default_value' => $this->entity->getSnippet(),
    ];
    $form['visibility'] = $this->buildVisibilityForm($form_state);
    return $form;
  }

  /**
   * The form title.
   */
  public function formTitle(Script $script) {
    return $this->t('Edit %script Script', ['%script' => $script->label()]);
  }

  /**
   * Build the visibility form.
   */
  protected function buildVisibilityForm(FormStateInterface $form_state) {

    $form['visibility_tabs'] = [
      '#type' => 'vertical_tabs',
      '#title' => $this->t('Visibility'),
      '#parents' => ['visibility_tabs'],
      '#attached' => [
        'library' => [
          'block/drupal.block',
        ],
      ],
    ];

    $visibility_configuration = $this->entity->getVisibilityConditions()->getConfiguration();

    foreach ($this->getEnabledVisibilityDefinitions() as $condition_id => $definition) {
      $condition = $this->conditionManager->createInstance($condition_id, isset($visibility_configuration[$condition_id]) ? $visibility_configuration[$condition_id] : []);
      $form_state->set(['conditions', $condition_id], $condition);

      $form[$condition_id] = [
        '#type' => 'details',
        '#title' => $condition->getPluginDefinition()['label'],
        '#group' => 'visibility_tabs',
      ] + $condition->buildConfigurationForm([], $form_state);
    }

    return $form;
  }

  /**
   * Get the enabled visibility plugin definitions.
   */
  protected function getEnabledVisibilityDefinitions() {
    $definitions = $this->conditionManager->getDefinitions();
    $enabled_plugins = $this->configuration->get('enabled_visibility_plugins');
    return $enabled_plugins ? array_filter($definitions, function($definition) use ($enabled_plugins) {
      return in_array($definition['id'], $enabled_plugins);
    }) : $definitions;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
    $this->validateVisibility($form, $form_state);
  }

  /**
   * Helper function to independently validate the visibility UI.
   *
   * @param array $form
   *   A nested array form elements comprising the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  protected function validateVisibility(array $form, FormStateInterface $form_state) {
    foreach ($form_state->getValue('visibility', []) as $condition_id => $values) {
      if (array_key_exists('negate', $values)) {
        $values['negate'] = (bool) $values['negate'];
      }
      $condition = $form_state->get(['conditions', $condition_id]);
      $condition_values = (new FormState())->setValues($values);
      $condition->validateConfigurationForm($form, $condition_values);
      $form_state->setValue(['visibility', $condition_id], $condition_values->getValues());
    }
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    parent::save($form, $form_state);
    $entity = $this->entity;

    foreach ($form_state->getValue('visibility', []) as $condition_id => $values) {
      $condition = $form_state->get(['conditions', $condition_id]);
      $condition->submitConfigurationForm($form, (new FormState())->setValues($values));

      if ($condition instanceof ContextAwarePluginInterface) {
        $context_mapping = isset($values['context_mapping']) ? $values['context_mapping'] : [];
        $condition->setContextMapping($context_mapping);
      }

      $condition_configuration = $condition->getConfiguration();
      $form_state->setValue(['visibility', $condition_id], $condition_configuration);

      $entity->getVisibilityConditions()->addInstanceId($condition_id, $condition_configuration);
    }

    $entity->save();
    $form_state->setRedirect('entity.script.collection');
  }

}

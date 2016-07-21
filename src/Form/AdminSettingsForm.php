<?php

namespace Drupal\twig_polymer\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class AdminSettingsForm.
 *
 * @package Drupal\twig_polymer\Form
 */
class AdminSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'twig_polymer.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'admin_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('twig_polymer.settings');
    $form['debug_mode'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Debug mode'),
      '#description' => $this->t('Whether to enable debug mode. In debug mode, cache will be disabled.'),
      '#default_value' => $config->get('debug_mode'),
    ];
    $form['max_age'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Max age'),
      '#description' => $this->t('Max age of cache'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => $config->get('max_age'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('twig_polymer.settings')
      ->set('debug_mode', $form_state->getValue('debug_mode'))
      ->set('max_age', $form_state->getValue('max_age'))
      ->save();
  }

}

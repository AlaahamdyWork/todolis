<?php
/**
 * @file
 * Contains \Drupal\todolist\Form\TaskSettingsForm.
 */

namespace Drupal\todolist\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class ContentEntityExampleSettingsForm.
 *
 * @package Drupal\dictionary\Form
 *
 * @ingroup dictionary
 */
class TaskSettingsForm extends FormBase {
  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'todolist_Task_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Empty implementation of the abstract submit class.
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['todolist_Task_settings']['#markup'] = 'Settings form for todolist Task. Manage field settings here.';
    return $form;
  }

}

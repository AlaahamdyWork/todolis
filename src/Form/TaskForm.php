<?php
/**
 * @file
 * Contains \Drupal\todolist\Form\TaskForm.
 */

namespace Drupal\todolist\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the content_entity_example entity edit forms.
 *
 * @ingroup content_entity_example
 */
class TaskForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\dictionary\Entity\Term */
    /*var_dump( $form_state);die();
      if (parent::getFormID() === 'todolist_Task_edit_form') {
          $form['field_status']['#access']=false;
      }*/
    $form = parent::buildForm($form, $form_state);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    // Redirect to term list after save.
    $form_state->setRedirect('entity.todolist_Task.collection');
    $entity = $this->getEntity();
    $entity->save();
  }

}

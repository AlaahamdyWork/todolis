<?php

/**
 * @file
 * Contains \Drupal\todolist\Form\FileFormAdd.
 */

namespace Drupal\todolist\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\User;
use Drupal\todolist\Entity\Task;


/**
 * ..........
 *
 * @todo
 *   sanitation.
 */
class UploadForm extends FormBase
{

    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'todolist_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $form = array(
            '#attributes' => array('enctype' => 'multipart/form-data'),
        );

        $form['file_upload_details'] = array(
            '#markup' => t('<b>The File</b>'),
        );

        $validators = array(
            'file_validate_extensions' => array('csv'),
        );
        $form['csv_upload'] = array(
            '#type' => 'managed_file',
            '#name' => 'excel_file',
            '#title' => t('File *'),
            '#size' => 20,
            '#description' => t('Excel format only'),
            '#upload_validators' => $validators,
            '#upload_location' => 'public://content/excel_files/',
        );

        $form['actions']['#type'] = 'actions';
        $form['actions']['submit'] = array(
            '#type' => 'submit',
            '#value' => $this->t('Save'),
            '#button_type' => 'primary',
        );

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        if ($form_state->getValue('csv_upload') == NULL) {
            $form_state->setErrorByName('csv_upload', $this->t('File.'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {

        //get current user id
        $user = \Drupal::currentUser();

        //get file path and open it
        $file = \Drupal::entityTypeManager()->getStorage('file')
            ->load($form_state->getValue('csv_upload')[0]);
        $full_path = $file->get('uri')->value;
        $file = fopen($full_path, "r");

        //get date from csv file and store in the entity
        while (!feof($file)) {
            $customer = fgetcsv($file);
            $task = Task::create(
                [
                    'task_title' => $customer[0],
                    'task_content' => $customer[1],
                    'status' => $customer[2],
                    'task_date' => date('Y-m-d', strtotime($customer[3])),
                    'user_id' => $user->id(),
                ]
            );
            $task->save();
        }

        fclose($file);

        drupal_set_message('CSV data added to the database');

        // Redirect to Task list after uploading tasks.
        $form_state->setRedirect('entity.todolist_Task.collection');

    }

}
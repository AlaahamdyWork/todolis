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
use Symfony\Component\HttpFoundation\Response;


/**
 * ..........
 *
 * @todo
 *   sanitation.
 */
class ExportTasksForm extends FormBase
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
    public function submitForm(array &$form, FormStateInterface $form_state)
    {

        //get current user id & roles
        $user = \Drupal::currentUser();
        $user_roles = \Drupal::currentUser()->getRoles();

        $response = new Response();
        $response->headers->set('Content-Type', 'text/csv; utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename=tasks.csv');

        //get all the tasks if user role is manager if not get only user's task
        $query = \Drupal::entityQuery('todolist_Task');
             if (!in_array("manager", $user_roles)) {
                 $query->condition('user_id', $user->id(), '=');
             }
        $ids= $query->execute();

        $tasks = Task::loadMultiple($ids);

        $csvdata = 'ID,Task Title,Task Content,Status,Date' . PHP_EOL;

        foreach ($tasks as $record) {

            $row = array();
            $row[] = $record->get('id')->value;
            $row[] = $record->get('task_title')->value;
            $row[] = $record->get('task_content')->value;
            $row[] = $record->get('status')->value;
            $row[] = $record->get('task_date')->value;

            $csvdata .= implode(',', $row) . PHP_EOL;
        }

        print $csvdata;
        exit();

    }

}
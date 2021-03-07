<?php

namespace Drupal\todolist\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\user\Entity\User;
use Drupal\todolist\Entity\Task;

/**
 * Provides a 'Hello' Block.
 *
 * @Block(
 *   id = "todolist_block",
 *   admin_label = @Translation("Todolist block"),
 *   category = @Translation("Todolist"),
 * )
 */
class Todolist extends BlockBase
{

    /**
     * {@inheritdoc}
     */
    public function build()
    {

        $header = array(
            array('data' => 'ID', 'field' => 'id', 'sort' => 'asc'),
            array('data' => 'Title', 'field' => 'task_title'),
            array('data' => 'Content', 'field' => 'content_title'),
            array('data' => 'Status', 'field' => 'status'),
            array('data' => 'Date', 'field' => 'task_date'),
        );

        //get current user and his roles
        $user = \Drupal::currentUser();
        $user_roles = \Drupal::currentUser()->getRoles();

        ///
        $query = \Drupal::entityQuery('todolist_Task');
        //get tasks due date is today
        $query->condition('task_date',  date('Y-m-d'), '=');
        //check the role of the user if he is not manager get only user's tasks
        if (!in_array("manager", $user_roles)) {
            $query->condition('user_id', $user->id(), '=');
        }
        $ids= $query->execute();
        $result = Task::loadMultiple($ids);

        $rows = array();
        foreach ($result as $entity) {
            $row['id'] = $entity->id();
            $row['task_title'] = $entity->task_title->value;
            $row['task_content'] = $entity->task_content->value;
            $row['status'] = $entity->status->value;
            $row['task_date'] = $entity->task_date->value;

            $rows[] = array('data' => (array)$row);
        }

        $table['table'] = [
            '#type' => 'table',
            '#header' => $header,
            '#rows' => $rows,
            '#empty' => "No Tasks For Today",
        ];
        return $table;

    }

    /**
     * @return int
     */
    public function getCacheMaxAge()
    {
        return 0;
    }

}
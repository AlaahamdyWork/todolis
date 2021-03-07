<?php

/**
 * @file
 * Contains \Drupal\content_entity_example\ContactAccessControlHandler.
 */

namespace Drupal\todolist;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Access controller for the Task entity.
 *
 * @see \Drupal\todolist\Entity\Task.
 */
class TaskAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   *
   * Link the activities to the permissions. checkAccess is called with the
   * $operation as defined in the routing.yml file.
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {

      $task_user = $entity->toArray();

      //put task permissions if user's role manger or user is the owner of the task
      if (in_array("manager", $account->getRoles()) || $account->id()==$task_user['user_id'][0]['target_id']) {
          switch ($operation) {
              case 'view':
                  return AccessResult::allowedIfHasPermission($account, 'view todolist_Task entity');

              case 'edit':
                  return AccessResult::allowedIfHasPermission($account, 'edit todolist_Task entity');

              case 'delete':
                  return AccessResult::allowedIfHasPermission($account, 'delete todolist_Task entity');
          }

      }
      return AccessResult::allowed();

  }

  /**
   * {@inheritdoc}
   *
   * Separate from the checkAccess because the entity does not yet exist, it
   * will be created during the 'add' process.
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add todolist_Task entity');
  }

}

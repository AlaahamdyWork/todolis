<?php
/**
 * @file
 * Contains \Drupal\content_entity_example\Entity\ContentEntityExample.
 */

namespace Drupal\todolist\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;
use Drupal\Core\Entity\EntityChangedTrait;

/**
 * Defines the ContentEntityExample entity.
 *
 * @ingroup todolist
 *
 *
 * @ContentEntityType(
 *   id = "todolist_Task",
 *   label = @Translation("todolist Task entity"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\todolist\Entity\Controller\TaskListBuilder",
 *     "form" = {
 *       "add" = "Drupal\todolist\Form\TaskForm",
 *       "edit" = "Drupal\todolist\Form\TaskForm",
 *       "delete" = "Drupal\todolist\Form\TaskDeleteForm",
 *     },
 *     "access" = "Drupal\todolist\TaskAccessControlHandler",
 *   },
 *   list_cache_contexts = { "user" },
 *   base_table = "todolist_Task",
 *   admin_permission = "administer todolist_Task entity",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "user_id" = "user_id",
 *     "task_title" = "task_title",
 *     "task_content" = "task_content",
 *     "status" = "status",
 *     "task_date" = "task_date",
 *   },
 *   links = {
 *     "canonical" = "/todolist_Task/{todolist_Task}",
 *     "edit-form" = "/todolist_Task/{todolist_Task}/edit",
 *     "delete-form" = "/todolist_Task/{todolist_Task}/delete",
 *     "collection" = "/todolist_Task/list"
 *   },
 *   field_ui_base_route = "entity.todolist.Task_settings",
 * )
 */
class Task extends ContentEntityBase
{

    use EntityChangedTrait;

    /**
     * {@inheritdoc}
     *
     * When a new entity instance is added, set the user_id entity reference to
     * the current user as the creator of the instance.
     */
    public static function preCreate(EntityStorageInterface $storage_controller, array &$values)
    {
        parent::preCreate($storage_controller, $values);
        // Default author to current user.
        $values += array(
            'user_id' => \Drupal::currentUser()->id(),
        );
    }

    /**
     * {@inheritdoc}
     *
     * Define the field properties here.
     *
     * Field name, type and size determine the table structure.
     *
     * In addition, we can define how the field and its content can be manipulated
     * in the GUI. The behaviour of the widgets used can be determined here.
     */
    public static function baseFieldDefinitions(EntityTypeInterface $entity_type)
    {

        // Standard field, used as unique if primary index.
        $fields['id'] = BaseFieldDefinition::create('integer')
            ->setLabel(t('ID'))
            ->setDescription(t('The ID of the Task entity.'))
            ->setReadOnly(TRUE);

        // Standard field, unique outside of the scope of the current project.
        $fields['uuid'] = BaseFieldDefinition::create('uuid')
            ->setLabel(t('UUID'))
            ->setDescription(t('The UUID of the Contact entity.'))
            ->setReadOnly(TRUE);

        // Name field for the contact.
        // We set display options for the view as well as the form.
        // Users with correct privileges can change the view and edit configuration.
        $fields['task_title'] = BaseFieldDefinition::create('string')
            ->setLabel(t('title'))
            ->setDescription(t('task title.'))
            ->setSettings(array(
                'default_value' => '',
                'max_length' => 255,
                'text_processing' => 0,
            ))
            ->setDisplayOptions('view', array(
                'label' => 'above',
                'type' => 'string',
                'weight' => 1,
            ))
            ->setDisplayOptions('form', array(
                'type' => 'string_textfield',
                'weight' => 1,
            ))
            ->setDisplayConfigurable('form', TRUE)
            ->setDisplayConfigurable('view', TRUE);

        $fields['task_content'] = BaseFieldDefinition::create('string_long')
            ->setLabel(t('content'))
            ->setDescription(t('task content.'))
            ->setSettings(array(
                'default_value' => '',
                'max_length' => 255,
                'text_processing' => 0,
            ))
            ->setDisplayOptions('view', array(
                'label' => 'above',
                'type' => 'basic_string',
                'weight' => 2,
            ))
            ->setDisplayOptions('form', array(
                'type' => 'string_textarea',
                'weight' => 2,
                'settings' => ['rows' => 4],
            ))
            ->setDisplayConfigurable('form', TRUE)
            ->setDisplayConfigurable('view', TRUE);

        $fields['status'] = BaseFieldDefinition::create('list_string')
            ->setLabel(t('status'))
            ->setDescription(t('task status.'))
            ->setDefaultValue('new')
            ->setSettings([
                'allowed_values' => [
                    'new' => 'new',
                    'active' => 'active',
                    'complete' => 'complete',
                ],
            ])
            ->setDisplayOptions('view', array(
                'label' => 'visible',
                'type' => 'list_default',
                'weight' => 3,
            ))
            ->setDisplayOptions('form', array(
                'type' => 'options_select',
                'weight' => 3,
            ))
            ->setDisplayConfigurable('form', TRUE)
            ->setDisplayConfigurable('view', TRUE);

        $fields['task_date'] = BaseFieldDefinition::create('datetime')
            ->setLabel(t('date'))
            ->setDescription(t('task date.'))
            ->setSettings([
                'datetime_type' => 'date'
            ])
            ->setDisplayOptions('view', array(
                'label' => 'above',
                'type' => 'string',
                'weight' => 4,
            ))
            ->setDisplayOptions('form', array(
                'type' => 'datetime_default',
                'weight' => 4,
            ))
            ->setDisplayConfigurable('form', TRUE)
            ->setDisplayConfigurable('view', TRUE);
        // Owner field of the contact.
        // Entity reference field, holds the reference to the user object.
        // The view shows the user name field of the user.
        // The form presents a auto complete field for the user name.
        $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
            ->setLabel(t('User Name'))
            ->setDescription(t('The Name of the associated user.'))
            ->setSetting('target_type', 'user')
            ->setSetting('handler', 'default');

        return $fields;
    }

}

<?php

/**
 * @file
 * Contains \Drupal\todolist\Entity\Controller\TaskListBuilder.
 */

namespace Drupal\todolist\Entity\Controller;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Routing\UrlGeneratorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
/**
 * Provides a list controller for todolist_Task entity.
 *
 * @ingroup dictionary
 */
class TaskListBuilder extends EntityListBuilder
{

    /**
     * The url generator.
     *
     * @var \Drupal\Core\Routing\UrlGeneratorInterface
     */
    protected $urlGenerator;


    /**
     * {@inheritdoc}
     */
    public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type)
    {
        return new static(
            $entity_type,
            $container->get('entity.manager')->getStorage($entity_type->id()),
            $container->get('url_generator')
        );
    }

    /**
     * Constructs a new TaskListBuilder object.
     *
     * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
     *   The entity type task.
     * @param \Drupal\Core\Entity\EntityStorageInterface $storage
     *   The entity storage class.
     * @param \Drupal\Core\Routing\UrlGeneratorInterface $url_generator
     *   The url generator.
     */
    public function __construct(EntityTypeInterface $entity_type, EntityStorageInterface $storage, UrlGeneratorInterface $url_generator)
    {
        parent::__construct($entity_type, $storage);
        $this->urlGenerator = $url_generator;
    }


    /**
     * {@inheritdoc}
     *
     * We override ::render() so that we can add our own content above the table.
     * parent::render() is where EntityListBuilder creates the table using our
     * buildHeader() and buildRow() implementations.
     */
    public function render()
    {
        $build['description'] = array(
            '#markup' => $this->t('', array(
                '@adminlink' => $this->urlGenerator->generateFromRoute('entity.todolist.Task_settings'),
            )),
        );
        $build['table'] = parent::render();
        return $build;
    }

    /**
     * {@inheritdoc}
     *
     * Building the header and content lines for the todolist_Task list.
     *
     * Calling the parent::buildHeader() adds a column for the possible actions
     * and inserts the 'edit' and 'delete' links as defined for the entity type.
     */
    public function buildHeader()
    {
        $header['id'] = $this->t('TaskID');
        $header['task_title'] = $this->t('task_title');
        $header['task_content'] = $this->t('task_content');
        $header['status'] = $this->t('status');
        $header['task_date'] = $this->t('task_date');
        return $header + parent::buildHeader();
    }

    /**
     * {@inheritdoc}
     */
    public function buildRow(EntityInterface $entity)
    {
        /* @var $entity \Drupal\dictionary\Entity\Task */
        $row['id'] = $entity->id();
        $row['task_title'] = $entity->task_title->value;
        $row['task_content'] = $entity->task_content->value;
        $row['status'] = $entity->status->value;
        $row['task_date'] = $entity->task_date->value;
        return $row + parent::buildRow($entity);
    }

    protected function getEntityIds()
    {
        $user = \Drupal::currentUser();
        $user_roles = \Drupal::currentUser()->getRoles();

        $query = $this->getStorage()->getQuery()
            ->sort($this->entityType->getKey('id'));
        if (!in_array("manager", $user_roles)) {
            $query->condition('user_id', $user->id(), '=');
        }

        $this->limit = 5;
        $query->pager($this->limit);

        return $query->execute();
    }

}

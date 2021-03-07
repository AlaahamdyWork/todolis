<?php

/**
 * Implementation of Delete Task.
 */
function todolist_delete_task(){
    global $user;
    /*if($res->uid == $user->uid || in_array("todolist_manager", $user->roles)) {*/
        $id = arg(1);
        $num_updated = db_delete('todolist_task')
            ->condition('tid', $id, '=');
        if(!in_array("todolist_manager", $user->roles)){
            $num_updated->condition('uid',$user->uid,'=');
        }
        $num_updated->execute();
        if($num_updated){
            drupal_set_message(t('Task has been deleted!'));

        }else{
            drupal_set_message(t('Task can not be deleted!'));
        }
    drupal_goto('todolist/list');

}
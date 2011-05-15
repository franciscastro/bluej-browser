<?php

/**
 * A mixin that causes imports to automatically have their error assigned.
 */
class AssignErrorClass extends CActiveRecordBehavior {
  
  public function attach($owner) {
    parent::attach($owner);
  }
  
  public function events() {
    return array_merge(parent::events(), array(
      'onAfterSave'=>'afterSave',
    ));
  }
  
  public function afterSave($event) {
    if($this->owner->messageText == '') return;
    //if(ErrorClass::model()->exists('compileSessionEntryId = :id', array(':id' => $this->owner->id))) return;
    $errorClass = new ErrorClass;
    $errorClass->compileSessionEntryId = $this->owner->id;
    $errorClass->assignClass($this->owner->messageText);    
    $errorClass->save();
  }
}

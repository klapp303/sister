<?php

App::uses('Model', 'Model');

class AppModel extends Model
{
    //半角文字だけかを判定するcustom validation
    public function isHalfLetter($data)
    {
        $str = current($data);
        
        return preg_match('/^[\x21-\x7E]*$/', $str);
    }
    
    //SoftDelete用の記述
    public function exists($id = null)
    {
        if ($this->Behaviors->attached('SoftDelete')) {
            return $this->existsAndNotDeleted($id);
            
        } else {
            return parent::exists($id);
        }
    }
    
    //SoftDelete用の記述
    public function delete($id = null, $cascade = true)
    {
        $result = parent::delete($id, $cascade);
        
        if ($result === false && $this->Behaviors->enabled('SoftDelete')) {
            return $this->field('deleted', array('deleted' => 1));
        }
        
        return $result;
    }
    
    public function loadModel($Model)
    {
        if (!isset($this->{$Model})) {
            $this->{$Model} = ClassRegistry::init(array('class' => $Model, 'alias' => $Model, 'id' => null));
        }
    }
}

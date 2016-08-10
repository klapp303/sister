<?php

App::uses('AppModel', 'Model');

class Birthday extends AppModel
{
    public $useTable = 'birthday';
    
    public $actsAs = array(/*'SoftDelete' , 'Search.Searchable' */);
    
//    public $hasMany = array(
//        'BirthdayBanner' => array(
//            'className' => 'Banner' //関連付けるModel
//        )
//    );
    
//    public $validate = array(
//        'title' => array(
//            'rule' => 'notBlank',
//            'required' => 'create'
//        ),
//        'amount' => array(
//            'rule' => 'numeric',
//            'required' => false,
//            'allowEmpty' => true,
//            'message' => '数値を正しく入力してください。'
//        )
//    );
    
//    public $filterArgs = array(
//        'id' => array('type' => 'value'),
//        'title' => array('type' => 'value')
//    );
}

<?php

App::uses('AppModel', 'Model');

class Link extends AppModel
{
    public $useTable = 'links';
    
//    public $actsAs = array('SoftDelete');
    
//    public $belongsTo = array(
//        'SamplesGenre' => array(
//            'className' => 'SamplesGenre', //関連付けるModel
//            'foreignKey' => 'genre_id', //関連付けるためのfield、関連付け先は上記Modelのid
//            'fields' => 'title' //関連付け先Modelの使用field
//        )
//    );
    
    public $validate = array(
        'title' => array(
            'rule' => 'notBlank',
            'required' => 'create'
        ),
        'link_url' => array(
            'rule' => 'notBlank',
            'required' => 'create'
        ),
        'description' => array(
            'rule' => 'notBlank',
            'required' => 'create'
        ),
    );
    
//    public $filterArgs = array(
//        'id' => array('type' => 'value'),
//        'title' => array('type' => 'value')
//    );
}

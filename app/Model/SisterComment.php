<?php

App::uses('AppModel', 'Model');

class SisterComment extends AppModel {

  public $useTable = 'sister_comments';
  public $actsAs = array(/*'SoftDelete', 'Search.Searchable'*/);

  /*public $belongsTo = array(
      'SamplesGenre' => array(
          'className' => 'SamplesGenre', //関連付けるModel
          'foreignKey' => 'genre_id', //関連付けるためのfield、関連付け先は上記Modelのid
          'fields' => 'title' //関連付け先Modelの使用field
      )
  );*/

  public $validate = array(
      'comment' => array(
          'rule' => 'notBlank',
          'required' => 'create'
      ),
      'charactor' => array(
          'rule' => 'notBlank',
          'required' => 'create'
      )
  );

  /*public $filterArgs = array(
      'id' => array('type' => 'value'),
      'title' => array('type' => 'value')
  );*/
}
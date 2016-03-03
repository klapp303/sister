<?php

App::uses('AppModel', 'Model');

class Photo extends AppModel {

  public $useTable = 'photos';
  public $actsAs = array(/*'SoftDelete', 'Search.Searchable'*/);

  /*public $belongsTo = array(
      'SamplesGenre' => array(
          'className' => 'SamplesGenre', //関連付けるModel
          'foreignKey' => 'genre_id', //関連付けるためのfield、関連付け先は上記Modelのid
          'fields' => 'title' //関連付け先Modelの使用field
      )
  );*/

  public $validate = array(
      'name' => array(
          'rule' => 'notBlank',
          'required' => 'create'
      )
  );

  /*public $filterArgs = array(
      'id' => array('type' => 'value'),
      'title' => array('type' => 'value')
  );*/
}
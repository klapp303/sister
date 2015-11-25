<?php

App::uses('AppModel', 'Model');

/**
 * Maker Model.
 */
class Maker extends AppModel {
  public $useTable = 'makers';
  public $actsAs = array(/*'SoftDelete', 'Search.Searchable'*/);

  /*public $belongsTo = array(
      'SamplesGenre' => array(
          'className' => 'SamplesGenre', //関連付けるModel
          'foreignKey' => 'genre_id', //関連付けるためのfield、関連付け先は上記Modelのid
          'fields' => 'title' //関連付け先Modelの使用field
      )
  );*/

  public $validate = array(
      'title' => array(
          'rule' => 'notBlank',
          'required' => 'create'
      ),
      /*'link_url' => array(
          'rule' => 'notBlank',
          'required' => 'create'
      )*/
  );

  /*public $filtetArgs = array(
      'id' => array('type' => 'value'),
      'title' => array('type' => 'value')
  );*/
}
<?php

App::uses('AppModel', 'Model');

/**
 * Product Model.
 */
class Product extends AppModel {
  public $useTable = 'products';
  public $actsAs = array(/*'SoftDelete', 'Search.Searchable'*/);

  /*public $belongsTo = array(
      'SamplesGenre' => array(
          'className' => 'SamplesGenre', //関連付けるModel
          'foreignKey' => 'genre_id', //関連付けるためのfield、関連付け先は上記Modelのid
          'fields' => 'title' //関連付け先Modelの使用field
      )
  );*/

    public $hasMany = array(
      'Music' => array(
          'className' => 'Music',
          'foreignKey' => 'product_id',
          //'conditions' => array(),
          'order' => array('Music.id' => 'asc')
      )
  );

  public $validate = array(
      'title' => array(
          'rule' => 'notBlank',
          'required' => 'create'
      ),
      'charactor' => array(
          'rule' => 'notBlank',
          'required' => 'create'
      ),
      'genre' => array(
          'rule' => 'notBlank',
          'required' => 'create'
      )
  );

  /*public $filterArgs = array(
      'id' => array('type' => 'value'),
      'title' => array('type' => 'value')
  );*/
}
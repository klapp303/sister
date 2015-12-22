<?php

App::uses('AppModel', 'Model');

/**
 * Diary Model.
 */
class Diary extends AppModel {
  public $useTable = 'diaries';
  public $actsAs = array('SoftDelete', 'Search.Searchable');

  public $belongsTo = array(
      'DiaryGenre' => array(
          'className' => 'DiaryGenre', //関連付けるModel
          'foreignKey' => 'genre_id', //関連付けるためのfield、関連付け先は上記Modelのid
          'fields' => 'title' //関連付け先Modelの使用field
      )
  );

  public $validate = array(
      'title' => array(
          'rule' => 'notBlank',
          'required' => 'create'
      ),
      'text' => array(
          'rule' => 'notBlank',
          'required' => 'create'
      )
  );

  public $filterArgs = array(
      'title' => array('type' => 'like', 'field' => 'Diary.title'),
      'text' => array('type' => 'like', 'field' => 'Diary.text')
  );
}
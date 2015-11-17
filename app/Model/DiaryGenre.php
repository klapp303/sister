<?php

App::uses('AppModel', 'Model');

/**
 * DiaryGenre Model.
 */
class DiaryGenre extends AppModel {
  public $useTable = 'diary_genres';
  //public $actsAs = array('SoftDelete');

  /*public $belongsTo = array(
      'SamplesGenre' => array(
          'className' => 'SamplesGenre', //関連付けるModel
          'foreignKey' => 'genre_id', //関連付けるためのfield、関連付け先は上記Modelのid
          'fields' => 'title' //関連付け先Modelの使用field
      )
  );*/

  /*public $validate = array(
      'title' => array(
          'rule' => 'notBlank',
          'required' => 'create'
      ),
      'amount' => array(
          'rule' => 'numeric',
          'required' => false,
          'allowEmpty' => true,
          'message' => '数値を正しく入力してください。'
      )
  );*/
}
<?php

App::uses('AppModel', 'Model');

/**
 * Game Model.
 */
class Game extends AppModel {
  public $useTable = 'games';
  public $actsAs = array('SoftDelete'/*, 'Search.Searchable'*/);

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
      'scenario_point' => array(
          'rule' => 'numeric',
          'required' => false,
          'allowEmpty' => true,
          'message' => '数値を正しく入力してください。'
      ),
      'music_point' => array(
          'rule' => 'numeric',
          'required' => false,
          'allowEmpty' => true,
          'message' => '数値を正しく入力してください。'
      ),
      'chara_point' => array(
          'rule' => 'numeric',
          'required' => false,
          'allowEmpty' => true,
          'message' => '数値を正しく入力してください。'
      ),
      'still_point' => array(
          'rule' => 'numeric',
          'required' => false,
          'allowEmpty' => true,
          'message' => '数値を正しく入力してください。'
      ),'config_point' => array(
          'rule' => 'numeric',
          'required' => false,
          'allowEmpty' => true,
          'message' => '数値を正しく入力してください。'
      )
  );

  /*public $filtetArgs = array(
      'id' => array('type' => 'value'),
      'title' => array('type' => 'value')
  );*/
}
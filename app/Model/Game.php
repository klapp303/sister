<?php

App::uses('AppModel', 'Model');

/**
 * Game Model.
 */
class Game extends AppModel {
  public $useTable = 'games';
  public $actsAs = array('SoftDelete'/*, 'Search.Searchable'*/);

  public $belongsTo = array(
      'Maker' => array(
          'className' => 'Maker', //関連付けるModel
          'foreignKey' => 'maker_id', //関連付けるためのfield、関連付け先は上記Modelのid
          //'fields' => 'title' //関連付け先Modelの使用field
      )
  );

  public $virtualFields = array(
      //各pointを2乗して加算、/8+37で満点ならば99.5
      'point' => 'ROUND((scenario_point * scenario_point + music_point * music_point + chara_point * chara_point + still_point * still_point + config_point * config_point)/8)+37'
  );

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

  /*public $filterArgs = array(
      'id' => array('type' => 'value'),
      'title' => array('type' => 'value')
  );*/
}
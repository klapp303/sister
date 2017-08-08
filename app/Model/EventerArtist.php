<?php

App::uses('AppModel', 'Model');

class EventerArtist extends AppModel
{
    //別のデータベース接続設定を使用する
    public $useDbConfig = 'sakuraEvesachi';
    
    public $useTable = 'artists';
    
//    public $actsAs = array('SoftDelete');
    
//    public $belongsTo = array(
//        'SamplesGenre' => array(
//            'className' => 'SamplesGenre', //関連付けるModel
//            'foreignKey' => 'genre_id', //関連付けるためのfield、関連付け先は上記Modelのid
//            'fields' => 'title' //関連付け先Modelの使用field
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
    
    public function getArtistLists($type = null)
    {
        //本番環境と開発環境でデータベース接続の設定を分けるため
        if (env('SERVER_ADDR') !== '127.0.0.1') {
            $this->useDbConfig = 'sakuraEvesachi';
        } else {
            $this->useDbConfig = 'localEvesachi';
        }
        
        $data = $this->find($type, array(
            'order' => array('kana' => 'asc'),
        ));
        return $data;
    }
}

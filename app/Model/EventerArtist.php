<?php

App::uses('AppModel', 'Model');

class EventerArtist extends AppModel
{
    //別のデータベース接続設定を使用する
    public $useDbConfig = 'sakuraEvesachi';
    
    public $useTable = 'artists';
    
    public $actsAs = array(/*'SoftDelete' , 'Search.Searchable' */);
    
//    public $hasMany = array(
//        'BirthdayBanner' => array(
//            'className' => 'Banner' //関連付けるModel
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

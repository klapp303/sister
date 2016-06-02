<?php

App::uses('AppModel', 'Model');

class Product extends AppModel
{
    public $useTable = 'products';
    
    public $actsAs = array(/* 'SoftDelete', 'Search.Searchable' */);
    
//    public $belongsTo = array(
//        'SamplesGenre' => array(
//            'className' => 'SamplesGenre', //関連付けるModel
//            'foreignKey' => 'genre_id', //関連付けるためのfield、関連付け先は上記Modelのid
//            'fields' => 'title' //関連付け先Modelの使用field
//        )
//    );
    
    public $hasMany = array(
        'Music' => array(
            'className' => 'Music',
            'foreignKey' => 'product_id',
            'conditions' => array('Music.deleted' => 0),
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
    
//    public $filterArgs = array(
//        'id' => array('type' => 'value'),
//        'title' => array('type' => 'value')
//    );
    
    public function editMusicData($id = false, $data = false)
    {
        //product_editでhasManyのmusicデータを編集時に加工する
        if ($data['Product']['pre_genre'] != 'music') {
            if ($data['Product']['genre'] == 'music') {
                //別ジャンルデータから音楽データに変更する場合
                //空の楽曲データを削除するためにfor構文で記述
                $count = count($data['Music']);
                for ($i = 0; $i < $count; $i++) {
                    if ($data['Music'][$i]['title'] == null) {
                        unset($data['Music'][$i]);
                    } else {
                        $data['Music'][$i]['product_id'] = $id;
                        $data['Music'][$i]['artist'] = $data['Product']['charactor'];
                    }
                }
            } else {
                //別ジャンルデータのみの修正の場合
                unset($data['Music']);
            }
            
        } elseif (@$data['Product']['genre'] != 'music') {
            //音楽データから別ジャンルデータに変更の場合
            //空の楽曲データを削除するためにfor構文で記述
            $count = count($data['Music']);
            for ($i = 0; $i < $count; $i++) {
                if ($data['Music'][$i]['id'] == null) {
                    unset($data['Music'][$i]);
                } else { //紐付くmusicデータは論理削除する
                    $data['Music'][$i]['deleted'] = 1;
                    $data['Music'][$i]['deleted_date'] = date('Y-m-d H:i:s');
                }
            }
            
        } else {
            //音楽データの修正の場合
            if (@$data['Product']['pre_hard'] != 'sg' && @$data['Product']['pre_hard'] != 'al') {
                //元々のmusicデータが存在しない場合
                //空の楽曲データを削除するためにfor構文で記述
                $count = count($data['Music']);
                for ($i = 0; $i < $count; $i++) {
                    if ($data['Music'][$i]['title'] == null) {
                        unset($data['Music'][$i]);
                    } else {
                        $data['Music'][$i]['product_id'] = $id;
                        $data['Music'][$i]['artist'] = $data['Product']['charactor'];
                    }
                }
            } else {
                //元々のmusicデータが存在する場合
                //空の楽曲データを削除するためにfor構文で記述
                $count = count($data['Music']);
                for ($i = 0; $i < $count; $i++) {
                    if ($data['Music'][$i]['id'] == null) {
                        if ($data['Music'][$i]['title'] == null) { //idもtitleもnull
                            unset($data['Music'][$i]);
                        } else { //idはnullだがtitleが存在
                            $data['Music'][$i]['product_id'] = $id;
                            $data['Music'][$i]['artist'] = $data['Product']['charactor'];
                        }
                    } else {
                        if ($data['Music'][$i]['title'] == null) { //idは存在するがtitleがnull
                            //紐付くmusicデータは論理削除する
                            $data['Music'][$i]['deleted'] = 1;
                            $data['Music'][$i]['deleted_date'] = date('Y-m-d H:i:s');
                            unset($data['Music'][$i]['title']); //論理削除するので元々のtitleは保持する
                        } //idもtitleも存在するならば通常の処理
                    }
                }
            }
        }
        
        return $data;
    }
}

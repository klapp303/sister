<?php

App::uses('AppModel', 'Model');

class Banner extends AppModel
{
    public $useTable = 'banners';
    
    public $actsAs = array(/* 'SoftDelete', 'Search.Searchable' */);
    
//    public $belongsTo = array(
//        'SamplesGenre' => array(
//            'className' => 'SamplesGenre', //関連付けるModel
//            'foreignKey' => 'genre_id', //関連付けるためのfield、関連付け先は上記Modelのid
//            'fields' => 'title' //関連付け先Modelの使用field
//        )
//    );
    
    public $validate = array(
        'title' => array(
            'rule' => 'notBlank',
            'required' => 'create'
        ),
        'link_url' => array(
            'rule' => 'notBlank',
            'required' => 'create'
        )
    );
    
//    public $filterArgs = array(
//        'id' => array('type' => 'value'),
//        'title' => array('type' => 'value')
//    );
    
    public function getAyachiBanner($data = false) {
        $data = array(
            0 => array(
                'Banner' => array(
                    'title' => 'Miss. Revolutionist',
                    'image_name' => '../birthday/banner/banner_ayachi_MissRevo.jpg',
                    'link_url' => 'http://ayanataketatsu.jp/discography.html'
                )
            ),
            1 => array(
                'Banner' => array(
                    'title' => 'だがしかし',
                    'image_name' => '../birthday/banner/banner_ayachi_dagashikashi.png',
                    'link_url' => 'http://special.canime.jp/dagashikashi/'
                )
            ),
            2 => array(
                'Banner' => array(
                    'title' => 'アニサマ2016',
                    'image_name' => '../birthday/banner/banner_ayachi_anisama2016.jpg',
                    'link_url' => 'http://anisama.tv/'
                )
            )
        );
        
        return $data;
    }
}

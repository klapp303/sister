<?php

App::uses('AppModel', 'Model');

class Banner extends AppModel
{
    public $useTable = 'banners';
    
    public $actsAs = array('SoftDelete'/* , 'Search.Searchable' */);
    
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
    
    public function belongsToBirthdayFromBanner($banner_lists = false)
    {
        if (!$banner_lists) {
            return $banner_lists;
        }
        
        //listがfind=firstの場合は整形
        $find_first_flg = 0;
        if (@!$banner_lists[0]) {
            $banner_lists[0] = $banner_lists;
            unset($banner_lists['Banner']);
            $find_first_flg = 1;
        }
        
        //バースデーバナーがあればデータを追加する
        foreach ($banner_lists as $key => $banner) {
            $banner_lists[$key]['Banner']['Birthday']['birthday_id'] = '';
            $banner_lists[$key]['Banner']['Birthday']['publish'] = '';
            $banner_lists[$key]['Banner']['Birthday']['system_name'] = '';
            $banner_lists[$key]['Banner']['Birthday']['nickname'] = '';
            
            if ($banner['Banner']['birthday_id']) {
                $birthday_id = $banner['Banner']['birthday_id'];
                $banner_lists[$key]['Banner']['Birthday']['birthday_id'] = $birthday_id;
                
                //バースデー仕様を追加
                $this->loadModel('Birthday');
                $birthday_data = $this->Birthday->find('first', array('conditions' => array('Birthday.id' => $birthday_id)));
                if (!$birthday_data) {
                    continue;
                }
                $banner_lists[$key]['Banner']['Birthday']['publish'] = $birthday_data['Birthday']['publish'];
                
                //声優情報を追加
                $voice_id = $birthday_data['Birthday']['voice_id'];
                $this->loadModel('Voice');
                $voice_data = $this->Voice->find('first', array('conditions' => array('Voice.id' => $voice_id)));
                if (!$voice_data) {
                    continue;
                }
                $banner_lists[$key]['Banner']['Birthday']['system_name'] = $voice_data['Voice']['system_name'];
                $banner_lists[$key]['Banner']['Birthday']['nickname'] = $voice_data['Voice']['nickname'];
            }
        }
        
        //listがfind=firstの場合は型を戻す
        if ($find_first_flg == 1) {
            $banner_lists = $banner_lists[0];
            unset($banner_lists[0]);
        }
        
        return $banner_lists;
    }
}

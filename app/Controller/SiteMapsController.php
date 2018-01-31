<?php

App::uses('AppController', 'Controller');
App::uses('Folder', 'Utility'); //フォルダAPI用

class SiteMapsController extends AppController
{
    public $uses = array('Information', 'Tool', 'Game', 'Voice', 'Diary', 'DiaryGenre', 'DiaryTag', 'DiaryRegtag'); //使用するModel
    
    public $helpers = array('Time');
    
    public $components = array('RequestHandler');
    
    public function beforeFilter()
    {
        parent::beforeFilter();
//        $this->Sample->Behaviors->disable('SoftDelete'); //SoftDeleteのデータも取得する
    }
    
    public function index()
    {
        $this->layout = '/xml/sitemap';
        
        $site_url = 'http://klapp.crap.jp';
        $publish_date = date('2016-02-28'); //sitemap公開日を最終更新日用に設定しておく
        
        //自作ツール
//        $tool_last = $this->Information->find('first', array(
//            'conditions' => array(
//                'Information.publish' => 1,
//                'Information.title LIKE' => '%' . '自作ツール' . '%'
//            ),
//            'order' => array('Information.id' => 'desc')
//        ));
        $array_tools = $this->Tool->getArrayTools();
        $tool_lists = $array_tools['list'];
        
        //エロゲレビュー
        $erg_lists = $this->Game->find('all', array(
            'conditions' => array('Game.publish' => 1),
            'order' => array('Game.modified' => 'desc'),
            'fields' => array('Game.id', 'Game.modified')
        ));
        
        //モンハンメモ
//        $mh_last = $this->Information->find('first', array(
//            'conditions' => array(
//                'Information.publish' => 1,
//                'Information.title LIKE' => '%' . 'モンハンメモ' . '%'
//            ),
//            'order' => array('Information.id' => 'desc')
//        ));
        $folder = new Folder('../View/mh');
        $mh = $folder->read();
        foreach ($mh[1] as $key => &$value) {
            $value = str_replace('.ctp', '', $value);
            if ($value == 'index') {
                $index_key = $key; //indexページを後で除くため
            }
        }
        unset($mh[1][$index_key]);
        $mh_lists = $mh[1];
        
        //声優コンテンツ
        $voice_lists = $this->Voice->find('list', array(
            'conditions' => array('Voice.publish' => 1),
            'fields' => 'system_name'
        ));
        
        //日記
        $diary_lists = $this->Diary->find('all', array(
            'conditions' => array('Diary.publish' => 1),
            'order' => array('Diary.modified' => 'desc'),
            'fields' => array('Diary.id', 'Diary.modified')
        ));
        //日記ジャンル
        $diary_genre_lists = $this->DiaryGenre->find('all', array(
            'order' => array('DiaryGenre.sort' => 'asc')
        ));
        $this->Diary->recursive = -1;
        foreach ($diary_genre_lists as $key => $val) {
            $genre_last = $this->Diary->find('first', array(
                'conditions' => array(
                    'Diary.genre_id' => $val['DiaryGenre']['id'],
                    'Diary.publish' => 1
                ),
                'order' => array('Diary.modified' => 'desc'),
                'fields' => array('Diary.modified')
            ));
            $diary_genre_lists[$key]['DiaryGenre']['lastmod'] = $genre_last['Diary']['modified'];
        }
        //日記タグ
        $diary_tag_lists = $this->DiaryTag->find('all');
        foreach ($diary_tag_lists as $key => $val) {
            $tag_last = $this->DiaryRegtag->find('first', array(
                'conditions' => array(
                    'DiaryRegtag.tag_id' => $val['DiaryTag']['id'],
                    'Diary.publish' => 1
                ),
                'order' => array('Diary.modified' => 'desc'),
                'fields' => array('Diary.modified'),
            ));
            $diary_tag_lists[$key]['DiaryTag']['lastmod'] = $tag_last['Diary']['modified'];
        }
        $this->set(compact(
            'site_url', 'publish_date',
            'tool_lists', 'erg_lists', 'mh_lists', 'voice_lists',
            'diary_lists', 'diary_genre_lists', 'diary_tag_lists'
        ));
        
        $this->RequestHandler->respondAs('xml'); //xmlファイルとして読み込む
    }
}

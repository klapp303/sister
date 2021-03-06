<?php

App::uses('AppController', 'Controller');

class TopController extends AppController
{
    public $uses = array('SisterComment', 'Information', 'Banner', 'Maker', 'Game', 'Diary', 'Product', 'Voice', 'Birthday'); //使用するModel
    
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->layout = 'sister_normal';
    }
    
    public function index()
    {
        //バースデー用
        $birthday = $this->Session->read('birthday');
        if ($birthday) {
            $birthday_voice_data = $this->Voice->find('first', array(
                'conditions' => array(
                    'Voice.system_name' => $birthday,
//                    'Voice.publish' => 1
                )
            ));
            $birthday_data = $this->Birthday->find('first', array(
                'conditions' => array(
                    'Birthday.voice_id' => $birthday_voice_data['Voice']['id'],
                    'Birthday.publish' => 1
                ),
                'order' => array('Birthday.id' => 'desc')
            ));
            $birthday_top_image_name = $birthday_data['Birthday']['top_image_name'];
            $this->set(compact('birthday_voice_data', 'birthday_top_image_name'));
        }
        
        //TOPランダムコメント用
        $sister_comment = $this->SisterComment->find('all', array(
            'conditions' => array('SisterComment.publish' => 1),
            'order' => 'rand()',
            'limit' => 1
        ));
        $this->set('sister_comment', $sister_comment);
        
        //お知らせ用
        /* 最終更新日の取得ここから */
        $last_update = '2014-11-28'; //サイト公開日を初期値に設定
        $contents = array('Information', 'Banner'); //公開日を設定できるコンテンツ
        foreach ($contents as $content) {
            $last_data = $this->$content->find('first', array(
                'conditions' => array($content . '.date_from <=' => date('Y-m-d'), $content . '.publish' => 1),
                'order' => array($content . '.date_from' => 'desc')
            ));
            if ($last_data) {
                if ($last_update <= $last_data[$content]['date_from']) {
                    $last_update = $last_data[$content]['date_from'];
                }
            }
        }
        $articles = array('Game', 'Diary', 'Product'); //作成日で管理する記事
        foreach ($articles as $article) {
            $last_data = $this->$article->find('first', array(
                'conditions' => array($article . '.created <=' => date('Y-m-d', strtotime('+1 day')), $article . '.publish' => 1),
                'order' => array($article . '.created' => 'desc')
            ));
            if ($last_data) {
                if ($last_update <= $last_data[$article]['created']) {
                    $last_update = $last_data[$article]['created'];
                }
            }
        }
        $last_update = mb_strimwidth($last_update, 0, 10);
        $this->set('last_update', $last_update);
        /* 最終更新日の取得ここまで */
        $information_lists = $this->Information->find('all', array(
            'conditions' => array(
                array('or' => array(
                    'Information.date_from <=' => date('Y-m-d'),
                    'Information.date_from' => null
                )),
                array('or' => array(
                    'Information.date_to >=' => date('Y-m-d'),
                    'Information.date_to' => null
                )),
                'Information.publish' => 1
            ),
            'order' => array('Information.id' => 'desc')
        ));
        $this->set('information_lists', $information_lists);
        
        //バナーの取得はPCのみ
        if (!$this->request->is('mobile')) {
            //バナー用
            $banner_lists = $this->Banner->find('all', array(
                'conditions' => array(
                    array('or' => array(
                        'Banner.date_from <=' => date('Y-m-d'),
                        'Banner.date_from' => null
                    )),
                    array('or' => array(
                        'Banner.date_to >=' => date('Y-m-d'),
                        'Banner.date_to' => null
                    )),
                    'Banner.publish' => 1
                ),
                'order' => array('Banner.sort' => 'desc')
            ));
            
            //バースデーバナー用
            if ($birthday) {
                $birthday_banner_lists = $this->Banner->find('all', array(
                    'conditions' => array(
                        'Banner.birthday_id' => $birthday_data['Birthday']['id']
                    ),
                    'order' => array('Banner.sort' => 'desc')
                ));
                if ($birthday_banner_lists) {
                    $banner_lists = $birthday_banner_lists;
                }
            }
            $this->set('banner_lists', $banner_lists);
            
            //メーカーバナー用
            $maker_lists = $this->Maker->find('all', array(
                'conditions' => array('Maker.publish' => 1),
                'order' => array('Maker.title' => 'asc')
            ));
            $this->set('maker_lists', $maker_lists);
            $this->set('mobile', false);
            
        } else {
            $this->set('mobile', true);
        }
    }
}

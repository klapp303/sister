<?php

App::uses('Controller', 'Controller');
App::uses('CakeEmail', 'Network/Email'); //CakeEmaiilの利用

class AppController extends Controller
{
    public $uses = array('Voice', 'Birthday'); //使用するModel
    
    public $components = array(
        'Session', //Paginateのため記述
//        'Flash', //ここからログイン認証用
//        'Auth' => array(
//            'loginRedirect' => array(
//                'controller' => 'top',
//                'action' => 'index'
//            ),
//            'logoutRedirect' => array(
//                'controller' => 'users',
//                'action' => 'login'
//            ),
//            'authenticate' => array(
//                'Form' => array('passwordHasher' => 'Blowfish')
//            )
//        ),
        'DebugKit.Toolbar' //ページ右上の開発用デバッグツール
    );
    
    public function beforeFilter()
    {
//        $this->Auth->allow('index'); //認証なしのページを設定
        
        //メインメニューやパンくずのために定義しておく
        $array_menu = array(
            1 => array(
                'title' => 'ご案内',
                'link' => '#',
                'menu' => array(
                    1 => array('label' => 'サイト紹介', 'link' => '/information/'),
                    2 => array('label' => '管理人の紹介', 'link' => '/author/'),
                    3 => array('label' => '自作ツール', 'link' => '/tools/'),
                    4 => array('label' => 'リンク', 'link' => '/link/')
                )
            ),
            2 => array(
                'title' => 'ゲーム etc',
                'link' => '#',
                'menu' => array(
                    1 => array('label' => 'エロゲレビュー', 'link' => '/game/erg/'),
                    2 => array('label' => 'モンハンメモ', 'link' => '/game/mh/')
                )
            ),
            3 => array(
                'title' => '音楽 etc',
                'link' => '#',
                'menu' => array(
//                    1 => array('label' => '音楽レビュー', 'link' => '#'),
//                    2 => array('label' => '作曲者からみる', 'link' => '#')
                )
            ),
            4 => array(
                'title' => '声優 etc',
                'link' => '#',
                'menu' => array(
//                    1 => array('label' => 'おとちん', 'link' => '/voice/otochin/')
                )
            ),
            5 => array(
                'title' => 'ブログ',
                'link' => '/diary/',
                'menu' => array()
            )
        );
        /* 声優コンテンツの一覧取得ここから */
        $array_menuVoice = $this->Voice->find('all', array('conditions' => array('Voice.publish' => 1)));
        foreach ($array_menuVoice as $menu) {
            array_push($array_menu[4]['menu'], array('label' => $menu['Voice']['nickname'], 'link' => '/voice/' . $menu['Voice']['system_name'] . '/', 'name' => $menu['Voice']['name']));
        }
        /* 声優コンテンツの一覧取得ここまで */
        $this->set('array_menu', $array_menu);
        
        //paginatorのオプションを定義しておく
        $paginator_option = array(
            'modulus' => 4, //現在ページから左右あわせてインクルードする個数
            'separator' => ' | ', //デフォルト値のセパレーター
            'first' => '＜', //先頭ページへのリンク
            'last' => '＞' //最終ページへのリンク
        );
        $this->set('paginator_option', $paginator_option);
        
        //バースデーの判定
        $this->Session->write('birthday', '');
        $voice_lists = $this->Voice->find('all', array(
//            'conditions' => array('Voice.publish' => 1)
        ));
        foreach ($voice_lists as $voice) {
            if (date('m-d', strtotime($voice['Voice']['birthday'])) == date('m-d')) {
                //バースデーの設定情報を取得する
                $birthday = $this->Birthday->find('first', array(
                    'conditions' => array(
                        'Birthday.voice_id' => $voice['Voice']['id'],
                        'Birthday.publish' => 1
                    ),
                    'order' => array('Birthday.id' => 'desc')
                ));
                
                if ($birthday) {
                    //セッション情報に1つまで書き込む
                    $this->Session->write('birthday', $voice['Voice']['system_name']);
                    
                    //テーマカラーを設定
                    if ($birthday['Birthday']['thema_color']) {
                        $this->set('thema_color', $birthday['Birthday']['thema_color']);
                    }
                    if ($birthday['Birthday']['shadow_color']) {
                        $this->set('shadow_color', $birthday['Birthday']['shadow_color']);
                    }
                    if ($birthday['Birthday']['strong_color']) {
                        $this->set('strong_color', $birthday['Birthday']['strong_color']);
                    }
                    if ($birthday['Birthday']['bg_color']) {
                        $this->set('bg_color', $birthday['Birthday']['bg_color']);
                    }
                    
                    //ヘッダー情報の書き換え
                    if ($birthday['Birthday']['header_title']) {
                        $this->set('header_title', $birthday['Birthday']['header_title']);
                    }
                    if ($birthday['Birthday']['header_image_name']) {
                        $this->set('header_image_name', $birthday['Birthday']['header_image_name']);
                    } else {
                        $this->set('header_image_name', 'flower.gif');
                    }
                    
                    //フッター情報の書き換え
                    if ($birthday['Birthday']['footer_title']) {
                        $this->set('footer_title', $birthday['Birthday']['footer_title']);
                    }
                    if ($birthday['Birthday']['footer_image_name']) {
                        $this->set('footer_image_name', $birthday['Birthday']['footer_image_name']);
                    } else {
                        $this->set('footer_image_name', 'cake.png');
                    }
                    
                    break;
                }
            }
        }
    }
}

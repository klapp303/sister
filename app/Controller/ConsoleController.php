<?php

App::uses('AppController', 'Controller');
App::uses('File', 'Utility'); //ファイルAPI用
App::uses('Folder', 'Utility'); //フォルダAPI用

class ConsoleController extends AppController
{
    public $uses = array(
        'Diary', 'DiaryGenre', 'Photo', 'Information', 'SisterComment', 'Banner', 'Link',
        'Administrator', 'Game', 'Maker', 'Voice', 'Product', 'Music', 'Tool'
    ); //使用するModel
    
    public $components = array(
        'Paginator',
        'Flash', //ここからログイン認証用
        'Auth' => array(
            'loginAction' => array(
                'controller' => 'Login',
                'action' => 'login'
            ),
            'loginRedirect' => array(
                'controller' => 'Console',
                'action' => 'index'
            ),
            'logoutRedirect' => array(
                'controller' => 'Login',
                'action' => 'login'
            ),
            'authenticate' => array(
                'Form' => array(
                    'passwordHasher' => 'Blowfish',
                )
            )
        ),
    );
    
    public $paginate = array(
        'limit' => 20,
        'order' => array('date' => 'desc')
    );
    
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->layout = 'console_fullwidth';
//        $this->Sample->Behaviors->disable('SoftDelete'); //SoftDeleteのデータも取得する
        
        /* コンソールメニューの声優データ取得ここから */
        $voice_lists = $this->Voice->find('all');
        $this->set('voice_lists', $voice_lists);
        /* コンソールメニューの声優データ取得ここまで */
        
        /* 出演作品のジャンルとハードの選択肢取得ここから */
        $array_genre = $this->Product->getGenreAndHardList();
        $this->set('array_genre', $array_genre);
        /* 出演作品のジャンルとハードの選択肢取得ここまで */
    }
    
    public function index()
    {
        //ダッシュボード用（件数取得）
        $this->set('comment_count', $this->SisterComment->find('count'));
        $this->set('comment_p_count', $this->SisterComment->find('count', array('conditions' => array('SisterComment.publish' => 1))));
        
        $this->set('banner_count', $this->Banner->find('count'));
        $this->set('banner_p_count', $this->Banner->find('count', array('conditions' => array('Banner.publish' => 1))));
        
        $this->set('game_count', $this->Game->find('count'));
        $this->set('game_p_count', $this->Game->find('count', array('conditions' => array('Game.publish' => 1))));
        
        $this->set('maker_count', $this->Maker->find('count'));
        $this->set('maker_p_count', $this->Maker->find('count', array('conditions' => array('Maker.publish' => 1))));
        
        $folder = new Folder('../View/mh'); //ディレクトリのファイル数から取得
        $mh = $folder->read();
        $this->set('mh_count', count($mh[1]) - 1);
        
        $array_tools = $this->Tool->getArrayTools();
        $this->set('tool_count', $array_tools['count']);
        //urlが設定されてなければ公開前と判断してcountを-1
        foreach ($array_tools['list'] as $tool) {
            if (!$tool['url']) {
                $array_tools['count']--;
            }
        }
        $this->set('tool_p_count', $array_tools['count']);
        
        $voice_lists = $this->Voice->find('all');
        foreach ($voice_lists as $voice_list) {
            $this->set($voice_list['Voice']['system_name'] . '_count', $this->Product->find('count', array('conditions' => array('Product.voice_id' => $voice_list['Voice']['id']))));
            $this->set($voice_list['Voice']['system_name'] . '_p_count', $this->Product->find('count', array('conditions' => array('Product.voice_id' => $voice_list['Voice']['id'], 'Product.publish' => 1))));
            //コンテンツ自体が非公開の場合
            if ($voice_list['Voice']['publish'] == 0) {
                $this->set($voice_list['Voice']['system_name'] . '_p_count', 0);
            }
        }
        
        $this->set('diary_count', $this->Diary->find('count'));
        $this->set('diary_p_count', $this->Diary->find('count', array('conditions' => array('Diary.publish' => 1))));
        
        $this->set('photo_count', $this->Photo->find('count'));
        
        //ダッシュボード用（最終更新）
        $comment_last = $this->SisterComment->find('first', array('order' => array('SisterComment.modified' => 'desc')));
        $this->set('comment_lastupdate', ($comment_last)? $comment_last['SisterComment']['modified'] : null);
        
        $banner_last = $this->Banner->find('first', array('order' => array('Banner.modified' => 'desc')));
        $this->set('banner_lastupdate', ($banner_last)? $banner_last['Banner']['modified'] : null);
        
        $game_last = $this->Game->find('first', array('order' => array('Game.modified' => 'desc')));
        $this->set('game_lastupdate', ($game_last)? $game_last['Game']['modified'] : null);
        
        $maker_last = $this->Maker->find('first', array('order' => array('Maker.modified' => 'desc')));
        $this->set('maker_lastupdate', ($maker_last)? $maker_last['Maker']['modified'] : null);
        
        $mh_last = $this->Information->find('first', array( //お知らせの最終更新から取得する
            'conditions' => array(
                array('or' => array(
                    'Information.date_from <=' => date('Y-m-d'),
                    'Information.date_from' => null
                )),
//                array('or' => array(
//                    'Information.date_to >=' => date('Y-m-d'),
//                    'Information.date_to' => null
//                )),
                'Information.publish' => 1,
                'Information.title LIKE' => '%' . 'モンハンメモ' . '%'
            ),
            'order' => array('Information.id' => 'desc')
        ));
        $this->set('mh_lastupdate', ($mh_last)? $mh_last['Information']['created'] : null);
        
        $tool_last = $this->Information->find('first', array( //お知らせの最終更新から取得する
            'conditions' => array(
                array('or' => array(
                    'Information.date_from <=' => date('Y-m-d'),
                    'Information.date_from' => null
                )),
//                array('or' => array(
//                    'Information.date_to >=' => date('Y-m-d'),
//                    'Information.date_to' => null
//                )),
                'Information.publish' => 1,
                'Information.title LIKE' => '%' . '自作ツール' . '%'
            ),
            'order' => array('Information.id' => 'desc')
        ));
        $this->set('tool_lastupdate', ($tool_last)? $tool_last['Information']['created'] : null);
        
        foreach ($voice_lists as $voice_list) {
            ${$voice_list['Voice']['system_name'] . '_last'} = $this->Product->find('first', array('order' => array('Product.modified' => 'desc'), 'conditions' => array('Product.voice_id' => $voice_list['Voice']['id'])));
            $this->set($voice_list['Voice']['system_name'] . '_lastupdate', (${$voice_list['Voice']['system_name'] . '_last'})? ${$voice_list['Voice']['system_name'] . '_last'}['Product']['modified'] : null);
        }
        
        $diary_last = $this->Diary->find('first', array('order' => array('Diary.modified' => 'desc')));
        $this->set('diary_lastupdate', ($diary_last)? $diary_last['Diary']['modified'] : null);
        
        $photo_last = $this->Photo->find('first', array('order' => array('Photo.modified' => 'desc')));
        $this->set('photo_lastupdate', ($photo_last)? $photo_last['Photo']['modified'] : null);
    }
    
    public function diary() {
        $this->Paginator->settings = array(
            'limit' => 20,
            'order' => array('Diary.id' => 'desc')
        );
        $diary_lists = $this->Paginator->paginate('Diary');
        $this->set('diary_lists', $diary_lists);

        //ジャンル選択肢用
        $genre_lists = $this->DiaryGenre->find('list', array(
            'fields' => 'title',
            'order' => array('DiaryGenre.id' => 'asc')
        ));
        $this->set('genre_lists', $genre_lists);
    }
    
    public function diary_add()
    {
        if ($this->request->is('post')) {
            $this->Diary->set($this->request->data); //postデータがあればModelに渡してvalidate
            if ($this->Diary->validates()) { //validate成功の処理
                $this->Diary->save($this->request->data); //validate成功でsave
                if ($this->Diary->save($this->request->data)) {
                    $this->Session->setFlash('日記を作成しました。', 'flashMessage');
                } else {
                    $this->Session->setFlash('作成できませんでした。', 'flashMessage');
                }
            } else { //validate失敗の処理
                $this->Session->setFlash('入力内容に不備があります。', 'flashMessage');
            }
        }
        
        $this->redirect('/console/diary/');
    }
    
    public function diary_edit()
    {
        $this->Paginator->settings = array(
            'limit' => 20,
            'order' => array('Diary.id' => 'desc')
        );
        $diary_lists = $this->Paginator->paginate('Diary');
        $this->set('diary_lists', $diary_lists);
        
        //ジャンル選択肢用
        $genre_lists = $this->DiaryGenre->find('list', array(
            'fields' => 'title',
            'order' => array('DiaryGenre.id' => 'asc')
        ));
        $this->set('genre_lists', $genre_lists);
        
        //日記の編集用
        if (empty($this->request->data)) {
            $id = $this->request->params['id'];
            $this->request->data = $this->Diary->findById($id); //postデータがなければ$idからデータを取得
            if (!empty($this->request->data)) { //データが存在する場合
                $this->set('id', $id); //viewに渡すために$idをセット
            } else { //データが存在しない場合
                $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
            }
        } else {
            $id = $this->request->data['Diary']['id'];
            $this->Diary->set($this->request->data); //postデータがあればModelに渡してvalidate
            if ($this->Diary->validates()) { //validate成功の処理
                $this->Diary->save($this->request->data); //validate成功でsave
                if ($this->Diary->save($id)) {
                    $this->Session->setFlash('修正しました。', 'flashMessage');
                } else {
                    $this->Session->setFlash('修正できませんでした。', 'flashMessage');
                }
                $this->redirect('/console/diary/');
            } else { //validate失敗の処理
                $this->Session->setFlash('入力内容に不備があります。', 'flashMessage');
                $this->set('id', $this->request->data['Diary']['id']); //viewに渡すために$idをセット
            }
        }
        
        $this->render('diary');
    }
    
    public function diary_delete($id = null)
    {
        if (empty($id)) {
            throw new NotFoundException(__('存在しないデータです。'));
        }
        
        if ($this->request->is('post')) {
            $this->Diary->Behaviors->enable('SoftDelete');
            if ($this->Diary->delete($id)) {
                $this->Session->setFlash('削除しました。', 'flashMessage');
            } else {
                $this->Session->setFlash('削除できませんでした。', 'flashMessage');
            }
            
            $this->redirect('/console/diary/');
        }
    }
    
    public function diary_preview($id = null)
    {
        if (empty($id)) {
            throw new NotFoundException(__('存在しないデータです。'));
        }
        
        $diary_lists = $this->Diary->find('all', array(
            'conditions' => array(
                'Diary.id' => $id
            )
        ));
        if (!empty($diary_lists)) { //データが存在する場合
            $this->set('diary_lists', $diary_lists);
        } else { //データが存在しない場合
            $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
        }
        
        $year = date('Y');
        $month = date('m');
        $hidden_id = 4; //日記一覧では非表示にするジャンル
        
        //カレンダー用
        $diary_cal_lists = $this->Diary->find('list', array( //任意月の日記リストを取得
            'conditions' => array(
                'Diary.date >=' => date($year . '-' . $month . '-01'),
                'Diary.date <=' => date($year . '-' . $month . '-31'),
                'Diary.publish' => 1,
                'Diary.genre_id !=' => $hidden_id
            ),
            'fields' => 'Diary.date'
        ));
        foreach ($diary_cal_lists as &$diary_cal_date) {
            $diary_date = new DateTime($diary_cal_date);
            $diary_cal_date = $diary_date->format('d');
        }
        $this->set('year', $year);
        $this->set('month', $month);
        $this->set('diary_cal_lists', $diary_cal_lists);
        
        //カレンダー前月来月リンク用
        $prev_year = date('Y', strtotime($year . '-' . $month . '-01 -1 month'));
        $prev_month = date('m', strtotime($year . '-' . $month . '-01 -1 month'));
        $this->set('prev_year', $prev_year);
        $this->set('prev_month', $prev_month);
        $next_year = date('Y', strtotime($year . '-' . $month . '-01 +1 month'));
        $next_month = date('m', strtotime($year . '-' . $month . '-01 +1 month'));
        $this->set('next_year', $next_year);
        $this->set('next_month', $next_month);
        
        //ジャンル別メニュー用
        $genre_lists = $this->DiaryGenre->find('all', array(
//            'conditions' => array('DiaryGenre.id >' => 1) //その他ジャンルを除外
        ));
        $this->set('genre_lists', $genre_lists);
        
        //ジャンル別メニュー日記数用
        foreach ($genre_lists as $genre_list) {
            ${'diary_counts_genre' . $genre_list['DiaryGenre']['id']} = $this->Diary->find('count', array(
                'conditions' => array(
                    'Diary.genre_id' => $genre_list['DiaryGenre']['id'],
                    'Diary.publish' => 1
                )
            ));
            $this->set('diary_counts_genre' . $genre_list['DiaryGenre']['id'], ${'diary_counts_genre' . $genre_list['DiaryGenre']['id']});
        }
        $diary_counts_all = $this->Diary->find('count', array( //すべてのジャンルの日記合計数を取得しておく
            'conditions' => array('Diary.publish' => 1)
        ));
        $this->set('diary_counts_all', $diary_counts_all);
        
        $this->layout = 'sister_partition';
        $this->render('/Diary/index');
    }
    
    public function photo($mode = null)
    {
        if ($mode == 'sub_pop') {
            $this->layout = 'sub_pop';
        }
        
        $this->Paginator->settings = array(
            'limit' => 10,
            'order' => array('Photo.id' => 'desc')
        );
        $photo_lists = $this->Paginator->paginate('Photo');
        $this->set('photo_lists', $photo_lists);
    }
    
    public function photo_add()
    {
        if (!empty($this->data)) {
            //空のデータは削除
            $post_data = $this->data['Photo'];
            foreach ($post_data as $key => $val) {
                if (!$val['file']['name']) {
                    unset($post_data[$key]);
                }
            }
            
            $upload_dir = '../webroot/files/photo/'; //保存するディレクトリ
            foreach ($post_data as $key => $data) {
                $upload_pass = $upload_dir . basename($data['file']['name']);
                if (move_uploaded_file($data['file']['tmp_name'], $upload_pass)) { //ファイルを保存
                    $this->Photo->create();
                    $this->Photo->set('name', $data['file']['name']);
                    if ($this->Photo->save()) { //ファイル名を保存
                        $this->Session->setFlash('画像を追加しました。', 'flashMessage');
                    } else {
                        $this->Session->setFlash('ファイル ' . $key . ' のファイル名に不備があるため追加処理中にエラーが発生しました。', 'flashMessage');
                        break;
                    }
                } else {
                    $this->Session->setFlash('ファイル ' . $key . ' の追加処理中にエラーが発生しました。', 'flashMessage');
                    break;
                }
            }
        }
        
        $this->redirect('/console/photo/');
    }
    
    public function photo_delete($id = null)
    {
        if (empty($id)) {
            throw new NotFoundException(__('存在しないデータです。'));
        }
        
        if ($this->request->is('post')) {
            $this->Photo->Behaviors->enable('SoftDelete');
            if ($this->Photo->delete($id)) {
                $this->Session->setFlash('削除しました。', 'flashMessage');
            } else {
                $this->Session->setFlash('削除できませんでした。', 'flashMessage');
            }
            
            $this->redirect('/console/photo/');
        }
    }
    
    public function diary_genre()
    {
        $this->Paginator->settings = array(
            'limit' => 20,
            'order' => array('DiaryGenre.id' => 'asc')
        );
        $diary_genre_lists = $this->Paginator->paginate('DiaryGenre');
        $this->set('diary_genre_lists', $diary_genre_lists);
        
//        if (isset($this->request->params['id']) == true) { //パラメータにidがあれば詳細ページを表示
//            $diary_detail = $this->Diary->find('first', array(
//                'conditions' => array('Diary.id' => $this->request->params['id'])
//            ));
//            if (!empty($diary_detail)) { //データが存在する場合
//                $this->set('diary_detail', $diary_detail);
//                
//                $this->render('detail');
//                
//            } else { //データが存在しない場合
//                $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
//            }
//        }
    }
    
    public function diary_genre_add()
    {
        if ($this->request->is('post')) {
            $this->DiaryGenre->set($this->request->data); //postデータがあればModelに渡してvalidate
            if ($this->DiaryGenre->validates()) { //validate成功の処理
                $this->DiaryGenre->save($this->request->data); //validate成功でsave
                if ($this->DiaryGenre->save($this->request->data)) {
                    $this->Session->setFlash('ジャンルを追加しました。', 'flashMessage');
                } else {
                    $this->Session->setFlash('追加できませんでした。', 'flashMessage');
                }
            } else { //validate失敗の処理
                $this->Session->setFlash('入力内容に不備があります。', 'flashMessage');
            }
        }
        
        $this->redirect('/console/diary_genre/');
    }
    
    public function diary_genre_edit()
    {
        $this->Paginator->settings = array(
            'limit' => 20,
            'order' => array('DiaryGenre.id' => 'asc')
        );
        $diary_genre_lists = $this->Paginator->paginate('DiaryGenre');
        $this->set('diary_genre_lists', $diary_genre_lists);
        
        //日記ジャンルの編集用
        if (empty($this->request->data)) {
            $id = $this->request->params['id'];
            $this->request->data = $this->DiaryGenre->findById($id); //postデータがなければ$idからデータを取得
            if (!empty($this->request->data)) { //データが存在する場合
                $this->set('id', $id); //viewに渡すために$idをセット
            } else { //データが存在しない場合
                $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
            }
        } else {
            $id = $this->request->data['DiaryGenre']['id'];
            $this->DiaryGenre->set($this->request->data); //postデータがあればModelに渡してvalidate
            if ($this->DiaryGenre->validates()) { //validate成功の処理
                $this->DiaryGenre->save($this->request->data); //validate成功でsave
                if ($this->DiaryGenre->save($id)) {
                    $this->Session->setFlash('修正しました。', 'flashMessage');
                } else {
                    $this->Session->setFlash('修正できませんでした。', 'flashMessage');
                }
                $this->redirect('/console/diary_genre/');
            } else { //validate失敗の処理
                $this->Session->setFlash('入力内容に不備があります。', 'flashMessage');
                $this->set('id', $this->request->data['DiaryGenre']['id']); //viewに渡すために$idをセット
            }
        }
        
        $this->render('diary_genre');
    }
    
    public function diary_genre_delete($id = null)
    {
        if (empty($id)) {
            throw new NotFoundException(__('存在しないデータです。'));
        }
        
        if ($this->request->is('post')) {
            $this->DiaryGenre->Behaviors->enable('SoftDelete');
            if ($this->DiaryGenre->delete($id)) {
                $this->Session->setFlash('削除しました。', 'flashMessage');
            } else {
                $this->Session->setFlash('削除できませんでした。', 'flashMessage');
            }
            
            $this->redirect('/console/diary_genre/');
        }
    }
    
    public function information()
    {
        $this->Paginator->settings = array(
            'limit' => 20,
            'order' => array('Information.id' => 'desc')
        );
        $information_lists = $this->Paginator->paginate('Information');
        $this->set('information_lists', $information_lists);
    }
    
    public function information_add()
    {
        if ($this->request->is('post')) {
            $this->Information->set($this->request->data); //postデータがあればModelに渡してvalidate
            if ($this->Information->validates()) { //validate成功の処理
                $this->Information->save($this->request->data); //validate成功でsave
                if ($this->Information->save($this->request->data)) {
                    $this->Session->setFlash('お知らせを追加しました。', 'flashMessage');
                } else {
                    $this->Session->setFlash('追加できませんでした。', 'flashMessage');
                }
            } else { //validate失敗の処理
                $this->Session->setFlash('入力内容に不備があります。', 'flashMessage');
            }
        }
        
        $this->redirect('/console/information/');
    }
    
    public function information_edit()
    {
        $this->Paginator->settings = array(
            'limit' => 20,
            'order' => array('Information.id' => 'desc')
        );
        $information_lists = $this->Paginator->paginate('Information');
        $this->set('information_lists', $information_lists);
        
        //お知らせの編集用
        if (empty($this->request->data)) {
            $id = $this->request->params['id'];
            $this->request->data = $this->Information->findById($id); //postデータがなければ$idからデータを取得
            if (!empty($this->request->data)) { //データが存在する場合
                $this->set('id', $id); //viewに渡すために$idをセット
            } else { //データが存在しない場合
                $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
            }
        } else {
            $id = $this->request->data['Information']['id'];
            /* nullにチェック時の処理ここから */
            if (isset($this->request->data['date_to']) == true && $this->request->data['date_to'] == 'on') {
                $this->request->data['Information']['date_to'] = null;
            }
            /* nullにチェック時の処理ここまで */
            $this->Information->set($this->request->data); //postデータがあればModelに渡してvalidate
            if ($this->Information->validates()) { //validate成功の処理
                $this->Information->save($this->request->data); //validate成功でsave
                if ($this->Information->save($id)) {
                    $this->Session->setFlash('修正しました。', 'flashMessage');
                } else {
                    $this->Session->setFlash('修正できませんでした。', 'flashMessage');
                }
                
                $this->redirect('/console/information/');
                
            } else { //validate失敗の処理
                $this->Session->setFlash('入力内容に不備があります。', 'flashMessage');
                $this->set('id', $this->request->data['Information']['id']); //viewに渡すために$idをセット
            }
        }
        
        $this->render('information');
    }
    
    public function information_delete($id = null)
    {
        if (empty($id)) {
            throw new NotFoundException(__('存在しないデータです。'));
        }
        
        if ($this->request->is('post')) {
            $this->Information->Behaviors->enable('SoftDelete');
            if ($this->Information->delete($id)) {
                $this->Session->setFlash('削除しました。', 'flashMessage');
            } else {
                $this->Session->setFlash('削除できませんでした。', 'flashMessage');
            }
            
            $this->redirect('/console/information/');
        }
    }
    
    public function comment()
    {
        $this->Paginator->settings = array(
            'limit' => 20,
            'order' => array('SisterComment.id' => 'desc')
        );
        $comment_lists = $this->Paginator->paginate('SisterComment');
        $this->set('comment_lists', $comment_lists);
    }
    
    public function comment_add()
    {
        if ($this->request->is('post')) {
            $this->SisterComment->set($this->request->data); //postデータがあればModelに渡してvalidate
            if ($this->SisterComment->validates()) { //validate成功の処理
                $this->SisterComment->save($this->request->data); //validate成功でsave
                if ($this->SisterComment->save($this->request->data)) {
                    $this->Session->setFlash('セリフを登録しました。', 'flashMessage');
                } else {
                    $this->Session->setFlash('追加できませんでした。', 'flashMessage');
                }
            } else { //validate失敗の処理
                $this->Session->setFlash('入力内容に不備があります。', 'flashMessage');
            }
        }
        
        $this->redirect('/console/comment/');
    }
    
    public function comment_edit()
    {
        $this->Paginator->settings = array(
            'limit' => 20,
            'order' => array('SisterComment.id' => 'desc')
        );
        $comment_lists = $this->Paginator->paginate('SisterComment');
        $this->set('comment_lists', $comment_lists);
        
        //セリフの編集用
        if (empty($this->request->data)) {
            $id = $this->request->params['id'];
            $this->request->data = $this->SisterComment->findById($id); //postデータがなければ$idからデータを取得
            if (!empty($this->request->data)) { //データが存在する場合
                $this->set('id', $id); //viewに渡すために$idをセット
            } else { //データが存在しない場合
                $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
            }
        } else {
            $id = $this->request->data['SisterComment']['id'];
            $this->SisterComment->set($this->request->data); //postデータがあればModelに渡してvalidate
            if ($this->SisterComment->validates()) { //validate成功の処理
                $this->SisterComment->save($this->request->data); //validate成功でsave
                if ($this->SisterComment->save($id)) {
                    $this->Session->setFlash('修正しました。', 'flashMessage');
                } else {
                    $this->Session->setFlash('修正できませんでした。', 'flashMessage');
                }
                
                $this->redirect('/console/comment/');
                
            } else { //validate失敗の処理
                $this->Session->setFlash('入力内容に不備があります。', 'flashMessage');
                $this->set('id', $this->request->data['SisterComment']['id']); //viewに渡すために$idをセット
            }
        }
        
        $this->render('comment');
    }
    
    public function comment_delete($id = null)
    {
        if (empty($id)) {
            throw new NotFoundException(__('存在しないデータです。'));
        }
        
        if ($this->request->is('post')) {
            $this->SisterComment->Behaviors->enable('SoftDelete');
            if ($this->SisterComment->delete($id)) {
                $this->Session->setFlash('削除しました。', 'flashMessage');
            } else {
                $this->Session->setFlash('削除できませんでした。', 'flashMessage');
            }
            
            $this->redirect('/console/comment/');
        }
    }
    
    public function banner()
    {
        $this->Paginator->settings = array(
            'limit' => 20,
            'order' => array('Banner.id' => 'desc')
        );
        $banner_lists = $this->Paginator->paginate('Banner');
        $this->set('banner_lists', $banner_lists);
    }
    
    public function banner_add()
    {
        if ($this->request->is('post')) {
            $this->request->data['Banner']['image_name'] = $this->data['Banner']['file']['name'];
            $this->Banner->set($this->request->data); //postデータがあればModelに渡してvalidate
            if ($this->Banner->validates()) { //validate成功の処理
                /* ファイルの保存ここから */
                $upload_dir = '../webroot/files/banner/'; //保存するディレクトリ
                $upload_pass = $upload_dir . basename($this->data['Banner']['file']['name']);
                if (move_uploaded_file($this->data['Banner']['file']['tmp_name'], $upload_pass)) { //ファイルを保存
                /* ファイルの保存ここまで */
                    $this->Banner->save($this->request->data); //validate成功でsave
                } else {
                    $this->Session->setFlash('画像ファイルに不備があります。', 'flashMessage');
                }
                if ($this->Banner->save($this->request->data)) {
                    $this->Session->setFlash('バナーを追加しました。', 'flashMessage');
                } else {
                    $this->Session->setFlash('追加できませんでした。', 'flashMessage');
                }
            } else { //validate失敗の処理
                $this->Session->setFlash('入力内容に不備があります。', 'flashMessage');
            }
        }
        
        $this->redirect('/console/banner/');
    }
    
    public function banner_edit()
    {
        $this->Paginator->settings = array(
            'limit' => 20,
            'order' => array('Banner.id' => 'desc')
        );
        $banner_lists = $this->Paginator->paginate('Banner');
        $this->set('banner_lists', $banner_lists);
        
        //バナーの編集用
        if (empty($this->request->data)) {
            $id = $this->request->params['id'];
            $this->request->data = $this->Banner->findById($id); //postデータがなければ$idからデータを取得
            if (!empty($this->request->data)) { //データが存在する場合
                $this->set('id', $id); //viewに渡すために$idをセット
                $this->set('image_name', $this->request->data['Banner']['image_name']); //viewに渡すためにファイル名をセット
            } else { //データが存在しない場合
                $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
            }
        } else {
            $id = $this->request->data['Banner']['id'];
            /* nullにチェック時の処理ここから */
            if (isset($this->request->data['date_to']) == true && $this->request->data['date_to'] == 'on') {
                $this->request->data['Banner']['date_to'] = null;
            }
            /* nullにチェック時の処理ここまで */
            $this->Banner->set($this->request->data); //postデータがあればModelに渡してvalidate
            if ($this->Banner->validates()) { //validate成功の処理
                /* ファイルの保存ここから */
                if ($this->data['Banner']['file']['error'] != 4) { //新しいファイルがある場合
                    $upload_dir = '../webroot/files/banner/'; //保存するディレクトリ
                    $upload_pass = $upload_dir . basename($this->data['Banner']['file']['name']);
                    if (move_uploaded_file($this->data['Banner']['file']['tmp_name'], $upload_pass)) { //ファイルを保存
                        $this->request->data['Banner']['image_name'] = $this->data['Banner']['file']['name'];
                        $file = new File(WWW_ROOT . 'files/banner/' . $this->request->data['Banner']['delete_name']); //前のファイルを削除
                        $file->delete();
                        $file->close();
                    } else {
                        $this->Session->setFlash('画像ファイルに不備があります。', 'flashMessage');
                        
                        $this->redirect('/console/banner/edit/' . $id);
                    }
                }
                /* ファイルの保存ここまで */
                $this->Banner->save($this->request->data); //validate成功でsave
                if ($this->Banner->save($id)) {
                    $this->Session->setFlash('修正しました。', 'flashMessage');
                } else {
                    $this->Session->setFlash('修正できませんでした。', 'flashMessage');
                }
                
                $this->redirect('/console/banner/');
                
            } else { //validate失敗の処理
                $this->Session->setFlash('入力内容に不備があります。', 'flashMessage');
                $this->set('id', $this->request->data['Banner']['id']); //viewに渡すために$idをセット
            }
        }
        
        $this->render('banner');
    }
    
    public function banner_delete($id = null)
    {
        if (empty($id)) {
            throw new NotFoundException(__('存在しないデータです。'));
        }
        
        if ($this->request->is('post')) {
            $this->Banner->Behaviors->enable('SoftDelete');
            if ($this->Banner->delete($id)) {
                $this->Session->setFlash('削除しました。', 'flashMessage');
            } else {
                $this->Session->setFlash('削除できませんでした。', 'flashMessage');
            }
            
            $this->redirect('/console/banner/');
        }
    }
    
    public function banner_sort()
    {
        $this->Paginator->settings = array(
            'conditions' => array(
                'Banner.publish' => 1,
                'or' => array(
                    array('Banner.date_to' => null),
                    array('Banner.date_to >=' => date('Y-m-d'))
                )
            ),
            'limit' => 20,
            'order' => array('Banner.sort' => 'desc', 'Banner.id' => 'desc')
        );
        $banner_lists = $this->Paginator->paginate('Banner');
        $this->set('banner_lists', $banner_lists);
        
        if ($this->request->is('post')) {
            $sort_id['Banner'] = array_values($this->request->data['Banner']);
            foreach ($sort_id['Banner'] as $key => $value) {
                $sort_id['Banner'][$key]['sort'] = count($sort_id['Banner']) - $key;
            }
            if ($this->Banner->saveMany($sort_id['Banner'])) {
                $this->Session->setFlash('並び順を変更しました。', 'flashMessage');
            } else {
                $this->Session->setFlash('並び順を変更できませんでした。', 'flashMessage');
            }
            
            $this->redirect('/console/banner_sort/');
        }
        
        $this->render('banner_sort');
    }
    
    public function link()
    {
        $this->Paginator->settings = array(
            'limit' => 20,
            'order' => array('Link.id' => 'asc')
        );
        $link_lists = $this->Paginator->paginate('Link');
        $this->set('link_lists', $link_lists);
    }
    
    public function link_add()
    {
        if ($this->request->is('post')) {
            $this->Link->set($this->request->data); //postデータがあればModelに渡してvalidate
            if ($this->Link->validates()) { //validate成功の処理
                $this->Link->save($this->request->data); //validate成功でsave
                if ($this->Link->save($this->request->data)) {
                    $this->Session->setFlash('リンクを追加しました。', 'flashMessage');
                } else {
                    $this->Session->setFlash('追加できませんでした。', 'flashMessage');
                }
            } else { //validate失敗の処理
                $this->Session->setFlash('入力内容に不備があります。', 'flashMessage');
            }
        }
        
        $this->redirect('/console/link/');
    }
    
    public function link_edit()
    {
        $this->Paginator->settings = array(
            'limit' => 20,
            'order' => array('Link.id' => 'asc')
        );
        $link_lists = $this->Paginator->paginate('Link');
        $this->set('link_lists', $link_lists);
        
        //リンクの編集用
        if (empty($this->request->data)) {
            $id = $this->request->params['id'];
            $this->request->data = $this->Link->findById($id); //postデータがなければ$idからデータを取得
            if (!empty($this->request->data)) { //データが存在する場合
                $this->set('id', $id); //viewに渡すために$idをセット
            } else { //データが存在しない場合
                $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
            }
        } else {
            $id = $this->request->data['Link']['id'];
            $this->Link->set($this->request->data); //postデータがあればModelに渡してvalidate
            if ($this->Link->validates()) { //validate成功の処理
                $this->Link->save($this->request->data); //validate成功でsave
                if ($this->Link->save($id)) {
                    $this->Session->setFlash('修正しました。', 'flashMessage');
                } else {
                    $this->Session->setFlash('修正できませんでした。', 'flashMessage');
                }
                
                $this->redirect('/console/link/');
                
            } else { //validate失敗の処理
                $this->Session->setFlash('入力内容に不備があります。', 'flashMessage');
                $this->set('id', $this->request->data['Link']['id']); //viewに渡すために$idをセット
            }
        }
        
        $this->render('link');
    }
    
    public function link_delete($id = null)
    {
        if (empty($id)) {
            throw new NotFoundException(__('存在しないデータです。'));
        }
        
        if ($this->request->is('post')) {
            $this->Link->Behaviors->enable('SoftDelete');
            if ($this->Link->delete($id)) {
                $this->Session->setFlash('削除しました。', 'flashMessage');
            } else {
                $this->Session->setFlash('削除できませんでした。', 'flashMessage');
            }
            
            $this->redirect('/console/link/');
        }
    }
    
    public function admin()
    {
        $this->Paginator->settings = array(
            'limit' => 20,
            'order' => array('Administrator.id' => 'asc')
        );
        $admin_lists = $this->Paginator->paginate('Administrator');
        $this->set('admin_lists', $admin_lists);
    }
    
    public function admin_add()
    {
        if ($this->request->is('post')) {
            $this->Administrator->set($this->request->data); //postデータがあればModelに渡してvalidate
            if ($this->Administrator->validates()) { //validate成功の処理
                $this->Administrator->save($this->request->data); //validate成功でsave
                if ($this->Administrator->save($this->request->data)) {
                    $this->Session->setFlash('管理者を追加しました。', 'flashMessage');
                } else {
                    $this->Session->setFlash('追加できませんでした。', 'flashMessage');
                }
            } else { //validate失敗の処理
                $this->Session->setFlash('入力内容に不備があります。', 'flashMessage');
            }
        }
        
        $this->redirect('/console/admin/');
    }
    
//    public function admin_edit()
//    {
//        $this->Paginator->settings = array(
//            'limit' => 20,
//            'order' => array('Administrator.id' => 'asc')
//        );
//        $admin_lists = $this->Paginator->paginate('Administrator');
//        $this->set('admin_lists', $admin_lists);
//        
//        //管理者の編集用
//        if (empty($this->request->data)) {
//            $id = $this->request->params['id'];
//            $this->request->data = $this->Administrator->findById($id); //postデータがなければ$idからデータを取得
//            if (!empty($this->request->data)) { //データが存在する場合
//                $this->set('id', $id); //viewに渡すために$idをセット
//            } else { //データが存在しない場合
//                $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
//            }
//        } else {
//            $id = $this->request->data['Administrator']['id'];
//            $this->Administrator->set($this->request->data); //postデータがあればModelに渡してvalidate
//            if ($this->Administrator->validates()) { //validate成功の処理
//                $this->Administrator->save($this->request->data); //validate成功でsave
//                if ($this->Administrator->save($id)) {
//                    $this->Session->setFlash('修正しました。', 'flashMessage');
//                } else {
//                    $this->Session->setFlash('修正できませんでした。', 'flashMessage');
//                }
//                
//                $this->redirect('/console/admin/');
//                
//            } else { //validate失敗の処理
//                $this->Session->setFlash('入力内容に不備があります。', 'flashMessage');
//                $this->set('id', $this->request->data['Administrator']['id']); //viewに渡すために$idをセット
//            }
//        }
//        
//        $this->render('admin');
//    }
    
    public function admin_delete($id = null)
    {
        if (empty($id)) {
            throw new NotFoundException(__('存在しないデータです。'));
        }
        
        if ($this->request->is('post')) {
            $this->Administrator->Behaviors->enable('SoftDelete');
            if ($this->Administrator->delete($id)) {
                $this->Session->setFlash('削除しました。', 'flashMessage');
            } else {
                $this->Session->setFlash('削除できませんでした。', 'flashMessage');
            }
            
            $this->redirect('/console/admin/');
        }
    }
    
    public function game()
    {
        $this->Paginator->settings = array(
            'limit' => 20,
            'order' => array('Game.id' => 'desc')
        );
        $game_lists = $this->Paginator->paginate('Game');
        $this->set('game_lists', $game_lists);
        
        //メーカー選択肢用
        $maker_lists = $this->Maker->find('list', array(
            'fields' => 'title',
            'order' => array('Maker.title' => 'asc')
        ));
        $this->set('maker_lists', $maker_lists);
    }
    
    public function game_add()
    {
        if ($this->request->is('post')) {
            $this->Game->set($this->request->data); //postデータがあればModelに渡してvalidate
            if ($this->Game->validates()) { //validate成功の処理
                $this->Game->save($this->request->data); //validate成功でsave
                if ($this->Game->save($this->request->data)) {
                    $this->Session->setFlash('レビューを作成しました。', 'flashMessage');
                } else {
                    $this->Session->setFlash('作成できませんでした。', 'flashMessage');
                }
            } else { //validate失敗の処理
                $this->Session->setFlash('入力内容に不備があります。', 'flashMessage');
            }
        }
        
        $this->redirect('/console/game/');
    }
    
    public function game_edit()
    {
        $this->Paginator->settings = array(
            'limit' => 20,
            'order' => array('Game.id' => 'desc')
        );
        $game_lists = $this->Paginator->paginate('Game');
        $this->set('game_lists', $game_lists);
        
        //メーカー選択肢用
        $maker_lists = $this->Maker->find('list', array(
            'fields' => 'title',
            'order' => array('Maker.title' => 'asc')
        ));
        $this->set('maker_lists', $maker_lists);
        
        //レビューの編集用
        if (empty($this->request->data)) {
            $id = $this->request->params['id'];
            $this->request->data = $this->Game->findById($id); //postデータがなければ$idからデータを取得
            if (!empty($this->request->data)) { //データが存在する場合
                $this->set('id', $id); //viewに渡すために$idをセット
            } else { //データが存在しない場合
                $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
            }
        } else {
            $id = $this->request->data['Game']['id'];
            $this->Game->set($this->request->data); //postデータがあればModelに渡してvalidate
            if ($this->Game->validates()) { //validate成功の処理
                $this->Game->save($this->request->data); //validate成功でsave
                if ($this->Game->save($id)) {
                    $this->Session->setFlash('修正しました。', 'flashMessage');
                } else {
                    $this->Session->setFlash('修正できませんでした。', 'flashMessage');
                }
                
                $this->redirect('/console/game/');
                
            } else { //validate失敗の処理
                $this->Session->setFlash('入力内容に不備があります。', 'flashMessage');
                $this->set('id', $this->request->data['Game']['id']); //viewに渡すために$idをセット
            }
        }
        
        $this->render('game');
    }
    
    public function game_delete($id = null)
    {
        if (empty($id)) {
            throw new NotFoundException(__('存在しないデータです。'));
        }
        
        if ($this->request->is('post')) {
            $this->Game->Behaviors->enable('SoftDelete');
            if ($this->Game->delete($id)) {
                $this->Session->setFlash('削除しました。', 'flashMessage');
            } else {
                $this->Session->setFlash('削除できませんでした。', 'flashMessage');
            }
            
            $this->redirect('/console/game/');
        }
    }
    
    public function maker()
    {
        $this->Paginator->settings = array(
            'limit' => 20,
            'order' => array('Maker.id' => 'desc')
        );
        $maker_lists = $this->Paginator->paginate('Maker');
        $this->set('maker_lists', $maker_lists);
    }
    
    public function maker_add()
    {
        if ($this->request->is('post')) {
            $this->request->data['Maker']['image_name'] = $this->data['Maker']['file']['name'];
            $this->Maker->set($this->request->data); //postデータがあればModelに渡してvalidate
            if ($this->Maker->validates()) { //validate成功の処理
                /* ファイルの保存ここから */
                $upload_dir = '../webroot/files/maker/'; //保存するディレクトリ
                $upload_pass = $upload_dir . basename($this->data['Maker']['file']['name']);
                if (move_uploaded_file($this->data['Maker']['file']['tmp_name'], $upload_pass)) { //ファイルを保存
                /* ファイルの保存ここまで */
                    $this->Maker->save($this->request->data); //validate成功でsave
                } else {
                    $this->Session->setFlash('画像ファイルに不備があります。', 'flashMessage');
                }
                if ($this->Maker->save($this->request->data)) {
                    $this->Session->setFlash('メーカーを追加しました。', 'flashMessage');
                } else {
                    $this->Session->setFlash('追加できませんでした。', 'flashMessage');
                }
            } else { //validate失敗の処理
                $this->Session->setFlash('入力内容に不備があります。', 'flashMessage');
            }
        }
        
        $this->redirect('/console/maker/');
    }
    
    public function maker_edit()
    {
        $this->Paginator->settings = array(
            'limit' => 20,
            'order' => array('Maker.id' => 'desc')
        );
        $maker_lists = $this->Paginator->paginate('Maker');
        $this->set('maker_lists', $maker_lists);
        
        //バナーの編集用
        if (empty($this->request->data)) {
            $id = $this->request->params['id'];
            $this->request->data = $this->Maker->findById($id); //postデータがなければ$idからデータを取得
            if (!empty($this->request->data)) { //データが存在する場合
                $this->set('id', $id); //viewに渡すために$idをセット
                $this->set('image_name', $this->request->data['Maker']['image_name']); //viewに渡すためにファイル名をセット
            } else { //データが存在しない場合
                $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
            }
        } else {
            $id = $this->request->data['Maker']['id'];
            $this->Maker->set($this->request->data); //postデータがあればModelに渡してvalidate
            if ($this->Maker->validates()) { //validate成功の処理
                /* ファイルの保存ここから */
                if ($this->data['Maker']['file']['error'] != 4) { //新しいファイルがある場合
                    $upload_dir = '../webroot/files/maker/'; //保存するディレクトリ
                    $upload_pass = $upload_dir . basename($this->data['Maker']['file']['name']);
                    if (move_uploaded_file($this->data['Maker']['file']['tmp_name'], $upload_pass)) { //ファイルを保存
                        $this->request->data['Maker']['image_name'] = $this->data['Maker']['file']['name'];
                        $file = new File(WWW_ROOT . 'files/maker/' . $this->request->data['Maker']['delete_name']); //前のファイルを削除
                        $file->delete();
                        $file->close();
                    } else {
                        $this->Session->setFlash('画像ファイルに不備があります。', 'flashMessage');
                        
                        $this->redirect('/console/maker/edit/' . $id);
                    }
                }
                /* ファイルの保存ここまで */
                $this->Maker->save($this->request->data); //validate成功でsave
                if ($this->Maker->save($id)) {
                    $this->Session->setFlash('修正しました。', 'flashMessage');
                } else {
                    $this->Session->setFlash('修正できませんでした。', 'flashMessage');
                }
                
                $this->redirect('/console/maker/');
                
            } else { //validate失敗の処理
                $this->Session->setFlash('入力内容に不備があります。', 'flashMessage');
                $this->set('id', $this->request->data['Maker']['id']); //viewに渡すために$idをセット
            }
        }
        
        $this->render('maker');
    }
    
    public function maker_delete($id = null)
    {
        if (empty($id)) {
            throw new NotFoundException(__('存在しないデータです。'));
        }
        
        if ($this->request->is('post')) {
            $this->Maker->Behaviors->enable('SoftDelete');
            if ($this->Maker->delete($id)) {
                $this->Session->setFlash('削除しました。', 'flashMessage');
            } else {
                $this->Session->setFlash('削除できませんでした。', 'flashMessage');
            }
            
            $this->redirect('/console/maker/');
        }
    }
    
    public function voice()
    {
        if (isset($this->request->params['actor']) == true) {
            $actor = $this->request->params['actor'];
            $this->set('actor', $actor);
            /* データベースからプロフィール情報の取得ここから */
            $profile = $this->Voice->find('first', array(
                'conditions' => array('Voice.system_name' => $this->request->params['actor'])
            ));
            $id = null; //PHPエラー回避のため
            $this->set(compact('profile', 'id'));
            if (!$profile) {
                $this->redirect('/console/index');
            }
            /* データベースからプロフィール情報の取得ここまで */
            $this->Paginator->settings = array(
                'limit' => 20,
                'order' => array('Product.date_from' => 'desc'),
                'conditions' => array('Product.voice_id' => $profile['Voice']['id'])
            );
            $product_lists = $this->Paginator->paginate('Product');
            $this->set('product_lists', $product_lists);
            
            $this->render('product');
        }
    }
    
    public function voice_add()
    {
        if ($this->request->is('post')) {
            $this->Voice->set($this->request->data); //postデータがあればModelに渡してvalidate
            if ($this->Voice->validates()) { //validate成功の処理
                $this->Voice->save($this->request->data); //validate成功でsave
                if ($this->Voice->save($this->request->data)) {
                    $this->Session->setFlash($this->request->data['Voice']['name'] . ' を登録しました。', 'flashMessage');
                    
                    $this->redirect('/console/voice/' . $this->request->data['Voice']['system_name']);
                    
                } else {
                    $this->Session->setFlash('登録できませんでした。', 'flashMessage');
                }
            } else { //validate失敗の処理
                $this->Session->setFlash('入力内容に不備があります。', 'flashMessage');
            }
        }
        
        $this->render('voice');
    }
    
    public function voice_edit()
    {
        if ($this->request->params['pass'][0]) {
            $profile = $this->Voice->find('first', array('conditions' => array('Voice.system_name' => $this->request->params['pass'][0])));
            if (!$profile) { //データが存在しない場合
                $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
            }
            
        } else {
            $this->redirect('/console/index');
        }
        
        //声優データの編集用
        if (empty($this->request->data)) {
            $this->request->data = $profile; //postデータがなければデータを取得
        } else {
            $id = $this->request->data['Voice']['id'];
            $this->Voice->set($this->request->data); //postデータがあればModelに渡してvalidate
            if ($this->Voice->validates()) { //validate成功の処理
                $this->Voice->save($this->request->data); //validate成功でsave
                if ($this->Voice->save($id)) {
                    $this->Session->setFlash('修正しました。', 'flashMessage');
                } else {
                    $this->Session->setFlash('修正できませんでした。', 'flashMessage');
                }
                
                $this->redirect('/console/voice/' . $this->request->data['Voice']['system_name']);
                
            } else { //validate失敗の処理
                $this->Session->setFlash('入力内容に不備があります。', 'flashMessage');
                $this->set('id', $this->request->data['Voice']['id']); //viewに渡すために$idをセット
            }
        }
        
        $this->render('voice');
    }
    
    public function voice_delete($id = null)
    {
        if (empty($id)) {
            throw new NotFoundException(__('存在しないデータです。'));
        }
        
        if ($this->request->is('post')) {
            $this->Voice->Behaviors->enable('SoftDelete');
            if ($this->Voice->delete($id)) {
                $this->Session->setFlash('削除しました。', 'flashMessage');
            } else {
                $this->Session->setFlash('削除できませんでした。', 'flashMessage');
            }
            
            $this->redirect('/console/index');
        }
    }
    
    public function product_add()
    {
        if ($this->request->is('post')) {
            /* checkedならばdate_toをnullにする処理ここから */
            if ($this->request->data['Product']['date_to_null'] == 1) {
                $this->request->data['Product']['date_to'] = null;
            }
            /* checkedならばdate_toをnullにする処理ここまで */
            $this->Product->set($this->request->data); //postデータがあればModelに渡してvalidate
            if ($this->Product->validates()) { //validate成功の処理
                $this->Product->save($this->request->data); //validate成功でsave
                if ($this->Product->save($this->request->data)) {
                    /* product_idを取得してmusicデータを保存ここから */
                    if ($this->request->data['Product']['hard'] == 'sg' || $this->request->data['Product']['hard'] == 'al') {
                        $latest_product = $this->Product->find('first', array('order' => array('Product.id' => 'desc')));
                        $product_id = $latest_product['Product']['id'];
                        //空の楽曲データを削除するためにfor構文で記述
                        $count = count($this->request->data['Music']);
                        for ($i = 0; $i < $count; $i++) {
                            if ($this->request->data['Music'][$i]['title'] == null) {
                                unset($this->request->data['Music'][$i]);
                            } else {
                                $this->request->data['Music'][$i]['product_id'] = $product_id;
                                $this->request->data['Music'][$i]['artist'] = $this->request->data['Product']['charactor'];
                            }
                        }
                        if ($this->Music->saveAll($this->request->data['Music'])) {
                            $this->Session->setFlash($this->request->data['Product']['title'] . ' を登録しました。', 'flashMessage');
                        } else {
                            $this->Session->setFlash('楽曲データを登録できませんでした。', 'flashMessage');
                        }
                    } else {
                        /* product_idを取得してmusicデータを保存ここまで */
                        $this->Session->setFlash($this->request->data['Product']['title'] . ' を登録しました。', 'flashMessage');
                    }
                } else {
                    $this->Session->setFlash('登録できませんでした。', 'flashMessage');
                }
            } else { //validate失敗の処理
                $this->Session->setFlash('入力内容に不備があります。', 'flashMessage');
            }
        }
        
        $this->redirect('/console/voice/' . $this->request->data['Product']['voice_actor']);
    }
    
    public function product_edit()
    {
        if (isset($this->request->params['actor']) == true) {
            $actor = $this->request->params['actor'];
            $this->set('actor', $actor);
            /* データベースからプロフィール情報の取得ここから */
            $profile = $this->Voice->find('first', array(
                'conditions' => array('Voice.system_name' => $this->request->params['actor'])
            ));
            $this->set('profile', $profile);
            if (!$profile) {
                $this->redirect('/console/index');
            }
            /* データベースからプロフィール情報の取得ここまで */
            $this->Paginator->settings = array(
                'limit' => 20,
                'order' => array('Product.date_from' => 'desc'),
                'conditions' => array('Product.voice_id' => $profile['Voice']['id'])
            );
            $product_lists = $this->Paginator->paginate('Product');
            $this->set('product_lists', $product_lists);
        }
        
        //出演作品の編集用
        if (empty($this->request->data)) {
            $id = $this->request->params['id'];
            $this->request->data = $this->Product->findById($id); //postデータがなければ$idからデータを取得
            if (!empty($this->request->data)) { //データが存在する場合
                $this->set('id', $id); //viewに渡すために$idをセット
            } else { //データが存在しない場合
                $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
            }
        } else {
            $id = $this->request->data['Product']['id'];
            /* checkedならばdate_toをnullにする処理ここから */
            if ($this->request->data['Product']['date_to_null'] == 1) {
                $this->request->data['Product']['date_to'] = null;
            }
            /* checkedならばdate_toをnullにする処理ここまで */
            $this->Product->set($this->request->data); //postデータがあればModelに渡してvalidate
            if ($this->Product->validates()) { //validate成功の処理
                /* musicデータを保存ここから */
                $this->request->data = $this->Product->editMusicData($id, $this->request->data);
                /* musicデータを保存ここまで */
                $this->Product->saveAssociated($this->request->data); //validate成功でsave
                if ($this->Product->save($id)) {
                    $this->Session->setFlash($this->request->data['Product']['title'] . ' を修正しました。', 'flashMessage');
                } else {
                    $this->Session->setFlash('修正できませんでした。', 'flashMessage');
                }
                
                $this->redirect('/console/voice/' . $this->request->data['Product']['voice_actor']);
                
            } else { //validate失敗の処理
                $this->Session->setFlash('入力内容に不備があります。', 'flashMessage');
                $this->set('id', $this->request->data['Product']['id']); //viewに渡すために$idをセット
            }
        }
        
        $this->render('product');
    }
    
    public function product_delete($id = null)
    {
        if (empty($id)) {
            throw new NotFoundException(__('存在しないデータです。'));
        }
        
        if ($this->request->is('post')) {
            $actor = $this->request->params['pass'][1];
            $this->Product->Behaviors->enable('SoftDelete');
            if ($this->Product->delete($id)) {
                $this->Session->setFlash('削除しました。', 'flashMessage');
            } else {
                $this->Session->setFlash('削除できませんでした。', 'flashMessage');
            }
            
            $this->redirect('/console/voice/' . $actor);
        }
    }
    
    public function music()
    {
        $this->Paginator->settings = array(
            'limit' => 20,
            'order' => array('Music.id' => 'desc')
        );
        $music_lists = $this->Paginator->paginate('Music');
        $this->set('music_lists', $music_lists);
    }
    
    public function music_add()
    {
        if ($this->request->is('post')) {
            $this->Music->set($this->request->data); //postデータがあればModelに渡してvalidate
            if ($this->Music->validates()) { //validate成功の処理
                $this->Music->save($this->request->data); //validate成功でsave
                if ($this->Music->save($this->request->data)) {
                    $this->Session->setFlash('楽曲を登録しました。', 'flashMessage');
                } else {
                    $this->Session->setFlash('追加できませんでした。', 'flashMessage');
                }
            } else { //validate失敗の処理
                $this->Session->setFlash('入力内容に不備があります。', 'flashMessage');
            }
        }
        
        $this->redirect('/console/music/');
    }
    
    public function music_edit()
    {
        $this->Paginator->settings = array(
            'limit' => 20,
            'order' => array('Music.id' => 'desc')
        );
        $music_lists = $this->Paginator->paginate('Music');
        $this->set('music_lists', $music_lists);
        
        //楽曲の編集用
        if (empty($this->request->data)) {
            $id = $this->request->params['id'];
            $this->request->data = $this->Music->findById($id); //postデータがなければ$idからデータを取得
            if (!empty($this->request->data)) { //データが存在する場合
                $this->set('id', $id); //viewに渡すために$idをセット
            } else { //データが存在しない場合
                $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
            }
        } else {
            $id = $this->request->data['Music']['id'];
            $this->Music->set($this->request->data); //postデータがあればModelに渡してvalidate
            if ($this->Music->validates()) { //validate成功の処理
                $this->Music->save($this->request->data); //validate成功でsave
                if ($this->Music->save($id)) {
                    $this->Session->setFlash('修正しました。', 'flashMessage');
                } else {
                    $this->Session->setFlash('修正できませんでした。', 'flashMessage');
                }
                
                $this->redirect('/console/music/');
                
            } else { //validate失敗の処理
                $this->Session->setFlash('入力内容に不備があります。', 'flashMessage');
                $this->set('id', $this->request->data['Music']['id']); //viewに渡すために$idをセット
            }
        }
        
        $this->render('music');
    }
    
    public function music_delete($id = null)
    {
        if (empty($id)) {
            throw new NotFoundException(__('存在しないデータです。'));
        }
        
        if ($this->request->is('post')) {
            $this->Music->Behaviors->enable('SoftDelete');
            if ($this->Music->delete($id)) {
                $this->Session->setFlash('削除しました。', 'flashMessage');
            } else {
                $this->Session->setFlash('削除できませんでした。', 'flashMessage');
            }
            
            $this->redirect('/console/music/');
        }
    }
}

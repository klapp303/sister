<?php

App::uses('AppController', 'Controller');

class DiaryController extends AppController
{
    public $uses = array('Diary', 'DiaryGenre'); //使用するModel
    
    public $components = array(
        'Paginator',
        'Search.Prg' => array(
            'commonProcess' => array(
                'paramType' => 'querystring',
                'filterEmpty' => true
            )
        )
    );
    
    public $presetVars = true;
    
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->layout = 'sister_partition';
//        $this->Sample->Behaviors->disable('SoftDelete'); //SoftDeleteのデータも取得する
    }
    
    public function index()
    {
        $year = date('Y');
        $month = date('m');
        $hidden_id = 4; //日記一覧では非表示にするジャンル
        
        //diaryページの日記一覧を設定
        $this->Paginator->settings = array(
            'limit' => 5,
            'conditions' => array('Diary.publish' => 1, 'Diary.genre_id !=' => $hidden_id),
            'order' => array('Diary.date' => 'desc', 'Diary.id' => 'desc')
        );
        $diary_lists = $this->Paginator->paginate('Diary');
//        $diary_lists = $this->Diary->changeCodeToDiary($diary_lists);
        $diary_lists = $this->Diary->formatDiaryToLazy($diary_lists);
        $this->set('diary_lists', $diary_lists);
        
        //パラメータにidがあれば詳細ページを表示
        if (isset($this->request->params['id']) == true) {
            $diary_lists = $this->Diary->find('all', array(
                'conditions' => array(
                    'Diary.id' => $this->request->params['id'],
                    'Diary.publish' => 1
                )
            ));
            if (!empty($diary_lists)) { //データが存在する場合
                $this->set('sub_page', $diary_lists[0]['Diary']['title']); //breadcrumbの設定
                $diary_lists = $this->Diary->changeCodeToDiary($diary_lists);
                $diary_lists = $this->Diary->changePhotoToFull($diary_lists); //任意の日記は詳細ページのみ画像をfullsize
                $diary_lists = $this->Diary->formatDiaryToLazy($diary_lists);
                $this->set('diary_lists', $diary_lists);
                //OGPタグ用
                $this->set('ogp_title', $diary_lists[0]['Diary']['title']);
                $this->set('ogp_image', $this->Diary->getThumbnailFromText($diary_lists[0]['Diary']['text']));
                $this->set('ogp_description', strip_tags($diary_lists[0]['Diary']['text']));
                
            } else { //データが存在しない場合
                $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
            }
        }
        
        //パラメータにdate_idがなくmonth_idがあれば月別一覧ページを表示
        if (isset($this->request->params['date_id']) == false && isset($this->request->params['month_id']) == true) {
            $diary_lists = $this->Diary->find('all', array(
                'conditions' => array(
                    'Diary.date >=' => date($this->request->params['year_id'] . '-' . $this->request->params['month_id'] . '-01 00:00:00'),
                    'Diary.date <=' => date($this->request->params['year_id'] . '-' . $this->request->params['month_id'] . '-31 23:59:59'),
                    'Diary.publish' => 1,
                    'Diary.genre_id !=' => $hidden_id
                ),
                'order' => array('Diary.date' => 'desc', 'Diary.id' => 'desc')
            ));
            $year = $this->request->params['year_id']; //カレンダー用に定義
            $month = $this->request->params['month_id']; //カレンダー用に定義
            if (!empty($diary_lists)) { //データが存在する場合
                $this->set('sub_page', $year . '年' . $month . '月'); //breadcrumbの設定
//                $diary_lists = $this->Diary->changeCodeToDiary($diary_lists);
                $diary_lists = $this->Diary->formatDiaryToLazy($diary_lists);
                $this->set('diary_lists', $diary_lists);
            } else { //データが存在しない場合
                $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
            }
        }
        
        //パラメータにdate_idがあれば日にち別一覧ページを表示
        if (isset($this->request->params['date_id']) == true) {
            $diary_lists = $this->Diary->find('all', array(
                'conditions' => array(
                    'Diary.date >=' => date($this->request->params['year_id'] . '-' . $this->request->params['month_id'] . '-' . $this->request->params['date_id'] . ' 00:00:00'),
                    'Diary.date <=' => date($this->request->params['year_id'] . '-' . $this->request->params['month_id'] . '-' . $this->request->params['date_id'] . ' 23:59:59'),
                    'Diary.publish' => 1,
                    'Diary.genre_id !=' => $hidden_id
                ),
                'order' => array('Diary.date' => 'desc', 'Diary.id' => 'desc')
            ));
            $year = $this->request->params['year_id']; //カレンダー用に定義
            $month = $this->request->params['month_id']; //カレンダー用に定義
            if (!empty($diary_lists)) { //データが存在する場合
                $this->set('sub_page', $year . '年' . $month . '月' . sprintf('%02d', $this->request->params['date_id']) . '日'); //breadcrumbの設定
//                $diary_lists = $this->Diary->changeCodeToDiary($diary_lists);
                $diary_lists = $this->Diary->formatDiaryToLazy($diary_lists);
                $this->set('diary_lists', $diary_lists);
            } else { //データが存在しない場合
                $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
            }
        }
        
        //カレンダー用
        $calendar = $this->Diary->getCalendarMenu($year, $month, $hidden_id);
        $this->set('calendar', $calendar);
        
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
    }
    
    public function genre()
    {
        $year = date('Y');
        $month = date('m');
        $hidden_id = 4; //日記一覧では非表示にするジャンル
        
        //パラメータにgenre_idがあればジャンル別一覧ページを表示
        if (isset($this->request->params['genre_id']) == true) {
            $this->Paginator->settings = array(
                'limit' => 5,
                'conditions' => array(
                    'Diary.publish' => 1,
                    'Diary.genre_id' => $this->request->params['genre_id']
                ),
                'order' => array('Diary.date' => 'desc', 'Diary.id' => 'desc')
            );
            $diary_lists = $this->Paginator->paginate('Diary');
            if (!empty($diary_lists)) { //データが存在する場合
                $diary_lists = $this->Diary->changeCodeToDiary($diary_lists);
                $diary_lists = $this->Diary->formatDiaryToLazy($diary_lists);
                $this->set('diary_lists', $diary_lists);
            } else { //データが存在しない場合
                $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
                
                $this->redirect('/diary/');
            }
        } else { //ジャンルが存在しない場合
            $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
            
            $this->redirect('/diary/');
        }
        
        //カレンダー用
        $calendar = $this->Diary->getCalendarMenu($year, $month, $hidden_id);
        $this->set('calendar', $calendar);
        
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
        
        //breadcrumbの設定
        $this->set('sub_page', $genre_lists[$this->request->params['genre_id'] - 1]['DiaryGenre']['title'] . '(' . ${'diary_counts_genre' . $this->request->params['genre_id']} . ')');
        
        $this->render('index');
    }
    
    public function search()
    {
        $year = date('Y');
        $month = date('m');
        $hidden_id = 4; //日記一覧では非表示にするジャンル
        
        /* search wordを整形ここから */
        $search_query = @$this->request->query['search_word'];
        $search_word = str_replace('　', ' ', $search_query); //and検索用
        $search_word = str_replace(' OR ', '|', $search_word); //or検索用
        $this->request->query['search_word'] = $search_word;
        /* search wordを整形ここまで */
        $this->Diary->recursive = 0;
        $this->Prg->commonProcess('Diary');
//        $this->Prg->parsedParams();
        $this->Paginator->settings = array(
            'limit' => 5,
            'conditions' => array(
                $this->Diary->parseCriteria($this->passedArgs),
                'Diary.publish' => 1
            ),
            'order' => array('Diary.date' => 'desc', 'Diary.id' => 'desc')
        );
        $diary_lists = $this->Paginator->paginate('Diary');
        $this->request->data['Diary']['search_word'] = $search_query; //seach wordを戻しておく
        if (!empty($diary_lists)) { //データが存在する場合
            $diary_lists = $this->Diary->changeCodeToDiary($diary_lists);
            $diary_lists = $this->Diary->formatDiaryToLazy($diary_lists);
            $this->set('diary_lists', $diary_lists);
        } else { //データが存在しない場合
            $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
            
            $this->redirect('/diary/');
        }
        
        //カレンダー用
        $calendar = $this->Diary->getCalendarMenu($year, $month, $hidden_id);
        $this->set('calendar', $calendar);
        
        //ジャンル別メニュー用
        $genre_lists = $this->DiaryGenre->find('all', array(
//            'conditions' => array('DiaryGenre.id >' => 1) //その他ジャンルを除外
        ));
        $this->set('genre_lists', $genre_lists);
        
        //ジャンル別メニュー日記数用
        foreach ($genre_lists AS $genre_list) {
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
        
        $this->render('index');
    }
    
    public function past($page_id = false)
    {
        $year = date('Y');
        $month = date('m');
        $hidden_id = 4; //日記一覧では非表示にするジャンル
        
        $diary_lists = $this->Diary->formatDiaryFromFc2('agumion_blog_backup_02.txt');
        $diary_data = $this->Diary->selectDiaryToNew($diary_lists, $page_id);
        //cakeのpaginatorは使えないので日記データとpaginatorの設定を分ける
        $diary_lists = $diary_data['lists'];
        $paginator_setting = $diary_data['paginator'];
        //データが存在しない場合
        if (!$diary_lists) {
            $this->Session->setFlash('データが見つかりませんでした。', 'flashMessage');
            
            $this->redirect('/diary/past');
        }
        
//        $diary_lists = $this->Diary->changeCodeToDiary($diary_lists);
//        $diary_lists = $this->Diary->formatDiaryToLazy($diary_lists);
        $this->set('diary_lists', $diary_lists);
        $this->set('paginator_setting', $paginator_setting);
        
        //カレンダー用
        $calendar = $this->Diary->getCalendarMenu($year, $month, $hidden_id);
        $this->set('calendar', $calendar);
        
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
        
        $this->render('index');
    }
}

<?php

App::uses('AppController', 'Controller');
App::uses('File', 'Utility'); //ファイルAPI用
App::uses('Folder', 'Utility'); //フォルダAPI用

class LoginController extends AppController
{
    public $uses = array(
        'Administrator', 'Banner', 'Birthday', 'Diary', 'DiaryGenre', 'DiaryTag', 'Game', 'Information', 'Link',
        'Maker', 'Music', 'Photo', 'Product', 'SisterComment'/*, 'Tool'*/, 'Voice'
    ); //使用するModel
    
    public $components = array(
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
                    'userModel' => 'Administrator', //ログインに使用するModel
                    'fields' => array('username' => 'admin_name') //ログインに使用するfield
                )
            )
        )
    );
    
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->layout = 'sister_normal';
        $this->Auth->allow('login', 'logout');
//        $this->User->Behaviors->disable('SoftDelete'); //SoftDeleteのデータも取得する
    }
    
    public function index()
    {
        $this->redirect('/login/');
    }
    
    public function login()
    {
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                /* ログイン時に定期バックアップを判定して作成ここから */
                $file_pass = '../../backup';
                $file_name = 'sister_backup';
                $backup_flg = 1;
                
                $folder = new Folder($file_pass);
                $lists = $folder->read();
                foreach ($lists[1] as $list) { //ファイル名から日付を取得
                    $name = str_replace(
                            array($file_name . '_', '.txt'), '', $list
                    );
                    if (date('Ymd', strtotime('-7 day')) < date($name)) { //直近のファイルがあればflgを消去
                        $backup_flg = 0;
                        break;
                    }
                }
                
                if ($backup_flg == 1) { //flgがあればバックアップを作成
                    //DBデータを取得する
                    $array_model = array(
                        'Administrator', 'Banner', 'Birthday', 'Diary', 'DiaryGenre', 'DiaryTag', 'Game', 'Information', 'Link',
                        'Maker', 'Music', 'Photo', 'Product', 'SisterComment'/*, 'Tool'*/, 'Voice'
                    );
                    foreach ($array_model as $model) {
                        $this->$model->Behaviors->disable('SoftDelete');
                        $datas = $this->$model->find('all', array('order' => $model . '.id', 'recursive' => -1));
                        $this->set($model . '_datas', $datas);
                        $this->set($model . '_tbl', $this->$model->useTable);
                    }
                    $this->set('array_model', $array_model);
                    
                    $this->layout = false;
                    $sql = $this->render('sql_backup');
                    $file = new File($file_pass . '/' . $file_name . '_' . date('Ymd') . '.sql', true);
                    if ($file->write($sql)) { //バックアップ成功時の処理
                        $file->close();
                        foreach ($lists[1] as $list) {
                            $file = new File($file_pass . '/' . $list);
                            $file->delete();
                            $file->close();
                        }
                    } else { //バックアップ失敗時の処理
                        $file->close();
                        $admin_mail = Configure::read('admin_mail');
                        $email = new CakeEmail('gmail');
                        $email->to($admin_mail)
                                ->subject('【虹妹ｐｒｐｒシステム通知】バックアップエラー通知')
                                ->template('backup_error', 'sister_mail')
                                ->viewVars(array(
                                    'name' => '管理者'
                                )); //mailに渡す変数
                        $email->send();
                    }
                }
                /* ログイン時に定期バックアップを判定して作成ここまで */
                
                $this->redirect($this->Auth->redirect());
                
            } else {
                $this->Flash->error(__('ユーザ名かパスワードが間違っています。'));
                
                $this->redirect('/login/');
            }
        }
    }
    
    public function logout()
    {
        $this->redirect($this->Auth->logout());
    }
}

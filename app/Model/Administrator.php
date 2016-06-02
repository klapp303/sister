<?php

App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth'); //パスワードのハッシュ化のため

class Administrator extends AppModel
{
    public $useTable = 'administrators';
    
    public $actsAs = array('SoftDelete'/* , 'Search.Searchable' */);
    
    public $validate = array(
        'admin_name' => array(
            'rule_1' => array(
                'rule' => 'notBlank',
                'required' => 'create',
                'message' => 'ユーザ名を正しく入力してください。'
            ),
            'rule_2' => array(
                'rule' => 'alphaNumeric',
                'message' => 'ユーザ名は半角英数のみです'
            ),
            'rule_3' => array(
                'rule' => 'isHalfLetter',
                'message' => 'ユーザ名は半角英数のみです'
            ),
//            'rule_4' => array(
//                'rule' => array('between', 4, 10),
//                'message' => 'ユーザ名は4～10文字です'
//            ),
//            'rule_5' => array(
//                'rule' => array('email', true),
//                'message' => 'メールアドレスを正しく入力してください。'
//            ),
//            'rule_6' => array(
//                'rule' => 'isUnique',
//                'message' => '既に登録されているメールアドレスです。'
//            )
        ),
        'password' => array(
            'rule_1' => array(
                'rule' => 'notBlank',
                'required' => 'create',
                'message' => 'パスワードを正しく入力してください。'
            ),
            'rule_2' => array(
                'rule' => 'alphaNumeric',
                'message' => 'パスワードは半角英数のみです'
            ),
            'rule_3' => array(
                'rule' => 'isHalfLetter',
                'message' => 'パスワードは半角英数のみです'
            ),
            'rule_4' => array(
                'rule' => array('between', 4, 10),
                'message' => 'パスワードは4～10文字です'
            )
        )
    );
    
    public function beforeSave($options = [])
    {
        //パスワードのハッシュ化のため
        if (isset($this->data[$this->alias]['password'])) {
            $passwordHasher = new BlowfishPasswordHasher();
            $this->data[$this->alias]['password'] = $passwordHasher->hash(
                    $this->data[$this->alias]['password']
            );
        }
        
        return true;
    }
    
//    public $filterArgs = array(
//        'id' => array('type' => 'value'),
//        'title' => array('type' => 'value')
//    );
}

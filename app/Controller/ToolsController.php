<?php

App::uses('AppController', 'Controller');

class ToolsController extends AppController
{
    public $uses = array('Tool'); //使用するModel
    
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->layout = 'sister_normal';
    }
    
    public function index()
    {
        $array_tools = $this->Tool->getArrayTools();
        //ツール名とurlが設定されてなければ公開前と判断してunset
        foreach ($array_tools['list'] as $key => $tool) {
            if (!$tool['name'] || !$tool['url']) {
                unset($array_tools['list'][$key]);
            }
        }
        $this->set('array_tools', $array_tools);
    }
    
    public function ranking($data_type = null)
    {
        $tool_data = $this->Tool->getToolName('ranking');
        $this->set('tool_data', $tool_data);
        //breadcrumbの設定
        $this->set('sub_page', $tool_data['name']);
        
        //前にソートしたsessionデータが残っていれば削除
        if (@$this->Session->read('sort_data')) {
            $this->Session->delete('sort_data');
        }
        if (@$this->Session->read('sort_log')) {
            $this->Session->delete('sort_log');
        }
        if (@$this->Session->read('select_log')) {
            $this->Session->delete('select_log');
        }
        if (@$this->Session->read('sort_confirm')) {
            $this->Session->delete('sort_confirm');
        }
        
        //デフォルトのデータがある場合は設定
        if ($data_type) {
            $ranking_data = $this->Tool->createRankingData($data_type);
            $this->request->data['Tool']['data'] = $ranking_data;
        }
    }
    
    public function ranking_sort($reset = null)
    {
        $tool_data = $this->Tool->getToolName('ranking');
        $this->set('tool_data', $tool_data);
        //breadcrumbの設定
        $this->set('sub_page', $tool_data['name']);
        
        if (!$this->request->is('post')) {
            //ひとつ前の選択肢に戻るから遷移の場合ならばredirectしない
            if (@$reset && $this->Session->read('sort_log')) {
                
            //それ以外はpostデータがあるので、なければredirect
            } else {
                $this->redirect('/tools/ranking/');
            }
        }
        
        //ソートの処理内容
        //rankingページから遷移の場合、ソートを開始
        if (@$this->request->data['Tool']['data']) {
            $data = $this->request->data['Tool']['data'];
            //postデータを改行毎の配列にする
            $array_data = explode(PHP_EOL, $data);
            $array_data = array_map('trim', $array_data);
            $array_data = array_filter($array_data, 'strlen'); //改行のみ空白のみのデータは配列から削除
            $array_data = array_values($array_data);
            //配列の整形
            $sort_data = [];
            foreach ($array_data as $key => $val) {
                $sort_data[$key]['data'] = $val;
                $sort_data[$key]['sort'] = 0;
                $sort_data[$key]['flg'] = 0;
            }
            //選択肢の順番が予測しにくいように配列をランダムに並び替え
            shuffle($sort_data);
            //1つ目のデータは基準値としてソート値を代入しておく
            $sort_data[0]['sort'] = count($sort_data);
            
            //ソートするデータ数が複数ない場合
            if (count($sort_data) < 2) {
                $this->Session->setFlash('データ数が少なすぎます。<br>データとデータの間は改行してください。', 'flashMessage');
                $this->render('ranking');
                return;
            }
            //ソートするデータ数が100より多い場合
            if (count($sort_data) > 100) {
                $sort_confirm = @$this->Session->read('sort_confirm');
                //最初は確認メッセージを出してSession情報を更新する
                if (!@$sort_confirm || $sort_confirm != count($sort_data)) {
                    $this->Session->write('sort_confirm', count($sort_data));
                    $this->Session->setFlash('データ数が多すぎます。<br>途中でイヤになりますよ。', 'flashMessage');
                    
                    $this->render('ranking');
                    return;
                    
                //2回目も同じSession情報ならばソート開始
                } else {
                    
                }
            }
            
            //選択肢を作成
            $select = [];
            $select['left']['key'] = 1;
            $select['left']['data'] = $sort_data[1]['data'];
            $select['right']['key'] = 0;
            $select['right']['data'] = $sort_data[0]['data'];
            
            //ひとつ前の選択肢に戻るを表示しないため
            $this->set('sort_back', 'no');
            
        //ranking_sortページから遷移の場合、ソートの途中
        } else {
            //通常はsession情報のsort_dataから読み込む
            if (@!$reset) {
                $sort_data = $this->Session->read('sort_data');
                //ひとつ前の選択肢に戻れるようsession情報にlogを残しておく
                if ($sort_data) {
                    $this->Session->delete('sort_log');
                    $this->Session->write('sort_log', $sort_data);
                    $this->Session->delete('select_log');
                    $this->Session->write('select_log', $this->request->data);
                }
            //ひとつ前の選択肢に戻った場合はsession情報のsort_logから読み込む
            } else {
                $sort_data = $this->Session->read('sort_log');
                $select_log = $this->Session->read('select_log');
            }
            
            //ソート中のデータを選択した場合
            if (@$this->request->data['Tool']['sort'] == 'left') {
                $selected = 'left';
                $left_key = $this->request->data['Tool']['left_key'];
                $right_key = $this->request->data['Tool']['right_key'];
                $sort_data[$right_key]['flg'] = 1;
                
                //次の選択肢を作成
                $select = [];
                //比較データはより上位のものを選ぶ
                $right_new_key = $right_key -1;
                if ($right_new_key >= 0 && $sort_data[$right_new_key]['flg'] != 1) {
                    //上位の比較データがあれば選択肢にする
                    $select['right']['key'] = $right_new_key;
                    $select['right']['data'] = $sort_data[$right_new_key]['data'];
                    //ソート中のデータは同じデータを選択肢にする
                    $select['left']['key'] = $left_key;
                    $select['left']['data'] = $sort_data[$left_key]['data'];
                } else {
                    //上位の比較データがなければソート中のデータのソート値を確定
                    foreach ($sort_data as $key => $val) {
                        if ($val['sort'] > 0 && $key >= $right_key) {
                            $sort_data[$key]['sort']--;
                        }
                    }
                    $sort_data[$left_key]['sort'] = $sort_data[$right_key]['sort'] +1;
                    //ソート結果をソート値の降順に並び替える
                    foreach ($sort_data as $key => $val) {
                        //ソート値が同じ場合、データの値によってソートされてしまうので
                        if ($val['sort'] == 0) {
                            $val['sort'] = 0 - $key;
                        }
                        $tmp_array_sort[$key] = $val['sort'];
                    }
                    array_multisort($tmp_array_sort, SORT_DESC, $sort_data);
                    //ソート中のデータは次のデータを選択肢にする
                    $left_new_key = $left_key +1;
                    $select['left']['key'] = $left_new_key;
                    $select['left']['data'] = $sort_data[$left_new_key]['data'];
                    //比較データはソート済の中から選ぶ
                    if ($left_key%2 == 0) {
                        $right_new_key = $left_key /2;
                    } else {
                        $right_new_key = ($left_key +1) /2 -1;
                    }
                    $select['right']['key'] = $right_new_key;
                    $select['right']['data'] = $sort_data[$right_new_key]['data'];
                    //flgはリセットしておく
                    foreach ($sort_data as $key => $val) {
                        $sort_data[$key]['flg'] = 0;
                    }
                }
            }
            
            //比較データを選択した場合
            if (@$this->request->data['Tool']['sort'] == 'right') {
                $selected = 'right';
                $left_key = $this->request->data['Tool']['left_key'];
                $right_key = $this->request->data['Tool']['right_key'];
                $sort_data[$right_key]['flg'] = 1;
                
                //次の選択肢を作成
                $select = [];
                //比較データはより下位のものを選ぶ
                $right_new_key = $right_key +1;
                if ($right_new_key < $left_key && $sort_data[$right_new_key]['flg'] != 1) {
                    //下位の比較データがあれば選択肢にする
                    $select['right']['key'] = $right_new_key;
                    $select['right']['data'] = $sort_data[$right_new_key]['data'];
                    //ソート中のデータは同じデータを選択肢にする
                    $select['left']['key'] = $left_key;
                    $select['left']['data'] = $sort_data[$left_key]['data'];
                } else {
                    //下位の比較データがなければソート中のデータのソート値を確定
                    foreach ($sort_data as $key => $val) {
                        if ($val['sort'] > 0 && $key > $right_key) {
                            $sort_data[$key]['sort']--;
                        }
                    }
                    $sort_data[$left_key]['sort'] = $sort_data[$right_key]['sort'] -1;
                    //ソート結果をソート値の降順に並び替える
                    foreach ($sort_data as $key => $val) {
                        //ソート値が同じ場合、データの値によってソートされてしまうので
                        if ($val['sort'] == 0) {
                            $val['sort'] = 0 - $key;
                        }
                        $tmp_array_sort[$key] = $val['sort'];
                    }
                    array_multisort($tmp_array_sort, SORT_DESC, $sort_data);
                    //ソート中のデータは次のデータを選択肢にする
                    $left_new_key = $left_key +1;
                    $select['left']['key'] = $left_new_key;
                    $select['left']['data'] = $sort_data[$left_new_key]['data'];
                    //比較データはソート済の中から選ぶ
                    if ($left_key%2 == 0) {
                        $right_new_key = $left_key /2;
                    } else {
                        $right_new_key = ($left_key +1) /2 -1;
                    }
                    $select['right']['key'] = $right_new_key;
                    $select['right']['data'] = $sort_data[$right_new_key]['data'];
                    //flgはリセットしておく
                    foreach ($sort_data as $key => $val) {
                        $sort_data[$key]['flg'] = 0;
                    }
                }
            }
            
            //ひとつ前の選択肢から戻った場合
            if (@$reset) {
                //次の選択肢を作成
                $select = [];
                //前の選択肢から取得する
                $left_new_key = $select_log['Tool']['left_key'];
                $select['left']['key'] = $left_new_key;
                $select['left']['data'] = $sort_data[$left_new_key]['data'];
                $right_new_key = $select_log['Tool']['right_key'];
                $select['right']['key'] = $right_new_key;
                $select['right']['data'] = $sort_data[$right_new_key]['data'];
            }
        }
        
        //ソートが完了している場合は結果を表示
        $sort_end_flg = 1;
        foreach ($sort_data as $val) {
            if ($val['sort'] == 0) {
                $sort_end_flg = 0;
            }
        }
        if ($sort_end_flg == 1) {
            $this->Session->setFlash('ソートが完了しました。', 'flashMessage');
            $this->set('sort_data', $sort_data);
            //textarea用のデータを整形
            $sort_data_text = $sort_data[0]['data'];
            foreach ($sort_data as $key => $val) {
                if ($key == 0) {
                    continue;
                }
                $sort_data_text = $sort_data_text . '&#13;' . $val['data'];
            }
            $this->set('sort_data_text', $sort_data_text);
            
            $this->render('ranking');
        }
        
//        echo'<pre>';print_r($select);echo'</pre>';
//        echo'<pre>';print_r($sort_data);echo'</pre>';
        
        $this->set('select', $select);
        //ソート結果はsession情報として渡す
        $this->Session->write('sort_data', $sort_data);
    }
    
    public function lot()
    {
        $tool_data = $this->Tool->getToolName('lot');
        $this->set('tool_data', $tool_data);
        //breadcrumbの設定
        $this->set('sub_page', $tool_data['name']);
        
        //postデータがあれば抽選を実行
        if ($this->request->is('post')) {
            //申込者と当選数を取得
            $data = $this->request->data['Tool']['data'];
            $lot_number = $this->request->data['Tool']['number'];
            
            //postデータを改行毎の配列にする
            $array_data = explode(PHP_EOL, $data);
            $array_data = array_map('trim', $array_data);
            $array_data = array_filter($array_data, 'strlen'); //改行のみ空白のみのデータは配列から削除
            $lot_data = array_values($array_data);
            
            //抽選数が多すぎる場合
            if (count($lot_data) > 100) {
                $this->Session->setFlash('抽選数が多すぎます。<br>申込者は100までにしてください。', 'flashMessage');
                $this->render('lot');
                return;
            }
            
            //抽選の実行
            //当選数が申込数より多い場合は申込数を当選数にする
            if (count($lot_data) <= $lot_number) {
                $lot_number = count($lot_data);
            //当選数が申込数より少ない場合は抽選を実行する
            } else {
                //入力された値を元にseed値を生成
                $str = "";
                foreach ($lot_data as $val) {
                    $str .= $val;
                }
                $str = md5($str);
                $str = str_replace(
                        array('a', 'b', 'c', 'd', 'e', 'f'),
                        array(1, 2, 3, 4, 5, 6),
                        $str);
                //intの最大値を越えないように
                $arr_seed = str_split($str, 8);
                $seed = 0;
                foreach ($arr_seed as $val) {
                    $seed += $val;
                }
                //抽選結果は日替わりにする
                $seed += date('Ymd');
                srand($seed);
                shuffle($lot_data);
            }
//            echo'<pre>';print_r($lot_data);echo'</pre>';
            $result_data = [];
            $result_data_text = '';
            for ($i = 0; $i < $lot_number; $i++) {
                $result_data[$i] = $lot_data[$i];
                $result_data_text .= $lot_data[$i] . '&#13'; 
            }
            
            $this->Session->setFlash('抽選が実行されました。', 'flashMessage');
            $this->set(compact('result_data', 'result_data_text'));
        }
    }
    
    public function mh_skill()
    {
        $tool_data = $this->Tool->getToolName('mh_skill');
        $this->set('tool_data', $tool_data);
        //breadcrumbの設定
        $this->set('sub_page', $tool_data['name']);
        
        //前に計算したログのsessionデータが残っていれば削除
        if (@$this->Session->read('weapon_logs')) {
            $this->Session->delete('weapon_logs');
        }
    }
    
    public function mh_skill_sim()
    {
        $tool_data = $this->Tool->getToolName('mh_skill');
        $this->set('tool_data', $tool_data);
        //breadcrumbの設定
        $this->set('sub_page', $tool_data['name']);
        
        if (!$this->request->is('post')) {
            $this->redirect('/tools/mh_skill/');
        }
        
        //武器とスキルを取得
        $weapon_data = $this->request->data['weapon'];
        $skill_data = $this->request->data['skill'];
        
        //スキル計算
        $weapon_sim = $this->Tool->MHSkillSim($weapon_data, $skill_data);
        $this->set('weapon_sim', $weapon_sim);
//        echo'<pre>';print_r($weapon_sim);echo'</pre>';
        
        //ここからログの保存用
        if (@$this->Session->read('weapon_logs')) {
            $weapon_logs = $this->Session->read('weapon_logs');
        } else {
            $weapon_logs = array();
        }
        $this->set('weapon_logs', $weapon_logs);
        //結果を新しくログに追加
        $attack_log = ($this->request->data['weapon']['attack'])? $this->request->data['weapon']['attack'] : 0;
        $element_log = ($this->request->data['weapon']['element'])? $this->request->data['weapon']['element'] : 0;
        $current_logs = array(
            array(
                'name' => '攻撃力' . $attack_log . ' / 属性値' . $element_log,
                'attack' => $weapon_sim['attack'],
                'element' => $weapon_sim['element']
            )
        );
        $weapon_logs = array_merge($current_logs, $weapon_logs);
        //ログの保持は最大4件にしておく
        if (count($weapon_logs) > 4) {
            unset($weapon_logs[4]);
        }
        $this->Session->write('weapon_logs', $weapon_logs);
        
        //値の引き継ぎに応じて、選択済みスキルの背景色を予め変えておく
        //有効スキル
        $array_checked = array();
        foreach ($skill_data as $key => $val) {
            if ($val >0) {
                $skillId = 'js-skill-' . $key . '-' . $val;
                $array_checked[] = $skillId;
            }
        }
        //無効スキル
        $array_invalid = array();
        foreach ($weapon_sim['skill_invalid'] as $key => $val) {
            $invalidId = 'js-skill-' . $key . '-' . $val;
            $array_invalid[] = $invalidId;
        }
        $this->set(compact('array_checked', 'array_invalid'));
        
        $this->render('mh_skill');
    }
    
    public function mhw_skill()
    {
        $tool_data = $this->Tool->getToolName('mhw_skill');
        $this->set('tool_data', $tool_data);
        //breadcrumbの設定
        $this->set('sub_page', $tool_data['name']);
        
        //前に計算したログのsessionデータが残っていれば削除
        if (@$this->Session->read('weapon_logs')) {
            $this->Session->delete('weapon_logs');
        }
    }
    
    public function mhw_skill_sim()
    {
        $tool_data = $this->Tool->getToolName('mhw_skill');
        $this->set('tool_data', $tool_data);
        //breadcrumbの設定
        $this->set('sub_page', $tool_data['name']);
        
        if (!$this->request->is('post')) {
            $this->redirect('/tools/mhw_skill/');
        }
        
        //武器とスキルを取得
        $weapon_data = $this->request->data['weapon'];
        $skill_data = $this->request->data['skill'];
        
        //スキル計算
        $weapon_sim = $this->Tool->MHWSkillSim($weapon_data, $skill_data);
        $this->set('weapon_sim', $weapon_sim);
//        echo'<pre>';print_r($weapon_sim);echo'</pre>';
        
        //ここからログの保存用
        if (@$this->Session->read('weapon_logs')) {
            $weapon_logs = $this->Session->read('weapon_logs');
        } else {
            $weapon_logs = array();
        }
        $this->set('weapon_logs', $weapon_logs);
        //結果を新しくログに追加
        $attack_log = ($this->request->data['weapon']['attack'])? $this->request->data['weapon']['attack'] : 0;
        $element_log = ($this->request->data['weapon']['element'])? $this->request->data['weapon']['element'] : 0;
        $current_logs = array(
            array(
                'name' => '攻撃力' . $attack_log . ' / 属性値' . $element_log,
                'attack' => $weapon_sim['attack'],
                'element' => $weapon_sim['element']
            )
        );
        $weapon_logs = array_merge($current_logs, $weapon_logs);
        //ログの保持は最大4件にしておく
        if (count($weapon_logs) > 4) {
            unset($weapon_logs[4]);
        }
        $this->Session->write('weapon_logs', $weapon_logs);
        
        //値の引き継ぎに応じて、選択済みスキルの背景色を予め変えておく
        //有効スキル
        $array_checked = array();
        foreach ($skill_data as $key => $val) {
            if ($val >0) {
                $skillId = 'js-skill-' . $key . '-' . $val;
                $array_checked[] = $skillId;
            }
        }
        //無効スキル
        $array_invalid = array();
        foreach ($weapon_sim['skill_invalid'] as $key => $val) {
            $invalidId = 'js-skill-' . $key . '-' . $val;
            $array_invalid[] = $invalidId;
        }
        $this->set(compact('array_checked', 'array_invalid'));
        
        $this->render('mhw_skill');
    }
}

<?php

App::uses('AppController', 'Controller');

class ToolsController extends AppController
{
    public $uses = array('Tool'); //使用するModel
    
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->layout = 'sister_fullwidth';
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
    
    public function ranking($data_type = false)
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
    
    public function ranking_sort($reset = false)
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
    
    public function mh_skill()
    {
        $tool_data = $this->Tool->getToolName('mh_skill');
        $this->set('tool_data', $tool_data);
        //breadcrumbの設定
        $this->set('sub_page', $tool_data['name']);
        
        
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
        
        //期待値の計算内容
        $weapon_data = $this->request->data['weapon'];
        $skill_data = $this->request->data['skill'];
        //基礎攻撃力に変換は今回なし
        
        //スキル
        //ここから攻撃力の計算
        //攻撃力UP
        if ($skill_data[1] == 1) {
            $weapon_data['attack'] += 10;
        } elseif($skill_data[1] == 2) {
            $weapon_data['attack'] += 15;
        } elseif($skill_data[1] == 3) {
            $weapon_data['attack'] += 20;
        }
        //挑戦者
        if ($skill_data[3] == 1) {
            $weapon_data['attack'] += 10*0.67;
        } elseif ($skill_data[3] == 2) {
            $weapon_data['attack'] += 20*0.67;
        }
        //フルチャージ、逆恨み
        if ($skill_data[6] == 1 || $skill_data[6] == 3) {
            $weapon_data['attack'] += 20;
        } elseif ($skill_data[6] == 2 || $skill_data[6] == 4) {
            $weapon_data['attack'] += 20*0.5;
        }
        //北風、南風
        if ($skill_data[7] == 1) {
            $weapon_data['attack'] += 20;
        } elseif ($skill_data[7] == 2) {
            $weapon_data['attack'] += 20*0.5;
        } elseif ($skill_data[7] == 3) {
            $weapon_data['attack'] += 15;
        } elseif ($skill_data[7] == 4) {
            $weapon_data['attack'] += 15*0.5;
        }
        //護符爪
        $weapon_data['attack'] += 15;
        //ネコ飯
        $weapon_data['attack'] += 7;
        //怪力の種 or 鬼人笛
        $weapon_data['attack'] += 10;
        
        //ここから会心率の計算
        //見切り
        if ($skill_data[2] == 1) {
            $weapon_data['critical'] += 10;
        } elseif($skill_data[2] == 2) {
            $weapon_data['critical'] += 20;
        } elseif ($skill_data[2] == 3) {
            $weapon_data['critical'] += 30;
        }
        //挑戦者
        if ($skill_data[3] == 1) {
            $weapon_data['critical'] += 10*0.67;
        } elseif ($skill_data[3] == 2) {
            $weapon_data['critical'] += 15*0.67;
        }
        //弱点特効
        if ($skill_data[4] == 1) {
            $weapon_data['critical'] += 50;
        }
        //連撃
        if ($skill_data[5] == 1) {
            $weapon_data['critical'] += 25;
        }
        //会心率が100%の判定
        if ($weapon_data['critical'] > 100) {
            $weapon_data['critical'] = 100;
        } elseif ($weapon_data['critical'] < -100) {
            $weapon_data['critical'] = -100;
        }
        
        //物理値に変換
        //会心マイナス
        if ($weapon_data['critical'] < 0) {
            $weapon_data['attack'] = $weapon_data['attack'] *0.75 *(-$weapon_data['critical']) + $weapon_data['attack'] *1 *(100 + $weapon_data['critical']);
        //会心強化あり
        } elseif ($skill_data[8] == 1) {
            $weapon_data['attack'] = $weapon_data['attack'] *1.4 *$weapon_data['critical'] + $weapon_data['attack'] *1 *(100 - $weapon_data['critical']);
        //会心強化なし
        } else {
            $weapon_data['attack'] = $weapon_data['attack'] *1.25 *$weapon_data['critical'] + $weapon_data['attack'] *1 *(100 - $weapon_data['critical']);
        }
        $weapon_data['attack'] = $weapon_data['attack'] /100;
        //斬れ味補正
        if ($weapon_data['sharp'] == 0) { //赤
            $weapon_data['attack'] = $weapon_data['attack'] *0.5 *0.5;
        } elseif ($weapon_data['sharp'] == 1) { //橙
            $weapon_data['attack'] = $weapon_data['attack'] *0.75 *0.5;
        } elseif ($weapon_data['sharp'] == 2) { //黄
            $weapon_data['attack'] = $weapon_data['attack'] *1.0 *0.5;
        } elseif ($weapon_data['sharp'] == 3) { //緑
            $weapon_data['attack'] = $weapon_data['attack'] *1.05;
        } elseif ($weapon_data['sharp'] == 4) { //青
            $weapon_data['attack'] = $weapon_data['attack'] *1.2;
        } elseif ($weapon_data['sharp'] == 5) { //白
            $weapon_data['attack'] = $weapon_data['attack'] *1.32;
//        } elseif ($weapon_data['sharp'] == 6) { //紫
//            $weapon_data['attack'] = $weapon_data['attack'] *1.45;
        }
        //中腹補正、大剣太刀はモーション中間かつ武器中腹ヒットで1.05
        $weapon_data['attack'] += $weapon_data['attack'] *0.05 *0.25;
        
        //ここから属性値の計算
        //各属性攻撃強化
        if ($skill_data[11] == 1) {
            $weapon_data['element'] = $weapon_data['element'] *1.05 +4;
        } elseif ($skill_data[11] == 2 && $skill_data[12] == 0) {
            $weapon_data['element'] = $weapon_data['element'] *1.1 +6;
        }
        //属性攻撃強化
        if ($skill_data[12] == 1 && $skill_data[11] != 2) {
            $weapon_data['element'] *= 1.1;
        //W属性強化
        } elseif ($skill_data[12] == 1 && $skill_data[11] == 2) {
            $weapon_data['element'] = $weapon_data['element'] *1.2 +6;
        }
        //属性会心強化
        if ($skill_data[13] == 1 && $weapon_data['critical'] > 0) {
            //会心時に太刀ならば属性値1.25倍
            $weapon_data['element'] = $weapon_data['element'] *1.25 *$weapon_data['critical'] + $weapon_data['element'] *1 *(100 - $weapon_data['critical']);
            $weapon_data['element'] = $weapon_data['element'] /100;
        }
        //斬れ味補正
        if ($weapon_data['sharp'] == 0) { //赤
            $weapon_data['element'] = $weapon_data['element'] *0.25;
        } elseif ($weapon_data['sharp'] == 1) { //橙
            $weapon_data['element'] = $weapon_data['element'] *0.5;
        } elseif ($weapon_data['sharp'] == 2) { //黄
            $weapon_data['element'] = $weapon_data['element'] *0.75;
        } elseif ($weapon_data['sharp'] == 3) { //緑
            $weapon_data['element'] = $weapon_data['element'] *1;
        } elseif ($weapon_data['sharp'] == 4) { //青
            $weapon_data['element'] = $weapon_data['element'] *1.0625;
        } elseif ($weapon_data['sharp'] == 5) { //白
            $weapon_data['element'] = $weapon_data['element'] *1.125;
//        } elseif ($weapon_data['sharp'] == 6) { //紫
//            $weapon_data['attack'] = $weapon_data['attack'] *1.2;
        }
        
//        echo'<pre>';print_r($weapon_data);echo'</pre>';
        
        //小数点以下を切り捨て
        $weapon_sim['attack'] = floor($weapon_data['attack']);
        $weapon_sim['element'] = floor($weapon_data['element']);
        $this->set('weapon_sim', $weapon_sim);
        
        $this->render('mh_skill');
    }
}

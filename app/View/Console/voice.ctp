<?php echo $this->Html->script('jquery-select_select', array('inline' => FALSE)); ?>
<?php echo $this->Html->script('jquery-checked', array('inline' => FALSE)); ?>
<?php if (empty($profile) || $id == 1) { //プロフィール情報の場合 ?>
<h3>プロフィール情報の登録</h3>

  <table>
    <?php if ($id == 1) { //編集用 ?>
      <?php echo $this->Form->create($Actor, array( //使用するModel
          'type' => 'put', //変更はput
          'url' => array('controller' => 'console', 'action' => 'voice_edit'), //Controllerのactionを指定
          'inputDefaults' => array('div' => '')
          )
      ); ?>
      <?php echo $this->Form->input('id', array('type' => 'hidden', 'label' => false, 'value' => $id)); ?>
    <?php } else { //登録用 ?>
      <?php echo $this->Form->create($Actor, array( //使用するModel
          'type' => 'post', //デフォルトはpost送信
          'url' => array('controller' => 'console', 'action' => 'voice_add'), //Controllerのactionを指定
          'inputDefaults' => array('div' => '')
          )
      ); ?>
    <?php } ?><!-- form start -->
    
    <tr>
      <td>名前</td>
      <td><?php echo $this->Form->input('title', array('type' => 'text', 'label' => false, 'size' => 49)); ?></td>
    </tr>
    <tr>
      <td>呼称</td>
      <td><?php echo $this->Form->input('charactor', array('type' => 'text', 'label' => false, 'size' => 49)); ?></td>
    </tr>
    <tr>
      <td>プロフィール</td>
      <td><?php echo $this->Form->input('note', array('type' => 'textarea', 'label' => false, 'cols' => 50, 'rows' => 10)); ?></td>
    </tr>
    <tr>
      <td>状態</td>
      <td><?php echo $this->Form->input('publish', array('type' => 'select', 'label' => false, 'options' => array(0 => '非公開', 1 => '公開'))); ?></td>
    </tr>
    
    <tr>
      <td></td>
      <td class="tbl-button"><?php if ($id == 1) { //編集用 ?>
          <?php echo $this->Form->submit('変更する'); ?>
        <?php } else { //登録用 ?>
          <?php echo $this->Form->submit('登録する'); ?>
        <?php } ?></td>
    </tr>
    <?php echo $this->Form->end(); ?><!-- form end -->
  </table>
<?php } else { //出演作品の場合 ?>
<h3>出演作品の登録</h3>

  <table>
    <?php if (preg_match('#/console/voice/'.$actor.'/edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
      <?php echo $this->Form->create($Actor, array( //使用するModel
          'type' => 'put', //変更はput
          'url' => array('controller' => 'console', 'action' => 'voice_edit'), //Controllerのactionを指定
          'inputDefaults' => array('div' => '')
          )
      ); ?>
      <?php echo $this->Form->input('id', array('type' => 'hidden', 'label' => false, 'value' => $id)); ?>
    <?php } else { //登録用 ?>
      <?php echo $this->Form->create($Actor, array( //使用するModel
          'type' => 'post', //デフォルトはpost送信
          'url' => array('controller' => 'console', 'action' => 'voice_add'), //Controllerのactionを指定
          'inputDefaults' => array('div' => '')
          )
      ); ?>
    <?php } ?><!-- form start -->
    
    <tr>
      <td>作品名</td>
      <td><?php echo $this->Form->input('title', array('type' => 'text', 'label' => false, 'size' => 49)); ?></td>
    </tr>
    <tr>
      <td>キャラ/名義</td>
      <td><?php echo $this->Form->input('charactor', array('type' => 'text', 'label' => false, 'size' => 49)); ?></td>
    </tr>
    <tr>
      <td>備考（任意）</td>
      <td><?php echo $this->Form->input('note', array('type' => 'textarea', 'label' => false, 'cols' => 50, 'rows' => 5)); ?></td>
    </tr>
    <tr>
      <td>ジャンル</td>
      <td><?php echo $this->Form->input('genre', array('type' => 'select', 'label' => false, 'id' => 'lv1Pulldown', 'options' => array('' => '', 'anime' => 'アニメ', 'game' => 'ゲーム', 'radio' => 'ラジオ', 'music' => '音楽', 'other' => 'その他'))); ?></td>
    </tr>
    <tr>
      <td>ハード（任意）</td>
      <?php if (preg_match('#/console/voice/'.$actor.'/edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
      <td>
        <select name="data[Otochin][hard]" id="lv2Pulldown" disabled="disabled">
          <?php if (!$this->request->data[$Actor]['hard']) {echo '<option>ジャンルを選択してください</option>';} ?>
          <option value="tv" class="panime" <?php if ($this->request->data[$Actor]['hard'] == 'tv') {echo 'selected="selected"';} ?>>TV</option>
          <option value="ova" class="panime" <?php if ($this->request->data[$Actor]['hard'] == 'ova') {echo 'selected="selected"';} ?>>OVA</option>
          <option value="pc" class="pgame" <?php if ($this->request->data[$Actor]['hard'] == 'pc') {echo 'selected="selected"';} ?>>PC</option>
          <option value="ps3" class="pgame" <?php if ($this->request->data[$Actor]['hard'] == 'ps3') {echo 'selected="selected"';} ?>>PS3</option>
          <option value="ps2" class="pgame" <?php if ($this->request->data[$Actor]['hard'] == 'ps2') {echo 'selected="selected"';} ?>>PS2</option>
          <option value="ps" class="pgame" <?php if ($this->request->data[$Actor]['hard'] == 'ps') {echo 'selected="selected"';} ?>>PS</option>
          <option value="psvita" class="pgame" <?php if ($this->request->data[$Actor]['hard'] == 'psvita') {echo 'selected="selected"';} ?>>PSvita</option>
          <option value="psp" class="pgame" <?php if ($this->request->data[$Actor]['hard'] == 'psp') {echo 'selected="selected"';} ?>>PSP</option>
          <option value="xbox" class="pgame" <?php if ($this->request->data[$Actor]['hard'] == 'xbox') {echo 'selected="selected"';} ?>>Xbox</option>
          <option value="app" class="pgame" <?php if ($this->request->data[$Actor]['hard'] == 'app') {echo 'selected="selected"';} ?>>スマホ</option>
          <option value="web" class="pradio" <?php if ($this->request->data[$Actor]['hard'] == 'web') {echo 'selected="selected"';} ?>>Web</option>
          <option value="sg" class="pmusic" <?php if ($this->request->data[$Actor]['hard'] == 'sg') {echo 'selected="selected"';} ?>>シングル</option>
          <option value="al" class="pmusic" <?php if ($this->request->data[$Actor]['hard'] == 'al') {echo 'selected="selected"';} ?>>アルバム</option>
        </select>
      </td>
      <?php } else { //登録用 ?>
      <td>
        <select name="data[Otochin][hard]" id="lv2Pulldown" disabled="disabled">
          <option>ジャンルを選択してください</option>
          <option value="tv" class="panime">TV</option>
          <option value="ova" class="panime">OVA</option>
          <option value="pc" class="pgame">PC</option>
          <option value="ps3" class="pgame">PS3</option>
          <option value="ps2" class="pgame">PS2</option>
          <option value="ps" class="pgame">PS</option>
          <option value="psvita" class="pgame">PSvita</option>
          <option value="psp" class="pgame">PSP</option>
          <option value="xbox" class="pgame">Xbox</option>
          <option value="app" class="pgame">スマホ</option>
          <option value="web" class="pradio">Web</option>
          <option value="sg" class="pmusic">シングル</option>
          <option value="al" class="pmusic">アルバム</option>
        </select>
      </td>
      <?php } ?>
    </tr>
    <tr>
      <td>日付</td>
      <td><?php echo $this->Form->input('date_from', array('type' => 'date', 'label' => false, 'dateFormat' => 'YMD', 'monthNames' => false, 'separator' => '/', 'maxYear' => date('Y')+1, 'minYear' => 2000)); ?> ～
          <?php if (preg_match('#/console/voice/'.$actor.'/edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
            <?php echo $this->Form->input('date_to', array('type' => 'date', 'label' => false, 'class' => 'js-input_date_to', 'dateFormat' => 'YMD', 'monthNames' => false, 'separator' => '/', 'maxYear' => 2038, 'minYear' => 2000, 'disabled' => ($this->request->data[$Actor]['date_to'] == null)? 'disabled': '')); ?>
            <?php echo $this->Form->input('date_to_null', array('type' => 'checkbox', 'label' => false, 'class' => 'js-checkbox_date_to', 'checked' => ($this->request->data[$Actor]['date_to'] == null)? 'checked': '')); ?>null
          <?php } else { //登録用 ?>
            <?php echo $this->Form->input('date_to', array('type' => 'date', 'label' => false, 'class' => 'js-input_date_to', 'dateFormat' => 'YMD', 'monthNames' => false, 'separator' => '/', 'maxYear' => 2038, 'minYear' => 2000, 'disabled' => 'disabled')); ?>
            <?php echo $this->Form->input('date_to_null', array('type' => 'checkbox', 'label' => false, 'class' => 'js-checkbox_date_to', 'checked' => 'checked')); ?>null
          <?php } ?></td>
    </tr>
    <tr><td></td><td><span class="txt-min">※放送中は終了日を 2038/01/19 に設定する</span></td></tr>
    <tr>
      <td>リンクURL（任意）</td>
      <td><?php echo $this->Form->input('link_url', array('type' => 'text', 'label' => false, 'size' => 49)); ?></td>
    </tr>
    <tr>
      <td>状態</td>
      <td><?php echo $this->Form->input('publish', array('type' => 'select', 'label' => false, 'options' => array(0 => '非公開', 1 => '公開'))); ?></td>
    </tr>
    
    <tr>
      <td></td>
      <td class="tbl-button"><?php if (preg_match('#/console/voice/'.$actor.'/edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
            <?php echo $this->Form->submit('修正する'); ?>
          <?php } else { //登録用 ?>
            <?php echo $this->Form->submit('登録する'); ?>
          <?php } ?></td>
    </tr>
    <?php echo $this->Form->end(); ?><!-- form end -->
  </table>
<?php } ?>

<?php if (!empty($profile)) { ?>
<h3>プロフィール情報</h3>

  <table class="prof-list_voice">
    <tr><td class="tbl-ico"><?php if ($profile[$Actor]['publish'] == 0) {echo '<span class="icon-false">非公開</span>';}
                              elseif ($profile[$Actor]['publish'] == 1) {echo '<span class="icon-true">公開</span>';} ?></td>
        <td><?php echo $this->Html->link('変更する', '/console/voice/'.$actor.'/edit/1'); ?></td>
        <td><?php echo $this->Html->link('確認する', '/voice/'.$actor, array('target' => '_blank')); ?></td></tr>
  </table>
<?php } ?>

<h3>出演作品一覧</h3>

  <?php $this->Paginator->options(array(
      'url' => array('controller' => 'console', 'action' => 'voice', $actor)
  )); ?>
  <?php echo $this->Paginator->numbers(array(
      'modulus' => 4, //現在ページから左右あわせてインクルードする個数
      'separator' => '|', //デフォルト値のセパレーター
      'first' => '＜', //先頭ページへのリンク
      'last' => '＞' //最終ページへのリンク
  )); ?>

<div class="detail-list-scr">
  <table class="tbl-list_voice">
    <tr><th class="tbl-num">id<?php echo $this->Paginator->sort($Actor.'.id', '▼'); ?></th>
        <th class="tbl-title_voice">作品名<?php echo $this->Paginator->sort($Actor.'.title', '▼'); ?></th>
        <th class="tbl-chara_voice">キャラ/名義<?php echo $this->Paginator->sort($Actor.'.charactor', '▼'); ?></th>
        <th class="tbl-date_voice">状態<br>
                                   日付<?php echo $this->Paginator->sort($Actor.'.date_from', '▼'); ?></th>
        <th class="tbl-ico">ジャンル<br>
                            ハード</th>
        <th>備考</th>
        <th class="tbl-act_voice">action</th></tr>
    
    <?php foreach ($product_lists AS $product_list) { ?>
    <tr><td class="tbl-num"><?php echo $product_list[$Actor]['id']; ?></td>
        <td class="tbl-title_voice"><?php if ($product_list[$Actor]['link_url']) { ?>
                                      <a href="<?php echo $product_list[$Actor]['link_url']; ?>" target="_blank">
                                        <?php echo $product_list[$Actor]['title']; ?></a>
                                    <?php } else { ?>
                                      <?php echo $product_list[$Actor]['title']; ?>
                                    <?php } ?></td>
        <td class="tbl-chara_voice"><?php echo $product_list[$Actor]['charactor']; ?></td>
        <td class="tbl-date_voice"><?php if ($product_list[$Actor]['publish'] == 0) {echo '<span class="icon-false">非公開</span>';}
                                     elseif ($product_list[$Actor]['publish'] == 1) {echo '<span class="icon-true">公開</span>';} ?><br>
                                   <?php echo $product_list[$Actor]['date_from']; ?>
                                   <?php if (@$product_list[$Actor]['date_to'] == '2038-01-19') {echo '<br>～now on air';}
                                     elseif ($product_list[$Actor]['date_to']) {echo '<br>～'.$product_list[$Actor]['date_to'];} ?></td>
        <td class="tbl-ico"><?php if ($product_list[$Actor]['genre'] == 'anime') {echo 'アニメ';}
                              elseif ($product_list[$Actor]['genre'] == 'game') {echo 'ゲーム';}
                              elseif ($product_list[$Actor]['genre'] == 'radio') {echo 'ラジオ';}
                              elseif ($product_list[$Actor]['genre'] == 'music') {echo '音楽';}
                              elseif ($product_list[$Actor]['genre'] == 'other') {echo 'その他';} ?><br>
                            <?php if ($product_list[$Actor]['hard'] == 'tv') {echo 'TV';}
                              elseif ($product_list[$Actor]['hard'] == 'ova') {echo 'OVA';}
                              elseif ($product_list[$Actor]['hard'] == 'pc') {echo 'PC';}
                              elseif ($product_list[$Actor]['hard'] == 'ps3') {echo 'PS3';}
                              elseif ($product_list[$Actor]['hard'] == 'ps2') {echo 'PS2';}
                              elseif ($product_list[$Actor]['hard'] == 'ps') {echo 'PS';}
                              elseif ($product_list[$Actor]['hard'] == 'psvita') {echo 'PSvita';}
                              elseif ($product_list[$Actor]['hard'] == 'psp') {echo 'PSP';}
                              elseif ($product_list[$Actor]['hard'] == 'xbox') {echo 'Xbox';}
                              elseif ($product_list[$Actor]['hard'] == 'app') {echo 'スマホ';}
                              elseif ($product_list[$Actor]['hard'] == 'web') {echo 'Web';}
                              elseif ($product_list[$Actor]['hard'] == 'sg') {echo 'シングル';}
                              elseif ($product_list[$Actor]['hard'] == 'al') {echo 'アルバム';}
                              else {echo 'その他';} ?></td>
        <td><?php echo $product_list[$Actor]['note']; ?></td>
        <td class="tbl-act_voice"><?php echo $this->Html->link('修正', '/console/voice/'.$actor.'/edit/'.$product_list[$Actor]['id']); ?>
                                  <?php echo $this->Form->postLink('削除', array('controller' => 'Console', 'action' => 'voice_delete', $product_list[$Actor]['id'], $actor), null, '本当に#'.$product_list[$Actor]['id'].'を削除しますか'); ?></td></tr>
    <?php } ?>
  </table>
</div>
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
      <td>キャラ</td>
      <td><?php echo $this->Form->input('charactor', array('type' => 'text', 'label' => false, 'size' => 49)); ?></td>
    </tr>
    <tr>
      <td>備考（任意）</td>
      <td><?php echo $this->Form->input('note', array('type' => 'textarea', 'label' => false, 'cols' => 50, 'rows' => 5)); ?></td>
    </tr>
    <tr>
      <td>ジャンル</td>
      <td><?php echo $this->Form->input('genre', array('type' => 'select', 'label' => false, 'options' => array('anime' => 'アニメ', 'game' => 'ゲーム', 'other' => 'その他'))); ?></td>
    </tr>
    <tr>
      <td>日付</td>
      <td><?php echo $this->Form->input('date_from', array('type' => 'date', 'label' => false, 'dateFormat' => 'YMD', 'monthNames' => false, 'separator' => '/', 'maxYear' => date('Y')+1, 'minYear' => 2000)); ?></td>
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

  <?php echo $this->Paginator->numbers(array(
      'modulus' => 4, //現在ページから左右あわせてインクルードする個数
      'separator' => '|', //デフォルト値のセパレーター
      'first' => '＜', //先頭ページへのリンク
      'last' => '＞' //最終ページへのリンク
  )); ?>

  <table class="detail-list-min">
    <tr><th class="tbl-num">id<?php echo $this->Paginator->sort($Actor.'.id', '▼'); ?></th>
        <th class="tbl-date">日付<?php echo $this->Paginator->sort($Actor.'.date_from', '▼'); ?></th>
        <th>作品名<?php echo $this->Paginator->sort($Actor.'.title', '▼'); ?></th>
        <th>キャラ<?php echo $this->Paginator->sort($Actor.'.charactor', '▼'); ?></th>
        <th>備考</th>
        <th>ジャンル</th>
        <th class="tbl-ico">状態</th>
        <th class="tbl-act_comment">action</th></tr>
    
    <?php foreach ($product_lists AS $product_list) { ?>
    <tr><td class="tbl-num"><?php echo $product_list[$Actor]['id']; ?></td>
        <td class="tbl-date"><?php echo $product_list[$Actor]['date_from']; ?></td>
        <td><?php echo $product_list[$Actor]['title']; ?></td>
        <td><?php echo $product_list[$Actor]['charactor']; ?></td>
        <td><?php echo $product_list[$Actor]['note']; ?></td>
        <td><?php if ($product_list[$Actor]['genre'] == 'anime') {echo 'アニメ';}
              elseif ($product_list[$Actor]['genre'] == 'game') {echo 'ゲーム';}
              elseif ($product_list[$Actor]['genre'] == 'other') {echo 'その他';} ?></td>
        <td class="tbl-ico"><?php if ($product_list[$Actor]['publish'] == 0) {echo '<span class="icon-false">非公開</span>';}
                              elseif ($product_list[$Actor]['publish'] == 1) {echo '<span class="icon-true">公開</span>';} ?></td>
        <td class="tbl-act_comment"><?php echo $this->Html->link('修正', '/console/voice/'.$actor.'/edit/'.$product_list[$Actor]['id']); ?>
                                  <?php echo $this->Form->postLink('削除', array('controller' => 'Console', 'action' => 'voice_delete', $product_list[$Actor]['id'], $actor), null, '本当に#'.$product_list[$Actor]['id'].'を削除しますか'); ?></td></tr>
    <?php } ?>
  </table>
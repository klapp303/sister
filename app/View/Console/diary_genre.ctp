<h3>ジャンルの作成</h3>

  <table>
    <?php if (preg_match('#/console/diary_genre/edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
      <?php echo $this->Form->create('DiaryGenre', array( //使用するModel
          'type' => 'put', //変更はput
          'url' => array('controller' => 'console', 'action' => 'diary_genre_edit'), //Controllerのactionを指定
          'inputDefaults' => array('div' => '')
          )
      ); ?>
      <?php echo $this->Form->input('id', array('type' => 'hidden', 'label' => false, 'value' => $id)); ?>
    <?php } else { //登録用 ?>
      <?php echo $this->Form->create('DiaryGenre', array( //使用するModel
          'type' => 'post', //デフォルトはpost送信
          'url' => array('controller' => 'console', 'action' => 'diary_genre_add'), //Controllerのactionを指定
          'inputDefaults' => array('div' => '')
          )
      ); ?>
    <?php } ?><!-- form start -->
    
    <tr>
      <td>ジャンル名</td>
      <td><?php echo $this->Form->input('title', array('type' => 'text', 'label' => false, 'size' => 30)); ?></td>
    </tr>
    
    <tr>
      <td></td>
      <td class="tbl-button"><?php if (preg_match('#/console/diary_genre/edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
            <?php echo $this->Form->submit('修正する'); ?>
          <?php } else { //登録用 ?>
            <?php echo $this->Form->submit('登録する'); ?>
          <?php } ?></td>
    </tr>
    <?php echo $this->Form->end(); ?><!-- form end -->
  </table>

<h3>日記一覧</h3>

  <?php echo $this->Paginator->numbers(array(
      'modulus' => 4, //現在ページから左右あわせてインクルードする個数
      'separator' => '|', //デフォルト値のセパレーター
      'first' => '＜', //先頭ページへのリンク
      'last' => '＞' //最終ページへのリンク
  )); ?>

  <table class="detail-list-min">
    <tr><th class="tbl-num">id<?php echo $this->Paginator->sort('DiaryGenre.id', '▼'); ?></th>
        <th>ジャンル名</th>
        <th class="tbl-act_genre">action</th></tr>
    
    <?php foreach ($diary_genre_lists AS $diary_genre_list) { ?>
    <tr><td class="tbl-num"><?php echo $diary_genre_list['DiaryGenre']['id']; ?></td>
        <td><?php echo $diary_genre_list['DiaryGenre']['title']; ?></td>
        <td class="tbl-act_genre"><?php echo $this->Html->link('日記の確認', '/diary/genre/'.$diary_genre_list['DiaryGenre']['id'], array('target' => '_blank')); ?><br>
            <?php echo $this->Html->link('修正', '/console/diary_genre/edit/'.$diary_genre_list['DiaryGenre']['id']); ?>
            <?php echo $this->Form->postLink('削除', array('controller' => 'Console', 'action' => 'diary_genre_delete', $diary_genre_list['DiaryGenre']['id']), null, '本当に「'.$diary_genre_list['DiaryGenre']['title'].'」を削除しますか'); ?></td></tr>
    <?php } ?>
  </table>
<h3>楽曲の追加</h3>

  <table>
    <?php if (preg_match('#/console/music/edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
      <?php echo $this->Form->create('Music', array( //使用するModel
          'type' => 'put', //変更はput
          'url' => array('controller' => 'console', 'action' => 'music_edit'), //Controllerのactionを指定
          'inputDefaults' => array('div' => '')
          )
      ); ?>
      <?php echo $this->Form->input('id', array('type' => 'hidden', 'label' => false, 'value' => $id)); ?>
    <?php } else { //登録用 ?>
      <?php echo $this->Form->create('Music', array( //使用するModel
          'type' => 'post', //デフォルトはpost送信
          'url' => array('controller' => 'console', 'action' => 'music_add'), //Controllerのactionを指定
          'inputDefaults' => array('div' => '')
          )
      ); ?>
    <?php } ?><!-- form start -->
    
    <tr>
      <td>タイトル</td>
      <td><?php echo $this->Form->input('title', array('type' => 'text', 'label' => false, 'size' => 49)); ?></td>
    </tr>
    <tr>
      <td>アーティスト</td>
      <td><?php echo $this->Form->input('artist', array('type' => 'text', 'label' => false, 'size' => 49)); ?></td>
    </tr>
    <tr>
      <td>作詞者</td>
      <td><?php echo $this->Form->input('writer', array('type' => 'text', 'label' => false, 'size' => 49)); ?></td>
    </tr>
    <tr>
      <td>作曲者</td>
      <td><?php echo $this->Form->input('composer', array('type' => 'text', 'label' => false, 'size' => 49)); ?></td>
    </tr>
    <tr>
      <td>備考（任意）</td>
      <td><?php echo $this->Form->input('note', array('type' => 'textarea', 'label' => false, 'cols' => 30, 'rows' => 10)); ?></td>
    </tr>
    <tr>
      <td>リンクURL（任意）</td>
      <td><?php echo $this->Form->input('link_url', array('type' => 'text', 'label' => false, 'size' => 49)); ?></td>
    </tr>
    <tr>
      <td>レビュー</td>
      <td><?php echo $this->Form->input('review', array('type' => 'textarea', 'label' => false, 'cols' => 50, 'rows' => 10)); ?></td>
    </tr>
    <tr>
      <td>状態</td>
      <td><?php echo $this->Form->input('publish', array('type' => 'select', 'label' => false, 'options' => array(0 => '非公開', 1 => '公開'))); ?></td>
    </tr>
    
    <tr>
      <td></td>
      <td class="tbl-button"><?php if (preg_match('#/console/music/edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
            <?php echo $this->Form->submit('修正する'); ?>
          <?php } else { //登録用 ?>
            <?php echo $this->Form->submit('追加する'); ?>
          <?php } ?></td>
    </tr>
    <?php echo $this->Form->end(); ?><!-- form end -->
  </table>

<h3>楽曲一覧</h3>

  <?php echo $this->Paginator->numbers(array(
      'modulus' => 4, //現在ページから左右あわせてインクルードする個数
      'separator' => '|', //デフォルト値のセパレーター
      'first' => '＜', //先頭ページへのリンク
      'last' => '＞' //最終ページへのリンク
  )); ?>

  <table class="detail-list-min">
    <tr><th class="tbl-num">id<?php echo $this->Paginator->sort('Music.id', '▼'); ?></th>
        <th>タイトル</th>
        <th class="tbl-ico">状態</th>
        <th class="tbl-act_information">action</th></tr>
    
    <?php foreach ($music_lists AS $music_list) { ?>
    <tr><td class="tbl-num"><?php echo $music_list['Music']['id']; ?></td>
        <td><?php echo $music_list['Music']['title']; ?></td>
        <td class="tbl-ico"><?php if ($music_list['Music']['publish'] == 0) {echo '<span class="icon-false">非公開</span>';}
                              elseif ($music_list['Music']['publish'] == 1) {echo '<span class="icon-true">公開</span>';} ?></td>
        <td class="tbl-act_information"><?php echo $this->Html->link('修正', '/console/music/edit/'.$music_list['Music']['id']); ?>
                                  <?php echo $this->Form->postLink('削除', array('controller' => 'Console', 'action' => 'music_delete', $music_list['Music']['id']), null, '本当に#'.$music_list['Music']['id'].'を削除しますか'); ?></td></tr>
    <?php } ?>
  </table>
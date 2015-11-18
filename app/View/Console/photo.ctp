<?php if (preg_match('#/console/photo/sub_pop#', $_SERVER['REQUEST_URI'])) { ?>
<?php } else { //sub_popでない場合 ?>
<h3>画像のアップロード</h3>

  <table>
      <?php echo $this->Form->create('Photo', array( //使用するModel
          'type' => 'post', //デフォルトはpost送信
          'url' => array('controller' => 'console', 'action' => 'photo_add'), //Controllerのactionを指定
          'inputDefaults' => array('div' => '')
          )
      ); ?><!-- form start -->
    
    <tr>
      <td>ファイル名</td>
      <td><?php echo $this->Form->input('name', array('type' => 'text', 'label' => false, 'size' => 20)); ?></td>
    </tr>
    
    <tr>
      <td><?php echo $this->Form->submit('追加する'); ?></td>
    </tr>
    <?php echo $this->Form->end(); ?><!-- form end -->
  </table>
<?php } ?>

<h3>画像一覧</h3>

  <?php echo $this->Paginator->numbers(array(
      'modulus' => 4, //現在ページから左右あわせてインクルードする個数
      'separator' => '|', //デフォルト値のセパレーター
      'first' => '＜', //先頭ページへのリンク
      'last' => '＞' //最終ページへのリンク
  )); ?>

  <table class="<?php if (preg_match('#/console/photo/sub_pop#', $_SERVER['REQUEST_URI'])) {} else {echo 'detail-list-min';} ?>">
    <tr><th class="tbl-num">id<?php echo $this->Paginator->sort('Photo.id', '▼'); ?></th>
        <th>プレビュー</th>
        <th>ファイル名</th>
        <th>日付<?php echo $this->Paginator->sort('Photo.created', '▼'); ?></th>
        <?php if (preg_match('#/console/photo/sub_pop#', $_SERVER['REQUEST_URI'])) { ?>
        <?php } else { //sub_popでない場合 ?>
        <th>action</th>
        <?php } ?></tr>
    
    <?php foreach ($photo_lists AS $photo_list) { ?>
    <tr><td class="tbl-num"><?php echo $photo_list['Photo']['id']; ?></td>
        <td>ここにプレビュー</td>
        <td><?php echo $photo_list['Photo']['name']; ?></td>
        <td class="tbl-date"><?php echo $photo_list['Photo']['created']; ?></td>
        <?php if (preg_match('#/console/photo/sub_pop#', $_SERVER['REQUEST_URI'])) { ?>
        <?php } else { //sub_popでない場合 ?>
        <td><?php echo $this->Form->postLink('削除', array('controller' => 'Console', 'action' => 'photo_delete', $photo_list['Photo']['id']), null, '本当に「'.$photo_list['Photo']['name'].'」を削除しますか'); ?></td>
        <?php } ?></tr>
    <?php } ?>
  </table>
<?php echo $this->Html->script('jquery-tmb', array('inline' => false)); ?>
<h3>メーカーの追加</h3>

  <table>
    <?php if (preg_match('#/console/maker/edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
      <?php echo $this->Form->create('Maker', array( //使用するModel
          'type' => 'put', //変更はput
          'enctype' => 'multipart/form-data', //fileアップロードの場合
          'url' => array('controller' => 'console', 'action' => 'maker_edit'), //Controllerのactionを指定
          'inputDefaults' => array('div' => '')
      )); ?>
      <?php echo $this->Form->input('id', array('type' => 'hidden', 'label' => false, 'value' => $id)); ?>
    <?php } else { //登録用 ?>
      <?php echo $this->Form->create('Maker', array( //使用するModel
          'type' => 'post', //デフォルトはpost送信
          'enctype' => 'multipart/form-data', //fileアップロードの場合
          'url' => array('controller' => 'console', 'action' => 'maker_add'), //Controllerのactionを指定
          'inputDefaults' => array('div' => '')
      )); ?>
    <?php } ?><!-- form start -->
    
    <tr>
      <td>メーカー</td>
      <td><?php echo $this->Form->input('title', array('type' => 'text', 'label' => false, 'size' => 49)); ?></td>
    </tr>
    <tr>
      <td>バナー画像</td>
      <td><?php if (preg_match('#/console/maker/edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
            <?php echo $this->Form->input('Maker.delete_name', array('type' => 'hidden', 'label' => false, 'value' => $image_name)); ?>
            <?php echo $this->Html->image('../files/maker/' . $image_name, array('class' => 'img_maker js-tmb_pre')); ?>
          <?php } ?>
          <?php echo $this->Form->input('Maker.file', array('type' => 'file', 'label' => false)); ?></td>
    </tr>
    <tr>
      <td>リンク先URL</td>
      <td><?php echo $this->Form->input('link_url', array('type' => 'text', 'label' => false, 'size' => 49)); ?></td>
    </tr>
    <tr>
      <td>状態</td>
      <td><?php echo $this->Form->input('publish', array('type' => 'select', 'label' => false, 'options' => array(0 => '非表示', 1 => '表示'))); ?></td>
    </tr>
    
    <tr>
      <td></td>
      <td class="tbl-button"><?php if (preg_match('#/console/maker/edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
                               <?php echo $this->Form->submit('修正する'); ?>
                             <?php } else { //登録用 ?>
                               <?php echo $this->Form->submit('追加する'); ?>
                             <?php } ?></td>
    </tr>
    <?php echo $this->Form->end(); ?><!-- form end -->
  </table>

<h3>メーカー一覧</h3>

  <?php echo $this->Paginator->numbers($paginator_option); ?>

  <table class="detail-list-min">
    <tr><th class="tbl-num">id<?php echo $this->Paginator->sort('Maker.id', '▼'); ?></th>
        <th>プレビュー</th>
        <th class="tbl-ico">状態</th>
        <th class="tbl-act_maker">action</th></tr>
    
    <?php foreach ($maker_lists as $maker_list) { ?>
      <tr><td class="tbl-num"><?php echo $maker_list['Maker']['id']; ?></td>
          <td class="tbl-tmb_maker"><a href="<?php echo $maker_list['Maker']['link_url']; ?>" target="_blank">
            <?php echo $this->Html->image('../files/maker/' . $maker_list['Maker']['image_name'], array('alt' => $maker_list['Maker']['title'], 'class' => 'img_maker')); ?></a></td>
          <td class="tbl-ico"><?php if ($maker_list['Maker']['publish'] == 0) { ?>
                                <span class="icon-false">非表示</span>
                              <?php } elseif ($maker_list['Maker']['publish'] == 1) { ?>
                                <span class="icon-true">表示</span>
                              <?php } ?></td>
          <td class="tbl-act_maker"><?php echo $this->Html->link('修正', '/console/maker/edit/' . $maker_list['Maker']['id']); ?>
                                    <?php echo $this->Form->postLink('削除', array('controller' => 'Console', 'action' => 'maker_delete', $maker_list['Maker']['id']), null, '本当に#' . $maker_list['Maker']['id'] . 'を削除しますか'); ?></td></tr>
    <?php } ?>
  </table>
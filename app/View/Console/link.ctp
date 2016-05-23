<h3>リンクの追加</h3>

  <table>
    <?php if (preg_match('#/console/link/edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
      <?php echo $this->Form->create('Link', array( //使用するModel
          'type' => 'put', //変更はput
          'url' => array('controller' => 'console', 'action' => 'link_edit'), //Controllerのactionを指定
          'inputDefaults' => array('div' => '')
          )
      ); ?>
      <?php echo $this->Form->input('id', array('type' => 'hidden', 'label' => false, 'value' => $id)); ?>
    <?php } else { //登録用 ?>
      <?php echo $this->Form->create('Link', array( //使用するModel
          'type' => 'post', //デフォルトはpost送信
          'url' => array('controller' => 'console', 'action' => 'link_add'), //Controllerのactionを指定
          'inputDefaults' => array('div' => '')
          )
      ); ?>
    <?php } ?><!-- form start -->
    
    <tr>
      <td>タイトル</td>
      <td><?php echo $this->Form->input('title', array('type' => 'text', 'label' => false, 'size' => 49)); ?></td>
    </tr>
    <tr>
    <tr>
      <td>リンク先URL</td>
      <td><?php echo $this->Form->input('link_url', array('type' => 'text', 'label' => false, 'size' => 49)); ?></td>
    </tr>
    <tr>
      <td>説明</td>
      <td><?php echo $this->Form->input('description', array('type' => 'textarea', 'label' => false, 'cols' => 50, 'rows' => 10, 'class' => 'js-insert_area')); ?></td>
    </tr>
    <tr>
      <td>分類</td>
      <td><?php echo $this->Form->input('category', array('type' => 'select', 'label' => false, 'options' => array('myself' => '自分', 'friends' => '友人', 'develop' => '開発', 'others' => 'その他'))); ?></td>
    </tr>
    <tr>
      <td>状態</td>
      <td><?php echo $this->Form->input('publish', array('type' => 'select', 'label' => false, 'options' => array(0 => '非公開', 1 => '公開'))); ?></td>
    </tr>
    
    <tr>
      <td></td>
      <td class="tbl-button"><?php if (preg_match('#/console/link/edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
            <?php echo $this->Form->submit('修正する'); ?>
          <?php } else { //登録用 ?>
            <?php echo $this->Form->submit('作成する'); ?>
          <?php } ?></td>
    </tr>
    <?php echo $this->Form->end(); ?><!-- form end -->
  </table>

<h3>リンク一覧</h3>

  <?php echo $this->Paginator->numbers($paginator_option); ?>

  <table class="detail-list-min">
    <tr><th class="tbl-num">id<?php echo $this->Paginator->sort('Link.id', '▼'); ?></th>
        <th>タイトル</th>
        <th>説明</th>
        <th class="tbl-ico">分類<br>
                            状態</th>
        <th class="tbl-act_link">action</th></tr>
    
    <?php foreach ($link_lists AS $link_list) { ?>
    <tr><td class="tbl-num"><?php echo $link_list['Link']['id']; ?></td>
        <td><?php echo $link_list['Link']['title']; ?></td>
        <td><?php echo $link_list['Link']['description']; ?></td>
        <td class="tbl-ico"><?php if ($link_list['Link']['category'] == 'myself') {echo '自分';}
                            elseif ($link_list['Link']['category'] == 'friends') {echo '友人';}
                            elseif ($link_list['Link']['category'] == 'develop') {echo '開発';}
                            elseif ($link_list['Link']['category'] == 'others') {echo 'その他';} ?><br>
                            <?php if ($link_list['Link']['publish'] == 0) {echo '<span class="icon-false">非公開</span>';}
                              elseif ($link_list['Link']['publish'] == 1) {echo '<span class="icon-true">公開</span>';} ?></td>
        <td class="tbl-act_link"><?php echo $this->Html->link('修正', '/console/link/edit/'.$link_list['Link']['id']); ?>
                                 <?php echo $this->Form->postLink('削除', array('controller' => 'Console', 'action' => 'link_delete', $link_list['Link']['id']), null, '本当に#'.$link_list['Link']['id'].'を削除しますか'); ?></td></tr>
    <?php } ?>
  </table>
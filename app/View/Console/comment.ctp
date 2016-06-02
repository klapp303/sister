<h3>セリフの登録</h3>

  <table>
    <?php if (preg_match('#/console/comment/edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
      <?php echo $this->Form->create('SisterComment', array( //使用するModel
          'type' => 'put', //変更はput
          'url' => array('controller' => 'console', 'action' => 'comment_edit'), //Controllerのactionを指定
          'inputDefaults' => array('div' => '')
      )); ?>
      <?php echo $this->Form->input('id', array('type' => 'hidden', 'label' => false, 'value' => $id)); ?>
    <?php } else { //登録用 ?>
      <?php echo $this->Form->create('SisterComment', array( //使用するModel
          'type' => 'post', //デフォルトはpost送信
          'url' => array('controller' => 'console', 'action' => 'comment_add'), //Controllerのactionを指定
          'inputDefaults' => array('div' => '')
      )); ?>
    <?php } ?><!-- form start -->
    
    <tr>
      <td>セリフ</td>
      <td><?php echo $this->Form->input('comment', array('type' => 'textarea', 'label' => false, 'cols' => 50, 'rows' => 5)); ?></td>
    </tr>
    <tr>
      <td>キャラ</td>
      <td><?php echo $this->Form->input('charactor', array('type' => 'text', 'label' => false, 'size' => 49)); ?></td>
    </tr>
    <tr>
      <td>状態</td>
      <td><?php echo $this->Form->input('publish', array('type' => 'select', 'label' => false, 'options' => array(0 => '非公開', 1 => '公開'))); ?></td>
    </tr>
    
    <tr>
      <td></td>
      <td class="tbl-button"><?php if (preg_match('#/console/comment/edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
                               <?php echo $this->Form->submit('修正する'); ?>
                             <?php } else { //登録用 ?>
                               <?php echo $this->Form->submit('登録する'); ?>
                             <?php } ?></td>
    </tr>
    <?php echo $this->Form->end(); ?><!-- form end -->
  </table>

<h3>セリフ一覧</h3>

  <?php echo $this->Paginator->numbers($paginator_option); ?>

  <table class="detail-list-min">
    <tr><th class="tbl-num">id<?php echo $this->Paginator->sort('SisterComment.id', '▼'); ?></th>
        <th>セリフ</th>
        <th class="tbl-chara_comment">キャラ<?php echo $this->Paginator->sort('SisterComment.charactor', '▼'); ?></th>
        <th class="tbl-ico">状態</th>
        <th class="tbl-act_comment">action</th></tr>
    
    <?php foreach ($comment_lists as $comment_list) { ?>
      <tr><td class="tbl-num"><?php echo $comment_list['SisterComment']['id']; ?></td>
          <td><?php echo nl2br($comment_list['SisterComment']['comment']); ?></td>
          <td class="tbl-chara_comment"><?php echo $comment_list['SisterComment']['charactor']; ?></td>
          <td class="tbl-ico"><?php if ($comment_list['SisterComment']['publish'] == 0) { ?>
                                <span class="icon-false">非公開</span>
                              <?php } elseif ($comment_list['SisterComment']['publish'] == 1) { ?>
                                <span class="icon-true">公開</span>
                              <?php } ?></td>
          <td class="tbl-act_comment"><?php echo $this->Html->link('修正', '/console/comment/edit/' . $comment_list['SisterComment']['id']); ?>
                                      <?php echo $this->Form->postLink('削除', array('controller' => 'Console', 'action' => 'comment_delete', $comment_list['SisterComment']['id']), null, '本当に#' . $comment_list['SisterComment']['id'] . 'を削除しますか'); ?></td></tr>
    <?php } ?>
  </table>
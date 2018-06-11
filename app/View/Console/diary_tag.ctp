<h3>タグの作成</h3>

  <table>
    <?php if (preg_match('#/console/diary_tag/edit/#', $_SERVER['REQUEST_URI'])): //編集用 ?>
    <?php echo $this->Form->create('DiaryTag', array( //使用するModel
        'type' => 'put', //変更はput
        'url' => array('controller' => 'console', 'action' => 'diary_tag_edit'), //Controllerのactionを指定
        'inputDefaults' => array('div' => '')
    )); ?>
    <?php echo $this->Form->input('id', array('type' => 'hidden', 'label' => false, 'value' => $id)); ?>
    <?php else: //登録用 ?>
    <?php echo $this->Form->create('DiaryTag', array( //使用するModel
        'type' => 'post', //デフォルトはpost送信
        'url' => array('controller' => 'console', 'action' => 'diary_tag_add'), //Controllerのactionを指定
        'inputDefaults' => array('div' => '')
    )); ?>
    <?php endif; ?><!-- form start -->
    
    <tr>
      <td>タグ名</td>
      <td><?php echo $this->Form->input('title', array('type' => 'text', 'label' => false, 'size' => 30)); ?></td>
    </tr>
    
    <tr>
      <td></td>
      <td class="tbl-button"><?php if (preg_match('#/console/diary_tag/edit/#', $_SERVER['REQUEST_URI'])): //編集用 ?>
                             <?php echo $this->Form->submit('修正する'); ?>
                             <?php else: //登録用 ?>
                             <?php echo $this->Form->submit('登録する'); ?>
                             <?php endif; ?></td>
    </tr>
    <?php echo $this->Form->end(); ?><!-- form end -->
  </table>

<h3>タグ一覧</h3>

  <?php echo $this->Paginator->numbers($paginator_option); ?>

  <table class="detail-list-min">
    <tr><th class="tbl-num">id<?php echo $this->Paginator->sort('DiaryTag.id', '▼'); ?></th>
        <th>タグ名</th>
        <th class="tbl-act_tag">action</th></tr>
    
    <?php foreach ($diary_tag_lists as $diary_tag_list): ?>
    <tr><td class="tbl-num"><?php echo $diary_tag_list['DiaryTag']['id']; ?></td>
        <td class="txt-c"><?php echo $diary_tag_list['DiaryTag']['title']; ?></td>
        <td class="tbl-act_tag"><?php echo $this->Html->link('日記の確認', '/diary/tag/' . $diary_tag_list['DiaryTag']['id'], array('target' => '_blank')); ?><br>
                                  <?php echo $this->Html->link('修正', '/console/diary_tag/edit/' . $diary_tag_list['DiaryTag']['id']); ?>
                                  <?php echo $this->Form->postLink('削除', array('controller' => 'Console', 'action' => 'diary_tag_delete', $diary_tag_list['DiaryTag']['id']), null, '本当に「' . $diary_tag_list['DiaryTag']['title'] . '」を削除しますか'); ?></td></tr>
    <?php endforeach; ?>
  </table>

<div class="link-page_tag">
  <span class="link-page"><?php echo $this->Html->link('⇨ タグを並び替える', '/console/diary_tag_sort/'); ?></span>
</div>
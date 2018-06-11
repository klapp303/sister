<?php echo $this->Html->script('http://code.jquery.com/ui/1.11.3/jquery-ui.js', array('inline' => false)); ?>
<h3>タグ一覧</h3>

  <div class="detail-title-min_tag">
    <span class="li-num">sort<?php echo $this->Paginator->sort('DiaryTag.sort', '▼'); ?></span>
    <span class="li-num">id<?php echo $this->Paginator->sort('DiaryTag.id', '▼'); ?></span>
    <span class="li-txt_tag">タグ名</span>
    <span>action</span>
  </div>
  
  <?php echo $this->Form->create('DiaryTag', array( //使用するModel
      'type' => 'post', //デフォルトはpost送信
      'url' => array('controller' => 'console', 'action' => 'diary_tag_sort'), //Controllerのactionを指定
      'inputDefaults' => array('div' => ''),
      'class' => 'sort-form_tag'
  )); ?><!-- form start -->
  
  <ul class="detail-list-min_tag sortable">
    <?php foreach ($diary_tag_lists as $tag_list): ?>
    <li><?php echo $this->Form->input($tag_list['DiaryTag']['id'] . '.id', array('type' => 'hidden', 'value' => $tag_list['DiaryTag']['id'])); ?>
        <span class="li-num"><?php echo $tag_list['DiaryTag']['sort']; ?></span>
        <span class="li-num"><?php echo $tag_list['DiaryTag']['id']; ?></span>
        <span class="li-txt_tag"><?php echo $tag_list['DiaryTag']['title']; ?></span>
        <span>
          <?php echo $this->Html->link('修正', '/console/diary_tag/edit/' . $tag_list['DiaryTag']['id']); ?>
          <?php // echo $this->Form->postLink('削除', array('controller' => 'Console', 'action' => 'diary_tag_delete', $tag_list['DiaryTag']['id']), null, '本当に#' . $tag_list['DiaryTag']['id'] . 'を削除しますか'); ?>
        </span></li>
    <?php endforeach; ?>
  </ul>
  
  <?php echo $this->Form->submit('並び替える', array('div' => false, 'class' => 'sort-btn_tag')); ?>
  <?php echo $this->Form->end(); ?><!-- form end -->

<script>
    jQuery(function($) {
        $('.sortable').sortable();
        $('.sortable').disableSelection();
    });
</script>

<div class="link-page_tag">
  <span class="link-page"><?php echo $this->Html->link('⇨ タグ一覧に戻る', '/console/diary_tag/'); ?></span>
</div>
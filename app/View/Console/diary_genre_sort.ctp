<?php echo $this->Html->script('http://code.jquery.com/ui/1.11.3/jquery-ui.js', array('inline' => false)); ?>
<h3>ジャンル一覧</h3>

  <div class="detail-title-min_genre">
    <span class="li-num">sort<?php echo $this->Paginator->sort('DiaryGenre.sort', '▼'); ?></span>
    <span class="li-num">id<?php echo $this->Paginator->sort('DiaryGenre.id', '▼'); ?></span>
    <span class="li-txt_genre">ジャンル名</span>
    <span class="li-ico">状態</span>
    <span>action</span>
  </div>
  
  <?php echo $this->Form->create('DiaryGenre', array( //使用するModel
      'type' => 'post', //デフォルトはpost送信
      'url' => array('controller' => 'console', 'action' => 'diary_genre_sort'), //Controllerのactionを指定
      'inputDefaults' => array('div' => ''),
      'class' => 'sort-form_genre'
  )); ?><!-- form start -->
  
  <ul class="detail-list-min_genre sortable">
    <?php foreach ($diary_genre_lists as $genre_list): ?>
    <li><?php echo $this->Form->input($genre_list['DiaryGenre']['id'] . '.id', array('type' => 'hidden', 'value' => $genre_list['DiaryGenre']['id'])); ?>
        <span class="li-num"><?php echo $genre_list['DiaryGenre']['sort']; ?></span>
        <span class="li-num"><?php echo $genre_list['DiaryGenre']['id']; ?></span>
        <span class="li-txt_genre"><?php echo $genre_list['DiaryGenre']['title']; ?></span>
        <span class="li-ico"><?php if ($genre_list['DiaryGenre']['publish'] == 0): ?>
                             <span class="icon-false">非公開</span>
                             <?php elseif ($genre_list['DiaryGenre']['publish'] == 1): ?>
                             <span class="icon-true">公開</span>
                             <?php endif; ?></span>
        <span>
          <?php echo $this->Html->link('修正', '/console/diary_genre/edit/' . $genre_list['DiaryGenre']['id']); ?>
          <?php // echo $this->Form->postLink('削除', array('controller' => 'Console', 'action' => 'diary_genre_delete', $genre_list['DiaryGenre']['id']), null, '本当に#' . $genre_list['DiaryGenre']['id'] . 'を削除しますか'); ?>
        </span></li>
    <?php endforeach; ?>
  </ul>
  
  <?php echo $this->Form->submit('並び替える', array('div' => false, 'class' => 'sort-btn_genre')); ?>
  <?php echo $this->Form->end(); ?><!-- form end -->

<script>
    jQuery(function($) {
        $('.sortable').sortable();
        $('.sortable').disableSelection();
    });
</script>

<div class="link-page_genre">
  <span class="link-page"><?php echo $this->Html->link('⇨ ジャンル一覧に戻る', '/console/diary_genre/'); ?></span>
</div>
<?php echo $this->Html->css('diary', array('inline' => false)); ?>
<?php echo $this->Html->script('jquery.lazyload.min', array('inline' => false)); ?>
<script>
    $(function() {
        $('img.lazy').lazyload( {
            //LazyLoadのオプション設定
            threshold: 200 //数値のpxまでスクロールしたら画像を読み込み
//            effect: 'fadeIn', //読み込みにフェードイン効果
//            effect_speed: 3000, //読み込み速度、単位はs
//            failure_limit: 3 //非表示の判定があってもいくつ先まで判定させるか
        } );
    });
</script>
<?php $birthday = $this->Session->read('birthday'); ?>
<?php
//過去日記かどうか
if (preg_match('#/diary/past#', $_SERVER['REQUEST_URI'])) {
    $past = 'past/';
    echo $this->Html->script('img_check', array('inline' => false));
} else {
    $past = null;
}
?>
<div id="menu_top" class="mobile cf">
  <?php echo $this->element('submenu_mobile', array('diary_lists' => $diary_lists, 'mode' => 'top')); ?>
</div>

<?php if (isset($this->request->params['id']) == false && isset($this->request->params['year_id']) == false): ?>
  <?php if (isset($this->request->params['genre_id']) == true): //ジャンル別一覧時のページリンク ?>
  <?php $this->Paginator->options(array(
      'url' => array('controller' => 'Diary', 'action' => 'genre', 'genre_id' => $this->request->params['genre_id'])
  )); ?>
  <?php endif; ?>
<div class="mobile">
  <?php echo $this->Paginator->numbers($paginator_option); ?>
  <?php if ($past) { //過去日記用のページリンク
      echo $this->element('sister_past_paginator');
  } ?>
</div>
<?php endif; ?>

<?php foreach ($diary_lists as $diary_list): ?>
<div id="article-<?php echo $diary_list['Diary']['id']; ?>" class="article"<?php echo (@$strong_color)? ' style="background-color: #' . $strong_color . ';"' : ''; ?>>
  <div class="art-header"><h3 id="diary-<?php echo $diary_list['Diary']['id']; ?>">
    <?php echo $this->Html->link($diary_list['Diary']['title'], '/diary/' . $past . $diary_list['Diary']['id']); ?></h3></div>
  <hr>
  <div class="art-body"><?php echo nl2br($diary_list['Diary']['text']); ?></div>
  <hr>
  <div class="art-footer">
    <?php if (!$past): ?>
    <span><?php echo $this->Html->link($diary_list['DiaryGenre']['title'], '/diary/' . $past . 'genre/' . $diary_list['Diary']['genre_id']); ?></span>
      <?php foreach ($diary_list['DiaryRegtag'] as $Regtag): ?>
      <span><?php echo $this->Html->link($Regtag['DiaryTag']['title'], '/diary/tag/' . $Regtag['tag_id']); ?></span>
      <?php endforeach; ?>
    <?php else: //過去日記用のタグ ?>
    <span><?php echo $this->Html->link('過去日記', '/diary/past/'); ?></span>
    <span><?php echo $diary_list['DiaryGenre']['title']; ?></span>
    <?php endif; ?>
    <span class="fr"><?php echo $diary_list['Diary']['date']; ?></span>
  </div>
</div>
<?php endforeach; ?>

<?php if (isset($this->request->params['id']) == false && isset($this->request->params['year_id']) == false): ?>
  <?php if (isset($this->request->params['genre_id']) == true): //ジャンル別一覧時のページリンク ?>
  <?php $this->Paginator->options(array(
      'url' => array('controller' => 'Diary', 'action' => 'genre', 'genre_id' => $this->request->params['genre_id'])
  )); ?>
  <?php endif; ?>
  <?php if (isset($this->request->params['tag_id']) == true): //タグ別一覧時のページリンク ?>
  <?php $this->Paginator->options(array(
      'url' => array('controller' => 'Diary', 'action' => 'tag', 'tag_id' => $this->request->params['tag_id'])
  )); ?>
  <?php endif; ?>
<?php echo $this->Paginator->numbers($paginator_option); ?>
  <?php if ($past) { //過去日記用のページリンク
      echo $this->element('sister_past_paginator');
  } ?>
<?php endif; ?>

<?php if (@$tag_diary_lists): ?>
<div id="menu_bottom" class="mobile cf">
  <?php echo $this->element('submenu_mobile', array('diary_lists' => $tag_diary_lists, 'mode' => 'bottom')); ?>
</div>
<?php endif; ?>
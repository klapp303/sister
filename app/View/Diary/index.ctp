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
<!-- Google AdSense Start ヘッダー下 -->
<!--<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-8547890407483708"
     data-ad-slot="1192690260"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>-->
<!-- Google AdSense End -->
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

<?php foreach ($diary_lists as $key => $diary_list): ?>
  <?php if (@$single_page && $key == 1): //singleページは2つ目の日記から関連日記なので ?>
  <div class="related-menu"><span>- 関連日記 -</span></div>
  <?php endif; ?>
<div id="article-<?php echo $diary_list['Diary']['id']; ?>" class="article"<?php echo (@$strong_color)? ' style="background-color: #' . $strong_color . ';"' : ''; ?>>
  <div class="art-header"><h3 id="diary-<?php echo $diary_list['Diary']['id']; ?>">
    <?php echo $this->Html->link($diary_list['Diary']['title'], '/diary/' . $past . $diary_list['Diary']['id']); ?></h3></div>
  <hr>
  <div class="art-body cf">
    <?php if (@$single_page && $key == 0): //singleページは1つ目の日記を全文表示 ?>
    <p><?php echo nl2br($diary_list['Diary']['text']); ?></p>
    <?php else: //日記一覧表示 ?>
    <img data-original="<?php echo $diary_list['Diary']['thumbnail']; ?>" class="lazy tmb_diary" align="left">
    <p><?php echo nl2br($diary_list['Diary']['description']); ?></p>
    <?php endif; ?>
  </div>
  <hr>
  <div class="art-footer">
    <span class="fr"><?php echo $diary_list['Diary']['date']; ?></span>
    <?php if (!$past): ?>
    <span><?php echo $this->Html->link($diary_list['DiaryGenre']['title'], '/diary/' . $past . 'genre/' . $diary_list['Diary']['genre_id']); ?></span>
      <?php
      //タグをsortの昇順に並び替える
      if (count($diary_list['DiaryRegtag']) > 1) {
          foreach ($diary_list['DiaryRegtag'] as $key2 => $val2) {
              $sort[$key2] = $val2['DiaryTag']['sort'];
          }
          array_multisort($sort, SORT_ASC, $diary_list['DiaryRegtag']);
          unset($sort);
      }
      ?>
      <?php foreach ($diary_list['DiaryRegtag'] as $Regtag): ?>
      <span><?php echo $this->Html->link($Regtag['DiaryTag']['title'], '/diary/tag/' . $Regtag['tag_id']); ?></span>
      <?php endforeach; ?>
    <?php else: //過去日記用のタグ ?>
    <span><?php echo $this->Html->link('過去日記', '/diary/past/'); ?></span>
    <span><?php echo $diary_list['DiaryGenre']['title']; ?></span>
    <?php endif; ?>
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
<!-- Google AdSense Start フッター上 -->
<!--<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-8547890407483708"
     data-ad-slot="6066577142"
     data-ad-format="rectangle"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>-->
<!-- Google AdSense End -->
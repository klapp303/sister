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
<div id="menu_top" class="mobile cf">
  <?php echo $this->element('submenu_mobile', array('diary_lists' => $diary_lists)); ?>
</div>

<?php if (isset($this->request->params['id']) == false && isset($this->request->params['year_id']) == false) { ?>
  <?php if (isset($this->request->params['genre_id']) == true) { //ジャンル別一覧時のページリンク ?>
    <?php $this->Paginator->options(array(
        'url' => array('controller' => 'Diary', 'action' => 'genre', 'genre_id' => $this->request->params['genre_id'])
    )); ?>
  <?php } ?>
  <div class="mobile"><?php echo $this->Paginator->numbers($paginator_option); ?></div>
<?php } ?>

<?php foreach ($diary_lists as $diary_list) { ?>
  <div class="article"<?php echo (@$strong_color)? ' style="background-color: #' . $strong_color . ';"' : ''; ?>>
    <div class="art-header"><h3 id="diary-<?php echo $diary_list['Diary']['id']; ?>">
      <?php echo $this->Html->link($diary_list['Diary']['title'], '/diary/' . $diary_list['Diary']['id']); ?></h3></div>
    <hr>
    <div class="art-body"><?php echo nl2br($diary_list['Diary']['text']); ?></div>
    <hr>
    <div class="art-footer">
      <span><?php echo $this->Html->link($diary_list['DiaryGenre']['title'], '/diary/genre/' . $diary_list['Diary']['genre_id']); ?></span>
      <span class="fr"><?php echo $diary_list['Diary']['date']; ?></span>
    </div>
  </div>
<?php } ?>

<?php if (isset($this->request->params['id']) == false && isset($this->request->params['year_id']) == false) { ?>
  <?php if (isset($this->request->params['genre_id']) == true) { //ジャンル別一覧時のページリンク ?>
    <?php $this->Paginator->options(array(
        'url' => array('controller' => 'Diary', 'action' => 'genre', 'genre_id' => $this->request->params['genre_id'])
    )); ?>
  <?php } ?>
  <?php echo $this->Paginator->numbers($paginator_option); ?>
<?php } ?>
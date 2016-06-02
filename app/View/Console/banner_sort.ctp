<?php echo $this->Html->script('jquery-tmb', array('inline' => false)); ?>
<?php echo $this->Html->script('http://code.jquery.com/ui/1.11.3/jquery-ui.js', array('inline' => false)); ?>
<h3>バナー一覧</h3>

  <div class="detail-title-min_banner">
    <span class="li-num">sort<?php echo $this->Paginator->sort('Banner.sort', '▼'); ?></span>
    <span class="li-num">id<?php echo $this->Paginator->sort('Banner.id', '▼'); ?></span>
    <span class="li-tmb_banner">プレビュー</span>
    <span class="li-date">公開開始日<?php echo $this->Paginator->sort('Banner.date_from', '▼'); ?><br>
                          公開終了日<?php echo $this->Paginator->sort('Banner.date_to', '▼'); ?></span>
    <span class="li-ico">状態</span>
    <span class="li-act_banner">action</span>
  </div>
  
  <?php echo $this->Form->create('Banner', array( //使用するModel
      'type' => 'post', //デフォルトはpost送信
      'url' => array('controller' => 'console', 'action' => 'banner_sort'), //Controllerのactionを指定
      'inputDefaults' => array('div' => ''),
      'class' => 'sort-form_banner'
  )); ?><!-- form start -->
  
  <ul class="detail-list-min_banner sortable">
    <?php foreach ($banner_lists as $banner_list) { ?>
      <li><?php echo $this->Form->input($banner_list['Banner']['id'].'.id', array('type' => 'hidden', 'value' => $banner_list['Banner']['id'])); ?>
          <span class="li-num"><?php echo $banner_list['Banner']['sort']; ?></span>
          <span class="li-num"><?php echo $banner_list['Banner']['id']; ?></span>
          <span class="li-tmb_banner"><a href="<?php echo $banner_list['Banner']['link_url']; ?>" target="_blank">
              <?php echo $this->Html->image('../files/banner/' . $banner_list['Banner']['image_name'], array('alt' => $banner_list['Banner']['title'], 'class' => 'img_banner')); ?></a></span>
          <span class="li-date"><?php echo $banner_list['Banner']['date_from']; ?><?php echo ($banner_list['Banner']['date_from'])? '～' : ''; ?>
                                <?php echo ($banner_list['Banner']['date_to'])? '<br>～' : '' ; ?><?php echo $banner_list['Banner']['date_to']; ?></span>
          <span class="li-ico"><?php if ($banner_list['Banner']['publish'] == 0) { ?>
                                 <span class="icon-false">非公開</span>
                               <?php } elseif ($banner_list['Banner']['publish'] == 1) { ?>
                                 <?php if ($banner_list['Banner']['date_to'] && $banner_list['Banner']['date_to'] < date('Y-m-d')) { ?>
                                   <span class="icon-false">終了</span>
                                 <?php } else { ?>
                                   <span class="icon-true">公開</span>
                                 <?php } ?>
                               <?php } ?></span>
          <span class="li-act_banner">
            <?php echo $this->Html->link('修正', '/console/banner/edit/' . $banner_list['Banner']['id']); ?>
            <?php // echo $this->Form->postLink('削除', array('controller' => 'Console', 'action' => 'banner_delete', $banner_list['Banner']['id']), null, '本当に#' . $banner_list['Banner']['id'] . 'を削除しますか'); ?>
            <?php echo $this->Html->link('削除', '/console/banner_delete/'.$banner_list['Banner']['id'], array('confirm' => '本当に#' . $banner_list['Banner']['id'] . 'を削除しますか？')); ?>
          </span></li>
    <?php } ?>
  </ul>
  
  <?php echo $this->Form->submit('並び替える', array('div' => false, 'class' => 'sort-btn_banner')); ?>
  <?php echo $this->Form->end(); ?><!-- form end -->

<script>
    $(function() {
        $('.sortable').sortable();
        $('.sortable').disableSelection();
    });
</script>

<div class="link-page_banner">
  <span class="link-page"><?php echo $this->Html->link('⇨ バナー一覧に戻る', '/console/banner/'); ?></span>
</div>
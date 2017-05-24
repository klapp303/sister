<h3>バースデーバナーの選択</h3>

  <table class="detail-list-min">
    <tr><th></th>
        <th class="tbl-num">id<?php echo $this->Paginator->sort('Banner.id', '▼'); ?></th>
        <th>プレビュー</th>
        <th>状態</th></tr>
  
    <?php echo $this->Form->create('Banner', array( //使用するModel
        'type' => 'post', //デフォルトはpost送信
        'url' => array('controller' => 'console', 'action' => 'birthday_banner_add', $actor), //Controllerのactionを指定
        'inputDefaults' => array('div' => '')
    )); ?><!-- form start -->
    
    <?php foreach ($banner_lists as $banner_list): ?>
    <tr><td class="tbl-chk_birthday"><?php echo $this->Form->input($banner_list['Banner']['id'] . '.birthday_id', array('type' => 'hidden', 'value' => $birthday_id)); ?>
                                     <?php echo $this->Form->input($banner_list['Banner']['id'] . '.birthday_flg', array('type' => 'checkbox', 'label' => false)); ?></td>
        <td class="tbl-num"><?php echo $banner_list['Banner']['id']; ?></td>
        <td class="tbl-tmb_banner"><a href="<?php echo $banner_list['Banner']['link_url']; ?>" target="_blank">
          <?php echo $this->Html->image('../files/banner/' . $banner_list['Banner']['image_name'], array('alt' => $banner_list['Banner']['title'], 'class' => 'img_banner')); ?></a></td>
        <td class="tbl-ico"><?php if ($banner_list['Banner']['Birthday']['publish'] == 1): ?>
                            <span class="icon-like"><?php echo $banner_list['Banner']['Birthday']['nickname']; ?></span>
                            <?php elseif ($banner_list['Banner']['birthday_id']): ?>
                            <span class="icon-false"><?php echo $banner_list['Banner']['Birthday']['nickname']; ?></span>
                            <?php else: ?>
                            <span class="icon-false">未設定</span>
                            <?php endif; ?></td></tr>
    <?php endforeach; ?>
  </table>
  
  <?php echo $this->Form->submit('選択したものを追加する'); ?>
  <?php echo $this->Form->end(); ?><!-- form end -->

<div class="link-page_banner">
  <span class="link-page"><?php echo $this->Html->link('⇨ バースデー仕様の設定に戻る', '/console/birthday_edit/' . $actor); ?></span>
</div>
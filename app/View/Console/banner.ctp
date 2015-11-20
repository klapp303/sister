<h3>バナーの追加</h3>

  <table>
    <?php if (preg_match('#/console/banner/edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
      <?php echo $this->Form->create('Banner', array( //使用するModel
          'type' => 'put', //変更はput
          'enctype' => 'multipart/form-data', //fileアップロードの場合
          'url' => array('controller' => 'console', 'action' => 'banner_edit'), //Controllerのactionを指定
          'inputDefaults' => array('div' => '')
          )
      ); ?>
      <?php echo $this->Form->input('id', array('type' => 'hidden', 'label' => false, 'value' => $id)); ?>
    <?php } else { //登録用 ?>
      <?php echo $this->Form->create('Banner', array( //使用するModel
          'type' => 'post', //デフォルトはpost送信
          'enctype' => 'multipart/form-data', //fileアップロードの場合
          'url' => array('controller' => 'console', 'action' => 'banner_add'), //Controllerのactionを指定
          'inputDefaults' => array('div' => '')
          )
      ); ?>
    <?php } ?><!-- form start -->
    
    <tr>
      <td>タイトル</td>
      <td><?php echo $this->Form->input('title', array('type' => 'text', 'label' => false, 'size' => 49)); ?></td>
    </tr>
    <tr>
      <td>バナー画像</td>
      <td><?php if (preg_match('#/console/banner/edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
          <?php echo $this->Form->input('Banner.delete_name', array('type' => 'hidden', 'label' => false, 'value' => $image_name)); ?>
          <img src="/files/banner/<?php echo $image_name; ?>" class="img_banner"><br>
          <?php } ?>
          <?php echo $this->Form->input('Banner.file', array('type' => 'file', 'label' => false)); ?></td>
    </tr>
    <tr>
      <td>リンク先URL</td>
      <td><?php echo $this->Form->input('link_url', array('type' => 'text', 'label' => false, 'size' => 49)); ?></td>
    </tr>
    <tr>
      <td>公開開始日（任意）</td>
      <td><?php echo $this->Form->input('date_from', array('type' => 'date', 'label' => false, 'dateFormat' => 'YMD', 'monthNames' => false, 'separator' => '/', 'maxYear' => date('Y')+1, 'minYear' => 2015)); ?></td>
    </tr>
    <tr>
      <td>公開終了日（任意）</td>
      <td><?php echo $this->Form->input('date_to', array('type' => 'date', 'label' => false, 'dateFormat' => 'YMD', 'monthNames' => false, 'separator' => '/', 'maxYear' => date('Y')+1, 'minYear' => 2015)); ?></td>
    </tr>
    <tr>
      <td>状態</td>
      <td><?php echo $this->Form->input('publish', array('type' => 'select', 'label' => false, 'options' => array(0 => '非公開', 1 => '公開'))); ?></td>
    </tr>
    
    <tr>
      <td></td>
      <td class="tbl-button"><?php if (preg_match('#/console/banner/edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
            <?php echo $this->Form->submit('修正する'); ?>
          <?php } else { //登録用 ?>
            <?php echo $this->Form->submit('追加する'); ?>
          <?php } ?></td>
    </tr>
    <?php echo $this->Form->end(); ?><!-- form end -->
  </table>

<h3>バナー一覧</h3>

  <?php echo $this->Paginator->numbers(array(
      'modulus' => 4, //現在ページから左右あわせてインクルードする個数
      'separator' => '|', //デフォルト値のセパレーター
      'first' => '＜', //先頭ページへのリンク
      'last' => '＞' //最終ページへのリンク
  )); ?>

  <table class="detail-list-min">
    <tr><th class="tbl-num">id<?php echo $this->Paginator->sort('Banner.id', '▼'); ?></th>
        <th>プレビュー</th>
        <th class="tbl-date">公開開始日<?php echo $this->Paginator->sort('Banner.date_from', '▼'); ?><br>
                             公開終了日<?php echo $this->Paginator->sort('Banner.date_to', '▼'); ?></th>
        <th class="tbl-ico">状態</th>
        <th class="tbl-act_banner">action</th></tr>
    
    <?php foreach ($banner_lists AS $banner_list) { ?>
    <tr><td class="tbl-num"><?php echo $banner_list['Banner']['id']; ?></td>
        <td class="tbl-tmb_banner"><a href="<?php echo $banner_list['Banner']['link_url']; ?>" target="_blank"><img src="/files/banner/<?php echo $banner_list['Banner']['image_name']; ?>" alt="<?php echo $banner_list['Banner']['title']; ?>" class="img_banner"</td>
        <td class="tbl-date"><?php echo $banner_list['Banner']['date_from']; ?><?php if ($banner_list['Banner']['date_from']) {echo '～'; } ?>
                             <?php if ($banner_list['Banner']['date_to']) {echo '<br>～'; } ?><?php echo $banner_list['Banner']['date_to']; ?></td>
        <td class="tbl-ico"><?php if ($banner_list['Banner']['publish'] == 0) {echo '<span class="icon-false">非公開</span>';}
                              elseif ($banner_list['Banner']['publish'] == 1) {echo '<span class="icon-true">公開</span>';} ?></td>
        <td class="tbl-act_diary"><?php echo $this->Html->link('修正', '/console/banner/edit/'.$banner_list['Banner']['id']); ?>
                                  <?php echo $this->Form->postLink('削除', array('controller' => 'Console', 'action' => 'banner_delete', $banner_list['Banner']['id']), null, '本当に#'.$banner_list['Banner']['id'].'を削除しますか'); ?></td></tr>
    <?php } ?>
  </table>
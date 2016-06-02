<?php echo $this->Html->script('jquery-tmb', array('inline' => false)); ?>
<?php if (preg_match('#/console/photo/sub_pop#', $_SERVER['REQUEST_URI'])) { //sub_pop用 ?>
  <?php echo $this->Html->css('console', array('inline' => false)); ?>
<?php } else { //main用 ?>
  <h3>画像のアップロード</h3>
  
    <table>
        <?php echo $this->Form->create('Photo', array( //使用するModel
            'enctype' => 'multipart/form-data', //fileアップロードの場合
            'url' => array('controller' => 'console', 'action' => 'photo_add'), //Controllerのactionを指定
            'inputDefaults' => array('div' => '')
        )); ?><!-- form start -->
      
      <tr>
        <td>画像ファイル</td>
        <td><?php echo $this->Form->input('Photo.file', array('type' => 'file', 'label' => false)); ?></td>
      </tr>
      
      <tr>
        <td></td>
        <td class="tbl-button"><?php echo $this->Form->submit('追加する'); ?></td>
      </tr>
      <?php echo $this->Form->end(); ?><!-- form end -->
    </table>
<?php } ?>

<h3>画像一覧</h3>

  <?php echo $this->Paginator->numbers($paginator_option); ?>

<div class="detail-list-scr">
  <table class="<?php if (preg_match('#/console/photo/sub_pop#', $_SERVER['REQUEST_URI'])) { //sub_pop用
                    echo 'detail-list-pop';
                } else { //main用
                    echo 'tbl-list_photo';
                } ?>">
    <tr><th class="tbl-num">id<?php echo $this->Paginator->sort('Photo.id', '▼'); ?></th>
        <th>プレビュー</th>
        <th>ファイル名</th>
        <th class="tbl-date">日付<?php echo $this->Paginator->sort('Photo.created', '▼'); ?></th>
        <?php if (preg_match('#/console/photo/sub_pop#', $_SERVER['REQUEST_URI'])) { //sub_pop用 ?>
        　　<!-- -->
        <?php } else { //main用 ?>
        　　<th class="tbl-act_photo">action</th>
        <?php } ?></tr>
    
    <?php foreach ($photo_lists as $photo_list) { ?>
      <tr><td class="tbl-num"><?php echo $photo_list['Photo']['id']; ?></td>
          <td class="tbl-tmb_photo"><?php echo $this->Html->image('../files/photo/' . $photo_list['Photo']['name'], array('alt' => '', 'class' => 'img_photo')); ?></td>
          <td><?php echo $photo_list['Photo']['name']; ?></td>
          <td class="tbl-date"><?php echo $photo_list['Photo']['created']; ?></td>
          <?php if (preg_match('#/console/photo/sub_pop#', $_SERVER['REQUEST_URI'])) { //sub_pop用 ?>
            <!-- -->
          <?php } else { //main用 ?>
            <td class="tbl-act_photo"><?php echo $this->Form->postLink('削除', array('controller' => 'Console', 'action' => 'photo_delete', $photo_list['Photo']['id']), null, '本当に「' . $photo_list['Photo']['name'] . '」を削除しますか'); ?></td>
          <?php } ?></tr>
    <?php } ?>
  </table>
</div>
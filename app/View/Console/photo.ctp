<?php // echo $this->Html->script('jquery-tmb', array('inline' => false)); ?>
<?php if (preg_match('#/console/photo/sub_pop#', $_SERVER['REQUEST_URI'])): //sub_pop用 ?>
<?php echo $this->Html->css('console', array('inline' => false)); ?>
<?php else: //main用 ?>
<h3>画像のアップロード</h3>

  <table>
    <?php echo $this->Form->create('Photo', array( //使用するModel
        'enctype' => 'multipart/form-data', //fileアップロードの場合
        'url' => array('controller' => 'console', 'action' => 'photo_add'), //Controllerのactionを指定
        'inputDefaults' => array('div' => '')
    )); ?><!-- form start -->
    
    <?php for ($i = 1; $i <= 5; $i++): ?>
    <tr>
      <td>ファイル <?php echo $i; ?></td>
      <td><?php echo $this->Form->input('Photo.' . $i . '.file', array('type' => 'file', 'label' => false, 'class' => 'photo_' . $i)); ?></td>
    </tr>
    <script>
        jQuery(function($) {
            var key = <?php echo json_encode($i, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
            $('.photo_' + key).before('<span class="tmb-image tmb_' + key + '"></span>');
            
            //アップロードするファイルを選択
            $('.photo_' + key).change(function() {
                var file = $(this).prop('files')[0];
                
                //画像以外は処理を停止
                if (!file.type.match('image.*')) {
                    //クリア
                    $(this).val('');
                    $('span').html('');
                    return;
                }
                
                //画像表示
                var reader = new FileReader();
                reader.onload = function() {
                    var img_src = $('<img class="js-tmb">').attr('src', reader.result);
                    $('.tmb_' + key).html(img_src);
                };
                reader.readAsDataURL(file);
                //元画像を非表示
                $('.js-tmb_pre').hide();
            });
        });
    </script>
    <?php endfor; ?>
    
    <tr>
      <td></td>
      <td class="tbl-button"><?php echo $this->Form->submit('追加する'); ?></td>
    </tr>
    <?php echo $this->Form->end(); ?><!-- form end -->
  </table>
<?php endif; ?>

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
        <?php if (preg_match('#/console/photo/sub_pop#', $_SERVER['REQUEST_URI'])): //sub_pop用 ?>
        <!-- -->
        <?php else: //main用 ?>
        <th class="tbl-act_photo">action</th>
        <?php endif; ?></tr>
    
    <?php foreach ($photo_lists as $photo_list): ?>
    <tr><td class="tbl-num"><?php echo $photo_list['Photo']['id']; ?></td>
        <td class="tbl-tmb_photo">
          <?php
          $photo_url_y = substr($photo_list['Photo']['created'], 0, 4);
          $photo_url_m = substr($photo_list['Photo']['created'], 5, 2);
          ?>
          <?php echo $this->Html->image('../files/photo/' . $photo_url_y . '/' . $photo_url_m . '/' . $photo_list['Photo']['name'], array('alt' => '', 'class' => 'img_photo')); ?>
        </td>
        <td><?php echo $photo_list['Photo']['name']; ?></td>
        <td class="tbl-date"><?php echo $photo_list['Photo']['created']; ?></td>
        <?php if (preg_match('#/console/photo/sub_pop#', $_SERVER['REQUEST_URI'])): //sub_pop用 ?>
        <!-- -->
        <?php else: //main用 ?>
        <td class="tbl-act_photo"><?php echo $this->Form->postLink('削除', array('controller' => 'Console', 'action' => 'photo_delete', $photo_list['Photo']['id']), null, '本当に「' . $photo_list['Photo']['name'] . '」を削除しますか'); ?></td>
        <?php endif; ?></tr>
    <?php endforeach; ?>
  </table>
</div>
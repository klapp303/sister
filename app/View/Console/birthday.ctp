<?php // echo $this->Html->script('jquery-tmb', array('inline' => false)); ?>
<h3>バースデー仕様の設定<span class="txt-min txt-n">　　（各項目は任意です）</span></h3>

  <table>
    <?php if (preg_match('#/console/birthday_edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
      <?php echo $this->Form->create('Birthday', array( //使用するModel
          'enctype' => 'multipart/form-data', //fileアップロードの場合
          'url' => array('controller' => 'console', 'action' => 'birthday_edit', $actor), //Controllerのactionを指定
          'inputDefaults' => array('div' => '')
      )); ?>
      <?php echo $this->Form->input('id', array('type' => 'hidden', 'label' => false)); ?>
    <?php } else { //登録用 ?>
      <?php echo $this->Form->create('Birthday', array( //使用するModel
          'enctype' => 'multipart/form-data', //fileアップロードの場合
          'url' => array('controller' => 'console', 'action' => 'birthday_add'), //Controllerのactionを指定
          'inputDefaults' => array('div' => '')
      )); ?>
      <?php echo $this->Form->input('voice_id', array('type' => 'hidden', 'label' => false, 'value' => $voice_id)); ?>
    <?php } ?><!-- form start -->
    
    <tr>
      <td>ヘッダータイトル</td>
      <td><?php echo $this->Form->input('header_title', array('type' => 'text', 'label' => false, 'size' => 49)); ?></td>
    </tr>
    <tr>
      <td>フッタータイトル</td>
      <td><?php echo $this->Form->input('footer_title', array('type' => 'text', 'label' => false, 'size' => 49)); ?></td>
    </tr>
    <tr>
      <td>ヘッダー画像</td>
      <td><?php echo $this->Form->input('header_image', array('type' => 'file', 'label' => false, 'class' => 'header_image')); ?></td>
    </tr>
    <tr>
      <td>フッター画像</td>
      <td><?php echo $this->Form->input('footer_image', array('type' => 'file', 'label' => false, 'class' => 'footer_image')); ?></td>
    </tr>
    <tr>
      <td>TOP画像</td>
      <td><?php echo $this->Form->input('top_image', array('type' => 'file', 'label' => false, 'class' => 'top_image')); ?></td>
    </tr>
    <tr>
      <td>テーマカラー</td>
      <td><?php echo $this->Form->input('thema_color', array('type' => 'text', 'label' => false, 'size' => 24)); ?></td>
    </tr>
    <tr>
      <td>テーマカラー強調</td>
      <td><?php echo $this->Form->input('thema_color_strong', array('type' => 'text', 'label' => false, 'size' => 24)); ?></td>
    </tr>
    <tr>
      <td>状態</td>
      <td><?php echo $this->Form->input('publish', array('type' => 'select', 'label' => false, 'options' => array(0 => '非適用', 1 => '適用'))); ?></td>
    </tr>
    
    <tr>
      <td></td>
      <td class="tbl-button"><?php if (preg_match('#/console/birthday_edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
                               <?php echo $this->Form->submit('変更する'); ?>
                             <?php } else { //登録用 ?>
                               <?php echo $this->Form->submit('設定する'); ?>
                             <?php } ?></td>
    </tr>
    <?php echo $this->Form->end(); ?><!-- form end -->
  </table>
<script>
    jQuery(function($) {
        $(function() {
            $('.header_image').before('<span class="tmb-image header_tmb"></span>');
            $('.footer_image').before('<span class="tmb-image footer_tmb"></span>');
            $('.top_image').before('<span class="tmb-image top_tmb"></span>');
            
            //アップロードするファイルを選択
            $('.header_image').change(function() {
                var file = $(this).prop('files')[0];
                
                //画像以外は処理を停止
                if (! file.type.match('image.*')) {
                    //クリア
                    $(this).val('');
                    $('span').html('');
                    return;
                }
                
                //画像表示
                var reader = new FileReader();
                reader.onload = function() {
                    var img_src = $('<img class="js-tmb">').attr('src', reader.result);
                    $('.header_tmb').html(img_src);
                };
                reader.readAsDataURL(file);
                //元画像を非表示
                $('.js-tmb_pre').hide();
            });
            
            //アップロードするファイルを選択
            $('.footer_image').change(function() {
                var file = $(this).prop('files')[0];
                
                //画像以外は処理を停止
                if (! file.type.match('image.*')) {
                    //クリア
                    $(this).val('');
                    $('span').html('');
                    return;
                }
                
                //画像表示
                var reader = new FileReader();
                reader.onload = function() {
                    var img_src = $('<img class="js-tmb">').attr('src', reader.result);
                    $('.footer_tmb').html(img_src);
                };
                reader.readAsDataURL(file);
                //元画像を非表示
                $('.js-tmb_pre').hide();
            });
            
            //アップロードするファイルを選択
            $('.top_image').change(function() {
                var file = $(this).prop('files')[0];
                
                //画像以外は処理を停止
                if (! file.type.match('image.*')) {
                    //クリア
                    $(this).val('');
                    $('span').html('');
                    return;
                }
                
                //画像表示
                var reader = new FileReader();
                reader.onload = function() {
                    var img_src = $('<img class="js-tmb">').attr('src', reader.result);
                    $('.top_tmb').html(img_src);
                };
                reader.readAsDataURL(file);
                //元画像を非表示
                $('.js-tmb_pre').hide();
            });
        });
    });
</script>
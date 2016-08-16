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
      <td><?php echo $this->Form->input('header_title', array('type' => 'text', 'label' => false, 'size' => 49, 'placeholder' => '例）虹(+0.5)の妹たちを ｐｒｐｒするサイト')); ?></td>
    </tr>
    <tr>
      <td>フッタータイトル</td>
      <td><?php echo $this->Form->input('footer_title', array('type' => 'text', 'label' => false, 'size' => 49, 'placeholder' => '例）虹妹ｐｒｐｒ推進委員会')); ?></td>
    </tr>
    <?php
    $array_image = array(
        'ヘッダー画像' => 'header',
        'フッター画像' => 'footer',
        'TOP画像' => 'top'
    );
    ?>
    <?php foreach ($array_image as $key => $val) { ?>
      <tr>
        <td><?php echo $key; ?></td>
        <td>
          <span class="tmb-image <?php echo $val; ?>_tmb"><img class="js-tmb" src="<?php echo @${$val . '_image_url'}; ?>"></span>
          <span class="tmb-checkbox"><input type="checkbox" name="data[Birthday][<?php echo $val; ?>_image][delete_flg]"><span class="txt-min">画像を削除する</span></span>
          <?php echo $this->Form->input($val . '_image', array('type' => 'file', 'label' => false, 'class' => $val . '_image')); ?>
        </td>
      </tr>
      <script>
          jQuery(function($) {
              $(function() {
//                  $('.header_image').before('<span class="tmb-image header_tmb"></span>');
                  
                  //アップロードするファイルを選択
                  $('.<?php echo $val; ?>_image').change(function() {
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
                          $('.<?php echo $val; ?>_tmb').html(img_src);
                      };
                      reader.readAsDataURL(file);
                      //元画像を非表示
                      $('.js-tmb_pre').hide();
                  });
              });
          });
      </script>
    <?php } ?>
    <?php
    $array_color = array(
        'テーマカラー' => 'thema',
        'シャドーカラー' => 'shadow',
        '強調テーマカラー' => 'strong',
        '背景カラー' => 'bg'
    );
    ?>
    <?php foreach ($array_color as $key => $val) { ?>
      <tr>
        <td><?php echo $key; ?></td>
        <td>
          <?php echo $this->Form->input($val . '_color', array('type' => 'text', 'label' => false, 'size' => 12, 'placeholder' => '例）ffffff')); ?>
          <div class="color-sample"<?php echo (@$this->request->data['Birthday'][$val . '_color'])? ' style="background-color: #' . $this->request->data['Birthday'][$val . '_color'] . ';"' : ''; ?>></div>
        </td>
      </tr>
    <?php } ?>
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
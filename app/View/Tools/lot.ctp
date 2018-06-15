<?php echo $this->Html->css('tools', array('inline' => false)); ?>
<h3><?php echo $tool_data['name']; ?> <span class="txt-min">ver<?php echo $tool_data['version_latest']; ?></span></h3>

<?php if (@$result_data): ?>
<ol class="result_rank">
  <?php foreach ($result_data as $val): ?>
  <li><?php echo $val; ?></li>
  <?php endforeach; ?>
</ol>

<textarea class="result_txt_rank"><?php echo $result_data_text; ?></textarea>

<hr>
<?php endif; ?>

<p class="intro_tools">
  申込者の一覧と当選数を入力する事で、厳正な抽選を行えます。<br>
  行けないリリイベのシリアルとかに活用してください。<br>
  実際には配列のランダム並び替えをしてるだけとも言う。<br>
  <br>
  データとデータの間は改行してください。
</p>

<!-- Google AdSense Start -->

<!-- Google AdSense End -->

  <table>
    <?php echo $this->Form->create('Tool', array( //使用するModel
        'type' => 'post', //デフォルトはpost送信
        'url' => array('controller' => 'tools', 'action' => 'lot'), //Controllerのactionを指定
        'inputDefaults' => array('div' => '')
    )); ?><!-- form start -->
    <tr>
      <td><label>申込者</label></td>
      <td><?php echo $this->Form->input('data', array('type' => 'textarea', 'label' => false, 'placeholder' => '例）' . PHP_EOL . 'フォロワー1' . PHP_EOL . 'フォロワー2' . PHP_EOL . 'フォロワー3' . PHP_EOL . 'FF外失')); ?></td>
    </tr>
    <tr>
      <td><label>当選数</label></td>
      <td><?php echo $this->Form->input('number', array('type' => 'integer', 'label' => false, 'placeholder' => '2', 'required')); ?></td>
    </tr>
    
    <tr>
      <td></td>
      <td><?php echo $this->Form->submit('厳正な抽選を行う', array('id' => 'lot_submit')); ?></td>
    </tr>
    <?php echo $this->Form->end(); ?><!-- form end -->
    <script>
        jQuery(function($) {
            //validate
            $('#lot_submit').click(function() {
                var error_flg = 0;
                //当選数のvalidate
                var number = $('#ToolNumber').val();
                if (number) {
                    //全角は半角に変換
                    number = number.replace(/[０-９]/g, function(s) {
                        return String.fromCharCode(s.charCodeAt(0) - 0xFEE0);
                    });
                    //数字以外はfalse
                    if (/^[-]?([1-9]\d*|0)$/.test(number) === false) {
                        error_flg = 1;
                    }
                    //0以下はfalse
                    if (number <= 0) {
                        error_flg = 1;
                    }
                }
                
                if (error_flg == 1) {
                    alert('入力された当選数が正しくありません。');
                    return false;
                }
            });
        });
    </script>
  </table>

<h4 class="h4_tools">更新履歴</h4>

  <div class="update-log">
    <?php foreach ($tool_data['version'] as $key => $version): ?>
    <div><span class="txt-min">ver<?php echo $key; ?></span> <?php echo $version[1]; ?></div>
    <div class="update-date"><?php echo $version[0]; ?></div>
    <hr>
    <?php endforeach; ?>
  </div>
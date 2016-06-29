<?php echo $this->Html->css('tools', array('inline' => false)); ?>
<h3>ランキング作成ツール</h3>

<?php if (@$sort_data) { ?>
  <ul>
    <?php foreach ($sort_data as $key => $val) { ?>
      <li><?php echo $key +1; ?>. <?php echo $val['data']; ?></li>
    <?php } ?>
  </ul>
<?php } ?>

<p class="intro_ranking">
  データを複数入力すると、自動的に二択の選択肢が作られていきます。<br>
  画面に従ってどちらかを選んでいく事で最終的にソートが可能です。<br>
  <br>
  データとデータの間は改行してください。
</p>

  <table>
    <?php echo $this->Form->create('Tool', array( //使用するModel
        'type' => 'post', //デフォルトはpost送信
        'url' => array('controller' => 'tools', 'action' => 'ranking_sort'), //Controllerのactionを指定
        'inputDefaults' => array('div' => '')
    )); ?><!-- form start -->
    <tr>
      <td><label>データ</label></td>
      <td><?php echo $this->Form->input('data', array('type' => 'textarea', 'label' => false)); ?></td>
    </tr>
    
    <tr>
      <td></td>
      <td><?php echo $this->Form->submit('ソートを始める'); ?></td>
    </tr>
    <?php echo $this->Form->end(); ?><!-- form end -->
  </table>
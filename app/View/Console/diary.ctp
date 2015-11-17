<h3>日記の作成</h3>

  <?php echo $this->Form->create('Diary', array( //使用するModel
      'type' => 'post', //デフォルトはpost送信
      'action' => 'add', //Controllerのactionを指定
      'inputDefaults' => array('div' => '')
      )
  ); ?><!-- form start -->
  <?php echo $this->Form->input('title', array('type' => 'text', 'label' => 'タイトル')); ?><br>
  <?php echo $this->Form->input('text', array('type' => 'text', 'label' => '記事')); ?><br>
  <?php echo $this->Form->input('date', array('type' => 'date', 'label' => '日付', 'dateFormat' => 'YMD', 'monthNames' => false, 'separator' => '/', 'maxYear' => date('Y')+1, 'minYear' => 2015)); ?><br>
  <?php echo $this->Form->input('publish', array('type' => 'select', 'label' => '状態', 'options' => array(0 => '非公開', 1 => '公開'))); ?><br>
  
  <?php echo $this->Form->submit('作成する'); ?>
  <?php echo $this->Form->end(); ?><!-- form end -->

<h3>日記一覧</h3>

  <?php echo $this->Paginator->numbers(array(
      'modulus' => 4, //現在ページから左右あわせてインクルードする個数
      'separator' => '|', //デフォルト値のセパレーター
      'first' => '＜', //先頭ページへのリンク
      'last' => '＞' //最終ページへのリンク
  )); ?>

  <table class="detail-list-min">
    <tr><th class="tbl-num">id</th>
        <th>タイトル</th>
        <th>記事</th>
        <th>日付<?php echo $this->Paginator->sort('date', '▼'); ?></th>
        <th class="tbl-ico">状態</th>
        <th>action</th></tr>
    
    <?php foreach ($diary_lists AS $diary_list) { ?>
    <tr><td class="tbl-num"><?php echo $diary_list['Diary']['id']; ?></td>
        <td><?php echo $diary_list['Diary']['title']; ?></td>
        <td><?php echo $diary_list['Diary']['text']; ?></td>
        <td class="tbl-date"><?php echo $diary_list['Diary']['date']; ?></td>
        <td class="tbl-ico"><?php if ($diary_list['Diary']['publish'] == 0) {echo '<span class="icon-false">非公開</span>';}
                              elseif ($diary_list['Diary']['publish'] == 1) {echo '<span class="icon-true">公開</span>';} ?></td>
        <td><?php echo $this->Html->link('修正', '/samples/edit/'.$diary_list['Diary']['id']); ?>
            <?php echo $this->Form->postLink('削除', array('action' => 'deleted', $diary_list['Diary']['id']), null, '本当に削除しますか'); ?></td></tr>
    <?php } ?>
  </table>
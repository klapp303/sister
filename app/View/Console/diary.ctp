<?php echo $this->Html->script('jquery-file_insert', array('inline' => FALSE)); ?>
<?php echo $this->Html->script('sub_pop', array('inline' => FALSE)); ?>
<h3>日記の作成</h3>

  <table>
    <?php if (preg_match('#/console/diary/edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
      <?php echo $this->Form->create('Diary', array( //使用するModel
          'type' => 'put', //変更はput
          'url' => array('controller' => 'console', 'action' => 'diary_edit'), //Controllerのactionを指定
          'inputDefaults' => array('div' => '')
          )
      ); ?>
      <?php echo $this->Form->input('id', array('type' => 'hidden', 'label' => false, 'value' => $id)); ?>
    <?php } else { //登録用 ?>
      <?php echo $this->Form->create('Diary', array( //使用するModel
          'type' => 'post', //デフォルトはpost送信
          'url' => array('controller' => 'console', 'action' => 'diary_add'), //Controllerのactionを指定
          'inputDefaults' => array('div' => '')
          )
      ); ?>
    <?php } ?><!-- form start -->
    
    <tr>
      <td>タイトル</td>
      <td><?php echo $this->Form->input('title', array('type' => 'text', 'label' => false, 'size' => 49)); ?></td>
    </tr>
    <tr>
      <td></td>
      <td><button type="button" id="main_pop" class="js-insert" data="<?php echo 'sample.jpg'; ?>">画像を挿入</button>
          <input type="text" class="js-insert_data">
          <?php echo $this->Html->link('画像一覧を確認', array('controller' => 'console', 'action' => 'photo', $mode = 'sub_pop'), array('target' => 'sub_pop', 'onClick' => 'disp("/console/photo/")')); ?></td>
    </tr>
    <tr>
      <td>記事</td>
      <td><?php echo $this->Form->input('text', array('type' => 'textarea', 'label' => false, 'cols' => 50, 'rows' => 30, 'class' => 'js-insert_area')); ?></td>
    </tr>
    <tr>
      <td>日付</td>
      <td><?php echo $this->Form->input('date', array('type' => 'date', 'label' => false, 'dateFormat' => 'YMD', 'monthNames' => false, 'separator' => '/', 'maxYear' => date('Y')+1, 'minYear' => 2013)); ?></td>
    </tr>
    <tr>
      <td>ジャンル</td>
      <td><?php echo $this->Form->input('genre_id', array('type' => 'select', 'label' => false, 'options' => $genre_lists)); ?></td>
    </tr>
    <tr>
      <td>状態</td>
      <td><?php echo $this->Form->input('publish', array('type' => 'select', 'label' => false, 'options' => array(0 => '非公開', 1 => '公開'))); ?></td>
    </tr>
    
    <tr>
      <td></td>
      <td class="tbl-button"><?php if (preg_match('#/console/diary/edit/#', $_SERVER['REQUEST_URI'])) { //編集用 ?>
            <?php echo $this->Form->submit('修正する'); ?>
          <?php } else { //登録用 ?>
            <?php echo $this->Form->submit('作成する'); ?>
          <?php } ?></td>
    </tr>
    <?php echo $this->Form->end(); ?><!-- form end -->
  </table>

<h3>日記一覧</h3>

  <?php echo $this->Paginator->numbers(array(
      'modulus' => 4, //現在ページから左右あわせてインクルードする個数
      'separator' => '|', //デフォルト値のセパレーター
      'first' => '＜', //先頭ページへのリンク
      'last' => '＞' //最終ページへのリンク
  )); ?>

  <table class="detail-list-min">
    <tr><th class="tbl-num">id<?php echo $this->Paginator->sort('Diary.id', '▼'); ?></th>
        <th>タイトル</th>
        <th class="tbl-date">日付<?php echo $this->Paginator->sort('Diary.date', '▼'); ?></th>
        <th class="tbl-ico_diary">ジャンル<br>
                                  状態</th>
        <th class="tbl-act_diary">action</th></tr>
    
    <?php foreach ($diary_lists AS $diary_list) { ?>
    <tr><td class="tbl-num"><?php echo $diary_list['Diary']['id']; ?></td>
        <td><?php echo $diary_list['Diary']['title']; ?></td>
        <td class="tbl-date"><?php echo $diary_list['Diary']['date']; ?></td>
        <td class="tbl-ico_diary"><?php echo $diary_list['DiaryGenre']['title']; ?><br>
                                  <?php if ($diary_list['Diary']['publish'] == 0) {echo '<span class="icon-false">非公開</span>';}
                                    elseif ($diary_list['Diary']['publish'] == 1) {echo '<span class="icon-true icon-button">';
                                                                                   echo $this->Html->link('公開', '/diary/'.$diary_list['Diary']['id'], array('target' => '_blank'));
                                                                                   echo '</span>';} ?></td>
        <td class="tbl-act_diary"><?php echo $this->Html->link('プレビュー', '/console/diary_preview/'.$diary_list['Diary']['id'], array('target' => '_blank')); ?><br>
                                  <?php echo $this->Html->link('修正', '/console/diary/edit/'.$diary_list['Diary']['id']); ?>
                                  <?php echo $this->Form->postLink('削除', array('controller' => 'Console', 'action' => 'diary_delete', $diary_list['Diary']['id']), null, '本当に#'.$diary_list['Diary']['id'].'を削除しますか'); ?></td></tr>
    <?php } ?>
  </table>
<?php echo $this->Html->script('sub_pop', array('inline' => false)); ?>
<h3>日記の作成</h3>

  <table>
    <?php if (preg_match('#/console/diary/edit/#', $_SERVER['REQUEST_URI'])): //編集用 ?>
    <?php echo $this->Form->create('Diary', array( //使用するModel
        'type' => 'put', //変更はput
        'url' => array('controller' => 'console', 'action' => 'diary_edit'), //Controllerのactionを指定
        'inputDefaults' => array('div' => '')
    )); ?>
    <?php echo $this->Form->input('id', array('type' => 'hidden', 'label' => false, 'value' => $id)); ?>
    <?php else: //登録用 ?>
    <?php echo $this->Form->create('Diary', array( //使用するModel
        'type' => 'post', //デフォルトはpost送信
        'url' => array('controller' => 'console', 'action' => 'diary_add'), //Controllerのactionを指定
        'inputDefaults' => array('div' => '')
    )); ?>
    <?php endif; ?><!-- form start -->
    
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
    <script>
        jQuery(function($) {
            $.fn.extend({
                insertAtCaret: function(v) {
                    var o = this.get(0);
                    o.focus();
                    if ($.browser.msie) {
//                    if ($.support.noCloneEvent) {
                        var r = document.selection.createRange();
                        r.text = v;
                        r.select();
                    } else {
                        var s = o.value;
                        var p = o.selectionStart;
                        var np = p + v.length;
                        o.value = s.substr(0, p) + v + s.substr(p);
                        o.setSelectionRange(np, np);
                    }
                }
            });
            
            $('.js-insert').click(function() {
//                var img_name = $(this).attr('data');
                var img_name = $('.js-insert_data').val();
                if (!img_name) {
                    alert('画像ファイルを選んでください');
                    return false;
                }
                var date = new Date();
                
                //年月ベースの画像URLを設定するため
                var year = date.getFullYear();
                var month = date.getMonth() + 1;
                var month = ('0' + month).slice(-2);
                
                $('.js-insert_area').insertAtCaret('<img src="/files/photo/' + year + '/' + month + '/' + img_name + '" alt="" class="img_diary">');
            });
        });
    </script>
    <tr>
      <td>記事</td>
      <td><?php echo $this->Form->input('text', array('type' => 'textarea', 'label' => false, 'cols' => 50, 'rows' => 30, 'class' => 'js-insert_area')); ?></td>
    </tr>
    <tr>
      <td>日付</td>
      <td><?php echo $this->Form->input('date', array('type' => 'date', 'label' => false, 'dateFormat' => 'YMD', 'monthNames' => false, 'separator' => '/', 'maxYear' => date('Y') +1, 'minYear' => 2010)); ?></td>
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
      <td class="tbl-button"><?php if (preg_match('#/console/diary/edit/#', $_SERVER['REQUEST_URI'])): //編集用 ?>
                             <?php echo $this->Form->submit('修正する'); ?>
                             <?php else: //登録用 ?>
                             <?php echo $this->Form->submit('作成する'); ?>
                             <?php endif; ?></td>
    </tr>
    <?php echo $this->Form->end(); ?><!-- form end -->
  </table>

<h3>日記一覧</h3>

  <?php echo $this->Paginator->numbers($paginator_option); ?>

  <table class="detail-list-min">
    <tr><th class="tbl-num">id<?php echo $this->Paginator->sort('Diary.id', '▼'); ?></th>
        <th>タイトル</th>
        <th class="tbl-date">日付<?php echo $this->Paginator->sort('Diary.date', '▼'); ?></th>
        <th class="tbl-ico_diary">ジャンル<br>
                                  状態</th>
        <th class="tbl-act_diary">action</th></tr>
    
    <?php foreach ($diary_lists as $diary_list): ?>
    <tr><td class="tbl-num"><?php echo $diary_list['Diary']['id']; ?></td>
        <td><?php echo $diary_list['Diary']['title']; ?></td>
        <td class="tbl-date"><?php echo $diary_list['Diary']['date']; ?></td>
        <td class="tbl-ico_diary"><?php echo $diary_list['DiaryGenre']['title']; ?><br>
                                  <?php if ($diary_list['Diary']['publish'] == 0): ?>
                                  <span class="icon-false">非公開</span>
                                  <?php elseif ($diary_list['Diary']['publish'] == 1): ?>
                                  <span class="icon-true icon-button"><?php echo $this->Html->link('公開', '/diary/' . $diary_list['Diary']['id'], array('target' => '_blank')); ?></span>
                                  <?php endif; ?></td>
        <td class="tbl-act_diary"><?php echo $this->Html->link('プレビュー', '/preview/diary/' . $diary_list['Diary']['id'], array('target' => '_blank')); ?><br>
                                  <?php echo $this->Html->link('タグを登録', '/console/diary_regtag/edit/' . $diary_list['Diary']['id']); ?><br>
                                  <?php echo $this->Html->link('修正', '/console/diary/edit/' . $diary_list['Diary']['id']); ?>
                                  <?php echo $this->Form->postLink('削除', array('controller' => 'Console', 'action' => 'diary_delete', $diary_list['Diary']['id']), null, '本当に#' . $diary_list['Diary']['id'] . 'を削除しますか'); ?></td></tr>
    <?php endforeach; ?>
  </table>
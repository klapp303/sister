<h3>ジャンルの作成</h3>

  <table>
    <?php if (preg_match('#/console/diary_genre/edit/#', $_SERVER['REQUEST_URI'])): //編集用 ?>
    <?php echo $this->Form->create('DiaryGenre', array( //使用するModel
        'type' => 'put', //変更はput
        'url' => array('controller' => 'console', 'action' => 'diary_genre_edit'), //Controllerのactionを指定
        'inputDefaults' => array('div' => '')
    )); ?>
    <?php echo $this->Form->input('id', array('type' => 'hidden', 'label' => false, 'value' => $id)); ?>
    <?php else: //登録用 ?>
    <?php echo $this->Form->create('DiaryGenre', array( //使用するModel
        'type' => 'post', //デフォルトはpost送信
        'url' => array('controller' => 'console', 'action' => 'diary_genre_add'), //Controllerのactionを指定
        'inputDefaults' => array('div' => '')
    )); ?>
    <?php endif; ?><!-- form start -->
    
    <tr>
      <td>ジャンル名</td>
      <td><?php echo $this->Form->input('title', array('type' => 'text', 'label' => false, 'size' => 30)); ?></td>
    </tr>
    <tr>
      <td>状態</td>
      <td><?php echo $this->Form->input('publish', array('type' => 'select', 'label' => false, 'options' => array(0 => '非公開', 1 => '公開'))); ?></td>
    </tr>
    <tr>
      <td>menu</td>
      <td><?php echo $this->Form->input('menu', array('type' => 'select', 'label' => false, 'options' => array(0 => '表示しない', 1 => '表示する'))); ?></td>
    </tr>
    
    <tr>
      <td></td>
      <td class="tbl-button"><?php if (preg_match('#/console/diary_genre/edit/#', $_SERVER['REQUEST_URI'])): //編集用 ?>
                             <?php echo $this->Form->submit('修正する'); ?>
                             <?php else: //登録用 ?>
                             <?php echo $this->Form->submit('登録する'); ?>
                             <?php endif; ?></td>
    </tr>
    <?php echo $this->Form->end(); ?><!-- form end -->
  </table>

<h3>ジャンル一覧</h3>

  <?php echo $this->Paginator->numbers($paginator_option); ?>

  <table class="detail-list-min">
    <tr><th class="tbl-num">id<?php echo $this->Paginator->sort('DiaryGenre.id', '▼'); ?></th>
        <th>ジャンル名</th>
        <th class="tbl-ico">状態</th>
        <th class="tbl-act_genre">action</th></tr>
    
    <?php foreach ($diary_genre_lists as $genre_list): ?>
    <tr><td class="tbl-num"><?php echo $genre_list['DiaryGenre']['id']; ?></td>
        <td class="txt-c"><?php echo $genre_list['DiaryGenre']['title']; ?></td>
        <td class="tbl-ico"><?php if ($genre_list['DiaryGenre']['publish'] == 0): ?>
                            <span class="icon-false">非公開</span>
                            <?php elseif ($genre_list['DiaryGenre']['publish'] == 1): ?>
                            <span class="icon-true">公開</span>
                            <?php endif; ?><br>
                            <?php if ($genre_list['DiaryGenre']['menu'] == 0): ?>
                            <span class="icon-false">なし</span>
                            <?php elseif ($genre_list['DiaryGenre']['menu'] == 1): ?>
                            <span class="icon-like">menu</span>
                            <?php endif; ?></td>
        <td class="tbl-act_genre"><?php echo $this->Html->link('日記の確認', '/diary/genre/' . $genre_list['DiaryGenre']['id'], array('target' => '_blank')); ?><br>
                                  <?php echo $this->Html->link('修正', '/console/diary_genre/edit/' . $genre_list['DiaryGenre']['id']); ?>
                                  <?php echo $this->Form->postLink('削除', array('controller' => 'Console', 'action' => 'diary_genre_delete', $genre_list['DiaryGenre']['id']), null, '本当に「' . $genre_list['DiaryGenre']['title'] . '」を削除しますか'); ?></td></tr>
    <?php endforeach; ?>
  </table>

<div class="link-page_genre">
  <span class="link-page"><?php echo $this->Html->link('⇨ ジャンルを並び替える', '/console/diary_genre_sort/'); ?></span>
</div>
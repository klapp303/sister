<h3>お知らせの追加</h3>

  <table>
    <?php if (preg_match('#/console/information/edit/#', $_SERVER['REQUEST_URI'])): //編集用 ?>
    <?php echo $this->Form->create('Information', array( //使用するModel
        'type' => 'put', //変更はput
        'url' => array('controller' => 'console', 'action' => 'information_edit'), //Controllerのactionを指定
        'inputDefaults' => array('div' => '')
    )); ?>
    <?php echo $this->Form->input('id', array('type' => 'hidden', 'label' => false, 'value' => $id)); ?>
    <?php else: //登録用 ?>
    <?php echo $this->Form->create('Information', array( //使用するModel
        'type' => 'post', //デフォルトはpost送信
        'url' => array('controller' => 'console', 'action' => 'information_add'), //Controllerのactionを指定
        'inputDefaults' => array('div' => '')
    )); ?>
    <?php endif; ?><!-- form start -->
    
    <tr>
      <td>タイトル</td>
      <td><?php echo $this->Form->input('title', array('type' => 'text', 'label' => false, 'size' => 49)); ?></td>
    </tr>
    <tr>
      <td>内容（任意）</td>
      <td><?php echo $this->Form->input('text', array('type' => 'textarea', 'label' => false, 'cols' => 50, 'rows' => 10)); ?></td>
    </tr>
    <tr>
      <td>公開開始日（任意）</td>
      <td><?php echo $this->Form->input('date_from', array('type' => 'date', 'label' => false, 'dateFormat' => 'YMD', 'monthNames' => false, 'separator' => '/', 'maxYear' => date('Y') +1, 'minYear' => 2015)); ?></td>
    </tr>
    <tr>
      <td>公開終了日（任意）</td>
      <?php if (preg_match('#/console/information/edit/#', $_SERVER['REQUEST_URI'])): //編集用 ?>
        <?php if ($this->request->data['Information']['date_to'] == null): //値がnull ?>
        <td><?php echo $this->Form->input('date_to', array('type' => 'date', 'label' => false, 'dateFormat' => 'YMD', 'monthNames' => false, 'separator' => '/', 'maxYear' => date('Y') +1, 'minYear' => 2015, 'class' => 'js-input_date_to', 'disabled' => 'disabled')); ?>
            <input type="checkbox" class="js-checkbox_date_to" checked="checked">null</td>
        <?php else: //値がnullではない ?>
        <td><?php echo $this->Form->input('date_to', array('type' => 'date', 'label' => false, 'dateFormat' => 'YMD', 'monthNames' => false, 'separator' => '/', 'maxYear' => date('Y') +1, 'minYear' => 2015, 'class' => 'js-input_date_to')); ?>
            <input type="checkbox" class="js-checkbox_date_to" name="date_to">null</td>
        <?php endif; ?>
      <?php else: //登録用 ?>
      <td><?php echo $this->Form->input('date_to', array('type' => 'date', 'label' => false, 'dateFormat' => 'YMD', 'monthNames' => false, 'separator' => '/', 'maxYear' => date('Y') +1, 'minYear' => 2015, 'class' => 'js-input_date_to')); ?>
          <input type="checkbox" class="js-checkbox_date_to">null</td>
      <?php endif; ?>
    </tr>
    <tr>
      <td>状態</td>
      <td><?php echo $this->Form->input('publish', array('type' => 'select', 'label' => false, 'options' => array(0 => '非公開', 1 => '公開'))); ?></td>
    </tr>
    
    <tr>
      <td></td>
      <td class="tbl-button"><?php if (preg_match('#/console/information/edit/#', $_SERVER['REQUEST_URI'])): //編集用 ?>
                             <?php echo $this->Form->submit('修正する'); ?>
                             <?php else: //登録用 ?>
                             <?php echo $this->Form->submit('追加する'); ?>
                             <?php endif; ?></td>
    </tr>
    <?php echo $this->Form->end(); ?><!-- form end -->
  </table>

<h3>お知らせ一覧</h3>

  <?php echo $this->Paginator->numbers($paginator_option); ?>

  <table class="detail-list-min">
    <tr><th class="tbl-num">id<?php echo $this->Paginator->sort('Information.id', '▼'); ?></th>
        <th>タイトル</th>
        <th class="tbl-date">公開開始日<?php echo $this->Paginator->sort('Information.date_from', '▼'); ?><br>
                             公開終了日<?php echo $this->Paginator->sort('Information.date_to', '▼'); ?></th>
        <th class="tbl-ico">状態</th>
        <th class="tbl-act_information">action</th></tr>
    
    <?php foreach ($information_lists as $information_list): ?>
    <tr><td class="tbl-num"><?php echo $information_list['Information']['id']; ?></td>
        <td><?php echo $information_list['Information']['title']; ?></td>
        <td class="tbl-date"><?php echo $information_list['Information']['date_from']; ?><?php echo ($information_list['Information']['date_from'])? '～' : ''; ?>
                             <?php echo ($information_list['Information']['date_to'])? '<br>～' : ''; ?><?php echo $information_list['Information']['date_to']; ?></td>
        <td class="tbl-ico"><?php if ($information_list['Information']['publish'] == 0): ?>
                            <span class="icon-false">非公開</span>
                            <?php elseif ($information_list['Information']['publish'] == 1): ?>
                              <?php if ($information_list['Information']['date_to'] && $information_list['Information']['date_to'] < date('Y-m-d')): ?>
                              <span class="icon-false">終了</span>
                              <?php else: ?>
                              <span class="icon-true">公開</span>
                              <?php endif; ?>
                            <?php endif; ?></td>
        <td class="tbl-act_information"><?php echo $this->Html->link('修正', '/console/information/edit/' . $information_list['Information']['id']); ?>
                                  <?php echo $this->Form->postLink('削除', array('controller' => 'Console', 'action' => 'information_delete', $information_list['Information']['id']), null, '本当に#' . $information_list['Information']['id'] . 'を削除しますか'); ?></td></tr>
    <?php endforeach; ?>
  </table>
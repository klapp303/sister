<h3>管理者の追加</h3>

  <table>
    <?php echo $this->Form->create('Administrator', array( //使用するModel
        'type' => 'post', //デフォルトはpost送信
        'url' => array('controller' => 'console', 'action' => 'admin_add'), //Controllerのactionを指定
        'inputDefaults' => array('div' => '')
    )); ?><!-- form start -->
    
    <tr>
      <td>ユーザ名</td>
      <td><?php echo $this->Form->input('admin_name', array('type' => 'text', 'label' => false, 'size' => 24)); ?></td>
    </tr>
    <tr>
    <tr>
      <td>パスワード</td>
      <td><?php echo $this->Form->input('password', array('type' => 'text', 'label' => false, 'size' => 24)); ?></td>
    </tr>
    
    <tr>
      <td></td>
      <td class="tbl-button"><?php echo $this->Form->submit('追加する'); ?></td>
    </tr>
    <?php echo $this->Form->end(); ?><!-- form end -->
  </table>

<h3>管理者一覧</h3>

  <?php echo $this->Paginator->numbers($paginator_option); ?>

  <table class="detail-list-min">
    <tr><th class="tbl-num">id<?php echo $this->Paginator->sort('Administrator.id', '▼'); ?></th>
        <th>ユーザ名</th>
        <!--th>パスワード</th-->
        <th class="tbl-act_admin">action</th></tr>
    
    <?php foreach ($admin_lists as $admin_list) { ?>
      <tr><td class="tbl-num"><?php echo $admin_list['Administrator']['id']; ?></td>
          <td class="txt-c"><?php echo $admin_list['Administrator']['admin_name']; ?></td>
          <!--td><?php // echo $admin_list['Administrator']['password']; ?></td-->
          <td class="tbl-act_admin"><?php // echo $this->Html->link('修正', '/console/admin/edit/' . $admin_list['Administrator']['id']); ?>
                                    <?php echo $this->Form->postLink('削除', array('controller' => 'Console', 'action' => 'admin_delete', $admin_list['Administrator']['id']), null, '本当に#' . $admin_list['Administrator']['id'] . 'を削除しますか'); ?></td></tr>
    <?php } ?>
  </table>
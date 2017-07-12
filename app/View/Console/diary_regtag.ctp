<h3>タグの登録</h3>

  <?php echo $this->Form->create('Diary', array( //使用するModel
      'type' => 'post', //デフォルトはpost送信
      'url' => array('controller' => 'console', 'action' => 'diary_regtag_edit'), //Controllerのactionを指定
      'inputDefaults' => array('div' => '')
  )); ?>
  <?php echo $this->Form->input('DiaryRegTag.diary_id', array('type' => 'hidden', 'value' => $id)); ?>
  
  <table>
    <tr>
      <td colspan="3"><?php echo $diary_title; ?></td>
    </tr>
    <tr>
      <td><select name="regtag-lists" size="15" style="width: 200px;" multiple="multiple">
        <?php foreach ($regtag_lists as $regtag): ?>
        <option value="<?php echo $regtag['tag_id']; ?>"><?php echo $regtag['title']; ?></option>
        <?php endforeach; ?>
      </select></td>
      <td><button type="button" class="tag-add-button"><< 追加する</button><br><br>
          <button type="button" class="regtag-delete-button">>> 削除する</butotn></td>
      <td><select name="tag-lists" size="15" style="width: 200px;" multiple="multiple">
        <?php foreach ($tag_lists as $tag): ?>
        <option value="<?php echo $tag['tag_id']; ?>"><?php echo $tag['title']; ?></option>
        <?php endforeach; ?>
      </select></td>
    </tr>
    <tr><td><?php echo $this->Form->submit('これで登録する'); ?></td></tr>
    <?php echo $this->Form->end(); ?><!-- form end -->
  </table>

<script>
    jQuery(function($) {
        //タグの追加
        $('.tag-add-button').click(function() {
            //選択されているタグを取得
            var tag_id = $('[name=tag-lists]').val();
            var tag_name = [];
            $('[name=tag-lists] option:selected').each(function() {
                tag_name.push($(this).text());
            });
            
            $.each(tag_id, function(i) {
                //登録タグ一覧に追加する
                $('[name=regtag-lists]').append('<option value="' + tag_id[i] + '">' + tag_name[i] + '</option>');
                //タグ一覧から削除する
                $('[name=tag-lists] option[value=' + tag_id[i] + ']').remove();
            });
        });
        
        //登録タグの削除
        $('.regtag-delete-button').click(function() {
            //選択されている登録タグを取得
            var regtag_id = $('[name=regtag-lists]').val();
            var regtag_name = [];
            $('[name=regtag-lists] option:selected').each(function() {
                regtag_name.push($(this).text());
            });
            
            $.each(regtag_id, function(i) {
                //タグ一覧に追加する
                $('[name=tag-lists]').append('<option value="' + regtag_id[i] + '">' + regtag_name[i] + '</option>');
                //登録タグ一覧から削除する
                $('[name=regtag-lists] option[value=' + regtag_id[i] + ']').remove();
            });
        });
        
        //タグの登録
        $('#DiaryDiaryRegtagEditForm').submit(function() {
            //登録タグ一覧を取得する
            var regtag_id_all = $('[name=regtag-lists]').children();
            
            //要素を一つずつ追加
            for(var i = 0; i < regtag_id_all.length; i++) {
                $('<input>').attr('type', 'hidden')
                        .attr('name', 'data[DiaryRegTag][' + i + '][tag_id]')
                        .attr('value', regtag_id_all.eq(i).val())
                        .appendTo('#DiaryDiaryRegtagEditForm');
            }
        });
    });
</script>

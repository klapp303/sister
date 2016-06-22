<?php $birthday = $this->Session->read('birthday'); ?>
<p class="flash-msg <?php echo ($birthday)? 'flash-msg_' . $birthday : ''; ?>">
  <?php echo $message; ?>
</p>
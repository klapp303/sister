<?php if (isset($title) == true) { //headerタグのtitle
    if ($title == 'short') {
        echo '虹妹ｐｒｐｒ';
    } elseif ($title == 'console') {
        echo '虹妹ｐｒｐｒ -管理画面-';
    } else {
        echo '虹妹ｐｒｐｒ -エロゲレビューと声優イベントレポのサイト-';
    }
} ?>
<?php if (isset($ogp) == true && isset($ogp_url) == true) { //headerタグのOGP
    //OGPタグ用のデフォルトデータを設定
    if (@!$ogp_title) {
        $ogp_title = '-エロゲレビューと声優イベントレポのサイト-';
    }
    if (@!$ogp_image) {
        $ogp_image = 'files/top_maia.jpg';
    }
    if (@!$ogp_description) {
        $ogp_description = '虹妹ｐｒｐｒ -エロゲレビューと声優イベントレポのサイト-';
    }
    
    $ogp_title = '虹妹ｐｒｐｒ ' . $ogp_title;
    $ogp_type = $ogp;
    $ogp_image = $this->Html->url('/', true) . $ogp_image;
    $ogp_description =  mb_strimwidth($ogp_description, 0, 250, '...', 'UTF-8');
?>
<!-- OGPtag start -->
<meta property="og:title" content="<?php echo $ogp_title; ?>" />
<meta property="og:type" content="<?php echo $ogp_type; ?>" />
<meta property="og:url" content="<?php echo $ogp_url; ?>" />
<meta property="og:image" content="<?php echo $ogp_image; ?>" />
<meta property="og:site_name" content="虹妹ｐｒｐｒ -エロゲレビューと声優イベントレポのサイト-" />
<meta property="og:description" content="<?php echo $ogp_description; ?>" />
<!-- OGPtag end -->
<!-- TwitterCard start -->
<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="@klapp_kamp" />
<!-- TwitterCard end -->
<?php } ?>
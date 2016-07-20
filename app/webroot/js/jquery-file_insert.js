jQuery(function($) {
    $.fn.extend({
        insertAtCaret: function(v) {
            var o = this.get(0);
            o.focus();
            if (jQuery.browser.msie) {
//            if (jQuery.support.noCloneEvent) {
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
//        var img_name = $(this).attr('data');
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
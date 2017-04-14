jQuery(function($) {
    var arr = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
    $.each(arr, function(i, val) {
        $('.js-check-' + val).click(function() {
            if ($(this).prop('checked')) {
                //一旦全てのcheckをクリアして…
                $('.js-check-' + val).prop('checked', false);
                //選択されたものだけをcheckする
                $(this).prop('checked', true);
            }
        });
    });
});
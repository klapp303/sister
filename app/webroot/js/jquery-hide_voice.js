jQuery(function($) {
    $('#SelectHard select').change(function() { //formのchangeイベントがあれば値を取得
        var val = $(this).val();
        if (val == 'sg') {
          $('.tbl-music_voice').show();
          $('.tbl-music_voice-sg').show();
          $('.tbl-music_voice-al').hide();
        } else if (val == 'al') {
          $('.tbl-music_voice').show();
          $('.tbl-music_voice-sg').show();
          $('.tbl-music_voice-al').show();
        } else {
          $('.tbl-music_voice').hide();
          $('.tbl-music_voice-sg').hide();
          $('.tbl-music_voice-al').hide();
        }
    });
    $('#SelectGenre select').change(function() { //formのchangeイベントがあれば値を取得
        var val = $(this).val();
        if (val !== 'music') {
          $('.tbl-music_voice').hide();
          $('.tbl-music_voice-sg').hide();
          $('.tbl-music_voice-al').hide();
        }
    });
});
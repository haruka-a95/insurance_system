$(document).ready(function(){
    // 検索フォーム表示切替
    $('#toggle-search').on('click', function(){
        $('#search-form').toggleClass('hidden');
    });

    // クリアボタン
    $('#clear-filters').on('click', function(){
        $('#search-form').find('input').val(''); // 全inputを空に
        $('#search-form').find('select').val(''); // selectリセット
        $('#search-form form').submit(); // フォーム再送信
    });
});

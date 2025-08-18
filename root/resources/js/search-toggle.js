export default function initSearchToggle($) {
    $(document).ready(function() {
        // 検索フォーム表示切替
        $('#toggle-search').on('click', function() {
            $('#search-form').toggleClass('hidden');
        });

        // クリアボタン
        $('#clear-filters').on('click', function() {
            $('#search-form').find('input').val('');  // 全 input を空に
            $('#search-form').find('select').val(''); // select をリセット
            $('#search-form form').submit();         // フォーム再送信
        });
    });
}

export default function initSearchToggle($) {
    $(document).ready(function() {
        // 検索フォーム表示切替
        $('#toggle-search').on('click', function() {
            $('#search-form').toggleClass('hidden');
        });

        //ボタンの文字を切り替え
        if ($('#search-form').hasClass('hidden')) {
            $(this).text('検索フォームを表示');
        } else {
            $(this).text('検索フォームを非表示')
        }

        // クリアボタン
        $('#clear-filters').on('click', function() {
            $('#search-form').find('input').val('');  // 全 input を空に
            $('#search-form').find('select').val(''); // select をリセット
            $('#search-form form').submit();         // フォーム再送信
        });
    });
}

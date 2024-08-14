$(document).on("click", ".navbar_item > a", function() {
    let attr = $(this).attr('href');
    if(typeof attr == 'undefined' || attr == false){
        let current_scroll = $(this).closest('.navbar_item').find('div');
        let total_children = current_scroll.children().length;
        let styling = (current_scroll.css('height') == '' || current_scroll.css('height') == '0px') ? `calc(43px * ${total_children})` : "";
        current_scroll.css("height", styling);
    }
});
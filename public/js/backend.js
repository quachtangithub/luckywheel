$(document).ready(function() {
    $("#user_search").on("keyup", function () {
        var value = $(this).val();
        searchItem(value, ".item_user");
    });
});

function searchItem(value, classname) {
    var $listItemAlls = $(classname);
    if(value.length > 0) {
        let search_arr = createTextSearch(formatString(value.toUpperCase()));
        $listItemAlls.filter(function() {
            let searchable = formatString($(this).data("user"));
            $(this).toggle(search_arr.filter((item) => searchable.includes(item)).length >= search_arr.length);
        });  
        
    } else {
        $listItemAlls.hide().show();
    }
}

function createTextSearch (str) {
    var search_arr = [];
    var search_len = str.length;
    var last = '';
    for (let i = 0; i < search_len; i++) {
        let char_code = str[i];
        if (char_code == ' ') {
            if (last != '') {
                search_arr.push(last);
                // if (last.length > 1) {
                //     search_arr.push(...last.split(''));
                // }
                last = '';
            }                
        } else {
            last = last + char_code;
        }
    }
    if (last != '') {
        search_arr.push(last);
        // if (last.length > 1) {
        //     search_arr.push(...last.split(''));
        // }
    }    
    return search_arr;
}

function formatString(str) {
    return str.normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/đ/g, "d").replace(/Đ/g, "D");
}
//date box formatter

$.fn.datebox.defaults.formatter = function(date) {
    var y = date.getFullYear();
    var m = date.getMonth() + 1;
    var d = date.getDate();
    // return y + '-' + (m < 10 ? ('0' + m) : m) + '-' + (d < 10 ? ('0' + d) : d);
    return (d < 10 ? ('0' + d) : d) + '-' + (m < 10 ? ('0' + m) : m) + '-' + y;
};

$.fn.datebox.defaults.parser = function(s) {
    if (!s) return new Date();
    var ss = s.split('-');
    var y = parseInt(ss[0], 10);
    var m = parseInt(ss[1], 10);
    var d = parseInt(ss[2], 10);
    if (!isNaN(y) && !isNaN(m) && !isNaN(d)) {
        // return new Date(y, m - 1, d);
        return new Date(d, m - 1, y);
    } else {
        return new Date();
    }
};

//Expand the combobox panle on focus
$.fn.combobox.defaults.showPanelOnFocus = true;
$.extend($.fn.combobox.defaults.inputEvents, {
    focus: function(e){
        var target = this;
    var showPanelOnFocus = $(e.data.target).combobox('options').showPanelOnFocus;
    if(showPanelOnFocus)
      $(e.data.target).combobox('showPanel');
    }
});

$.fn.datagrid.defaults = $.extend({}, $.fn.datagrid.defaults, {
  pageSize: 10,
    pageList: [10,15,20,25,30,40,50]

});


  $.extend($.fn.edatagrid.defaults, {
    onError: function(index, errorRow){

        var errorMessage = errorRow.jqXHR.responseJSON.msg;

        showEdatagridTypingError(errorMessage);

        bindEdatagridKeypress(this, index);

        $(this).edatagrid('editRow', index);
    }
  });

//
// Edatagrid
//
// On save fail , return the error message
function showEdatagridTypingError(message) {
    $.messager.show({
        title: 'Error',
        msg: message,
        timeout: 4000,
        showType: 'slide'
    });
}

/*
  bind the enter key to the target editors,
  they would be able to save the row on pressing enter
*/

function bindEdatagridKeypress(edatagrid, index) {

    // get the current row editors
    var editors = $(edatagrid).edatagrid('getEditors', index);

    // set focus on the first field
    var focused = editors[0];

    if(focused) {
        if (focused.type === 'validatebox') {
            $(focused.target).focus();
        } else {
            $(focused.target).textbox('textbox').focus();
        }
    }


    // bind the enter key press event to the current row editors
    $.each(editors, function(i, ed) {
        $(ed.target).keypress(function(event) {
            if (event.keyCode == 13) {
                $(edatagrid).edatagrid('saveRow');
            }
        });
    });

}

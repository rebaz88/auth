
<table id="CodesDatagrid">
    <thead>
      <tr>
          <th data-options="field:'name',width:120">Name</th>
          <th data-options="field:'category',width:120">Category</th>
      </tr>
  </thead>
</table>

<div id="CodesDatagridToolbar">

  <div style="padding:5px;">

    <a href="#" class="easyui-linkbutton" iconCls="icon-add"  onclick="addCode()">New</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-edit"  onclick="editCode('save')">Edit</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-remove"  onclick="removeCode()">Remove</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-reload"  onclick="javascript:$('#CodesDatagrid').datagrid('reload')">Reload</a>

  </div>


</div>

<div id="CodesDialog" class="easyui-dialog" style="width:80%;height:80%;" title="Code" closed="true" modal="true">

</div>

<div id="CodesDialogButtons">
    <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveCode()">Save</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#CodesDialog').dialog('close')" style="width:90px">Cancel</a>
</div>


<script>


  $('#CodesDatagrid').datagrid({
    title:'Codes',
    fit:true,
    toolbar:'#CodesDatagridToolbar',
    nowrap:false,
    fitColumns:true,
    singleSelect:true,
    method:'get',
    rownumbers:true,
    pagination:true,
    border:false,
    remoteFilter:true,
    filterTypeMatch:'any',
    url:'codes/list'
  })

  $('#CodesDatagrid').datagrid('enableFilter');

  var url;

  function addCode() {

      $('#CodesDialog').dialog('open').dialog('refresh', 'codes/insert');
  }

  function editCode() {

      var row = $('#CodesDatagrid').datagrid('getSelected');

      if (!row) {

      	$.messager.show({ title: 'Error', msg: 'Please select a code'});
        return;
      }

      $('#CodesDialog').dialog('open').dialog('refresh', 'codes/update?id=' +  row.id);

  }

  function saveCode(url) {

    $.messager.progress();  // display the progress bar

    $('#CodesForm').form('submit', {

        url: url,

        onSubmit: function(param) {
          param._token = window.CSRF_TOKEN;
          param.description = $('#summernote').summernote('code');


          var formValidated = $(this).form('validate');

          if(formValidated)
            return true;

          $.messager.progress('close');
          return formValidated;

        },
        success: function(result) {

            var result = eval('(' + result + ')');

            if (result.isError) {

              $.messager.show({ title: 'Error', msg: result.msg});


            } else {

                $('#CodesForm').form('clear');
                $('#CodesDatagrid').datagrid('reload')
                $('#CodesDialog').dialog('close');

            }

            $.messager.progress('close');

        }
    });

  }

  function removeCode() {

      // var row = quotaDataGrid.datagrid('getSelected');
      var row = $('#CodesDatagrid').datagrid('getSelected');

      if (!row) {
      	$.messager.show({ title: 'Error', msg: 'Please select a code'});
        return;
      }

      $.messager.confirm('Confirm', 'Are you sure you want to delete this code?', function(r) {
          if (r) {

              $.post('codes/destroy', {
                  'id': row.id,
              }, function(result) {
                  if (result.success) {

                      $('#CodesDatagrid').datagrid('reload');

                  } else {
                  	$.messager.show({ title: 'Error', msg: result.msg});
                  }
              }, 'json');
          }
      });
  }

  function saveEditorImage(file) {
      // return;
      data = new FormData();
      data.append("attachment[]", file);
      $.ajax({
          data: data,
          type: "POST",
          url: "/codes/save-editor-image",
          cache: false,
          contentType: false,
          processData: false,
          success: function(url) {
              $('#summernote').summernote('insertImage', url, function($image) {
                $image.css('width', '100%');
              });
          }
      });
  }


</script>



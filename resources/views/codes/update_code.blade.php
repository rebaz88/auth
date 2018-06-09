


    <form id="CodesForm" method="post" novalidate style="width:100%; height: 100%;padding:0;" enctype="multipart/form-data">

      <div class="easyui-layout" fit="true">


        <div data-options="region:'north',border:false" style="height:60px;padding:20px 0 20px 20px;overflow: hidden;">
          <div class="ftitle">
            Code Information
          </div>
        </div>

        <div data-options="region:'west',border:false" style="width:230px;padding:0px 0px 20px 20px">

          <div class="fitem">
              <input name="name" class="easyui-textbox" prompt="Code" value="{!! $code->name !!}" required="true" style="width:200px;">
          </div>

          <div class="fitem">
              <input class="easyui-combobox" prompt="Category" id="category" name="category" value="{!! $code->category !!}" style="width:200px;" data-options="
                  url: 'codes/list-categories',
                  method: 'get',
                  valueField: 'category',
                  textField: 'category',
                  hasDownArrow: true,
                  panelHeight:'auto',
                  prompt: 'Select or write a category',
                  required:true
                  ">
          </div>

          <div class="fitem">
              <input class="easyui-filebox" name="attachment[]" multiple="true" accept="image/*" buttonAlign="left" style="width:200px">
          </div>

          @foreach ($code->getMedia() as $mediaItem)
            <div class="easyui-layout" id="MediaItem{!!$mediaItem->id!!}" style="height: 100px;width:200px;margin:5px 0;">
              <div data-options="region:'west'" style="width:160px">
                <img src="{{ $mediaItem->getUrl() }}" style="width:100%;height: 100%;">

              </div>
              <div data-options="region:'center'" style="text-align: center;padding:30px 0px">
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" onclick="removeCodeImage('{!! $mediaItem->id !!}', '{!! $code->id !!}')"></a>
              </div>
            </div>
          @endforeach

        </div>

        <div data-options="region:'south', border:true" style="height:50px;padding:10px;text-align: center;">

          <a href="javascript:void(0)" class="easyui-linkbutton c4" iconCls="icon-save" onclick="saveCode('codes/update?id=' + {!!$code->id!!})">Save</a>
          <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#CodesDialog').dialog('close')" style="width:90px">Cancel</a>

        </div>

        <div data-options="region:'center', border:false" style="padding:10px;">


          <div class="fitem">
            <div id="editor"></div>
          </div>

        </div>


      </div>


    </form>

<style>
  #CodesForm .fitem{
    margin: 10px 0;
  }
</style>

<script>

  $(function(){

      var description = {!! json_encode($code->description) !!};

      initializeEditor();

      window.quill.root.innerHTML = description;

      $('#CodeForm').form('load', {
        category: '{!! $code->category !!}',
      });
  });

  function removeCodeImage(mediaId, codeId) {

      $.post('codes/destroy/media', {
          'media_id': mediaId,
          'code_id': codeId,
      }, function(result) {
          if (result.success) {

              $('#MediaItem' + mediaId).hide();

          } else {
            $.messager.show({ title: 'Error', msg: result.msg});
          }
      }, 'json');
  }

</script>

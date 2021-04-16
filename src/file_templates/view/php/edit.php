<link rel=stylesheet media=all href="<?php echo CSS_URL ?>edit.css">
<style>
  /* 宽度在750像素以上的设备 */
  @media only screen and (min-width:751px) {}
</style>

<!--<script defer src="<?php //echo JS_URL 
                        ?>edit.js"></script>-->
<script>
  // 页面主要数据
  let item = <?php echo json_encode($item) ?>;
  console.log(item);

  let item_id = <?php echo $item[$this->id_name] ?>;
  console.log(item_id);

  $(function() {
    // 点击触发异步请求
    $('[type=submit]').on('click', function() {
      // 取值各字段，并请求API
      var params = common_params; // 初始化异步请求公共参数
      var params_needed = 'name1,name2'.split(',');
      for (let item of params_needed) {
        params[item] = $('[name=' + item + ']').val();
      }
      params.id = item_id;
      console.log(params);

      $.post(
        api_url + class_name + '/edit',
        params,
        function(result) {
          console.log(result); // 输出回调数据到控制台

          if (result.status == 200) {
            // 操作成功后业务逻辑
            alert('succeed')
            location.href = base_url + class_name // 转到列表页

          } else {
            // 操作失败后业务逻辑
            alert(result.content.error.message)
          }
        }
      ).fail(
        // 请求失败回调
        function() {
          alert("error")
        }
      );

      return false
    });

  });
</script>


<base href="<?php echo $this->media_root ?>">

<div id=breadcrumb>
  <ol class="breadcrumb container">
    <li><a href="<?php echo base_url() ?>">首页</a></li>
    <li><a href="<?php echo base_url($this->class_name) ?>"><?php echo $this->class_name_cn ?></a></li>
    <li class=active><?php echo $title ?></li>
  </ol>
</div>

<div id=content>
  <div class=container>

    <div class=btn-group role=group>
      <a class="btn btn-default" title="所有<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name) ?>"><i class="far fa-list fa-fw"></i> 所有<?php echo $this->class_name_cn ?></a>
      <a class="btn btn-default" title="<?php echo $this->class_name_cn ?>回收站" href="<?php echo base_url($this->class_name . '/trash') ?>"><i class="far fa-trash fa-fw"></i> 回收站</a>
      <a class="btn btn-default" title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name . '/create') ?>"><i class="far fa-plus fa-fw"></i> 创建<?php echo $this->class_name_cn ?></a>
    </div>

    <?php
    if (!empty($error)) echo '<div class="alert alert-warning" role=alert>' . $error . '</div>';
    $attributes = array('class' => 'form-' . $this->class_name . '-edit form-horizontal', 'role' => 'form');
    echo form_open_multipart($this->class_name . '/edit?id=' . $item[$this->id_name], $attributes);
    ?>
    <p class=help-block>必填项以“*”符号标示</p>

    <fieldset>
      <legend>基本信息</legend>

      <input name=id type=hidden value="<?php echo $item[$this->id_name] ?>">

      [[content]]
    </fieldset>

    <div class=form-group>
      <div class="col-xs-12 col-sm-offset-2 col-sm-2">
        <button class="btn btn-primary btn-lg btn-block" type=submit>确定</button>
      </div>
    </div>
    </form>

  </div><!-- end #content.container-->
</div>
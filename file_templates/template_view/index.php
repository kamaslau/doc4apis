<style>


	/* 宽度在750像素以上的设备 */
	@media only screen and (min-width:751px)
	{

	}
</style>

<script>
    // 页面主要数据
    let items = <?php echo json_encode($items) ?>;
    console.log(items);

    $(function(){
        // 点击触发异步请求
        $('#dom_id').on('click',function(){
            // 取值各字段，并请求API
            var params = common_params; // 初始化异步请求公共参数
            var params_needed = 'name1,name2'.split(',');
            for (let item of params_needed)
            {
                params[item] = $('[name='+ item +']').val();
            }
            console.log(params);

            $.post(
                api_url + class_name + '/function',
                params,
                function(result)
                {
                    console.log(result); // 输出回调数据到控制台

                    if (result.status == 200)
                    {
                        // 操作成功后业务逻辑
                        alert('succeed')

                    } else {
                        // 操作失败后业务逻辑
                        alert(result.content.error.message)
                    }
                }
            ).fail(
                // 请求失败回调
                function()
                {
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
		<li class=active><?php echo $this->class_name_cn ?></li>
	</ol>
</div>

<div id=content>
    <div class=container>
        <div class=btn-group role=group>
            <a class="btn btn-primary" title="所有<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name) ?>"><i class="far fa-list fa-fw" aria-hidden=true></i> 所有<?php echo $this->class_name_cn ?></a>
            <a class="btn btn-default" title="<?php echo $this->class_name_cn ?>回收站" href="<?php echo base_url($this->class_name.'/trash') ?>"><i class="far fa-trash fa-fw" aria-hidden=true></i> 回收站</a>
            <a class="btn btn-default" title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create') ?>"><i class="far fa-plus fa-fw" aria-hidden=true></i> 创建<?php echo $this->class_name_cn ?></a>
        </div>
		
	<?php if ( isset($content) ) echo '<div class="alert alert-warning" role=alert>'.$content.'</div>'; ?>

	<?php if ( empty($items) ): ?>
	<blockquote>
		<p>这里空空如也……</p>
	</blockquote>

	<?php else: ?>
		<ul id=item-list class=row>
		<?php foreach ($items as $item): ?>

            <li class=item data-item-id=<?php echo $item[$this->id_name] ?>>
                <span>ID <?php echo $item[$this->id_name] ?></span>

                <section class=row>
                    <a title="<?php echo $item['name'] ?>" href="<?php echo base_url($this->class_name. '/detail?id='.$item[$this->id_name]) ?>">
                        <h2><?php echo $item['brief_name'] ?></h2>
                    </a>
                </section>

                <ul>
                    <li>
                        <a class=edit data-id="<?php echo $item[$this->id_name] ?>" href="<?php echo base_url($this->class_name.'/edit?id='.$item[$this->id_name]) ?>" target=_blank><i class="far fa-edit"></i> 修改</a>
                        <a class=delete data-op-class=<?php echo $this->class_name ?> data-op-name=delete data-id="<?php echo $item[$this->id_name] ?>" href="<?php echo base_url($this->class_name.'/delete?ids='.$item[$this->id_name]) ?>" target=_blank><i class="far fa-trash"></i> 删除</a>
                    </li>
                </ul>
            </li>

		<?php endforeach ?>
		</ul>
	<?php endif ?>

    </div><!-- end #content.container-->
</div>
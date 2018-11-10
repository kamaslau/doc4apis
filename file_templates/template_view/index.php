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
		
	<?php if ( isset($content) ) echo '<div class="alert alert-warning" role=alert>'.$content.'</div>' ?>

    <blockquote v-if="items.length == 0">
        <p>这里空空如也……</p>
    </blockquote>

    <ul id=item-list class=item-list v-else>
        <li class=item v-for="item in items" :data-item-id="item.[[id_name]]">

            <span>ID {{ item.[[id_name]] }}</span>
            <section>
                <a :title="item.name" :href="url_for_class('detail', item.[[id_name]])">
                    <h2>{{ item.name }}</h2>
                </a>
            </section>

            <ul class=item-ops>
                <li>
                    <a class=edit :href="url_for_class('edit', item.[[id_name]])" target=_blank><i class="far fa-fw fa-edit"></i> 修改</a>
                </li>
                <li>
                    <a class=delete @click.prevent="edit_bulk('delete', item.[[id_name]])" :href="url_for_class('delete', item.[[id_name]])" target=_blank><i class="far fa-fw fa-trash"></i> 删除</a>
                </li>
            </ul>

        </li>
    </ul>

    </div><!-- end #content.container-->
</div>

<script>
    // Vue业务代码必须位于DOM之后
    var vue_app = new Vue({
        el: '#content',
        data: {
            common_params: common_params,
            class_name: class_name,
            items: items,
        },
        methods: {
            url_for_class: function(method, item_id){
                var method = method || ''; // 默认为列表页（即index)
                var url_to_return = class_url + method;

                // 根据方法名不同拼接不同URL信息
                switch (method)
                {
                    case '':
                        break;
                    case 'detail':
                    case 'edit':
                        url_to_return += '?id=' + item_id;
                        break;
                    default:
                        url_to_return += '?ids=' + item_id;
                }

                return url_to_return
            },
            edit_bulk: function(method, ids){
                var request_url = api_url + class_name + '/edit_bulk';

                // 尝试获取待操作项ID，若未传入，则可能为批量操作
                var ids = ids || null;
                // 获取待批量操作项ID列表（CSV）
                if (ids == null){
                    console.log('批量操作');
                }

                var params = common_params;
                params.ids = ids;
                params.operation = method;
                //params.biz_id = 1;
                //params.password = '123456';

                $(function(){
                    $.post(
                        request_url,
                        params,
                        function(result)
                        {
                            console.log(result); // 输出回调数据到控制台

                            if (result.status == 200)
                            {
                                // 操作成功后业务逻辑
                                $('[data-item-id='+ ids +']').remove();

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
                });
            }
        }
    });
</script>
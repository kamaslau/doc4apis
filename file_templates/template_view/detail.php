<link rel=stylesheet media=all href="<?php echo CSS_URL ?>detail.css">
<style>


	/* 宽度在750像素以上的设备 */
	@media only screen and (min-width:751px)
	{
		
	}
</style>

<!--<script defer src="<?php //echo JS_URL ?>detail.js"></script>-->
<script>
    // 页面主要数据
    let item = <?php echo json_encode($item) ?>;
    console.log(item);

    let item_id = <?php echo $item[$this->id_name] ?>;
    console.log(item_id);

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
		<li><a href="<?php echo base_url($this->class_name) ?>"><?php echo $this->class_name_cn ?></a></li>
		<li class=active><?php echo $title ?></li>
	</ol>
</div>

<div id=content>
    <div class=container>

    <div class=btn-group role=group>
        <a class="btn btn-default" title="所有<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name) ?>"><i class="far fa-list fa-fw"></i> 所有<?php echo $this->class_name_cn ?></a>
        <a class="btn btn-default" title="<?php echo $this->class_name_cn ?>回收站" href="<?php echo base_url($this->class_name.'/trash') ?>"><i class="far fa-trash fa-fw"></i> 回收站</a>
        <a class="btn btn-default" title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create') ?>"><i class="far fa-plus fa-fw"></i> 创建<?php echo $this->class_name_cn ?></a>
    </div>

    <p v-if="item.length == 0"><?php echo empty($error)? '没有符合条件的数据': $error; ?></p>

    <template v-else>
        <?php
        // 需要特定角色和权限进行该操作
        $current_role = $this->session->role; // 当前用户角色
        $current_level = $this->session->level; // 当前用户级别
        $role_allowed = array('管理员', '经理');
        $level_allowed = 30;
        if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
        ?>
        <ul id=item-actions class=list-unstyled>
            <li><a href="<?php echo base_url($this->class_name.'/edit?id='.$item[$this->id_name]) ?>">编辑</a></li>
        </ul>
        <?php endif ?>

        <dl id=list-info class=dl-horizontal>
            <dt><?php echo $this->class_name_cn ?>ID</dt>
            <dd><?php echo $item[$this->id_name] ?></dd>

            <dt>描述</dt>
            <dd><?php echo empty($item['description'])? 'N/A': $item['description'] ?></dd>

            <dt>主图</dt>
            <dd class=row>
                <?php
                    $column_image = 'url_image_main';
                    if ( empty($item[$column_image]) ):
                ?>
                <p>未上传</p>
                <?php else: ?>
                <figure class="col-xs-12 col-sm-6 col-md-4">
                    <img src="<?php echo $item[$column_image] ?>">
                </figure>
                <?php endif ?>
            </dd>

            <dt>形象图</dt>
            <dd>
                <?php
                    $column_images = 'url_image_main';
                    if ( empty($item[$column_images]) ):
                ?>
                <p>未上传</p>
                <?php else: ?>
                <ul class=row>
                    <?php
                        $image_urls = explode(',', $item[$column_images]);
                        foreach($image_urls as $url):
                    ?>
                    <li class="col-xs-6 col-sm-4 col-md-3">
                        <img src="<?php echo $url ?>">
                    </li>
                    <?php endforeach ?>
                </ul>
                <?php endif ?>
            </dd>

            [[content]]
        </dl>

        <dl id=list-record class=dl-horizontal>
            <dt>创建时间</dt>
            <dd>
                {{ item.time_create }}
                <a :href="url_stuff_detail + item.creator_id" target=new>查看创建者</a>
            </dd>

            <template v-if="item.time_delete != null">
                <dt>删除时间</dt>
                <dd>{{ item.time_delete }}</dd>
            </template>

            <dt>最后操作时间</dt>
            <dd>
                {{ item.time_edit }}
                <a :href="url_stuff_detail + item.operator_id" target=new>查看最后操作者</a>
            </dd>
        </dl>
    </template>
	
    </div><!-- end #content.container-->
</div>

<script>
    // Vue业务代码必须位于DOM之后
    var vue_app = new Vue({
        el: '#content',
        data: {
            item: item,
            url_stuff_detail: base_url + 'stuff/detail?id='
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
                    case 'delete':
                    case 'restore':
                        url_to_return += '?ids=' + item_id;
                        break;
                    default:
                        url_to_return += '?id=' + item_id;
                }

                return url_to_return
            }
        }
    });
</script>
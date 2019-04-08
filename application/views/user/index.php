<link rel=stylesheet media=all href="<?php echo VIEWS_PATH ?>css/index.css">
<style>

	/* 宽度在768像素以上的设备 */
	@media only screen and (min-width:769px)
	{

	}
	
	/* 宽度在992像素以上的设备 */
	@media only screen and (min-width:993px)
	{

	}

	/* 宽度在1200像素以上的设备 */
	@media only screen and (min-width:1201px)
	{

	}
</style>

<div id=breadcrumb>
	<ol class="breadcrumb container">
		<li><a href="<?php echo base_url() ?>">首页</a></li>
		<li class=active><?php echo $this->class_name_cn ?></li>
	</ol>
</div>

<div id=content class=container>
	<?php
	// 需要特定角色和权限进行该操作
	$current_role = $this->session->role; // 当前用户角色
	$current_level = $this->session->level; // 当前用户权限
	$role_allowed = array('经理', '管理员');
	$level_allowed = 1;
	if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
	?>
	<div class=btn-group role=group>
		<a class="btn btn-primary" title="所有<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name) ?>"><i class="fal fa-list fa-fw"></i> 所有</a>
	  	<a class="btn btn-default" title="<?php echo $this->class_name_cn ?>回收站" href="<?php echo base_url($this->class_name.'/trash') ?>"><i class="fal fa-trash fa-fw"></i> 回收站</a>
		<a class="btn btn-default" title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create') ?>"><i class="fal fa-plus fa-fw"></i> 创建</a>
	</div>
	<?php endif ?>

	<?php if ( empty($items) ): ?>
	<blockquote>
		<p>这里空空如也，快点添加<?php echo $this->class_name_cn ?>吧</p>
	</blockquote>

	<?php else: ?>
    <table class="table table-condensed table-responsive table-striped sortable">
		<thead>
            <tr>
                <th>姓名</th>
                <th>手机号</th>
                <th>角色</th>
                <th>级别</th>
                <th>操作</th>
            </tr>
		</thead>

        <tbody id=dom_container></tbody>
	</table>
	<?php endif ?>
</div>

<script>
    var items = <?php echo json_encode($items) ?>;
    //console.log(items);

    $(function(){
        // 若无数据项，不继续其它逻辑
        if (items.length == 0) return false;

        var url_item = base_url + class_name + '/detail?id=';
        var url_edit = base_url + class_name + '/edit?id=';
        var url_delete = base_url + class_name + '/delete?ids=';

        // 将相关数据输出为DOM
        generate_items_dom(items);

        /**
         * 输出数据集为DOM的外围方法，可通用
         * @param object items 待生成DOM的内容
         * @param string dom_container 外围容器的唯一属性
         */
        function generate_items_dom(items, dom_container)
        {
            var container = dom_container || '#dom_container'; // 父容器DOM的ID

            var dom = generate_item_doms(items);
            //console.log(dom);

            $(container).append(dom);
        } // end generate_items_dom

        /**
         * 输出DOM的实际方法，可根据需求修改适应实际场景
         * @param object items
         * @returns string
         */
        function generate_item_doms(items)
        {
            var items_dom = ''; // 待生成DOM

            for (var index in items)
            {
                var item = items[index]
                //console.log(item);
                var url_item_current = url_item + item.user_id

                items_dom += '<tr>';
                items_dom +=
                    '       <td>'+ item.lastname+' '+item.firstname +'</td>' +
                    '       <td>'+ item.mobile +'</td>' +
                    '       <td>'+ item.role +'</td>' +
                    '       <td>'+ item.level +'</td>' +
                    '       <td>' +
                    '           <ul class="list-actions list-unstyled horizontal">' +
                    '               <li><a title=查看 href="'+ url_item_current +'" target=_blank><i class="fal fa-eye"></i></a></li>' +
                    '               <li><a title=编辑 href="'+ url_edit+item.user_id +'" target=_blank><i class="fal fa-edit"></i></a></li>' +
                    '               <li><a title=删除 href="'+ url_delete+item.user_id +'" target=_blank><i class="fal fa-trash"></i></a></li>' +
                    '           </ul>' +
                    '       </td>';
                items_dom += '</tr>';
            }

            //console.log(item_dom);
            return items_dom;
        } // end generate_item_doms
    });
</script>
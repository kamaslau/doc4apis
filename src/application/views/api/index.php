<link rel=stylesheet media=all href="<?php echo VIEWPATH ?>css/index.css">
<style>
    td.api-code {font-weight:bold;}
        .api-status {float:right;text-align:right;}

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
		<?php if ( isset($project) ): ?>
		<li><a title="<?php echo $project['name'] ?>" href="<?php echo base_url('project/detail?id='.$project['project_id']) ?>"><?php echo $project['name'] ?></a></li>
		<?php endif ?>
		<li class=active><?php echo $this->class_name_cn ?></li>
	</ol>
</div>

<div id=content class=container>
	<?php
	// 需要特定角色和权限进行该操作
	$current_role = $this->session->role; // 当前用户角色
	$current_level = $this->session->level; // 当前用户级别
	$role_allowed = array('管理员', '经理');
	$level_allowed = 30;
	if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
	?>
	<div class=btn-group role=group>
		<a class="btn btn-primary" href="<?php echo base_url($this->class_name) ?>"><i class="fal fa-list"></i> 所有</a>
	  	<a class="btn btn-default" href="<?php echo base_url($this->class_name.'/trash') ?>"><i class="fal fa-trash"></i> 回收站</a>
		<a class="btn btn-default" href="<?php echo base_url($this->class_name.'/create') ?>"><i class="fal fa-plus"></i> 创建</a>
	</div>
	<?php endif ?>

	<?php if ( empty($items) ): ?>
	<blockquote>
		<p>这里空空如也，快点添加<?php echo $this->class_name_cn ?>吧</p>
	</blockquote>

	<?php else: ?>
	<ul class="well well-sm dl-horizontal list-unstyled list-inline">
    <li><i class="fal fa-fw fa-hourglass-half"></i> 草稿/未发布</li>
		<li><i class="fal fa-fw fa-bolt"></i> 3天内添加</li>
		<li><i class="fal fa-fw fa-exclamation"></i> 3天内更新</li>
	</ul>

    <table class="table table-condensed table-responsive table-striped sortable">
		<thead>
			<tr>
        <th>组</th>
        <th>序号&名称</th>
        <th>所属企业</th>
        <th>所属项目</th>
				<th>操作</th>
			</tr>
		</thead>

		<tbody id=dom_container></tbody>
	</table>
	<?php endif ?>
</div>

<script>
    let items = <?php echo json_encode($items) ?>;
    console.log(items);

    $(function(){
        // 若无数据项，不继续其它逻辑
        if (items.length == 0) return false;

        var url_item = base_url + class_name + '/detail?id=';
        var url_edit = base_url + class_name + '/edit?id=';
        var url_delete = base_url + class_name + '/delete?ids=';
        var url_duplicate = base_url + class_name + '/duplicate?id=';

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

            var last_code = ''; // 上一项API组代码前缀
            var time_current = Math.round(new Date().getTime()/1000) ; // 当前UNIX时间戳

            for (var index in items)
            {
                var item = items[index]
                //console.log(item);
                var url_item_current = url_item + item.api_id

                items_dom += '<tr>';

                // 每组仅第一项显示API组代码前缀
                var code_group = item.code.substr(0,3)
                if (last_code == code_group)
                {
                    items_dom += '<td></td>'
                }
                else
                {
                    items_dom += '<td class=api-code>' + code_group + '</td>';
                    last_code = code_group
                }

                items_dom +=
                    '       <td>' +
                    '           <a href="'+ url_item_current +'" target=_blank>'+ item.code.substr(3)+' '+item.name +'</a>' +
                    '           <div class=api-status>' +
                    (item.status == '0'? '<i class="fal fa-fw fa-hourglass-half"></i>': '') + // 草稿
                    ((item.time_create + 60*60*24*3) > time_current? ' <i class="fal fa-fw fa-bolt"></i>': '') + // 3天内创建
                    ((item.time_edit + 60*60*24*3) > time_current? ' <i class="fal fa-fw fa-exclamation"></i>': '') + // 3天内修改
                    '           </div>' +
                    '       </td>' +
                    '       <td>'+ (item.biz_id == null? '': 'ID '+item.biz_id) +'</td>' +
                    '       <td>'+ (item.project_id == null? '': 'ID '+item.project_id) +'</td>' +
                    '       <td>' +
                    '           <ul class="list-actions list-unstyled list-inline">' +
                    '               <li><a title=查看 href="'+ url_item_current +'" target=_blank><i class="fal fa-eye"></i></a></li>' +
                    '               <li><a title=编辑 href="'+ url_edit+item.api_id +'" target=_blank><i class="fal fa-edit"></i></a></li>' +
                    '               <li><a title=复制 href="'+ url_duplicate+item.api_id +'" target=_blank><i class="fal fa-copy"></i></a></li>' +
                    '               <li><a title=删除 href="'+ url_delete+item.api_id +'" target=_blank><i class="fal fa-trash"></i></a></li>' +
                    '           </ul>' +
                    '       </td>';
                items_dom += '</tr>';
            }

            //console.log(item_dom);
            return items_dom;
        } // end generate_item_doms
    });
</script>

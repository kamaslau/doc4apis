<style>

	/* 宽度在750像素以上的设备 */
	@media only screen and (min-width:751px)
	{

	}
	
	/* 宽度在960像素以上的设备 */
	@media only screen and (min-width:961px)
	{

	}

	/* 宽度在1280像素以上的设备 */
	@media only screen and (min-width:1281px)
	{

	}
</style>

<div id=breadcrumb>
	<ol class="breadcrumb container">
		<li><a href="<?php echo base_url() ?>">首页</a></li>
		<li><a href="<?php echo base_url($this->class_name) ?>"><?php echo $this->class_name_cn ?></a></li>
		<li class=active><?php echo $title ?></li>
	</ol>
</div>

<div id=content class=container>
	<?php $name = $item['lastname'].' '.$item['firstname'] ?>
	<h2>
		<?php echo $name ?>
		<?php echo !empty($item['nickname'])? '('.$item['nickname'].')': NULL; ?>
	</h2>

	<dl class=dl-horizontal>
		<?php if ( !empty($item['avatar']) ): ?>
		<dt>头像</dt>
		<dd>
			<figure class="col-xs-12 col-md-3">
				<img alt="<?php echo $name.'的头像' ?>" src="<?php echo $item['avatar'] ?>">
			</figure>
		</dd>
		<?php endif ?>

		<dt>性别</dt>
		<dd><?php echo $item['gender'] ?></dd>

		<?php
			// 若已设置生日，显示生日祝福或生日预告
			if ( !empty($item['dob']) && $item['dob'] !== '0000-00-00' ):
				if ( date('m-d') !== substr($item['dob'],5) ):
					$dob_next = date('Y-'). substr($item['dob'],5); // 当前年份生日时间
					// 如果当前年份的生日已过，则计算距离明年生日的时间
					if ( (strtotime($dob_next) - time()) < 0 ):
						$dob_next = (date('Y') + 1).'-'.substr($item['dob'],5);
					endif;

					$time_to_dob = strtotime($dob_next) - time(); // 计算秒数
					$days_to_dob = round( $time_to_dob / (60 * 60 * 24) ); // 计算天数，四舍五入到个位数
					
					$dob_string = $item['dob']. ' （<i class="far fa-calendar"></i> '. $days_to_dob. '天后过生日）';

				else:
					$dob_string = '<i class="far fa-birthday-cake"></i> 生日快乐！！！';

				endif;
		?>
		<dt>生日</dt>
		<dd><?php echo $dob_string ?></dd>
		<?php endif ?>

        <dt>手机号</dt>
        <dd><?php echo $item['mobile'] ?></dd>

		<?php if ( !empty($item['email']) ): ?>
		<dt>Email</dt>
		<dd><?php echo $item['email'] ?></dd>
		<?php endif ?>
	</dl>

    <?php if ( !empty($item['biz_id']) ): ?>
        <h3><?php echo $biz['name'] ?> <a class="btn btn-info btn-sm" href="<?php echo base_url('biz/detail?id='.$biz['biz_id']) ?>" target=_blank>企业信息</a></h3>

        <dl class=dl-horizontal>
            <dt>权限</dt>
            <dd><?php echo $item['role'] ?> <?php echo $item['level'] ?>级</dd>
        </dl>
    <?php endif ?>

	<ul class="list-unstyled list-inline">
		<li><a href="<?php echo base_url($this->class_name.'/edit?id='.$item[$this->id_name]) ?>" target=_blank><i class="far fa-edit"></i> 编辑</a></li>
	</ul>
</div>
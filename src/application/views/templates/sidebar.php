<aside>
	<ul>
		<?php foreach ($aside_items as $item): ?>
		<li>
			<a title="<?php echo $item['name'] ?>" href="<?php echo base_url($aside_class.'/detail?id=').$api[$aside_id] ?>"><?php echo $item['name'] ?></a>
		</li>
		<?php endforeach ?>
	</ul>
</aside>
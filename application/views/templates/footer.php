			<?php if ( isset($aside_items) ): ?>
			<aside>
				<ul>
					<?php foreach ($aside_items as $item): ?>
					<li>
						<a title="<?php echo $item['name'] ?>" href="<?php echo base_url($aside_class.'/detail?id=').$api[$aside_id] ?>"><?php echo $item['name'] ?></a>
					</li>
					<?php endforeach ?>
				</ul>
			</aside>
			<?php endif ?>
		</main>
<!-- End #maincontainer -->

		<footer id=footer role=contentinfo>
		<?php
			// 若通过微信访问，则显示部分内容
			$user_agent = $_SERVER['HTTP_USER_AGENT'];
			$is_wechat = strpos($user_agent, 'MicroMessenger')? TRUE: FALSE;
			if ( ! $is_wechat):
		?>
			<div class=container>
				<!-- 页面底部导航、文章列表等 -->
				<p>此站点基于在宽屏设备上使用的场景进行设计、开发。</p>
			</div>
		<?php endif ?>

			<div id=copyright class=container>
				<p>&copy;<?php echo date('Y') ?>
				
				<a title="<?php echo SITE_DESCRIPTION ?>" href="<?php echo base_url() ?>"><?php echo SITE_NAME ?></a>

				<?php if ( !empty(ICP_NUMBER)): ?>
				<a title="工业和信息化部网站备案系统" href="http://www.miitbeian.gov.cn/" target=_blank rel=nofollow><?php echo ICP_NUMBER ?></a>
				<?php endif ?>

				由
				<a class=support title="访问BasicCodeigniter的Github主页" href="https://github.com/kamaslau/BasicCodeigniter" target=_blank>BasicCodeigniter</a>
				、
				<a class=support title="访问BootStrap的Github主页" href="https://github.com/twbs/bootstrap" target=_blank>BootStrap</a>
				驱动
				</p>
			</div>

			<a id=totop title="回到页首" href="#"><i class="fa fa-chevron-up" aria-hidden=true></i></a>
		</footer>

		<script>
			$(function(){
				// 回到页首按钮
				$('a#totop').click(function()
				{
					$('body,html').stop(false, false).animate({scrollTop:0}, 800);
					return false;
				});
			});
		</script>
	</body>
</html>
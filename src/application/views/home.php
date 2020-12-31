<link rel=stylesheet media=all href="<?php echo VIEWPATH ?>css/index.css">

<div id=content class=container>
  	<?php
  		// 检查是否正在使用默认密码（手机号后6位的sha1加密值）
  		$initial_password = SHA1( substr($this->session->mobile, -6) );
  		if ($initial_password === $this->session->password):
  	?>
	<div class="alert alert-warning" role="alert">
		<p>Hey，你正在用默认密码，这有一定的安全隐患；请考虑 <a class="btn btn-info btn-sm" href="<?php echo base_url('password_change') ?>" target=_blank>修改密码</a>。</p>
	</div>
  	<?php endif ?>

  <div v-if="bookmarks.length > 0" id="bookmarks">
    <ul>
      <li v-for="(item, index) in bookmarks" :key="index">
        <a :title="item.name" :href="item.url" target="_blank">{{ item.name }}</a>
      </li>
    </ul>
  </div>
	<!--
	<section class="col-xs-12 col-md-6 col-lg-4">
		<h2>条形图</h2>
		<div id=bar-chart>

		</div>
	</section>

	<section class="col-xs-12 col-md-6 col-lg-4">
		<h2>折线图</h2>
		<div id=line-chart>

		</div>
	</section>
	
	<section class="col-xs-12 col-md-6 col-lg-4">
		<h2>饼状图</h2>
		<div id=pie-chart>

		</div>
	</section>
	-->
</div>

<script>
  let vue_app = new Vue({
    el: '#content',

    data: {
      bookmarks: [
        { name: 'Github', url: 'https://github.com' },
        { name: 'Vue.js', url: 'https://vuejs.org/v2/guide' },
        { name: 'Nuxt.js', url: 'https://nuxtjs.org/guide' },
        { name: 'BootStrap', url: 'https://getbootstrap.com/docs/4.3/getting-started/introduction/' },
        { name: 'FontAwesome', url: 'https://fontawesome.com/icons' },

        { name: 'Udemy', url: 'https://www.udemy.com/home/my-courses/learning/' },
        { name: '阿里云', url: 'https://home.console.aliyun.com/' },
        { name: '华为云', url: 'https://console.huaweicloud.com/console/' }
      ]
    }
  })

</script>

<!--
<script defer src="https://cdn.key2all.com/js/highcharts.js"></script>
<script>
// 条形图
Highcharts.chart(
	'bar-chart',
	{
    chart: {
        type: 'bar'
    },
    title: {
        text: '任务数量'
    },
    subtitle: {
        text: '<?php echo date('Y-m-d H:i:s') ?>'
    },
    xAxis: {
        categories:
		<?php
		$categories = array('非洲', '美洲', '亚洲', '欧洲', '大洋洲');
		$categories = json_encode($categories);
		echo $categories;
		?>,
        title: {
            text: null
        }
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Population (millions)',
            align: 'high'
        },
        labels: {
            overflow: 'justify'
        }
    },
    tooltip: {
        valueSuffix: ' millions'
    },
    plotOptions: {
        bar: {
            dataLabels: {
                enabled: true
            }
        }
    },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'top',
        x: -40,
        y: 80,
        floating: true,
        borderWidth: 1,
        backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
        shadow: true
    },
    credits: {
        enabled: false
    },
    series: [{
        name: 'Year 1800',
        data: [107, 31, 635, 203, 2]
    }, {
        name: 'Year 1900',
        data: [133, 156, 947, 408, 6]
    }, {
        name: 'Year 2012',
        data: [1052, 954, 4250, 740, 38]
    }]
});
</script>

<script>
	// 线形图
	Highcharts.chart(
		'line-chart',
		{
	    chart: {
	        type: 'line'
	    },
	    title: {
	        text: '项目进度'
	    },
	    subtitle: {
	        text: 'Source: WorldClimate.com'
	    },
	    xAxis: {
	        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
	    },
	    yAxis: {
	        title: {
	            text: 'Temperature (°C)'
	        }
	    },
	    plotOptions: {
	        line: {
	            dataLabels: {
	                enabled: true
	            },
	            enableMouseTracking: false
	        }
	    },
	    series: [{
	        name: 'Tokyo',
	        data: [7.0, 6.9, 9.5, 14.5, 18.4, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]
	    }, {
	        name: 'London',
	        data: [3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]
	    }]
	});
</script>

<script>
// 饼状图
Highcharts.chart(
	'pie-chart',
	{
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: '任务比例'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: false
            },
            showInLegend: true
        }
    },
    series: [{
        name: 'Brands',
        colorByPoint: true,
        data: [{
            name: 'Microsoft Internet Explorer',
            y: 56.33
        }, {
            name: 'Chrome',
            y: 24.03,
            sliced: true,
            selected: true
        }, {
            name: 'Firefox',
            y: 10.38
        }, {
            name: 'Safari',
            y: 4.77
        }, {
            name: 'Opera',
            y: 0.91
        }, {
            name: 'Proprietary or Undetectable',
            y: 0.2
        }]
    }]
});
</script>
->
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name = "format-detection" content = "telephone=no">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
	<title>往期排名</title>
	<link rel="stylesheet" type="text/css" href="/media/css/Past_rankings.css">
	<script type="text/javascript" src="/media/js/rem.js"></script>
</head>
<body>
	<div class="Past_rankings">
		<?php if($data): ?>
		<?php foreach ($data as $k => $v):?> 
		<div class="details">
			<div class="list">
				第<span><?php echo $v['period']; ?></span>期
			</div>
			<ul class="rankDer">
				<li class="pastFontsize">
					<span class="pastRank">榜单时间：</span>
					<?php echo $v['starttime']; ?>-<?php echo $v['endtime']; ?>
				</li>
				<li class="pastFontsize">
					<span class="pastRank">增长指数：</span>
					<?php echo $v['inte']; ?>
				</li>
				<li class="pastFontsize">
<!-- 					<span class="pastRank">榜单排名：</span> -->
<!-- 					暂未上榜 -->
				</li> 
			</ul>
		</div>
		<?php endforeach; ?>
		<?php else: ?>
		<p style="height:200px;line-height:200px;text-align:center;background:#fff;font-size;18px;color:#444">暂时没有信息</p>
		<?php endif; ?>
	</div>
</body>
</html>
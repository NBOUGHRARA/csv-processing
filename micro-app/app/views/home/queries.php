<!DOCTYPE html>
<html>
	<head>
		<title>Queries view</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
	</head>
	<body>
		<div class="container">
			<h2>Queries Result</h2>
			<p>These queries has been generated with a memory limit of 128Mo and a max execution time 30 sec</p>  
	        <input type="button" class="btn btn-primary" value="Home" onclick="window.location.href='../home/index'" style="float: right;">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Label</th>
						<th>Result</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Total calls duration after 15/02/2012</td>
						<td><?= $data["callDuration"]["total_time"]; ?></td>
					</tr>
					<tr>
						<td>Total sms number</td>
						<td><?= $data["smsNumber"]["total_sms"]; ?></td>
					</tr>
					<?php foreach ($data["topFacturedData"] as $subscriber => $top_10) { ?>
						<tr>
							<td rowspan="10">Top 10 factured data out of 08h 18h for : <?=$subscriber;?></td>
							<td><?= $top_10[0]; ?></td>
						</tr>
						<?php for($i=1;$i<10;$i++) { ?>
							<tr>
								<td><?= $top_10[$i]; ?></td>
							</tr>
						<?php }
					} ?>
				</tbody>
			</table>
		</div>
	</body>
</html>

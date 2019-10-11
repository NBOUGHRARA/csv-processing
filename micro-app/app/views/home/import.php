<!DOCTYPE html>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

<html>
	<head>
		<title>CSV Processing</title>
	</head>
	<body>
		<div>
			<table class="table">
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col">Billed Account</th>
						<th scope="col">Invoice Number</th>
						<th scope="col">Subscriber Number</th>
						<th scope="col">Date</th>
						<th scope="col">Time</th>
						<th scope="col">Real Duration/Volume</th>
						<th scope="col">Real Duration/Volume</th>
						<th scope="col">Type</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($data as $key => $detail) { ?>
						<tr>
							<th scope="row"><?= $key ?></th>
							<td><?= $detail['billedAccount']; ?></td>
							<td><?= $detail['invoiceNumber']; ?></td>
							<td><?= $detail['subscriberNumber']; ?></td>
							<td><?= $detail['date']; ?></td>
							<td><?= $detail['time']; ?></td>
							<td><?= $detail['realDuration_Volume']; ?></td>
							<td><?= $detail['billedDuration_Volume']; ?></td>
							<td><?= $detail['type']; ?></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
        </div>
	</body>
</html>
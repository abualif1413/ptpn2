<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?php echo $title; ?></title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" content="all,follow">
	
	<style type="text/css">
		@page {
			margin-top: 1cm;
			margin-right: 0.5cm;
			margin-left: 0.5cm;
			margin-bottom: 0.5cm;
			size: a4 landscape;
		}
		.laporan { border-collapse: collapse; font-size: 10pt; }
		.laporan th, td { border-right: solid 1px #d8e3ff; padding: 1px 2px; border-left: solid 1px #d8e3ff; padding: 1px 2px; }
		.laporan tbody tr:nth-last-child(1) td { border-bottom: solid 1px #d8e3ff; padding: 1px 2px; }
		.laporan th { background-color: #F2F2F2; }
		.laporan tbody tr:nth-child(odd) { background-color: #E9E9F4; }
	</style>
</head>
<body>
    <h3 style="padding: 0; margin: 0; text-align: center;">PT. PERKEBUNAN NUSANTARA 2</h3>
    <h3 style="padding: 0; margin: 0; text-align: center;">Laporan Premi Panen Harian</h3>
    <h3 style="padding: 0; margin: 0; text-align: center;">Tanggal : <?php echo $tanggal; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Mandor : <?php echo $nama_mandor; ?></h3>
    <br />
    <table cellspacing="0" cellpadding="0" width="100%" class="laporan" style="font-family: sans-serif;">
    	<thead>
    		<tr>
    			<th rowspan="2" width="30px">No.</th>
    			<th rowspan="2">Nama Pemanen</th>
    			<th rowspan="2">Blok</th>
    			<th rowspan="2" align="right">BT</th>
    			<th rowspan="2" align="right" width="80px">Kg. TBS</th>
    			<th rowspan="2" align="right" width="80px">Kg. Brd</th>
    			<th colspan="4" align="right">Premi TBS</th>
    			<th rowspan="2" width="80px" align="right">Premi BRD</th>
    			<th rowspan="2" width="80px" align="right">Premi Alat</th>
    			<th rowspan="2" width="80px" align="right">Total Premi</th>
    		</tr>
    		<tr>
    			<th width="80px" align="right">P1</th>
    			<th width="80px" align="right">P2</th>
    			<th width="80px" align="right">P3</th>
    			<th width="80px" align="right">Total</th>
    		</tr>
    	</thead>
    	<tbody>
    		<?php
    			foreach ($data as $index => $data) {
					echo "<tr>";
						echo "<td align='right'>" . ($index + 1) . "</td>";
						echo "<td>" . $data["nama_pemanen"] . "</td>";
						echo "<td>" . $data["blok"] . "</td>";
						echo "<td align='right'>" . $data["bt"] . "</td>";
						echo "<td align='right'>" . number_format($data["kg_tbs"], 2) . "</td>";
						echo "<td align='right'>" . number_format($data["kg_brd"], 2) . "</td>";
						echo "<td align='right'>" . number_format($data["tbs_p1"] + $data["tbs_p2"], 2) . "</td>";
						echo "<td align='right'>" . number_format($data["tbs_p3"], 2) . "</td>";
						echo "<td align='right'>" . number_format($data["tbs_p4"], 2) . "</td>";
						echo "<td align='right'>" . number_format($data["hasil_tbs_p"], 2) . "</td>";
						echo "<td align='right'>" . number_format($data["brd_p"], 2) . "</td>";
						echo "<td align='right'>" . number_format($data["premi_alat"], 2) . "</td>";
						echo "<td align='right'>" . number_format($data["total_premi"], 2) . "</td>";
					echo "</tr>";
				}
    		?>
    	</tbody>
    </table>
</body>
</html>
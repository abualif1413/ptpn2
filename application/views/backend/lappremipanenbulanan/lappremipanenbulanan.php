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
			size: 30cm 60cm landscape;
		}
		.laporan { border-collapse: collapse; font-size: 9pt; }
		.laporan th, td { border-right: solid 1px #d8e3ff; padding: 1px 2px; border-left: solid 1px #d8e3ff; padding: 1px 2px; }
		.laporan tbody tr:nth-last-child(1) td { border-bottom: solid 1px #d8e3ff; padding: 1px 2px; }
		.laporan th { background-color: #F2F2F2; }
		.laporan tbody tr:nth-child(odd) { background-color: #E9E9F4; }
	</style>
</head>
<body>
    <h3 style="padding: 0; margin: 0; text-align: center;">PT. PERKEBUNAN NUSANTARA 2</h3>
    <h3 style="padding: 0; margin: 0; text-align: center;">Laporan Premi Panen Bulanan</h3>
    <h3 style="padding: 0; margin: 0; text-align: center;">Bulan : <?php echo $bulan . " " . $tahun; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Mandor : <?php echo $nama_mandor; ?></h3>
    <br />
    <table cellspacing="0" cellpadding="0" width="100%" class="laporan">
    	<thead>
    		<tr>
    			<th width="30px">No.</th>
    			<th>Nama Pemanen</th>
    			<?php
    				foreach ($tanggal_format as $tgl) {
						echo "<th width='60px'>" . $tgl . "</th>";
					}
    			?>
    			<th width="60px">Total</th>
    		</tr>
    	</thead>
    	<tbody>
    		<?php
    			foreach ($data as $no => $data) {
    				echo "<tr>";
						echo "<td>" . ($no + 1) . "</td>";
						echo "<td>" . $data["nama_pemanen"] . "</td>";
						$total = 0;
						foreach ($data["bulanan"] as $premi) {
							$total += $premi;
							echo "<td align='right'>" . number_format($premi, 2, ".", ",") . "</td>";
						}
						echo "<td align='right' style='font-weight: bold'>" . number_format($total, 2, ".", ",") . "</td>";
					echo "</tr>";
				}
    		?>
    	</tbody>
    </table>
</body>
</html>
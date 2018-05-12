<?php include 'header.php';	?>

<h3><span class="glyphicon glyphicon-briefcase"></span>  Data Barang Terjual</h3>
<button style="margin-bottom:20px" data-toggle="modal" data-target="#myModal" class="btn btn-info btn-sm"><span class="glyphicon glyphicon-pencil"></span>  Tambah</button>

<div class="row">
	<form action="" method="get">
		<div class="col-sm-4 col-sm-offset-1">
			<div class="form-group">
				<div class="input-group">
					<span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-calendar"></span></span>
					<input type="text" name="tgl_awal" id="tgl_awal" value="<?php echo isset($_GET['tgl_awal']) ? $_GET['tgl_awal'] : '' ?>" class="form-control" placeholder="Tanggal Awal">
				</div>
			</div>
		</div>

		<div class="col-sm-2 text-center">
			s/d
		</div>

		<div class="col-sm-4">
			<div class="form-group">
				<div class="input-group">
					<span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-calendar"></span></span>
					<input type="text" name="tgl_akhir" id="tgl_akhir" value="<?php echo isset($_GET['tgl_akhir']) ? $_GET['tgl_akhir'] : '' ?>" class="form-control" placeholder="Tanggal Akhir">
				</div>
			</div>
		</div>

		<div class="col-sm-12 text-center">
			<button type="submit" class="btn btn-sm btn-primary"><li class="glyphicon glyphicon-search"></li> Cari Data</button>
		</div>
	</form>
</div>

<?php
$tg="lap_barang_laku.php";
$judul = "Data Penjualan";
$brg=mysql_query("SELECT * FROM barang_laku ORDER BY tanggal DESC");
$pemasukan=mysql_fetch_array(mysql_query("SELECT sum(total_harga) as total from barang_laku"));
$laba=mysql_fetch_array(mysql_query("SELECT sum(laba) as total from barang_laku"));

if((isset($_GET['tgl_awal']) && $_GET['tgl_awal'] != '') && (isset($_GET['tgl_akhir']) && $_GET['tgl_akhir'] != '') ){
	$tgl_awal=$_GET['tgl_awal'];
	$tgl_akhir=$_GET['tgl_akhir'];
	$brg=mysql_query("SELECT * FROM barang_laku WHERE tanggal >= '$tgl_awal' AND tanggal <= '$tgl_akhir' ORDER BY tanggal DESC");
	$pemasukan=mysql_fetch_array(mysql_query("SELECT sum(total_harga) as total from barang_laku where tanggal >= '$tgl_awal' AND tanggal <= '$tgl_akhir'"));
	$laba=mysql_fetch_array(mysql_query("SELECT sum(laba) as total from barang_laku where tanggal >= '$tgl_awal' AND tanggal <= '$tgl_akhir'"));
	$tg="lap_barang_laku.php?tgl_awal=$tgl_awal&tgl_akhir=$tgl_akhir";
	$judul = "Data Penjualan Tanggal ".date('d F Y', strtotime($tgl_awal))." - ".date('d F Y', strtotime($tgl_akhir));
}else if(isset($_GET['tgl_awal']) && $_GET['tgl_awal'] != ''){
	$tgl_awal=mysql_real_escape_string($_GET['tgl_awal']);
	$brg=mysql_query("SELECT * FROM barang_laku WHERE tanggal >= '$tgl_awal' ORDER BY tanggal DESC");
	$pemasukan=mysql_fetch_array(mysql_query("SELECT sum(total_harga) as total from barang_laku where tanggal >= '$tgl_awal'"));
	$laba=mysql_fetch_array(mysql_query("SELECT sum(laba) as total from barang_laku where tanggal >= '$tgl_awal'"));
	$tg="lap_barang_laku.php?tgl_awal=$tgl_awal";
	$judul = "Data Penjualan Tanggal ".date('d F Y', strtotime($tgl_awal));
}else if(isset($_GET['tgl_akhir']) && $_GET['tgl_akhir'] != ''){
	$tgl_akhir=mysql_real_escape_string($_GET['tgl_akhir']);
	$brg=mysql_query("SELECT * FROM barang_laku WHERE tanggal <= '$tgl_akhir' ORDER BY tanggal DESC");
	$pemasukan=mysql_fetch_array(mysql_query("SELECT sum(total_harga) as total from barang_laku where tanggal <= '$tgl_akhir'"));
	$laba=mysql_fetch_array(mysql_query("SELECT sum(laba) as total from barang_laku where tanggal <= '$tgl_akhir'"));
	$tg="lap_barang_laku.php?tgl_akhir=$tgl_akhir";
	$judul = "Data Penjualan Tanggal ".date('d F Y', strtotime($tgl_akhir));
}
?>

<div class="row">
	<div class="col-sm-6">
		<h4><?php echo $judul ?></h4>
	</div>
	<div class="col-sm-6">
		<a style="margin-bottom:10px" href="<?php echo $tg ?>" target="_blank" class="btn btn-default btn-sm pull-right"><span class='glyphicon glyphicon-print'></span>  Cetak</a>
	</div>
</div>

<table class="table table-hover table-striped table-bordered">
	<tr>
		<th>No</th>
		<th>Tanggal</th>
		<th>Nama Barang</th>
		<th>Harga Per Biji</th>
		<th>Total Harga</th>
		<th>Jumlah</th>			
		<th>Laba</th>			
		<th>Opsi</th>
	</tr>
	<?php 
	$no=1;
	while($b=mysql_fetch_array($brg)){

		?>
		<tr>
			<td><?php echo $no++ ?></td>
			<td><?php echo $b['tanggal'] ?></td>
			<td><?php echo $b['nama'] ?></td>
			<td>Rp.<?php echo number_format($b['harga']) ?>,-</td>
			<td>Rp.<?php echo number_format($b['total_harga']) ?>,-</td>
			<td><?php echo $b['jumlah'] ?></td>			
			<td><?php echo "Rp.".number_format($b['laba']).",-"?></td>			
			<td>		

				<a onclick="if(confirm('Apakah anda yakin ingin menghapus data ini ??')){ location.href='hapus_laku.php?id=<?php echo $b['id']; ?>&jumlah=<?php echo $b['jumlah'] ?>&nama=<?php echo $b['nama']; ?>' }" class="btn btn-danger btn-xs">Hapus</a>
			</td>
		</tr>

		<?php 
	}
	?>
	<tr>
		<td colspan="6">Total Pemasukan</td>
		<td colspan="2" align="center"><strong>Rp. <?php echo number_format($pemasukan['total']) ?></strong></td>
	</tr>
	<tr>
		<td colspan="6">Total Laba</td>
		<td colspan="2" align="center"><strong>Rp. <?php echo number_format($laba['total']) ?></strong></td>
	</tr>
</table>

<!-- modal input -->
<div id="myModal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Tambah Penjualan
				</div>
				<div class="modal-body">				
					<form action="barang_laku_act.php" method="post">
						<div class="form-group">
							<label>Tanggal</label>
							<input name="tgl" type="text" class="form-control" id="tgl" autocomplete="off">
						</div>	
						<div class="form-group">
							<label>Nama Barang</label>
							<select name="nama" type="text" class="form-control" id="nama" onchange="changeValue(this.value)" autocomplete="off">
								<option value=0>-Pilih-</option>
								<?php
								$result = mysql_query("select * from barang");
								$jsArray = "var hg = new Array();\n";
								while ($row = mysql_fetch_array($result)) {
									echo '<option value="' . $row['nama'] . '">' . $row['nama'] . '</option>';
									$jsArray .= "hg['".$row['nama']."'] = {harga:'".addslashes($row['harga'])."'};\n";
								}
								?>
							</select>
						</div>									
						<div class="form-group">
							<label>Harga Jual / unit</label>
							<input name="harga" id="harga" type="text" class="form-control" placeholder="Harga" autocomplete="off">
						</div>	
						<div class="form-group">
							<label>Jumlah</label>
							<input name="jumlah" type="text" class="form-control" placeholder="Jumlah" autocomplete="off">
						</div>																	

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
						<input type="reset" class="btn btn-danger" value="Reset">												
						<input type="submit" class="btn btn-primary" value="Simpan">
					</div>
				</form>
			</div>
		</div>
	</div>


	<script type="text/javascript">
		$(document).ready(function(){
			$("#tgl_awal").datepicker({dateFormat : 'yy-mm-dd'});
			$("#tgl_akhir").datepicker({dateFormat : 'yy-mm-dd'});
		});
	</script>
	<script type="text/javascript">    
		<?php
		echo $jsArray;
		?>  
		function changeValue(nama){  
			document.getElementById('harga').value = hg[nama].harga;  

		};  
	</script>

	<script>
		$( function() {
			$( "#datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
			$( "#datepicker2" ).datepicker({ dateFormat: 'yy-mm-dd' });
		} );
	</script>
	<?php include 'footer.php'; ?>
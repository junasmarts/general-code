<?php

include 'config.php';
require('../assets/pdf/fpdf.php');

$judul = "";
$brg=mysql_query("SELECT * FROM barang_laku ORDER BY tanggal DESC");
$pemasukan=mysql_fetch_array(mysql_query("SELECT sum(total_harga) as total from barang_laku"));
$laba=mysql_fetch_array(mysql_query("SELECT sum(laba) as total from barang_laku"));

if((isset($_GET['tgl_awal']) && $_GET['tgl_awal'] != '') && (isset($_GET['tgl_akhir']) && $_GET['tgl_akhir'] != '') ){
	$tgl_awal=mysql_real_escape_string($_GET['tgl_awal']);
	$tgl_akhir=mysql_real_escape_string($_GET['tgl_akhir']);
	$brg=mysql_query("SELECT * FROM barang_laku WHERE tanggal >= '$tgl_awal' AND tanggal <= '$tgl_akhir' ORDER BY tanggal DESC");
	$pemasukan=mysql_fetch_array(mysql_query("SELECT sum(total_harga) as total from barang_laku where tanggal >= '$tgl_awal' AND tanggal <= '$tgl_akhir'"));
	$laba=mysql_fetch_array(mysql_query("SELECT sum(laba) as total from barang_laku where tanggal >= '$tgl_awal' AND tanggal <= '$tgl_akhir'"));
	$judul = "Periode Tanggal ".date('d F Y', strtotime($tgl_awal))." - ".date('d F Y', strtotime($tgl_akhir));
}else if(isset($_GET['tgl_awal']) && $_GET['tgl_awal'] != ''){
	$tgl_awal=mysql_real_escape_string($_GET['tgl_awal']);
	$brg=mysql_query("SELECT * FROM barang_laku WHERE tanggal >= '$tgl_awal' ORDER BY tanggal DESC");
	$pemasukan=mysql_fetch_array(mysql_query("SELECT sum(total_harga) as total from barang_laku where tanggal >= '$tgl_awal'"));
	$laba=mysql_fetch_array(mysql_query("SELECT sum(laba) as total from barang_laku where tanggal >= '$tgl_awal'"));
	$judul = "Periode Tanggal ".date('d F Y', strtotime($tgl_awal));
}else if(isset($_GET['tgl_akhir']) && $_GET['tgl_akhir'] != ''){
	$tgl_akhir=mysql_real_escape_string($_GET['tgl_akhir']);
	$brg=mysql_query("SELECT * FROM barang_laku WHERE tanggal <= '$tgl_akhir' ORDER BY tanggal DESC");
	$pemasukan=mysql_fetch_array(mysql_query("SELECT sum(total_harga) as total from barang_laku where tanggal <= '$tgl_akhir'"));
	$laba=mysql_fetch_array(mysql_query("SELECT sum(laba) as total from barang_laku where tanggal <= '$tgl_akhir'"));
	$judul = "Periode Tanggal ".date('d F Y', strtotime($tgl_akhir));
}


$pdf = new FPDF("L","cm","A4");

$pdf->SetMargins(2,1,1);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','B',11);
$pdf->Image('../logo/malasngoding.png',1,1,2,2);
$pdf->SetX(4);            
$pdf->MultiCell(19.5,0.5,'LAIS SHOES & BAG',0,'L');
$pdf->SetX(4);
$pdf->MultiCell(19.5,0.5,'Telpon : 081XXXXXXX',0,'L');    
$pdf->SetFont('Arial','B',10);
$pdf->SetX(4);
$pdf->MultiCell(19.5,0.5,'JL. Pertokoan Narmada',0,'L');
$pdf->SetX(4);
$pdf->MultiCell(19.5,0.5,'83371',0,'L');
$pdf->Line(1,3.1,28.5,3.1);
$pdf->SetLineWidth(0.1);      
$pdf->Line(1,3.2,28.5,3.2);   
$pdf->SetLineWidth(0);
$pdf->ln(1);
$pdf->SetFont('Arial','',9);
$pdf->Cell(0,0,"Di cetak pada : ".date("D-d/m/Y"),0,0,'R');
$pdf->ln(1);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,0.7,'Laporan Penjualan Barang',0,0,'C');
$pdf->ln(1);
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,0.5,$judul,0,0,'C');
$pdf->ln(1);
$pdf->Cell(1, 0.8, 'NO', 1, 0, 'C');
$pdf->Cell(3, 0.8, 'Tanggal', 1, 0, 'C');
$pdf->Cell(6, 0.8, 'Nama Barang', 1, 0, 'C');
$pdf->Cell(3, 0.8, 'Jumlah', 1, 0, 'C');
$pdf->Cell(4, 0.8, 'Harga (Rp)', 1, 0, 'C');
$pdf->Cell(4.5, 0.8, 'Total Harga (Rp)', 1, 0, 'C');
$pdf->Cell(4, 0.8, 'Laba (Rp)', 1, 1, 'C');

$no=1;
while($lihat=mysql_fetch_array($brg)){
	$pdf->Cell(1, 0.8, $no , 1, 0, 'C');
	$pdf->Cell(3, 0.8, $lihat['tanggal'],1, 0, 'C');
	$pdf->Cell(6, 0.8, $lihat['nama'],1, 0, 'C');
	$pdf->Cell(3, 0.8, $lihat['jumlah'], 1, 0,'C');
	$pdf->Cell(4, 0.8, number_format($lihat['harga']).",-", 1, 0,'C');
	$pdf->Cell(4.5, 0.8, number_format($lihat['total_harga']).",-",1, 0, 'C');
	$pdf->Cell(4, 0.8, number_format($lihat['laba']).",-", 1, 1,'C');	
	$no++;
}

$pdf->SetFont('Arial','B',10);
// select sum(total_harga) as total from barang_laku where tanggal='$tanggal'
$pdf->Cell(17, 0.8, "Total (Rp)", 1, 0,'C');		
$pdf->Cell(4.5, 0.8, number_format($pemasukan['total']).",-", 1, 0,'C');	

// select sum(total_harga) as total from barang_laku where tanggal='$tanggal'
$pdf->Cell(4, 0.8, number_format($laba['total']).",-", 1, 1,'C');	
$pdf->Output($judul.".pdf","I");

?>


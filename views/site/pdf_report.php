<?php 
	if($dataProvider->getModels() != null){ ?>
			<table border="1" style="font-size:12px;border-collapse:collapse;border: 1px solid black;padding-top:2px;padding-left:2px;padding-right:2px;padding-bottom:2px">
				<tr>
					<td width="30" align="center"><b>NO</b></td>
					<td width="100" align="center"><b>Tanggal Kinerja</b></td>
					<td width="400" align="center"><b>Tugas</b></td>
					<td width="30" align="center"><b>Jumlah</b></td>
					<td width="400" align="center"><b>Deskripsi</b></td>
				</tr>
				<?php 
				$no = 1;
				foreach($dataProvider->getModels() as $val){ ?>
					<tr>
						<td align="center"><?php echo $no;?></td>
						<td align="center"><?php echo date('d-m-Y', strtotime($val->tanggal_kinerja));?></td>
						<td><?php echo $val->tugas->nama_tugas;?></td>
						<td align="center"><?php echo $val->jumlah;?></td>
						<td><?php echo $val->deskripsi;?></td>
					</tr>
				<?php $no++;} ?>
			</table>
			<div class="page-break"></div>
			<table border="1" style="font-size:12px;border-collapse:collapse;border: 1px solid black;padding-top:2px;padding-left:2px;padding-right:2px;padding-bottom:2px;margin-top: 20px;">
				<tr>
					<td width="30" align="center" ><b>NO</b></td>
					<td width="400"><b>Rekapitulasi Logbook</b></td>
					<td width="30" align="center"><b>Jumlah</b></td>
				</tr>
				<?php 
				$no = 1;
				foreach($dataProvider_2->getModels() as $row){ ?>
					<tr>
						<td align="center"><?php echo $no;?></td>
						<td><?php echo $row->nama_tugas;?></td>
						<td align="center"><?php echo $row->jumlah;?></td>
					</tr>
				<?php $no++;} ?>
			</table>
			<table style="font-size:12px;border-collapse:collapse;margin-top: 20px;">
				<tr>
					<td width="200">Pegawai Yang Dinilai</td>
					<td width="200"></td>
					<td>Klaten, <?php echo date('d F Y');?></td>
					<td width="200"></td>
					<td>Atasan Pejabat Penilai</td>
				</tr>
				<tr>
					<td></td>
					<td width="200"></td>
					<td></td>
					<td width="200"></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td width="200"></td>
					<td></td>
					<td width="200"></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td width="200"></td>
					<td></td>
					<td width="200"></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td width="200"></td>
					<td></td>
					<td width="200"></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td width="200"></td>
					<td></td>
					<td width="200"></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td width="200"></td>
					<td></td>
					<td width="200"></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td width="200"></td>
					<td></td>
					<td width="200"></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td width="200"></td>
					<td></td>
					<td width="200"></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td width="200"></td>
					<td></td>
					<td width="200"></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td width="200"></td>
					<td></td>
					<td width="200"></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td width="200"></td>
					<td></td>
					<td width="200"></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td width="200"></td>
					<td></td>
					<td width="200"></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td width="200"></td>
					<td></td>
					<td width="200"></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td width="200"></td>
					<td></td>
					<td width="200"></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td width="200"></td>
					<td></td>
					<td width="200"></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td width="200"></td>
					<td></td>
					<td width="200"></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $user->pegawai->nama;?></td>
					<td width="200"></td>
					<td>
						<?php 
							if($penilai!= null){
								echo $penilai->penilai->nama;
							}
						?>
					</td>
					<td width="200"></td>
					<td>Rohmiyati, SE</td>
				</tr>
				<tr>
					<td>NIK/NIP. <?php echo $user->pegawai->nip;?></td>
					<td width="200"></td>
					<td>
						<?php 
							if($penilai!= null){
								echo 'NIP. '.$penilai->penilai->nip;
							}
						?>
					</td>
					<td width="200"></td>
					<td>NIP.196802261998032002</td>
				</tr>
			</table>
		
<?php
	}
?>
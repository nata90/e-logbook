<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
$this->title = Yii::t('app', 'Dashboard');

$this->registerJs('var url_cek_profile = "' . Url::to(['app/user/cekprofile']) . '";');
$this->registerJs("
	$.ajax({
      url: url_cek_profile,
      dataType: 'json',
      success: function (v) {
      	if(v.update == 0){
        	notifikasi('Profil anda belum update, silahkan cek dan simpan lagi profile anda','info');
      	}
      }
    });
");
?>
<div class="row">
	<?php Pjax::begin([
        'id'=>'data-rekap-kinerja',
        'timeout'=>false,
        'enablePushState'=>false,
        'clientOptions'=>['method'=>'GET'],
        'options'=>[
            'class'=>'yii-gridview',
        ],
    ]); ?>
	<div class="col-md-12">
		<div class="box box-danger">
			<div class="box-body">
				<?php echo $this->render('_search_staff', ['model' => $searchModel]); ?>
			</div>
		</div>
	</div>
	
	<?php /*<div class="col-lg-6 col-xs-6">
		<div class="info-box bg-green">
			<span class="info-box-icon">
				<i class="ion ion-ios-pricetag-outline"></i>
			</span>
			<div class="info-box-content">
				<span class="info-box-text">Logbook</span>
				<span class="info-box-number"><?php echo $total_logbook;?></span>
				<div class="progress">
					<div class="progress-bar" style="width: <?php echo $persen_logbook;?>%;"></div>
				</div>
				<span class="progress-description"><?php echo $approve_logbook;?> disetujui, <?php echo $notapprove_logbook;?> belum disetujui (<?php echo $persen_logbook;?>%)</span>
			</div>
		</div>
	</div>
	<div class="col-lg-6 col-xs-6">
		<div class="info-box bg-red">
			<span class="info-box-icon">
				<i class="ion ion-ios-pricetag-outline"></i>
			</span>
			<div class="info-box-content">
				<span class="info-box-text">Target</span>
				<span class="info-box-number"><?php echo $target;?></span>

				<div class="progress">
					<div class="progress-bar" style="width: <?php echo $persen_capaian;?>%;"></div>
				</div>
				<span class="progress-description">Capaian target <?php echo $total_rekap;?> (<?php echo $persen_capaian;?>%)</span>
			</div>
		</div>
	</div>*/ ?>
	
	<div class="col-md-4">
		<div class="box box-danger">
			<div class="box-body box-profile">
				<?php 
					if($user->pegawai->gender == 0){
	                    echo '<img src="'.Yii::$app->request->baseUrl.'/images/avatar5.png" class="profile-user-img img-responsive img-circle" alt="User Image"/>';
	                }else{
	                    echo '<img src="'.Yii::$app->request->baseUrl.'/images/avatar3.png" class="profile-user-img img-responsive img-circle" alt="User Image"/>';
	                }
				?>
				<h3 class="profile-username text-center"><?php echo $user->pegawai->nama;?></h3>
				
				<ul class="list-group list-group-unbordered">
					<li class="list-group-item"><b>NIP / NIK</b><a class="pull-right"><?php echo $user->pegawai->nip;?></a></li>
					<li class="list-group-item"><b>Jabatan</b><a class="pull-right">
						<?php 
						if($jab_pegawai != null){
							echo $jab_pegawai->jabatan->nama_jabatan;
						}else{
							echo '-';
						}

						?>
						</a></li>
					<li class="list-group-item"><b>Target</b><a class="pull-right"><?php echo $target;?></a></li>
					<li class="list-group-item"><b>Penilai</b><a class="pull-right"><?php 
						if($jab_pegawai != null){
								echo $jab_pegawai->penilai->nama;
							}else{
								echo '-';
							}
					?></a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="col-md-5">
		<div class="info-box bg-yellow">
			<span class="info-box-icon"><i class="ion ion-ios-pricetag-outline"></i></span>
			<div class="info-box-content">
				<span class="info-box-text">Logbook</span>
				<span class="info-box-number"><?php echo $total_logbook;?></span>
				<div class="progress">
					<div class="progress-bar" style="width: <?php echo $persen_logbook;?>%"></div>
				</div>
				<span class="progress-description"><?php echo $approve_logbook;?> disetujui, <?php echo $notapprove_logbook;?> belum disetujui (<?php echo $persen_logbook;?>%)</span>
			</div>
		</div>
	</div>
	<div class="col-md-5">
		<div class="info-box bg-green">
			<span class="info-box-icon"><i class="ion ion-ios-pricetag-outline"></i></span>
			<div class="info-box-content">
				<span class="info-box-text">Target</span>
				<span class="info-box-number"><?php echo $target;?></span>
				<div class="progress">
					<div class="progress-bar" style="width: <?php echo $persen_capaian;?>%"></div>
				</div>
				<span class="progress-description">Capaian target <?php echo $total_rekap;?> (<?php echo $persen_capaian;?>%)</span>
			</div>
		</div>
	</div>
	<div class="col-md-5">
		<div class="info-box bg-red">
			<span class="info-box-icon"><i class="ion ion-ios-pricetag-outline"></i></span>
			<div class="info-box-content">
				<span class="info-box-text">Hari Kerja</span>
				<span class="info-box-number"><?php echo $hari_kerja;?></span>
				<div class="progress">
					<div class="progress-bar" style="width: 100%"></div>
				</div>
				<span class="progress-description">Jumlah hari kerja <?php echo $hari_kerja;?></span>
			</div>
		</div>
	</div>
	<div class="col-md-12">
		<div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Logbook Pribadi</a></li>
				<li><a href="#tab_2" data-toggle="tab" aria-expanded="true">Rekap Logbook</a></li>
				<li><a href="#tab_3" data-toggle="tab" aria-expanded="true">Kinerja Staff</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
					
					<?= GridView::widget([
						
	                    'dataProvider' => $dataProvider,
	                    //'filterModel' => $searchModel,
	                    'summary' => '',
	                    'columns' => [
	                        ['class' => 'yii\grid\SerialColumn'],
	                        [
	                            'label'=>'Tanggal Kinerja',
	                            'format'=>'raw',
	                            'value'=>function($model){
	                                return date('d-m-Y', strtotime($model->tanggal_kinerja));
	                            }
	                        ],
	                        /*[
	                            'label'=>'Nama Pegawai',
	                            'format'=>'raw',
	                            'value'=>function($model){
	                                return $model->pegawai->nama;
	                            }
	                        ],*/
	                        [
	                            'label'=>'Tugas',
	                            'format'=>'raw',
	                            'value'=>function($model){
	                                return $model->tugas->nama_tugas;
	                            }
	                        ],
	                        [
	                            'label'=>'jumlah',
	                            'format'=>'raw',
	                            'value'=>function($model){
	                                return $model->jumlah;
	                            }
	                        ],
	                        [
	                            'label'=>'Deskripsi',
	                            'format'=>'raw',
	                            'value'=>function($model){
	                                return $model->deskripsi;
	                            }
	                        ],
	                        [
	                            'label'=>'Approval',
	                            'format'=>'raw',
	                            'value'=>function($model){
	                                if($model->approval == 1){
	                                    return '<span class="label label-success"> Approved</span>'; 
	                                }else{
	                                   return '<span class="label label-danger">Not Approve</span>'; 
	                                }
	                                
	                            }
	                        ],
	                    ],
	                ]); ?>
				</div>
				<div class="tab-pane" id="tab_2">
					<?= GridView::widget([
	                    'dataProvider' => $dataProvider5,
	                    //'filterModel' => $searchModel,
	                    'summary' => '',
	                    'showFooter'=>TRUE,
	                    'emptyText' => 'Rekap logbook anda masih kosong',
	                    'columns' => [
	                        ['class' => 'yii\grid\SerialColumn'],
	                        
	                        [
			                    'label'=>'Kategori',
			                    'format'=>'raw',
			                    'value'=>function($model){
			                        return $model->nama_kategori;
			                    },
			                ],
			                [
			                    'label'=>'Jumlah',
			                    'format'=>'raw',
			                    'value'=>function($model){
			                        return $model->jumlah;
			                    },
			                ],
			                [
			                    'label'=>'Poin',
			                    'format'=>'raw',
			                    'value'=>function($model){
			                        return round($model->poin_kategori,2);
			                    },
			                ],
			                [
			                    'label'=>'Total',
			                    'format'=>'raw',
			                    'value'=>function($model){
			                        return $model->jumlah * $model->poin_kategori;
			                    },
			                    'footer' => $total_rekap,
			                ],
	                    ],
	                ]); ?>
				</div>
				<div class="tab-pane" id="tab_3">
					<?= GridView::widget([
	                    'dataProvider' => $dataStaff,
	                    //'filterModel' => $searchModel,
	                    'summary' => '',
	                    'emptyText' => 'Anda tidak memiliki staff !!',
	                    'columns' => [
	                        ['class' => 'yii\grid\SerialColumn'],
	                        
	                        [
	                            'label'=>'Nama Staff',
	                            'format'=>'raw',
	                            'value'=>function($model){
	                                return $model->pegawai->nama;
	                            }
	                        ],
	                        [
	                        	'label'=>'',
	                        	'format'=>'raw',
	                            'value'=>function($model){
	                                return '<button type="button" class="btn bg-olive btn-flat margin btn-xs rekap-peg" url="'.Url::to(['site/excelrekap', 'id'=>$model->id_pegawai]).'">Rekap (.xls)</button>&nbsp<button type="button" class="btn bg-maroon btn-flat margin btn-xs logbook-peg" url="'.Url::to(['site/excellogbook', 'id'=>$model->id_pegawai]).'">Logbook (.xls)</button>';
	                            }
	                        ]
	                    ],
	                ]); ?>
				</div>
			</div>
		</div>
		
	</div>
	<?php Pjax::end(); ?>
</div>
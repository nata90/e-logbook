<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\app\models\AppUser;
use app\modules\pegawai\models\DataPegawai;
use app\components\Utility;

$this->title = Yii::t('app', 'Dashboard');
$this->registerJsFile(Yii::$app->request->BaseUrl . '/js/chart.js');
$this->registerJs('var url_cek_profile = "' . Url::to(['app/user/cekprofile']) . '";');
$this->registerJs('var url_load_chart = "' . Url::to(['site/loadchart']) . '";');
$this->registerJs("
	$(document).on('ready pjax:success', function() {
		var rangedate = $('#reservation').val();
		$.ajax({
		      url: url_load_chart,
		      dataType: 'json',
		      data:{'rangedate':rangedate},
		      type:'GET',
		      success: function (v) {
		      	var ctx = document.getElementById('pieChart').getContext('2d');
				var chart = new Chart(ctx, {
				    type: 'doughnut',
				    data: {
				    	labels: v.kategori,
				        datasets: [{
				            label: 'Jumlah per kategori',
				            backgroundColor: [
				            	'rgba(176, 69, 137, 1)',
				                'rgba(64, 132, 191, 1)',
				                'rgba(169, 129, 213, 1)',
				                'rgba(129, 213, 132, 1)',
				                'rgba(255, 99, 132, 1)',
				                'rgba(54, 162, 235, 1)',
				                'rgba(255, 206, 86, 1)',
				                'rgba(75, 192, 192, 1)',
				                'rgba(153, 102, 255, 1)',
				                'rgba(255, 159, 64, 1)',
				                'rgba(72, 176, 69, 1)',
				                
				            ],
				            borderColor: [
				            	'rgba(176, 69, 137, 1)',
				                'rgba(64, 132, 191, 1)',
				                'rgba(169, 129, 213, 1)',
				                'rgba(129, 213, 132, 1)',
				                'rgba(255, 99, 132, 1)',
				                'rgba(54, 162, 235, 1)',
				                'rgba(255, 206, 86, 1)',
				                'rgba(75, 192, 192, 1)',
				                'rgba(153, 102, 255, 1)',
				                'rgba(255, 159, 64, 1)',
				                'rgba(72, 176, 69, 1)',
				                
				            ],
				            borderWidth: 1,
				            data: v.data
				        }]
				    }
				});
		      }
		    });

	});

	$.ajax({
      url: url_cek_profile,
      dataType: 'json',
      success: function (v) {
      	if(v.update == 0){
        	notifikasi('Profil anda belum update, silahkan cek dan simpan lagi profile anda','info');
      	}
      }
    });

    $.ajax({
      url: url_load_chart,
      dataType: 'json',
      'type':'GET',
      success: function (v) {
      	var ctx = document.getElementById('pieChart').getContext('2d');
		var chart = new Chart(ctx, {
		    type: 'doughnut',
		    data: {
		    	labels: v.kategori,
		        datasets: [{
		            label: 'Jumlah per kategori',
		            backgroundColor: [
		            	'rgba(176, 69, 137, 1)',
		                'rgba(64, 132, 191, 1)',
		                'rgba(169, 129, 213, 1)',
		                'rgba(129, 213, 132, 1)',
		                'rgba(255, 99, 132, 1)',
		                'rgba(54, 162, 235, 1)',
		                'rgba(255, 206, 86, 1)',
		                'rgba(75, 192, 192, 1)',
		                'rgba(153, 102, 255, 1)',
		                'rgba(255, 159, 64, 1)',
		                'rgba(72, 176, 69, 1)',
		                
		            ],
		            borderColor: [
		            	'rgba(176, 69, 137, 1)',
		                'rgba(64, 132, 191, 1)',
		                'rgba(169, 129, 213, 1)',
		                'rgba(129, 213, 132, 1)',
		                'rgba(255, 99, 132, 1)',
		                'rgba(54, 162, 235, 1)',
		                'rgba(255, 206, 86, 1)',
		                'rgba(75, 192, 192, 1)',
		                'rgba(153, 102, 255, 1)',
		                'rgba(255, 159, 64, 1)',
		                'rgba(72, 176, 69, 1)',
		                
		            ],
		            borderWidth: 1,
		            data: v.data
		        }]
		    }
		});
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
				<p class="text-muted text-center"><?php echo $peg_unit_kerja->unitKerja->nama_unit_kerja;?></p>
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
		<div class="col-md-12">
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
		<div class="col-md-12">
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
		<div class="col-md-12">
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
	</div>
	<div class="col-md-3">
		<div class="box box-danger">
			<div class="box-header with-border">
				<h3 class="box-title">Chart Kategori</h3>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-md-10">
						<canvas id="pieChart" height="390" width="400" style="width: 200px; height: 195px;"></canvas>
					</div>
				</div>
			</div>
		</div>
		
	</div>
	<div class="col-md-12">
		<div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
				<li id="logbook-id" class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Logbook Pribadi</a></li>
				<li><a href="#tab_2" data-toggle="tab" aria-expanded="true">Rekap Per Kategori</a></li>
				<li><a href="#tab_4" data-toggle="tab" aria-expanded="true">Rekap Per Tugas</a></li>
				<li id="kin-staff"><a href="#tab_3" data-toggle="tab" aria-expanded="true">Kinerja Staff</a></li>
				<li><a href="#tab_5" data-toggle="tab" aria-expanded="true">Presensi</a></li>
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
	                    'emptyText' => 'Rekap per kategori anda masih kosong',
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
	                    'filterModel' => $search_staff,
	                    'summary' => '',
	                    'emptyText' => 'Anda tidak memiliki staff !!',
	                    'columns' => [
	                        ['class' => 'yii\grid\SerialColumn'],
	                        
	                        [
	                            'attribute'=>'nama',
	                            'format'=>'raw',
	                            'value'=>function($model){
	                                return $model->pegawai->nama;
	                            },
	                            'filter'=>Html::activeInput('text', $search_staff, 'nama', ['class' => 'form-control'])
	                        ],
	                        [
	                            'label'=>'Jabatan',
	                            'format'=>'raw',
	                            'value'=>function($model){
	                                return $model->jabatan->nama_jabatan;
	                            },
	                            'filter'=>Html::activeDropDownList($search_staff, 'id_jabatan',$list_jabatan,['class'=>'form-control','prompt'=>''])
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
				<div class="tab-pane" id="tab_4">
					<?= GridView::widget([
	                    'dataProvider' => $dataProvider6,
	                    //'filterModel' => $searchModel,
	                    'summary' => '',
	                    'showFooter'=>TRUE,
	                    'emptyText' => 'Rekap per tugas anda masih kosong',
	                    'columns' => [
	                        ['class' => 'yii\grid\SerialColumn'],
	                        
	                        [
			                    'label'=>'Tugas',
			                    'format'=>'raw',
			                    'value'=>function($model){
			                        return $model->nama_tugas;
			                    },
			                ],
			                [
			                    'label'=>'Jumlah',
			                    'format'=>'raw',
			                    'value'=>function($model){
			                        return $model->jumlah;
			                    },
			                    'footer' => $total_jumlah,
			                ],
			                
	                    ],
	                ]); ?>
				</div>
				<div class="tab-pane" id="tab_5">
					<?= GridView::widget([
	                    'dataProvider' => $dataProviderPresensi,
	                    //'filterModel' => $searchModel,
	                    'summary' => '',
	                    'showFooter'=>TRUE,
	                    'emptyText' => 'Presensi anda masih kosong',
	                    'columns' => [
	                        ['class' => 'yii\grid\SerialColumn'],
	                        [
	                        	'label'=>'Status WFH / WFO',
	                        	'format'=>'raw',
	                        	'value'=>function($model){
			                        return $model->sn;
			                    },
	                        ],
	                        [
	                        	'label'=>'Scan Date',
	                        	'format'=>'raw',
	                        	'value'=>function($model){
			                        return date('d-m-Y H:i:s', strtotime($model->scan_date));
			                    },
	                        ],
	                        [
	                        	'label'=>'Status Presensi',
	                        	'format'=>'raw',
	                        	'value'=>function($model){
	                        		if($model->inoutmode == 0){
	                        			return '<small class="label label-success">Masuk</small>';
	                        		}else{
	                        			return '<small class="label label-danger">Pulang</small>';
	                        		}
			                        
			                    },
	                        ],
			                [
	                        	'label'=>'Status',
	                        	'format'=>'raw',
	                        	'value'=>function($model){
	                        		if($model->inoutmode == 0){
	                        			$id_user = Yii::$app->user->id;
        								$user = AppUser::findOne($id_user);

	                        			$date_masuk = date_create($model->scan_date);
		                        		$date_default = date_create(date('Y-m-d', strtotime($model->scan_date)).' '.$user->pegawai->jam_masuk);

		                        		if($date_masuk > $date_default){
		                        			$diff    = date_diff($date_masuk,$date_default);
		                        			$menit = $diff->h * 60;
		                        			$total_menit = $menit + $diff->i;

		                        			if($diff->i == 0){
		                        				return '-';
		                        			}else{
		                        				
		                        				return '<small class="label label-danger">Terlambat : '.$total_menit.' menit</small>';
		                        			}
		                        			
		                        		}else{
		                        			return '-';
		                        		}
		                        		

		                        		
	                        		}else{
	                        			return '-';
	                        		}
	                        		
			                    },
	                        ],
	                    ],
	                ]); ?>
				</div>
			</div>
		</div>
		
	</div>
	<?php Pjax::end(); ?>
</div>
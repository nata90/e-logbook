<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

?>
<div class="row">
	<?php Pjax::begin([
        'id'=>'data-rekap-kinerja',
        'timeout'=>false,
        'enablePushState'=>false,
        'clientOptions'=>['method'=>'GET']
    ]); ?>
	<div class="col-md-12">
		<div class="box box-danger">
			<div class="box-body">
				<?php echo $this->render('_search_staff', ['model' => $searchModel]); ?>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-xs-6">
		<div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo $total_logbook;?></h3>

              <p>Total</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="#" class="small-box-footer">Logbook yg sudah dientri <i class="fa fa-arrow-circle-right"></i></a>
        </div>
	</div>
	<div class="col-lg-3 col-xs-6">
		<div class="small-box bg-green">
            <div class="inner">
              <h3><?php echo $approve_logbook;?></h3>

              <p>Approved</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="#" class="small-box-footer">Logbook yang sudah disetujui <i class="fa fa-arrow-circle-right"></i></a>
        </div>
	</div>
	<div class="col-lg-3 col-xs-6">
		<div class="small-box bg-red">
            <div class="inner">
              <h3><?php echo $notapprove_logbook;?></h3>

              <p>Not Approve</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer">Logbook yang belum disetujui <i class="fa fa-arrow-circle-right"></i></a>
        </div>
	</div>
	<div class="col-lg-3 col-xs-6">
		<div class="small-box bg-yellow">
            <div class="inner">
              <h3><?php echo $persen;?><sup style="font-size: 20px">%</sup></h3>

              <p>Persen yang disetujui</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="#" class="small-box-footer">Logbook yang disetujui(%) <i class="fa fa-arrow-circle-right"></i></a>
        </div>
	</div>
	
	<div class="col-md-12">
		<div class="box box-danger box-solid">
			<div class="box-header with-border">
				<h3 class="box-title">
					DASHBOARD
				</h3>
			</div>
			<div class="box-body">

                <div class="form-group">
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
	                        [
	                            'label'=>'Nama Pegawai',
	                            'format'=>'raw',
	                            'value'=>function($model){
	                                return $model->pegawai->nama;
	                            }
	                        ],
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
                

                
			</div>
		</div>
	</div>
	<?php Pjax::end(); ?>
</div>
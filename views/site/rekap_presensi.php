<?php 
	use yii\grid\GridView;
	use app\modules\app\models\AppUser;
?>
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
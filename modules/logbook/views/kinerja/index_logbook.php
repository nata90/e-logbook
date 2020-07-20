<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker ;
use app\modules\logbook\models\Tugas;
use yii\helpers\Json;

$this->registerJs('var url = "' . Url::to(['kinerja/simpanbacklog']) . '";');
$this->registerJs('var url_delete = "' . Url::to(['kinerja/deletebacklog']) . '";');
$this->registerJs('var url_auto = "' . Url::to(['kinerja/autotugas']) . '";');
$this->registerJs('var url_tugas = "' . Url::to(['kinerja/getidtugas']) . '";');
$this->registerJs('var url_get_data = "' . Url::to(['kinerja/getdatakinerja']) . '";');
$this->registerJs(<<<JS
	data = [];
	var tgl_now = $('#kinerja-tanggal_kinerja').val();

	var actionRenderer = function (instance, td, row, col, prop, value, cellProperties) {
	  td.innerHTML = '<button class="btn btn-block btn-danger btn-flat btn-xs del-button">DELETE</button>';

	  return td;
	};

	var container = document.getElementById('data-trans');
	var hot = new Handsontable(container, {
	  data: data,
	  rowHeaders: true,
	  colHeaders: [
	  	'TUGAS',
	  	'DESKRIPSI',
	  	'JUMLAH',
	  	'OPSI'
	  ],
	  columns: [
	  	{
	  		data : 'tugas',
	  		type : 'autocomplete',
	  		source: function (query, process) {
		        $.ajax({
		          url: url_auto,
		          dataType: 'json',
		          data: {
		            query: query
		          },
		          success: function (response) {
		            console.log("response", response);
		            process(response.data);
		          }
		        });
		    },
		    strict: false,
		    allowInvalid: true
	  	},
	  	{
	  		data : 'deskripsi',
	  		type : 'text',
	  	},
	  	{
	  		data : 'jumlah',
	  		type : 'numeric',
	  	},
	  	{
	  		data : 'opsi',
	  		//renderer: actionRenderer,
	  		renderer:'html',
	  		readOnly: true
	  	}
	  ],
	  filters: false,
	  dropdownMenu: false,
	  minSpareRows: 15,
	  maxRow:15,
	  width: '100%',
      colWidths: [400, 400, 120, 80],
	  licenseKey: 'non-commercial-and-evaluation',
	  formulas: true,
	  afterOnCellMouseDown: function(e, coords, TD){
	        if(coords.col === 3){
	            this.alter('remove_row', coords.row)
	            this.selectCell(coords.row,0);
	        }
	    },
	  cells: function(row, col){
            const cellPrp = {};
            if(col === 3){
              cellPrp.readOnly = true;
              cellPrp.renderer = function(instance, td, row, col, prop, value, cellProperties) {
                Handsontable.renderers.TextRenderer.apply(this, arguments);
                td.innerHTML = '<button class="btn btn-block btn-danger btn-flat btn-xs">Delete</button>'
              }
            }
            return cellPrp
        },
		afterChange : function( changes, source ) {
		  	var string = String(changes);
		  	var explode = string.split(",");
		  	var date = $('#kinerja-tanggal_kinerja').val();

		  	var rows = explode[0];
		  	var name_column = explode[1];


	  		var tugas = this.getDataAtCell(rows,0);
	  		var deskripsi = this.getDataAtCell(rows,1);
	  		var jumlah = this.getDataAtCell(rows,2);


			$.ajax({
	  		  type: 'post',
	          url: url,
	          dataType: 'json',
	          data: {
	            rows:rows,
	            tugas:tugas,
	            deskripsi:deskripsi,
	            jumlah:jumlah,
	            date:date
	          },
	          success: function (v) {
	          	if(v.success == 1){
		          	notifikasi(v.msg,"success");
		        }
	          }
		    });
		  },
	    afterRemoveRow(index, amount, physicalRows, source) {
	      var date_delete = $('#kinerja-tanggal_kinerja').val();

	      $.ajax({
	  		  type: 'post',
	          url: url_delete,
	          dataType: 'json',
	          'beforeSend':function(json)
				{ 
					SimpleLoading.start('gears'); 
				},
	          data: {
	            rows:index,
	            date:date_delete,
	          },
	          success: function (v) {

	          	if(v.success == 1){
	          		notifikasi(v.msg,"info");
	          	}
	          	
	          },
	          'complete':function(json)
				{
					SimpleLoading.stop();
				},
          });
	    },
	});
    
    hot.selectCell(0,0);

	$.ajax({
  		  type: 'get',
          url: url_get_data,
          dataType: 'json',
          data: {
            tgl:tgl_now,
          },
          success: function (v) {
          	hot.loadData(v.data);
          },
          
      });

      
    $(document).on("change", "#kinerja-tanggal_kinerja", function () {
        var new_date = $(this).val();
        
        $.ajax({
	  		  type: 'get',
	          url: url_get_data,
	          dataType: 'json',
	          'beforeSend':function(json)
				{ 
					SimpleLoading.start('gears'); 
				},
	          data: {
	            tgl:new_date,
	          },
	          success: function (v) {
	          	hot.loadData(v.data);
	          },
	          'complete':function(json)
				{
					SimpleLoading.stop();
				},
	      });
    });

JS
);
?>
<div class="row">
	<div class="col-md-12">
		<div class="box box-danger box-solid">
			<div class="box-header with-border">
				<h3 class="box-title">
					Logbook
				</h3>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-md-4">
						<?php $form = ActiveForm::begin(); ?>
						<div class="form-group">
					    	<div class="input-group date">
					    		<div class="input-group-addon">
					    			<i class="fa fa-calendar"></i>
					    		</div>
					    		<?php
					    		echo DatePicker::widget([
									    'model' => $model,
									    'attribute' => 'tanggal_kinerja',
									    'options'=>[
									    	'class'=>'form-control pull-right',
									    	'style'=>'z-index: 9999;'
									    ]
									    //'language' => 'ru',
									    //'dateFormat' => 'yyyy-MM-dd',
									]);
					    		 ?>
					    	</div>
					       
					    </div>
					    <?php ActiveForm::end(); ?>
					</div>	
				</div>

				<div id="data-trans" style="margin:auto !important;">

				</div>
			</div>
		</div>
	</div>
</div>
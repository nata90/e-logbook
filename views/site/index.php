<?php
use yii\helpers\Url;
use yii\helpers\Html;


$this->registerJs('var url = "' . Url::to(['site/simpanbacklog']) . '";');
$this->registerJs('var url_delete = "' . Url::to(['site/deletebacklog']) . '";');

$this->registerJs(<<<JS
	var data = [];

	var actionRenderer = function (instance, td, row, col, prop, value, cellProperties) {
	  td.innerHTML = '<button class="btn btn-block btn-danger btn-flat btn-xs del-button">DELETE</button>';

	  return td;
	};

	var container = document.getElementById('data-trans');
	var hot = new Handsontable(container, {
	  data: data,
	  rowHeaders: true,
	  colHeaders: [
	  	'BUTIR KEGIATAN',
	  	'DESKRIPSI',
	  	'JUMLAH',
	  	'OPSI'
	  ],
	  columns: [
	  	{
	  		data : 'butir_kegiatan',
	  		type : 'dropdown',
	  		source : ["Mengoptimalkan kinerja sistem komputer","Membuat algoritma pemrograman","Melaksanakan pengintegrasian sistem informasi","Memeriksa dokumentasi program dan petunjuk pengoperasian program","Melaksanakan tugas kedinasan lainnya yang diperintahkan oleh pimpinan"]
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

		  	var rows = explode[0];
		  	var name_column = explode[1];


	  		var butir_keg = this.getDataAtCell(rows,0);
	  		var deskripsi = this.getDataAtCell(rows,1);
	  		var jumlah = this.getDataAtCell(rows,2);


			$.ajax({
	  		  type: 'post',
	          url: url,
	          dataType: 'json',
	          data: {
	            rows:rows,
	            butir_keg:butir_keg,
	            deskripsi:deskripsi,
	            jumlah:jumlah
	          },
	          success: function (v) {
	          	if(v.success == 1){
		          	notifikasi(v.msg,"success");
		          }
	          }
		    });
		  },
	    afterRemoveRow(index, amount, physicalRows, source) {
	      $.ajax({
	  		  type: 'post',
	          url: url_delete,
	          dataType: 'json',
	          data: {
	            rows:index,
	          },
	          success: function (v) {
	          	if(v.success == 1){
	          		notifikasi(v.msg,"info");
	          	}
	          	
	          }
          });
	    },
	});
    
    hot.selectCell(0,0);



JS
);
?>
<div class="row">
	<div class="col-md-12">
		<div class="box box-danger box-solid">
			<div class="box-header with-border">
				<h3 class="box-title">
					Backlog
				</h3>
			</div>
			<div class="box-body">
				<div id="data-trans" style="margin:auto !important;">

				</div>
			</div>
		</div>
	</div>
</div>
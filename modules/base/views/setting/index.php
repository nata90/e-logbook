<?php
$this->registerJs(<<<JS
	$(function  () {
	  $("div#external-events").sortable();
	});
JS
);
?>
<div class="col-md-12">
    <div class="box box-success box-solid">
         <div class="box-header with-border">
            <h3 class="box-title">Setting Menu</h3>
        </div>
        <div class="box-body">
        	<div id="external-events" class="col-md-6">
        		<div class="external-event bg-yellow">
        			DINNER
        		</div>
        	</div>
        </div>
    </div>
</div>
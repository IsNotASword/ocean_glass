<?php 
use yii\helpers\Url;
use yii\helpers\Html;
use common\models\Promotion;
use common\models\GarmentType;
use common\models\Item;
?>
<div class="modal-header myheader">
	<button type="button" class="close closeModal" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h3 class='modal-title'>Editar Promocion</h3>
</div>
<form method='post' enctype='multipart/form-data' class='ajaxSubmit validateForm' action='<?= Url::to(['promotions/update','id'=>$promotion->id]) ?>'>
<div class="modal-body">
	<div class="form-group">
		<label>Nombre</label>
		<?= Html::activeInput('text',$promotion,'name',['class'=>'form-control input-sm required']); ?>
	</div>
	<div class="row">
		<div class="col-md-3 form-group">
			<label>Inicio</label>
			<?= Html::activeInput('text',$promotion,'startDate',['class'=>'form-control input-sm required calendarField']); ?>
		</div>
		<div class="col-md-3 form-group">
			<label>Fin</label>
			<?= Html::activeInput('text',$promotion,'endDate',['class'=>'form-control input-sm required calendarField']); ?>
		</div>
		<div class="col-md-4 form-group">
			<label>Limite de Usos</label>
			<?php 
			$aux =range(0,100);
			$aux[0]="Sin Limite";
			 ?>
			<?= Html::activeDropDownList($promotion,'limit',$aux,['class'=>'form-control input-sm required number']); ?>
		</div>
		<div class="col-md-2 form-group">
			<label>Prioridad</label>
			<?= Html::activeInput('text',$promotion,'priority',['class'=>'form-control input-sm required number']); ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4 form-group">
			<label>Tipo de Descuento</label>
			<?= Html::activeDropDownList($promotion,'discountType',$promotion->getDiscountTypeArray(),['class'=>'form-control input-sm required']); ?>
		</div>
		<div class="col-md-2 form-group">
			<label>Cantidad</label>
			<?= Html::activeInput('text',$promotion,'value',['class'=>'form-control input-sm required number']); ?>
		</div>
	</div>
	<div class="form-group">
		<label>Descripcion</label>
		<?= Html::activeTextArea($promotion,'description',['class'=>'form-control input-sm']); ?>
	</div>
	<?= Html::activeInput('hidden',$promotion,'type'); ?>
	<h3>Tipo de Promocion</h3>
	<ul class="nav nav-justified nav-tabs dinamicTab">
		<li class='<?= $promotion->type==Promotion::TypeItems?'active':'' ?>'><a href='#' class='promotion-type' data-type='<?= Promotion::TypeItems ?>' data-toggle='tab'>Seleccionar productos</a></li>
		<li class='<?= $promotion->type==Promotion::TypeGarmentTypes?'active':'' ?>'><a href='#' class='promotion-type' data-type='<?= Promotion::TypeGarmentTypes ?>' data-toggle='tab'>Por tipo de prenda</a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane <?= $promotion->type==Promotion::TypeItems?'active':'' ?>">
			<div class="form-group text-center">
				<button type='button' data-toggle='modal' data-target='#promotion-items-modal' class='btn btn-primary'><i class='icon-plus'></i> Agregar articulo</button>
			</div>
			<table id='promotionItems' class='table table-bordered table-condensed'>
				<thead>
					<tr>
						<th>ID</th>
						<th>Nombre</th>
						<th>Tipo</th>
						<th>Precio</th>
						<th>Agregar</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($promotion->items as $i): ?>
						<tr>
							<td><?= $i->id ?></td>
							<td><?= $i->name ?></td>
							<td><?= $i->garmentType->name ?></td>
							<td>$<?= number_format($i->price,2) ?></td>
							<td class='text-center'><button data-item-id='<?= $i->id ?>' type='button' class='btn btn-danger'><i class='icon-remove'></i></button><input type='hidden' name='items[]' value='<?= $i->id ?>'></td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
		<div class="tab-pane <?= $promotion->type==Promotion::TypeGarmentTypes?'active':'' ?>">
			<div class="row" style='margin-bottom:10px'>
				<label class='col-md-3'><input id='promotionAllItems' class='check-field' data-selector='.garmentTypes' type='checkbox' name='all' value='1'> Todos</label>
				<?php foreach(GarmentType::find()->all() as $g): ?>
					<?php 
					$finded =false;
					foreach ($promotion->garmentTypes as $gp) {
						if($gp->id==$g->id){
							$finded=true;
							break;
						}
					}
					 ?>
					<label class='col-md-3'><input type='checkbox' class='garmentTypes' <?= $finded?"checked='checked'":'' ?> name='garmentTypes[]' value='<?= $g->id ?>'> <?= $g->name ?></label>
				<?php endforeach ?>
			</div>			
		</div>
	</div>
</div>
<div class="modal-footer text-right">
	<button type='button' data-dismiss='modal' class='btn btn-default'>Cancelar</button>
	<button type='submit' class='btn btn-primary'>Guardar</button>
</div>
	</form>
<div class="modal fade" id='promotion-items-modal' data-width='700'>
	<div class="modal-body">
		<table class='hasDataTable table table-bordered table-condensed'>
			<thead>
				<tr>
					<th>ID</th>
					<th>Nombre</th>
					<th>Tipo</th>
					<th>Precio</th>
					<th>Agregar</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach (Item::find()->where(['status'=>Item::StatusActive])->all() as $i): ?>
				<tr>
					<td><?= $i->id ?></td>
					<td><?= $i->name ?></td>
					<td><?= $i->garmentType->name ?></td>
					<td>$<?= number_format($i->price,2) ?></td>
					<!-- <td class='text-center'><label style='width:100%;heigth:100%'><input type='checkbox'></label></td> -->
					<td class='text-center'><button data-item-tr="<tr><td><?= $i->id ?></td><td><?= $i->name ?></td><td ><?= $i->garmentType->name ?></td><td>$ <?= number_format($i->price,2) ?></td><td class='text-center'><button data-item-id='<?= $i->id ?>' type='button' class='btn btn-danger'><i class='icon-remove'></i></button><input type='hidden' name='items[]' value='<?= $i->id ?>'></td></tr>" data-item-id='<?= $i->id ?>' type='button' class='btn btn-warning btn-mini' data-selected-text="<i class='icon-ok'></i>"><i class='icon-plus'></i></button></td>
				</tr>
			<?php endforeach ?>
			</tbody>
		</table>
	</div>
</div>
<script>
		var itemsTable = $("#promotionItems");
		var items = {};
		<?php foreach ($promotion->items as $i): ?>
			items[<?= $i->id ?>] = "<tr><td><?= $i->id ?></td><td><?= $i->name ?></td><td ><?= $i->garmentType->name ?></td><td>$ <?= number_format($i->price,2) ?></td><td class='text-center'><button data-item-id='<?= $i->id ?>' type='button' class='btn btn-danger'><i class='icon-remove'></i></button><input type='hidden' name='items[]' value='<?= $i->id ?>'></td></tr>";		
			$("#promotion-items-modal button[data-item-id='<?= $i->id ?>']").button('selected').removeClass('btn-warning').addClass('btn-success');
		<?php endforeach ?>
		itemsTable.data('items',items);
</script>
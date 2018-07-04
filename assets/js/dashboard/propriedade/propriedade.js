 jQuery(document).ready(function(){

 	jQuery('#inserirPropriedade').submit(function(){
 		console.log($("#selectPropCidades").val() == 0);
 		if (($("#selectPropCidades").val() == 0) || ($("#selectPropCidades").val() == undefined)) {
 			swal("","Selecione a cidade da Propriedade","warning");
 		}else{

 			var dadosajax = new FormData(this);
 			console.log($("#selectPropCidades").val());
 			pageurl = base_urla + 'admin/api/propriedade/';

 			if ($("#id_propriedade").val() > 0) {
 				pageurl = pageurl + $("#id_propriedade").val();
 			}

 			request("Salvando as informações");
 			$.ajax({
 				url: pageurl,
 				type: 'POST',
 				data:  dadosajax,
 				mimeType:"multipart/form-data",
 				contentType: false,
 				cache: false,
 				processData:false,
 				success: function(data, textStatus, jqXHR)
 				{
 					console.log(jqXHR);
 					requestSuccess();
 					getPropriedades(IDPRODUTOR);
 					swal({
 						title: '',text: 'Sucesso!!',type: 'success'
 					},function(){
 						if ($("#id_propriedade").val() > 0) {
 							getPropriedadesId($("#id_propriedade").val());
 						}else{
 							closeModalPropriedade();
 						}
 					});

 				},
 				error: function(jqXHR, textStatus, errorThrown) 
 				{
 					console.log(jqXHR);
 					requestSuccess();
 				}          
 			});
 		}

 		return false;
 	});

 	if (IDPRODUTOR != "") {
 		getPropriedades(IDPRODUTOR);
 	}

 });

 var SAFRAPRE = 0;
 var SAFRAFE = 0;
 var SAFRACAFE = 0;

 function addSafraPrevisao(){

 	var selector = $("#tabelaSafraPrevisao");
 	var col = "";
 	col += '<td>'+
 	'<input type="number" value="2018" class="w3-input w3-border" name="safraPreAnoInicio[]" min="1900" max="2099" style="width: 45%;display: inline-block;margin-right:3px" required>'+
 	'<input type="number" value="2019" class="w3-input w3-border" name="safraPreAnoFim[]" min="1900" max="2099" style="width: 45%;display: inline-block" required>'+
 	'</td>';
 	col += '<td>'+
 	'<input type="number" class="w3-input w3-border" placeholder="Quantidade de sacas" name="safraPreQtd[]" required>'+
 	'</td>';
 	col += '<td>'+
 	'<button class="w3-button w3-border w3-round" type="button" onclick="removeSafraPrevisao('+SAFRAPRE+')">'+
 	'<i class="fa fa-times"></i> Remover'+
 	'</button>'+
 	'</td>';
 	selector.append("<tr id='rowSafraPre"+SAFRAPRE+"'>"+col+"</tr>");

 	SAFRAPRE = SAFRAPRE + 1;
 }

 function removeSafraPrevisao(id){
 	$("#rowSafraPre"+id).remove();
 }

 function addSafraFechamento(){

 	var selector = $("#tabelaSafraFechamento");
 	var col = "";
 	col += '<td>'+
 	'<input type="number" value="2018" class="w3-input w3-border" name="safraFeAnoInicio[]" min="1900" max="2099" style="width: 45%;display: inline-block;margin-right:3px" required>'+
 	'<input type="number" value="2019" class="w3-input w3-border" name="safraFeAnoFim[]" min="1900" max="2099" style="width: 45%;display: inline-block" required>'+
 	'</td>';
 	col += '<td>'+
 	'<input type="number" class="w3-input w3-border" placeholder="Quantidade de sacas" name="safraFeQtd[]" required>'+
 	'</td>';
 	col += '<td>'+
 	'<button class="w3-button w3-border w3-round" type="button" onclick="removeSafraFechamento('+SAFRAFE+')">'+
 	'<i class="fa fa-times"></i> Remover'+
 	'</button>'+
 	'</td>';
 	selector.append("<tr id='rowSafraFe"+SAFRAFE+"'>"+col+"</tr>");

 	SAFRAFE = SAFRAFE + 1;
 }

 function removeSafraFechamento(id){
 	$("#rowSafraFe"+id).remove();
 }


 function addSafraCafe(){

 	var selector = $("#tabelaSafraCafe");
 	var col = "";
 	col += '<td>'+
 	'<input type="number" value="2018" class="w3-input w3-border" name="safraCafeAnoInicio[]" min="1900" max="2099" style="width: 45%;display: inline-block;margin-right:3px" required>'+
 	'<input type="number" value="2019" class="w3-input w3-border" name="safraCafeAnoFim[]" min="1900" max="2099" style="width: 45%;display: inline-block" required>'+
 	'</td>';
 	col += '<td>'+
 	'<select class="w3-select w3-border w3-white" name="safraCafeVariedade[]">'+
 	'<option value="Café Arábica">Café Arábica</option>'+
 	'<option value="Outros">Outros</option>'+
 	'</select>'+
 	'</td>';
 	col += '<td><input type="number" class="w3-input w3-border" placeholder="Área" name="safraCafeArea[]"></td>';
 	col += '<td><input type="number" class="w3-input w3-border" placeholder="Média de Sacas" name="safraCafeQtd[]"></td>';
 	col += '<td>'+
 	'<button class="w3-button w3-border w3-round" type="button" onclick="removeSafraCafe('+SAFRACAFE+')">'+
 	'<i class="fa fa-times"></i> Remover'+
 	'</button>'+
 	'</td>';
 	selector.append("<tr id='rowSafraCafe"+SAFRACAFE+"'>"+col+"</tr>");

 	SAFRACAFE = SAFRACAFE + 1;
 }

 function removeSafraCafe(id){
 	$("#rowSafraCafe"+id).remove();
 }

//Modal Propriedade
function modalPropriedade(id) {
	if (IDPRODUTOR != "") {
		if (id != undefined) {
			getPropriedadesId(id);
			//muda a id para ediar
			$("#id_propriedade").val(id);
			$("#titleForm").html("Editar Propriedade");
		}else{
			$("#inserirPropriedade").trigger("reset");
			//getCidades('selectPropEstados','selectPropCidades');
		}
		$('html').css("overflow","hidden");
		$('#modalPropriedade').css("z-index","5");
		$('#modalPropriedade').css("display","block");
		//funcao editar
	}else{
		swal("","Para cadastrar uma propriedade você precisa salvar o produtor!","warning");
	}
}

function closeModalPropriedade() {
	$('html').css("overflow","auto");
	$('#modalPropriedade').css("display","none");
	$("#id_propriedade").val(null);
	$("#titleForm").html("Cadastrar Propriedade");
	$("#tabelaSafraCafe").empty();
	$("#tabelaSafraFechamento").empty();
	$("#tabelaSafraPrevisao").empty();
	SAFRAPRE = 0;
	SAFRAFE = 0;
	SAFRACAFE = 0;
}

//liberar select do tipo de processamento
function toogleTipoProcessamento(){
	if ($("#tipo_processamento").val() == "Via Úmida") {
		$("#processamento_via_umido").removeAttr("disabled");
	}else{
		$("#processamento_via_umido").val("Não Informado");
		$("#processamento_via_umido").prop("disabled","true");
	}
}

//Propriedades
function getPropriedades(id) {
	
	var selector = $("#propriedades");
	var url = base_urla + 'admin/api/propriedade/' + id;
	
	selector.empty();
	
	//request("Buscando Dados");
	$.get(url, function(res) {
		if (res) {
			data = JSON.parse(res);
			
			data.forEach(function(obj){
				var col = "";
				col += "<td><i class='fa fa-building-o'></i></td>"
				col += "<td>"+obj.nome_propriedade+"</td>";
				col += "<td>"+obj.nome_cidade+"</td>";
				col += "<td>"+obj.nome_estado+"</td>";
				col += "<td><button type='button' class='w3-button w3-round w3-red' onclick='removerPropriedade(event,"+obj.id_propriedade+")'><i class='fa fa-trash'></i></button></td>";
				selector.append("<tr onclick='modalPropriedade("+obj.id_propriedade+")'>"+col+"</tr>");
			});
		}
	})
	.done(function(){

	});
}


$("#propriedade_file").on('change', function () {
	if (typeof (FileReader) != "undefined") {

        var image_holder = $("#image-propriedade");
        image_holder.empty();

        var reader = new FileReader();
        reader.onload = function (e) {
            $("<img />", {
                "src": e.target.result,
                "class": "thumb-image"
            }).appendTo(image_holder);
        }
        image_holder.show();
        reader.readAsDataURL($(this)[0].files[0]);
        $("#image-propriedade-bd").css("display","none");
    } else{
        console.log("Este navegador nao suporta FileReader.");
    }

});

function modalFotoPropriedade() {
    $('#modalFotoPropriedade').css('display','block');
    $('html').css('overflow','hidden');
}

function closeModalFotoPropriedade(par) {
    
    $('#modalFotoPropriedade').css('display','none');
    if (par == 1) {
        $('#propriedade_file').val(null);
        $("#image-propriedade").empty();
        $("#image-propriedade-bd").css("display","block");
    }
}

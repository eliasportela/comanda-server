function removerSafraFechamento(id) {
	swal({
		title: "Você tem certeza?",
		text: "Não será possível recuperar os dados",
		type: "warning",
		showCancelButton: true,
		confirmButtonText: "Sim, quero remover",
		closeOnConfirm: true,
		html: false
	}, function(){
		url = base_urla + 'admin/api/safra-fechamento/remover/' + id;
		$.get(url).done(function(){
			getSafrasFechamento($("#id_propriedade").val());
		});
	});
}

function removerSafraPrevisao(id) {
	swal({
		title: "Você tem certeza?",
		text: "Não será possível recuperar os dados",
		type: "warning",
		showCancelButton: true,
		confirmButtonText: "Sim, quero remover",
		closeOnConfirm: true,
		html: false
	}, function(){
		url = base_urla + 'admin/api/safra-previsao/remover/' + id;
		$.get(url).done(function(){
			getSafrasPrevisao($("#id_propriedade").val());
		});
	});
}

function removerSafraCafe(id) {
	swal({
		title: "Você tem certeza?",
		text: "Não será possível recuperar os dados",
		type: "warning",
		showCancelButton: true,
		confirmButtonText: "Sim, quero remover",
		closeOnConfirm: true,
		html: false
	}, function(){
		url = base_urla + 'admin/api/safra-cafe/remover/' + id;
		$.get(url).done(function(){
			getSafrasCafes($("#id_propriedade").val());
		});
	});
}

function removerPropriedade(e,id) {
	e.stopPropagation();
	swal({
		title: "Você tem certeza?",
		text: "Não será possível recuperar os dados",
		type: "warning",
		showCancelButton: true,
		confirmButtonText: "Sim, quero remover",
		closeOnConfirm: true,
		html: false
	}, function(){
		url = base_urla + 'admin/api/propriedade/remover/' + id;
		$.get(url).done(function(){
			getPropriedades(IDPRODUTOR);
			swal("","A propriedade foi removida","success")
		});
	});
}
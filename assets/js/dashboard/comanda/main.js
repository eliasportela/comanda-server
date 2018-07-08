function visualizarComanda(id){
    location.href = base_urla + 'admin/comanda/' + id;
}

function toogleModalComanda(option){
    if(option == 1){
        $("#modalComanda").css("display","block")
    }else{
        $("#modalComanda").css("display","none")
    }
}
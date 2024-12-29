function onClickModalConfirmDelete(idUnitAdmin, unitName){

    document.querySelector('#modal-confirm-delete form').setAttribute('action','/users/unit/admin/delete/'+idUnitAdmin);

    document.querySelector('#modal-confirm-delete #confirmation-text')
        .innerHTML = "Tem certeza que deseja remover o administrador da unidade <strong>" + unitName + "</strong>?";
}

function onClickCancelDelete(){
    document.querySelector('#modal-confirm-delete form').setAttribute('action','');
    document.querySelector('#modal-confirm-delete #confirmation-text')
        .innerHTML = "";
}
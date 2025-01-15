function onClickEditUnit(id, oldAcronym, oldName){
    $('#modal-edit-unit').modal('show');
    $('#modal-edit-unit #unit-acronym').val(oldAcronym);
    $('#modal-edit-unit #unit-name').val(oldName);
    $('#modal-edit-unit form').attr('action','/units/' + id);
}
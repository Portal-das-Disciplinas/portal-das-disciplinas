function onClickUpdate(id, value, priorityLevel){
    $('#modal-education-level-edit').modal('show');
    $('#modal-education-level-edit form').attr('action','/ensino/niveis/' + id);
    $('#modal-education-level-edit #value').val(value);
    $('#modal-education-level-edit #priority-level').val(priorityLevel);
}

function setupModalDelete(id, value){
    document.querySelector('#modal-confirm-delete form').setAttribute('action',"/ensino/niveis/delete/" + id)
    document.querySelector('#modal-confirm-delete #education-level-value').innerHTML = value;
    document.querySelector('#modal-confirm-delete #education-level-id').innerHTML = id;
}

function setdownModalDelete(){
    document.querySelector('#modal-confirm-delete form').setAttribute('action',"");
    document.querySelector('#modal-confirm-delete #course-level-value').innerHTML ="";
    document.querySelector('#modal-confirm-delete #course-level-id').innerHTML = -1;
}
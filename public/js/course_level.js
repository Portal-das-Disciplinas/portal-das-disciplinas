function setupModalDelete(id, value){
    document.querySelector('#modal-confirm-delete form').setAttribute('action',"/courses/levels/delete/" + id)
    document.querySelector('#modal-confirm-delete #course-level-value').innerHTML = value;
    document.querySelector('#modal-confirm-delete #course-level-id').innerHTML = id;
}

function setdownModalDelete(){
    document.querySelector('#modal-confirm-delete form').setAttribute('action',"");
    document.querySelector('#modal-confirm-delete #course-level-value').innerHTML ="";
    document.querySelector('#modal-confirm-delete #course-level-id').innerHTML = -1;
}
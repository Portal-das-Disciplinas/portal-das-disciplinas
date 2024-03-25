function addTopicField(){
    let element = document.getElementById('area-fields-topics');
    let div1 = document.createElement('div');
    div1.classList.add('form-group');
    let textArea = document.createElement('textarea');
    textArea.classList.add('form-control');
    textArea.name = 'topics[]';
    textArea.required = true;
    let inputHidden = document.createElement('input');
    inputHidden.type = 'hidden';
    inputHidden.name = 'topicsId[]';
    inputHidden.value = '-1';
    div1.append(textArea);
    div1.append(inputHidden);
    let div2 = document.createElement('div');
    div2.classList.add('d-flex', 'justify-content-end');
    let small = document.createElement('small');
    small.classList.add('text-danger');
    small.style = 'cursor: pointer';
    small.textContent = 'remover'
    small.addEventListener('click',removeTopicField);
    div2.append(small);
    div1.append(div2);
    element.append(div1);
    
}

function removeTopicField(event){
    let element = event.target;
    let parent = element.parentElement;
    parent.parentElement.remove();
}
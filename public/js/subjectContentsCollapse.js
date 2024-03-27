$('#collapseTopics').on('shown.bs.collapse',function(){
    document.querySelector('#seeMoreTopics').textContent = "ver menos";
});

$('#collapseTopics').on('hidden.bs.collapse',function(){
    document.querySelector('#seeMoreTopics').textContent = "ver mais";
});

$('#collapseConcepts').on('shown.bs.collapse',function(){
    document.querySelector('#seeMoreConcepts').textContent = "ver menos";
});

$('#collapseConcepts').on('hidden.bs.collapse',function(){
    document.querySelector('#seeMoreConcepts').textContent = "ver mais";
});

$('#collapseReferences').on('shown.bs.collapse',function(){
    document.querySelector('#seeMoreReferences').textContent = "ver menos";
});

$('#collapseReferences').on('hidden.bs.collapse',function(){
    document.querySelector('#seeMoreReferences').textContent = "ver mais";
});
$('#collapseCreditos').on('show.bs.collapse', function (event) {
    event.stopPropagation();
    $('li[name=caret-icon-creditos]').removeClass('fa fa-caret-down');
    $('li[name=caret-icon-creditos]').addClass('fa fa-caret-up');
});

$('#collapseCreditos').on('hide.bs.collapse', function (event) {
    event.stopPropagation();
    $('li[name=caret-icon-creditos]').removeClass('fa fa-caret-up');
    $('li[name=caret-icon-creditos]').addClass('fa fa-caret-down');
});

$('#collapseFaq').on('show.bs.collapse', function (event) {
    event.stopPropagation();
    $('li[name=caret-icon-faq]').removeClass('fa fa-caret-down');
    $('li[name=caret-icon-faq]').addClass('fa fa-caret-up');
});

$('#collapseFaq').on('hide.bs.collapse', function (event) {
    event.stopPropagation();
    $('li[name=caret-icon-faq]').removeClass('fa fa-caret-up');
    $('li[name=caret-icon-faq]').addClass('fa fa-caret-down');
});

$('div[name=collapse-participant]').on('show.bs.collapse', function (event) {
    $('li[name=' + event.target.id + ']').removeClass('fa fa-caret-down');
    $('li[name=' + event.target.id + ']').addClass('fa fa-caret-up');
    event.stopPropagation();

});

$('div[name=collapse-participant]').on('hide.bs.collapse', function (event) {
    $('li[name=' + event.target.id + ']').removeClass('fa fa-caret-up');
    $('li[name=' + event.target.id + ']').addClass('fa fa-caret-down');
    event.stopPropagation();
});

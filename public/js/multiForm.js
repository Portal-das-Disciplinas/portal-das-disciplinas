$(function() {
    $("filter").on("submit", (e) => {
        e.preventDefault();
        const formData = $(this).serialize();
    
        $.ajax({
            url: '/discipline/filter',
            type: 'get',
            data: formData,
            success: (response) => {
    
            },
            error: (response) => {
    
            }
        });
    });

    $("advancedFilter").on("submit", (e) => {
        e.preventDefault();
        const formData = $(this).serialize();
        alert(formData);
        $.ajax({
            url: '/discipline/filter',
            type: 'get',
            data: formData,
            success: (response) => {
                
            },
            error: (response) => {
    
            }
        });
    });
})
// This file handles the pagination in the website by using the bootpag js plugin

$(document).ready(function(){

    //Displaying the first page in the pagination content div
    $('#pagination-content > div').hide(); 
    $('#page-1').show(); 
    
    // Creating the pagination buttons
    $('#pagination-btn').bootpag({ 
        total: getPaginationTotalCount(),
        maxVisible: 5
    }).on("page", function(event, num){ 
        $('#pagination-content > div').hide(); 
        var current_page = '#page-' + num; 
        $(current_page).show(); 
    });

    $('[data-lp]').addClass('page-item');
    $('.page-item > a').addClass('page-link');
});
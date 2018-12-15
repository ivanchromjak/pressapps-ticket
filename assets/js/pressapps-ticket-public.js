
function addjustbuttons(){
    files = jQuery('.file_ct');
    
    if(files.length==1){
        jQuery('.file_ct').find('.remove_btn').hide();
        jQuery('.file_ct').find('.add_btn').show();
    }else if (files.length>1){
        jQuery('.file_ct').find('.remove_btn').show();
        jQuery('.file_ct').last().find('.remove_btn').hide();
        
        jQuery('.file_ct').find('.add_btn').hide();
        jQuery('.file_ct').last().find('.add_btn').show();
    }
    
}

jQuery(document).ready(function(){
    
    
    jQuery('body').delegate('.file_ct .add_btn','click',function(){
        jQuery('.file_ct').first().clone().appendTo(jQuery(this).parents('.files_ct'));
        addjustbuttons();
    })
    
    jQuery('body').delegate('.files_ct .remove_btn','click',function(){
        jQuery(this).parents('.file_ct').first().remove();
        addjustbuttons();
    });
    
    addjustbuttons();

    jQuery("[name='pati-form']").validate({
        rules   : {
            title   : {
                required    : true,
                minlength   : 3,
                maxlength   : 50
            },
            /*
            content : {
                required    : true,
                minlength   : 3
            },
            */
            category: "required" ,
            //status  : "required" ,
            //priority: "required" ,
            //type    : "required"     
			
        }
        /*
        messages    : {
            title   : {
                required    : "Please enter a title",
                minlength   : "ticket title must consist of at least 3 characters",
                maxlength   : "ticket title must not consist of more than 50 characters"
            },
            content : {
                required    : "Please enter a Content",
                minlength   : "ticket Content must consist of at least 10 characters"
            },
            category: "Please Select Category of the ticket",
            status  : "Please Select Status of the ticket",
            priority: "Please Select Priority of the ticket",
            type    : "Please Select Type of the ticket"
        }
        */
    });

});

function pakbSort(column){
    alert(column);
}


jQuery().ready(function(){
    jQuery('.ticket_tags').tagsInput({
        'width': 'auto',
        'height':'40px'
    });
});
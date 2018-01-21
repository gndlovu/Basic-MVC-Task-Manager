/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function()
{
    validateForms();

    $('#pick_up_date').datetimepicker({
        format: 'YYYY-MM-DD hh:mm'
    });
    $('#drop_off_date').datetimepicker({
        format: 'YYYY-MM-DD hh:mm'
    });
    
    var search_input = $("input#site-search");
    search_input.typeahead(
            {
                source: function(query, process)
                {
                    getTypeaheadContent(query, process, true);
                }
            });

    $("div#site-btn-search").on("click", function() {
        doArticleSearch(search_input);
    });
    
    
});

function getTypeaheadContent(query, process)
{
    $.ajax({
        type: "POST",
        url: "index.php?function=ajax&action=get_typeahead_content",
        data: "search_value=" + query,
        dataType: "JSON",
        success: function(data)
        {
            process(data);
        }
    });
}

function doArticleSearch(search_input)
{
    if (search_input.val() === "")
    {
        search_input.focus();
        return false;
    }

    $.ajax({
        type: "POST",
        url: "index.php?function=ajax&action=database_search",
        data: "search_value=" + search_input.val(),
        dataType: "JSON",
        success: function(data)
        {
            $("div.body-content").html("");
            $("div.body-content").html(data.content).fadeIn(300);
        }
    });
}

function validateForms()
{
    /*Booking form*/
    if ($('form#booking-form').length > 0)
    {
        $('form#booking-form').validate({
            rules: {
                pick_up_date: {
                    required: true
                },
                drop_off_date: {
                    required: true
                }
            },
            highlight: function(element) {
                $(element).closest('.form-group').addClass('has-error');
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error');
            },
            errorElement: 'span',
            errorClass: 'help-block',
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            }
        });
    }
    
    /*Profile form*/
    if ($('form#profile-form').length > 0)
    {
        $('form#profile-form').validate({
            rules: {
                contact: {
                    required: true
                },
                drivers_license: {
                    required: true
                },
                username: {
                    required: true
                },
                address: {
                    required: true
                },
                password: {
                    required: true
                },
                password_conf: {
                    required: true
                }
            },
            highlight: function(element) {
                $(element).closest('.form-group').addClass('has-error');
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error');
            },
            errorElement: 'span',
            errorClass: 'help-block',
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            }
        });
    }
}

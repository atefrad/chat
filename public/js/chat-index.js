$(document).ready(function(){
    $(".camera-icon").click(function () {
        $("input[type='file']").trigger('click');
    });

    $('input[type="file"]').on('change', function() {
        var val = $(this).val();
        $(this).siblings('span').text(val);
    })

    $(document).on('click', '.message-body', function(){
        $(this).siblings('.user-message-operations').toggleClass('d-none');
        $(this).siblings('.user-message-operations').toggleClass('d-block');

        $(this).siblings('.admin-operations').toggleClass('d-none');
        $(this).siblings('.admin-operations').toggleClass('d-block');
    });

//scroll to the bottom of the chat box
    $('.chatBx').animate({
        scrollTop: $ ('.chatBx') .offset().top + $('.chatBx')[0].scrollHeight
    }, 0.01);


//ajax for storing messages
    const chatBox = $('#body');
    const imagePath = $('#image-path');
    const parentBox = $('#parent-box');
    var lastId = $('#last_id').val();
    const bodyError = $('#body-error');
    const imageError = $('#image-error');
    const statusError = $('#status-error');
    const storeForm = $('#message-store-form');
    const updateForm = $('#message-update-form');


    //ajax for storing a message
    storeForm.submit(function (e) {
        e.preventDefault();

        $.ajax({
            url: "/chats/messages/ajax/store",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData:false,
            success: function (response){
                chatBox.val('');
                imagePath.text('');
                const myResponse = JSON.parse(response);

                if(myResponse.message === 'success')
                {
                    lastId = myResponse.id;
                    parentBox.append(myResponse.content);

                }else{

                    bodyError.text(myResponse.errors.body);
                    imageError.text(myResponse.errors.image);
                    statusError.text(myResponse.errors.status);

                    setTimeout(function () {
                        bodyError.text('');
                        imageError.text('');
                        statusError.text('');
                    }, 3000);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert("Status: " + textStatus);
                alert("Error: " + errorThrown);
            }
        });
    });

    //ajax for getting the last message
    setInterval(function () {
        $.ajax({
            url: "/chats/messages/ajax/last",
            method: "GET",
            data: {
                id: lastId,
                chat_id: $('#chat_id').val()
            },
            success: function (response) {
                const myResponse = JSON.parse(response);
                parentBox.append(myResponse.content);
                lastId = myResponse.id;
            },
        });
    }, 3000)


    //blocked user validation jquery
    if($('#user_status').val() == 0)
    {
        $('#store-button').attr('disabled', true);
        $('#update-button').attr('disabled', true);

        chatBox.click(function () {

            bodyError.text('You have been blocked and can not send messages!');

            setTimeout(function () {
                bodyError.text('');
            }, 3000);
            }
        );

        $('.edit-link').click(function (e) {
            e.preventDefault();
            alert('You have been blocked and can not edit messages');
            $(this).parents('.user-message-operations').toggleClass('d-none');
            $(this).parents('.user-message-operations').toggleClass('d-block');
        });
    }

    //ajax edit
    $('.edit-link').click(function (e) {
        e.preventDefault();

        if($('#user_status').val() == 1)
        {
            $(this).parents('.user-message-operations').toggleClass('d-none');
            $(this).parents('.user-message-operations').toggleClass('d-block');

            const messageId = parseInt($(this).parents('.user-message-operations').siblings('.message-body').children('.message-id-span').text());

            $.ajax({
                url: "/chats/messages/ajax/edit",
                method: "GET",
                data: {
                    id: messageId
                },
                success: function(response) {
                    const myResponse = JSON.parse(response);

                    storeForm.addClass('d-none');
                    updateForm.removeClass('d-none');
                    updateForm.addClass('d-block');

                    $('#update-body').val(myResponse.content.body);
                    $('#edited-message-id').val(myResponse.content.id);

                }
            });
        }
    });

    //ajax update
    updateForm.submit(function (e) {
        e.preventDefault();

        $.ajax({
            url: "/chats/messages/ajax/update",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData:false,
            success: function(response) {
                const myResponse = JSON.parse(response);

                $('#update-body').val('');
                $('#update-image-path').text('');

                if(myResponse.message === 'success')
                {
                    storeForm.removeClass('d-none');
                    updateForm.removeClass('d-block');
                    updateForm.addClass('d-none');

                    $('.message-body').each(function () {

                        if($(this).children('.message-id-span').text() == myResponse.content.id)
                        {
                            $(this).children('.p-body').text(myResponse.content.body);
                            $(this).children('img').attr('src', myResponse.content.image);

                        }
                    });

                }else{

                    $('#update-body-error').text(myResponse.errors.body);
                    $('#update-image-error').text(myResponse.errors.image);
                    $('#update-status-error').text(myResponse.errors.status);

                    setTimeout(function () {
                        $('#update-body-error').text('');
                        $('#update-image-error').text('');
                        $('#update-status-error').text('');
                    }, 3000);
                }

            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert("Status: " + textStatus);
                alert("Error: " + errorThrown);
            }
        });
    });

    //ajax delete
    $('.delete-link').click(function (e) {

        e.preventDefault();

        if($('#user_status').val() == 1)
        {
            $(this).parents('.user-message-operations').toggleClass('d-none');
            $(this).parents('.user-message-operations').toggleClass('d-block');

            const deleteMessageId = parseInt($(this).parents('.user-message-operations').siblings('.message-body').children('.message-id-span').text());

            $.ajax({
                url: "/chats/messages/ajax/delete",
                method: "POST",
                data: {
                    id: deleteMessageId
                },
                success: function (response) {

                    const myResponse = JSON.parse(response);

                    $('.messages').each(function () {

                        if($(this).children('.message-body').children('.message-id-span').text() == myResponse.id)
                        {
                            $(this).remove();
                        }
                    });
                }
            });
        }

    });

    //ajax seen
    var last_seen_id = $('#last_seen_id').val();

    setInterval(function () {

        $.ajax({
            url: "/chats/messages/ajax/seen-messages",
            method: "GET",
            data:{
                    chat_id: $('#chat_id').val(),
                    last_seen_id: last_seen_id
                },
            success: function (response) {
                const myResponse = JSON.parse(response);
                if(myResponse.content.length !== 0)
                {
                    last_seen_id = myResponse.content[ myResponse.content.length - 1];
                }


                $('.message-body').each( function () {

                    const messageId = parseInt($(this).children('.message-id-span').text());

                        if((jQuery.inArray(messageId, myResponse.content) !== -1 ))
                        {
                            $(this).children('.message-date-and-seen').children('.seen-icon').removeClass('fa-check');
                            $(this).children('.message-date-and-seen').children('.seen-icon').addClass('fa-check-double');
                        }
                });
            }
        });
    },3000)

});





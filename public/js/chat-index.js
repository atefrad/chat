$(".camera-icon").click(function () {
    $("input[type='file']").trigger('click');
});

$('input[type="file"]').on('change', function() {
    var val = $(this).val();
    $(this).siblings('span').text(val);
})

$('.message-body').click(function (){
    $(this).siblings('.user-message-operations').toggleClass('d-none');
    $(this).siblings('.user-message-operations').toggleClass('d-block');

    $(this).siblings('.admin-operations').toggleClass('d-none');
    $(this).siblings('.admin-operations').toggleClass('d-block');
});


// let chat_id = $("#chat_id").value;
// let body = $("#body").value;
// let image = $("#image").value;
//
// $("#message-store-form").submit(function (e) {
//     e.preventDefault();
//     $.ajax({
//         url: "/chats/messages/store",
//         cache: false,
//         processData: false,
//         contentType: false,
//         type: 'POST',
//         data: new FormData(this),
//         success: function(response){
//             $.ajax({
//                 url: "chats/messages?id=" + chat_id,
//                 type: "POST",
//                 data: {
//                     "body" : body,
//                     'image' : image
//                 },
//                 success: function(response) {
//                     console.log('success');
//                 }
//
//             })
//         }
//     });
// });



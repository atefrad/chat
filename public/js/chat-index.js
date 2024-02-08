$(".camera-icon").click(function () {
    $("input[type='file']").trigger('click');
});

$('input[type="file"]').on('change', function() {
    var val = $(this).val();
    $(this).siblings('span').text(val);
})

// $('.rightSide').click(function () {
//     $(this).children('.user-message-operations').toggleClass('d-none');
//    $(this).children('.user-message-operations').toggleClass('d-block');
// });

// $('.rightSide').click(function () {
//     $(this).children('.user-message-operations').toggleClass('d-none');
//    $(this).children('.user-message-operations').toggleClass('d-block');
// });

$('.message-body').click(function (){
    $(this).siblings('.user-message-operations').toggleClass('d-none');
    $(this).siblings('.user-message-operations').toggleClass('d-block');

    $(this).siblings('.admin-operations').toggleClass('d-none');
    $(this).siblings('.admin-operations').toggleClass('d-block');
});



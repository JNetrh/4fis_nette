

/*----------------------------------------------------------------------------*/
/* FileInput beautify: */
var fileUpload = function (id, images, urlDel){
    var match = null;
    var options = {
        language: "cs",
        initialPreviewAsData: true,
        allowedFileExtensions: ['png', 'gif', 'jpg', 'jpeg', 'JPG', 'ico'],
        validateInitialCount: true,
        resizeImage: true,
        maxFileSize: 2000,
        maxImageWidth: 1000,
        browseClass: "btn btn-primary btn-block",
        showCaption: false,
        showRemove: false,
        showUpload: false,
        uploadAsync: false,
        autoReplace: true
    };
    if(images){
        match = images[0].match(/\.(jpg|png|gif|jpeg|JPG)\b/);
    }
    if (match != null) {
        options.initialPreview = images;
        options.initialPreviewConfig = [{
            url: urlDel
        }];
    }
    var uploader = $("#" + id);
    uploader.fileinput(options);
};


/*----------------------------------------------------------------------------*/
/* Confirm box before delete: */
var confirmBox = '<div class="modal fade confirm-modal">' +
    '<div class="modal-dialog modal-sm" role="document">' +
    '<div class="modal-content">' +
    '<button type="button" class="close m-4 c-pointer" data-dismiss="modal" aria-label="Close">' +
    '<span aria-hidden="true">&times;</span>' +
    '</button>' +
    '<div class="modal-body pb-5"></div>' +
    '<div class="modal-footer pt-3 pb-3">' +
    '<a href="#" class="btn btn-primary yesBtn btn-sm">OK</a>' +
    '<button type="button" class="btn btn-secondary abortBtn btn-sm" data-dismiss="modal">Do nothing</button>' +
    '</div>' +
    '</div>' +
    '</div>' +
    '</div>';

var dialog = function(el, text, trueCallback, abortCallback) {

    el.click(function(e) {

        var thisConfirm = $(confirmBox).clone();

        thisConfirm.find('.modal-body').text(text);

        e.preventDefault();
        $('body').append(thisConfirm);
        $(thisConfirm).modal('show');

        if (abortCallback) {
            $(thisConfirm).find('.abortBtn').click(function(e) {
                e.preventDefault();
                abortCallback();
                $(thisConfirm).modal('hide');
            });
        }

        if (trueCallback) {
            $(thisConfirm).find('.yesBtn').click(function(e) {
                e.preventDefault();
                trueCallback();
                $(thisConfirm).modal('hide');
            });
        } else {

            if (el.prop('nodeName') == 'A') {
                $(thisConfirm).find('.yesBtn').attr('href', el.attr('href'));
            }

            if (el.attr('type') == 'submit') {
                $(thisConfirm).find('.yesBtn').click(function(e) {
                    e.preventDefault();
                    el.off().click();
                });
            }
        }

        $(thisConfirm).on('hidden.bs.modal', function(e) {
            $(this).remove();
        });

    });
};


/*----------------------------------------------------------------------------*/
/* Flash messages: */

var position = $("#flashMessage").position();
var c = 0;
var t;
var timer_is_on = 0;
function flashMessage(message){
    startCount();
    c = 0
    ToggleMessage(true)
    $("#flashMessage").find('ul').append('<li>' + message + '</li>');
}

function ToggleMessage(bol) {
    if(bol) {
        $("#flashMessage").animate({
            top: '0px'
        }, 400);
    }
    else {
        $("#flashMessage").animate({
            top: '-400px'
        }, 400);
    }
}

function timedCount() {

    c = c + 1;
    t = setTimeout(function(){
        if(c > 3){
            stopCount()
            ToggleMessage(false)
            $("#flashMessage").find('ul').html('')
        }
        timedCount()
    }, 1000);
}


function startCount() {
    if (!timer_is_on) {
        timer_is_on = 1;
        timedCount();
    }
}

function stopCount() {
    clearTimeout(t);
    timer_is_on = 0;
}



























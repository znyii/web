
uploader = {

    removeByElement: function(elem, inputName) {
        var input = $('input[name ="'+inputName+'"]').find('input[type=file]');
        input.val('');
        // console.log(input);
        // var card = $(elem).closest('.card');
        // console.log(card.data("file-id"));
    },

    addFile: function (imageUrl, fileName, id) {
        var html = $('#image-template').html();
        var item = $(html);
        item.data("file-id", id);
        var img = item.find('.uploader-image');

        if(fileName !== undefined) {
            var title = item.find('.uploader-file-name');
            title.html(fileName);
        }

        img.attr('src', imageUrl);
        $('#image-container').append(item);
    },

    clearList: function () {
        $('#image-container').html('');
    }

};

$(function () {

    function readURL(input) {
        uploader.clearList();
        if (input.files && input.files.length > 0) {
            for (var i = 0; i < input.files.length; i++) {
                var file = input.files.item(i);
                var reader = new FileReader();
                reader.onload = function (e) {
                    uploader.addFile(e.target.result, file.name, i);
                };
                reader.readAsDataURL(file);
            }
        }
    }

    $('.file-uploader').on('change', function () {
        readURL(this);
    });
});

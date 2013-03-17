(function($) {
    var i = 1;
    $('textarea.photonics-custom-editor').each(function(e) {
        var id = $(this).attr('id');
     
        if (!id) {
            id = 'customEditor-' + i++;
            $(this).attr('id', id);
        }
     
        tinyMCE.execCommand('mceAddControl', false, id);
    });
})(jQuery);
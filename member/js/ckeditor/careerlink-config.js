CKEDITOR.editorConfig = function( config )
{
    config.fontSize_sizes = '9/9px;10/10px;11/11px;12/12px;14/14px;16/16px;18/18px;20/20px;24/24px;30/30px;36/36px';
    config.extraPlugins = 'font,justify,richcombo,colorbutton';

    config.toolbar = 'ClinkToolbar';
    config.allowedContent = true;

    config.toolbar_ClinkToolbarSource = [
        { name: 'document', items: [ 'Source' ] },
        { name: 'styles', items: [ 'Format', 'FontSize' ] },
        { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyBlock', '-', 'NumberedList', 'BulletedList', '-', 'RemoveFormat', '-','Link', 'Unlink', 'Image', '-', 'PasteText', 'PasteFromWord' ] },
    ];
};


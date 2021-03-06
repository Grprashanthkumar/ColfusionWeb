<html>
    <head>
        <meta charset="UTF-8">
        <title>File Uploader</title>
        <link type="text/css" href="{$appRootPath}/css/bootstrap.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="{$appRootPath}/css/font-awesome.css">
        <style>
            {literal}
                .fuCompleteImage {
                    height: 30px;
                    width: 30px;
                    max-height: 50px;
                    max-width: 150px;
                }
            {/literal}
        </style>

        <script type="text/javascript" src="{$appRootPath}/javascripts/jquery-1.9.1.min.js"></script>
        <script type="text/javascript" src="{$appRootPath}/javascripts/bootstrap.min.js"></script>
        <script type="text/javascript">
            var uploadController = '{$appRootPath}' + '{$model->uploadController}?sid={$sid}';
            {literal}
                $(function() {
                    'use strict';

                    // Initialize the jQuery File Upload widget:
                    $('#fileupload').fileupload({
                        // Uncomment the following to send cross-domain cookies:
                        //xhrFields: {ithCredentials: true},
                        url: uploadController,
                        acceptFileTypes: /^(?!.*\.exe$).*$/i
                    }).bind('fileuploadsubmit', function(e, data) {
                        // Send with description.
                        var des = data.context.find('.descriptionInput');
                        data.formData = {description: des.val()};                     
                    });

                    // Enable iframe cross-domain access via redirect option:
                    $('#fileupload').fileupload(
                            'option',
                            'redirect',
                            window.location.href.replace(
                            /\/[^\/]*$/,
                            '/cors/result.html?%s'
                            )
                            );
                });

                // Delete checked files using delete btn on top.
                function deleteCheckedFiles() {
                    $('input[name="delete"][type="checkbox"]:checked').each(function(index, checkBoxDom) {
                        deleteFile($(checkBoxDom).parent().children('button'));
                    });
                }

                // Delete files from file list.
                function deleteFile(deleteBtnDom) {
                    if (!deleteBtnDom)
                        return;
                    var deleteUrl = $(deleteBtnDom).attr('data-url');
                    $.post(deleteUrl, function(data) {
                    }, 'json');
                }

            {/literal}
        </script>               
    </head>
    <body>
        <input type="hidden" name="sid" id="sid" value="{$sid}"/>
        <!-- Force latest IE rendering engine or ChromeFrame if installed -->
        <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->
        <meta charset="utf-8">
        <!-- Bootstrap CSS fixes for IE6 -->
        <!--[if lt IE 7]><link rel="stylesheet" href="http://blueimp.github.com/cdn/css/bootstrap-ie6.min.css"><![endif]-->
        <!-- Bootstrap Image Gallery styles -->
        <link rel="stylesheet" href="http://blueimp.github.com/Bootstrap-Image-Gallery/css/bootstrap-image-gallery.min.css">
        <!-- Shim to make HTML5 elements usable in older Internet Explorer versions -->
        <!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->

        <div class="container">
            <div class="well" style="margin-bottom: 8px;">
                {$model->wellText}
            </div>
            <!-- The file upload form used as target for the file upload widget -->
            <form id="fileupload" action="//jquery-file-upload.appspot.com/" method="POST" enctype="multipart/form-data">
                <!-- Redirect browsers with JavaScript disabled to the origin page -->
                <noscript>
                <input type="hidden" name="redirect" value="http://blueimp.github.com/jQuery-File-Upload/">
                </noscript>
                <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
                <div class="row fileupload-buttonbar">
                    <div class="span7">
                        <!-- The fileinput-button span is used to style the file input field as button -->
                        <span class="btn btn-success fileinput-button">
                            <i class="icon-plus icon-white"></i>
                            <span>Add files...</span>
                            <input type="file" name="files[]" multiple>
                        </span>
                        <button type="submit" class="btn btn-primary start">
                            <i class="icon-upload icon-white"></i>
                            <span>Start upload</span>
                        </button>
                        <button type="reset" class="btn btn-warning cancel">
                            <i class="icon-ban-circle icon-white"></i>
                            <span>Cancel upload</span>
                        </button>
                        <button type="button" class="btn btn-danger delete">
                            <i class="icon-trash icon-white"></i>
                            <span>Delete</span>
                        </button>
                        <input type="checkbox" class="toggle">
                    </div>
                    <!-- The global progress information -->
                    <div class="span5 fileupload-progress fade">
                        <!-- The global progress bar -->
                        <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                            <div class="bar" style="width: 0%;"></div>
                        </div>
                        <!-- The extended global progress information -->
                        <div class="progress-extended">&nbsp;</div>
                    </div>
                </div>
                <!-- The loading indicator is shown during file processing -->
                <div class="fileupload-loading"></div>
                <br>
                <!-- The table listing the files available for upload/download -->
                <table role="presentation" class="table table-striped">
                    <tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery"></tbody>
                </table>
            </form>
            <br>
        </div>
        <!-- modal-gallery is the modal dialog used for the image gallery -->
        <div id="modal-gallery" class="modal modal-gallery hide fade" data-filter=":odd" tabindex="-1">
            <div class="modal-header">
                <a class="close" data-dismiss="modal">&times;</a>
                <h3 class="modal-title"></h3>
            </div>
            <div class="modal-body">
                <div class="modal-image"></div>
            </div>
            <div class="modal-footer">
                <a class="btn modal-download" target="_blank">
                    <i class="icon-download"></i>
                    <span>Download</span>
                </a>
                <a class="btn btn-success modal-play modal-slideshow" data-slideshow="5000">
                    <i class="icon-play icon-white"></i>
                    <span>Slideshow</span>
                </a>
                <a class="btn btn-info modal-prev">
                    <i class="icon-arrow-left icon-white"></i>
                    <span>Previous</span>
                </a>
                <a class="btn btn-primary modal-next">
                    <span>Next</span>
                    <i class="icon-arrow-right icon-white"></i>
                </a>
            </div>
        </div>
        <!-- The template to display files available for upload -->       
        <script id="template-upload" type="text/x-tmpl">      
            {literal}
            {% for (var i=0, file; file=o.files[i]; i++) { %}
            <tr class="template-upload fade">
            <td class="preview"><span class="fade"></span></td>
            <td class="name"><span>{%=file.name%}</span></td>
            <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
            <td class="description"><span><input style="height:25px;" class="descriptionInput" type="text" name="description[]" placeholder="File Description"></span></td>
            {% if (file.error) { %}
            <td class="error" colspan="2"><span class="label label-important">Error</span> {%=file.error%}</td>
            {% } else if (o.files.valid && !i) { %}
            <td>
            <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="bar" style="width:0%;"></div></div>
            </td>
            <td>{% if (!o.options.autoUpload) { %}
            <button class="btn btn-primary start">
            <i class="icon-upload icon-white"></i>
            <span>Start</span>
            </button>
            {% } %}</td>
            {% } else { %}
            <td colspan="2"></td>
            {% } %}
            <td>{% if (!i) { %}
            <button class="btn btn-warning cancel">
            <i class="icon-ban-circle icon-white"></i>
            <span>Cancel</span>
            </button>
            {% } %}</td>
            </tr>
            {% } %}
            {/literal}
        </script>      
        <!-- The template to display files available for download -->
        <script id="template-download" type="text/x-tmpl">
            {literal}
                {% for (var i=0, file; file=o.files[i]; i++) { %}
                <tr class="template-download fade">
                {% if (file.error) { %}
                <td></td>
                <td class="name"><span>{%=file.name%}</span></td>
                <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
                <td class="error" colspan="2"><span class="label label-important">Error</span> {%=file.error%}</td>
                {% } else { %}
                <td class="preview">{% if (file.thumbnail_url) { %}
                <a href="{%=file.url%}" title="{%=file.name%}" data-gallery="gallery" download="{%=file.name%}"><img class="fuCompleteImage" src="{%=file.thumbnail_url%}"></a>
                {% } %}</td>
                <td class="name">
                <a href="{%=file.url%}" title="{%=file.name%}" data-gallery="{%=file.thumbnail_url&&'gallery'%}" download="{%=file.name%}">{%=file.name%}</a>
                </td>
                <td class="size" style="color:grey;"><span>{%=o.formatFileSize(file.size)%}</span></td>
                <td class="description" style="word-break: break-word;"><span>{%=file.description%}</span></td>
                <td colspan="2"></td>
                {% } %}
                <td>
                <button class="btn btn-danger delete" onclick="deleteFile(this)" data-type="{%=file.delete_type%}" data-url="{%=file.delete_url%}"{% if (file.delete_with_credentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                <i class="icon-trash icon-white"></i>
                <span>Delete</span>
                </button>
                <input type="checkbox" name="delete" value="1" class="toggle">
                </td>
                </tr>
                {% } %}
            {/literal}
        </script>
        <!-- Bootstrap CSS fixes for IE6 -->
        <!--[if lt IE 7]><link rel="stylesheet" href="http://blueimp.github.com/cdn/css/bootstrap-ie6.min.css"><![endif]-->
        <!-- Bootstrap Image Gallery styles -->
        <link rel="stylesheet" href="{$appRootPath}/css/jquery-file-upload/bootstrap-image-gallery.min.css">
        <!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
        <!-- JQuery file upload -->
        <link href="{$appRootPath}/css/jquery-file-upload/jquery.fileupload-ui.css" rel="stylesheet" />
        <!-- CSS adjustments for browsers with JavaScript disabled -->
        <noscript>
        <link rel="stylesheet" href="{$appRootPath}/css/jquery-file-upload/jquery.fileupload-ui-noscript.css">
        </noscript>
        <!-- Shim to make HTML5 elements usable in older Internet Explorer versions -->
        <!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->

        <!-- JQuery file upload -->
        <script src="{$appRootPath}/javascripts/jquery-file-upload/vendor/jquery.ui.widget.js"></script>
        <script src="{$appRootPath}/javascripts/jquery-file-upload/tmpl.min.js"></script>
        <script src="{$appRootPath}/javascripts/jquery-file-upload/load-image.min.js"></script>
        <script src="{$appRootPath}/javascripts/jquery-file-upload/canvas-to-blob.min.js"></script>
        <script src="{$appRootPath}/javascripts/jquery-file-upload/bootstrap-image-gallery.min.js"></script>
        <script src="{$appRootPath}/javascripts/jquery-file-upload/jquery.iframe-transport.js"></script>
        <script src="{$appRootPath}/javascripts/jquery-file-upload/jquery.fileupload.js"></script>
        <script src="{$appRootPath}/javascripts/jquery-file-upload/jquery.fileupload-fp.js"></script>
        <script src="{$appRootPath}/javascripts/jquery-file-upload/jquery.fileupload-ui.js"></script>
    </body>
</html>

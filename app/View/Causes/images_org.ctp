<?php


	$this->start('header');
	echo $this->element('Common/header');
	$this->end();
?>


<div class="container">
	<div class="page-header">
		<!--<h3>jQuery File Upload Demo</h3>-->
	</div>
	<!-- The file upload form used as target for the file upload widget -->
	<form id="fileupload" action="//jquery-file-upload.appspot.com/"
		method="POST" enctype="multipart/form-data">
		<!-- Redirect browsers with JavaScript disabled to the origin page -->
		<noscript>
			<input type="hidden" name="redirect"
				value="http://blueimp.github.com/jQuery-File-Upload/">
		</noscript>
		<!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
		<div class="row fileupload-buttonbar">
			<div class="span7">
				<!-- The fileinput-button span is used to style the file input field as button -->
				<span class="btn btn-success fileinput-button"> <i
					class="icon-plus icon-white"></i> <span>Add files...</span> <input
					type="file" name="files[]" multiple> </span>
				<button type="submit" class="btn btn-primary start">
					<i class="icon-upload icon-white"></i> <span>Start upload</span>
				</button>
				<button type="reset" class="btn btn-warning cancel">
					<i class="icon-ban-circle icon-white"></i> <span>Cancel upload</span>
				</button>
				<button type="button" class="btn btn-danger delete">
					<i class="icon-trash icon-white"></i> <span>Delete</span>
				</button>
				<input type="checkbox" class="toggle">
			</div>
			<!-- The global progress information -->
			<div class="span5 fileupload-progress fade">
				<!-- The global progress bar -->
				<div class="progress progress-success progress-striped active"
					role="progressbar" aria-valuemin="0" aria-valuemax="100">
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
		<table role="presentation" class="table table-striped fileUploadTable">
			<tbody class="files" data-toggle="modal-gallery"
				data-target="#modal-gallery"></tbody>
		</table>
	</form>
	<br>
</div>
<!-- modal-gallery is the modal dialog used for the image gallery -->
<div id="modal-gallery" class="modal modal-gallery hide fade"
	data-filter=":odd" tabindex="-1">
	<div class="modal-header">
		<a class="close" data-dismiss="modal">&times;</a>
		<h3 class="modal-title"></h3>
	</div>
	<div class="modal-body">
		<div class="modal-image"></div>
	</div>
	<div class="modal-footer">
		<a class="btn modal-download" target="_blank"> <i
			class="icon-download"></i> <span>Download</span> </a> <a
			class="btn btn-success modal-play modal-slideshow"
			data-slideshow="5000"> <i class="icon-play icon-white"></i> <span>Slideshow</span>
		</a> <a class="btn btn-info modal-prev"> <i
			class="icon-arrow-left icon-white"></i> <span>Previous</span> </a> <a
			class="btn btn-primary modal-next"> <span>Next</span> <i
			class="icon-arrow-right icon-white"></i> </a>
	</div>
</div>
<div class="row">
	<div class="offset4 span4 text-center">
		<?php echo $this->Html->link('Home', array('action' => 'add'), array('class' => 'btn btn-green'));?>
	</div>
</div>
<!--/span-->
<div id="push"></div>
<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td class="preview"><span class="fade"></span></td>
        <td class="name"><span>{%=file.name%}</span></td>
        <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
        {% if (file.error) { %}
            <td class="error" colspan="2"><span class="label label-important">Error</span> {%=file.error%}</td>
        {% } else if (o.files.valid && !i) { %}
            <td>
                <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="bar" style="width:0%;"></div></div>
            </td>
            <td class="start">{% if (!o.options.autoUpload) { %}
                <button class="btn btn-primary">
                    <i class="icon-upload icon-white"></i>
                    <span>Start</span>
                </button>
            {% } %}</td>
        {% } else { %}
            <td colspan="2"></td>
        {% } %}
        <td class="cancel">{% if (!i) { %}
            <button class="btn btn-warning">
                <i class="icon-ban-circle icon-white"></i>
                <span>Cancel</span>
            </button>
        {% } %}</td>
    </tr>
{% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { 
	 

%}
    <tr class="template-download fade fileUploadCell">
        {% if (file.error) { %}
            <td></td>
            <td class="name"><span>{%=file.name%}</span></td>
            <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
            <td class="error" colspan="2"><span class="label label-important">Error</span> {%=file.error%}</td>
        {% } else { %}
            <td class="preview">{% if (file.thumbnail_url) { %}
                <!-- <a href="{%=file.url%}" title="{%=file.name%}" rel="gallery" download="{%=file.name%}"></a> -->
		<img  id="img{%=file.imageId%}" src="{%=file.thumbnail_url%}">


            {% } %}</td>
            <td class="name">
                <a href="{%=file.url%}" title="{%=file.name%}" rel="{%=file.thumbnail_url&&'gallery'%}" download="{%=file.name%}">{%=file.name%}</a>
            </td>
            <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
            <td colspan="2"></td>
        {% } %}
		<td class="primary">
			{% 
				if (file.isPrimary) {
			%}
				<button class="btn btn-success makePrimary" data-imageid="{%=file.imageId%}">
					<span>Primary Image</span>
				</button>
			{%
				} 
				else{					
			%}
				<button class="btn btn-info makePrimary" data-imageid="{%=file.imageId%}">
					<span>Set as Primary</span>
				</button>
			{%
				}
			%}
        </td>
        <td class="delete">
            <button class="btn btn-danger" data-type="{%=file.delete_type%}" data-url="{%=file.delete_url%}"{% if (file.delete_with_credentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                <i class="icon-trash icon-white"></i>
                <span>Delete</span>
            </button>
            <input type="checkbox" name="delete" value="1">
        </td>
		
    </tr>
{% } %}
</script>
<script>
		$(document).ready(function () {
			var imageId = 157 ;
			alert('img'+imageId);
		  $('#img'+imageId).imgAreaSelect({ maxWidth: 200, maxHeight: 150, handles: true });
		});
</script>
<?php
	echo $this->Html->script(array(
		'ui/jquery.ui.widget',
		'http://blueimp.github.com/JavaScript-Templates/tmpl.min.js',
		'http://blueimp.github.com/JavaScript-Load-Image/load-image.min.js',
		'http://blueimp.github.com/JavaScript-Canvas-to-Blob/canvas-to-blob.min.js',
		'http://blueimp.github.com/Bootstrap-Image-Gallery/js/bootstrap-image-gallery.min.js',
		'fileupload/jquery.iframe-transport',
		'fileupload/jquery.fileupload',
		'fileupload/jquery.fileupload-fp',
		'fileupload/jquery.fileupload-ui'
	));
?>
<script>



$(function () {
    'use strict';

    // Initialize the jQuery File Upload widget:
    $('#fileupload').fileupload({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: '<?php echo $this->Html->url(array('controller' => 'images', 'action' => 'upload', 'Cause', $id));?>'
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

    if (window.location.hostname){
        // Load existing files:
        $.ajax({
            // Uncomment the following to send cross-domain cookies:
            //xhrFields: {withCredentials: true},
            url: '<?php echo $this->Html->url(array('ext' => 'json', 'controller' => 'images', 'action' => 'upload', 'Cause', $id));?>',
            dataType: 'json',
            context: $('#fileupload')[0]
        }).done(function (result) {
            $(this).fileupload('option', 'done')
                .call(this, null, {result: result});
        });
    }
	$(".makePrimary").live('click', function(e){
		e.preventDefault();
		var self = $(this);
		var imageId = $(this).data('imageid');
		var itemId = <?php echo $id; ?>;
		var makePrimaryURL = '<?php echo $this->Html->url(array('controller' => 'images', 'action' => 'make_primary'));?>';
		$.ajax({
			url : makePrimaryURL,
			type : 'POST',
			data : {'imageId' : imageId, 'itemId' : itemId},
			success : function(response){
				var result = $.parseJSON(response);
				if(result.success == true){
					$(".makePrimary").removeClass('btn-info');
					$(".makePrimary").removeClass('btn-success');
					$(".makePrimary").addClass('btn-info');
					self.removeClass('btn-info');
					self.addClass('btn-success');
					self.find('span').text('Primary Image');
				}
			}
		});
	});

});



</script>
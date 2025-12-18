<?php
// Simple video uploader for cover videos
?>

<div id="jsvideoManagerModal" class="modal" tabindex="-1" role="dialog">
<div class="modal-dialog modal-lg">
<div class="modal-content">
<div class="container-fluid">
<div class="row">
	<div class="col p-3">

		<h3 class="mt-2 mb-3"><i class="fa fa-video-camera"></i> <?php $L->p('Videos'); ?></h3>

		<div id="jsalertVideo" class="alert alert-warning d-none" role="alert"></div>

		<!-- Form and Input file -->
		<form name="bluditFormUploadVideo" id="jsbluditFormUploadVideo" enctype="multipart/form-data">
			<div class="custom-file">
				<input type="file" class="custom-file-input" id="jsvideos" name="videos[]" accept="video/*" multiple>
				<label class="custom-file-label" for="jsvideos"><?php $L->p('Choose videos to upload'); ?></label>
			</div>
		</form>

		<!-- Progress bar -->
		<div class="progress mt-3">
			<div id="jsbluditVideoProgressBar" class="progress-bar bg-primary" role="progressbar" style="width:0%"></div>
		</div>

		<p class="mt-3 text-muted small">
			<?php $L->p('After upload, the first selected video will be used as cover for this post.'); ?>
		</p>

	</div>
</div>
</div>
</div>
</div>
</div>

<script>
function openVideoManager() {
	$('#jsvideoManagerModal').modal('show');
}

function closeVideoManager() {
	$('#jsvideoManagerModal').modal('hide');
}

function showVideoAlert(message) {
	$("#jsalertVideo").html(message).removeClass('d-none');
}

function hideVideoAlert() {
	$("#jsalertVideo").addClass('d-none');
}

function setCoverVideo(filename) {
	var videoUrl = "<?php echo DOMAIN_UPLOADS.'videos/'; ?>"+filename;

	// Set video field and preview
	if ($("#jscoverVideo").length) {
		$("#jscoverVideo").val(filename);
	}
	if ($("#jscoverVideoPreview").length) {
		$("#jscoverVideoPreview").attr("src", videoUrl).show();
	}

	// Clear image to enforce exclusivity
	if ($("#jscoverImage").length) {
		$("#jscoverImage").val('');
	}
	if ($("#jscoverImagePreview").length) {
		$("#jscoverImagePreview").attr('src', HTML_PATH_CORE_IMG+'default.svg');
	}
}

function uploadVideos() {
	// Remove current alerts
	hideVideoAlert();

	var videos = $("#jsvideos")[0].files;
	if (!videos.length) {
		return;
	}

	// Basic size check (reuse upload max filesize)
	for (var i=0; i < videos.length; i++) {
		if (videos[i].size > UPLOAD_MAX_FILESIZE) {
			showVideoAlert("<?php echo $L->g('Maximum load file size allowed:').' '.ini_get('upload_max_filesize') ?>");
			return false;
		}
	}

	// Clean progress bar
	$("#jsbluditVideoProgressBar").removeClass().addClass("progress-bar bg-primary");
	$("#jsbluditVideoProgressBar").width("0");

	// Data to send via AJAX
	var formData = new FormData($("#jsbluditFormUploadVideo")[0]);
	formData.append("tokenCSRF", tokenCSRF);

	$.ajax({
		url: HTML_PATH_ADMIN_ROOT+"ajax/upload-videos",
		type: "POST",
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		xhr: function() {
			var xhr = $.ajaxSettings.xhr();
			if (xhr.upload) {
				xhr.upload.addEventListener("progress", function(e) {
					if (e.lengthComputable) {
						var percentComplete = (e.loaded / e.total)*100;
						$("#jsbluditVideoProgressBar").width(percentComplete+"%");
					}
				}, false);
			}
			return xhr;
		}
	}).done(function(data) {
		if (data.status==0) {
			$("#jsbluditVideoProgressBar").removeClass("bg-primary").addClass("bg-success");

			if (data.videos && data.videos.length > 0) {
				// Use the first uploaded video as cover
				setCoverVideo(data.videos[0]);
			}

			closeVideoManager();
		} else {
			$("#jsbluditVideoProgressBar").removeClass("bg-primary").addClass("bg-danger");
			showVideoAlert(data.message);
		}
	});
}

$(document).ready(function() {
	// Select video event
	$("#jsvideos").on("change", function(e) {
		uploadVideos();
	});
});
</script>



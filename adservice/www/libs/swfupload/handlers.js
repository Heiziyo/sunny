
/* This is an example of how to cancel all the files queued up.  It's made somewhat generic.  Just pass your SWFUpload
object in to this method and it loops through cancelling the uploads. */
function cancelQueue(elementId) {

    var instance;

    if (elementId == "flashUI") {
        instance = swfu;
    }
    else if (elementId == "flashUI_0") {
        instance = swfu_0;
    }
    else if (elementId == "flashUI_1") {
        instance = swfu_1;
    }
    else if (elementId == "flashUI_2") {
        instance = swfu_2;
    }

	document.getElementById(instance.customSettings.cancelButtonId).disabled = true;
	instance.stopUpload();
	var stats;
	
	do {
		stats = instance.getStats();
		instance.cancelUpload();
	} while (stats.files_queued !== 0);
	
}

/* **********************
   Event Handlers
   These are my custom event handlers to make my
   web application behave the way I went when SWFUpload
   completes different tasks.  These aren't part of the SWFUpload
   package.  They are part of my application.  Without these none
   of the actions SWFUpload makes will show up in my application.
   ********************** */
function fileDialogStart() {
	/* I don't need to do anything here */
}
function fileQueued(file) {
	try {
		// You might include code here that prevents the form from being submitted while the upload is in
		// progress.  Then you'll want to put code in the Queue Complete handler to "unblock" the form

        //获取文件扩展名
        var fileName = file.name;
        var ext = fileName.slice(fileName.lastIndexOf(".")).toLowerCase();

        //重新构造key
        var timestamp = Date.parse(new Date());
        var hash = hex_md5(timestamp + "_" + Math.floor(Math.random() * ( 10000 + 1)));
        var key = hash + ext;

        if (this.settings.swfupload_element_id == "flashUI")
        {
            swfu.removePostParam('key');
            swfu.addPostParam('key', key);
        }
        else if (this.settings.swfupload_element_id == "flashUI_0")
        {
            swfu_0.removePostParam('key');
            swfu_0.addPostParam('key', key);
        }
        else if (this.settings.swfupload_element_id == "flashUI_1")
        {
            swfu_1.removePostParam('key');
            swfu_1.addPostParam('key', key);
        }
        else if (this.settings.swfupload_element_id == "flashUI_2")
        {
            swfu_2.removePostParam('key');
            swfu_2.addPostParam('key', key);
        }

		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.toggleCancel(true, this);

	} catch (ex) {
		this.debug(ex);
	}

}

function fileQueueError(file, errorCode, message) {
	try {
		if (errorCode === SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED) {
			alert("您添加了太多的文件到上传队列，" + (message === 0 ? "已达到最大限制，" : "您可以选择" + (message > 1 ? "最多" + message + "个文件。" : "一个文件。")));
			return;
		}

		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setError();
		progress.toggleCancel(false);

		switch (errorCode) {
		case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
            alert("文件大小超过最大尺寸限制！");
			this.debug("Error Code: File too big, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
            alert("不能上传0字节大小的文件！");
			this.debug("Error Code: Zero byte file, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
            alert("无效的文件类型！");
			this.debug("Error Code: Invalid File Type, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED:
			alert("您添加了太多的文件，" +  (message > 1 ? "您只能再添加 " +  message + " 个文件。" : "您不能再添加任何文件。"));
			break;
		default:
			if (file !== null) {
                alert("文件添加发生错误，错误码：" + errorCode);
			}
			this.debug("Error Code: " + errorCode + ", File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		}
	} catch (ex) {
        this.debug(ex);
    }
}

function fileDialogComplete(numFilesSelected, numFilesQueued) {
	try {
		if (this.getStats().files_queued > 0) {
			document.getElementById(this.customSettings.cancelButtonId).disabled = false;
		}
		
		/* I want auto start and I can do that here */
		this.startUpload();
	} catch (ex)  {
        this.debug(ex);
	}
}

function uploadStart(file) {
	try {
		/* I don't want to do any file validation or anything,  I'll just update the UI and return true to indicate that the upload should start */

        //弹出模态对话框
        $('#fileUploadModal').modal({
            backdrop: 'static'
        })

        //绑定取消按钮事件
        var elementId = this.settings.swfupload_element_id;
        $('#btnSWFCancel').click(function() {
            cancelQueue(elementId);
        });

		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.toggleCancel(true, this);
	}
	catch (ex) {
	}
	
	return true;
}

function uploadProgress(file, bytesLoaded, bytesTotal) {

	try {
		var percent = Math.ceil((bytesLoaded / bytesTotal) * 100);

		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setProgress(percent);
	} catch (ex) {
		this.debug(ex);
	}
}

function uploadSuccess(file, serverData) {
	try {
        serverResponseObj = eval('(' + serverData + ')');

        if (this.settings.swfupload_element_id == "flashUI")
        {
            $('#qiniu_key').val(serverResponseObj.key);
            $('#creative_file').val(file.name);
            $('#spanFileName').html(file.name);
            $('#spanFileHash').val(serverResponseObj.hash);
            $('#isNewFile').val("1");
        }
        else if (this.settings.swfupload_element_id == "flashUI_0")
        {
            $('#qiniu_key_0').val(serverResponseObj.key);
            $('#adBootAnimation').val(file.name);
            $('#spanFileName_0').html(file.name);
            $('#spanFileHash_0').val(serverResponseObj.hash);
            $('#isNewFile_0').val("1");
        }
        else if (this.settings.swfupload_element_id == "flashUI_1")
        {
            $('#qiniu_key_1').val(serverResponseObj.key);
            $('#adMovie').val(file.name);
            $('#spanFileName_1').html(file.name);
            $('#spanFileHash_1').val(serverResponseObj.hash);
            $('#isNewFile_1').val("1");
        }
        else if (this.settings.swfupload_element_id == "flashUI_2")
        {
            $('#click_url').val(domain + serverResponseObj.key + '?h=' + serverResponseObj.hash);
        }

		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setComplete();
		progress.toggleCancel(false);

	} catch (ex) {
		this.debug(ex);
	}
}

function uploadComplete(file) {
	try {
		/*  I want the next upload to continue automatically so I'll call startUpload here */
		if (this.getStats().files_queued === 0) {
			document.getElementById(this.customSettings.cancelButtonId).disabled = true;
		} else {	
			this.startUpload();
		}
        $('#fileUploadModal').modal('toggle');
	} catch (ex) {
		this.debug(ex);
	}

}

function uploadError(file, errorCode, message) {
	try {
		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setError();
		progress.toggleCancel(false);

		switch (errorCode) {
		case SWFUpload.UPLOAD_ERROR.HTTP_ERROR:
            alert("网络连接错误，错误信息： " + message);
			this.debug("Error Code: HTTP Error, File name: " + file.name + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.MISSING_UPLOAD_URL:
            alert("配置错误，错误信息：" + message);
			this.debug("Error Code: No backend file, File name: " + file.name + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_FAILED:
            alert("文件上传失败，错误信息： " + message);
			this.debug("Error Code: Upload Failed, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.IO_ERROR:
            alert("服务端IO错误，错误信息： " + message);
			this.debug("Error Code: IO Error, File name: " + file.name + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.SECURITY_ERROR:
            alert("安全错误，错误信息： " + message);
			this.debug("Error Code: Security Error, File name: " + file.name + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
            alert("超出上传限制，错误信息： " + message);
			this.debug("Error Code: Upload Limit Exceeded, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.SPECIFIED_FILE_ID_NOT_FOUND:
            alert("文件未找到，错误信息：" + message);
			this.debug("Error Code: The file was not found, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.FILE_VALIDATION_FAILED:
            alert("验证失败，错误信息：" + message);
			this.debug("Error Code: File Validation Failed, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
			if (this.getStats().files_queued === 0) {
				document.getElementById(this.customSettings.cancelButtonId).disabled = true;
			}
            alert("取消上传！");
			progress.setCancelled();
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
            alert("上传已中止！");
			break;
		default:
            alert("文件上传失败，错误信息：" + error_code);
			this.debug("Error Code: " + errorCode + ", File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		}
	} catch (ex) {
        this.debug(ex);
    }
}
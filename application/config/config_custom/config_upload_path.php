<?php
/**
 * Paths to upload files
 * @date 09-02-2018
 * @version 1.0
 * @copyright 2018, CODEPOINT Softwares Pvt. Ltd.
 */
################################################################################
# Path Settings

$config['_ALLOWED_TYPES']	='gif|jpg|png|pdf|doc|docx|jpeg|msg|ai';
$config['_MAX_SIZE']		= 51200;
$config['_MAX_WIDTH']		= 1024;
$config['_MAX_HEIGHT']		= 768;

//FORMVALIDATION CONFIG VARIABLES
$config['_FILE_UPLOAD_NOTE']				=	'(Note: Allowed file formats - gif, jpg, png, pdf, doc, docx, jpeg, msg, ai, Allowed maximum size - 50 MB)';
$config['_FILE_UPLOAD_ALLOWED_TYPES']		=	'image/jpeg,image/png,image/gif,image/png,application/pdf,application/msword,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-outlook,application/postscript';
$config['_FILE_UPLOAD_ALLOWED_EXTENSION']	=	'gif,jpg,png,pdf,doc,docx,jpeg,msg,ai'	;


//Path for uploading document
$config['_UPLOAD_PATH']		=	'./uploads/';

$config['_DOCUMENT_UPLOAD_PATH']		=	'documents';
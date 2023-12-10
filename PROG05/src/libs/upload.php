<?php
require_once __DIR__."/../bootstrap.php";

/**
 *  Messages associated with the upload error code
 */
const MESSAGES = [
  UPLOAD_ERR_OK => 'File uploaded successfully',
  UPLOAD_ERR_INI_SIZE => 'File is too big to upload',
  UPLOAD_ERR_FORM_SIZE => 'File is too big to upload',
  UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
  UPLOAD_ERR_NO_FILE => 'No file was uploaded',
  UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder on the server',
  UPLOAD_ERR_CANT_WRITE => 'File is failed to save.',
  UPLOAD_ERR_EXTENSION => 'File is not allowed to upload to this server',
];

const ALLOWED_FILES_IMG = [
  'image/png' => 'png',
  'image/jpeg' => 'jpg'
];

const ALLOWED_FILES_ASSIGNMENT = [
  'application/pdf' => 'pdf',
  'application/msword' => 'doc',
  'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx'
];

const ALLOWED_FILE_TEXT = [
  'text/plain' => 'txt'
];

const MAX_SIZE = 10 * 1024 * 1024; //  10MB
const UPLOAD_DIR = __DIR__.'/uploads';

function check_file_valid(&$inputs, &$errors, $file_upload): void {
  $filepath = null;
  $file_condition = array();
  switch($file_upload)  {
    case 'submission_path':
      $file_condition = ALLOWED_FILES_ASSIGNMENT;
      break;
    case 'assignment_path':
      $file_condition = ALLOWED_FILES_ASSIGNMENT;
      break;
    case 'avatar_path':
      $file_condition = ALLOWED_FILES_IMG;
      break;
    case 'challenge_path':
      $file_condition = ALLOWED_FILE_TEXT;
      break;
  }
  // File data
  $status = $_FILES[$file_upload]['error'];
  $filename = $_FILES[$file_upload]['name'];
  $tmp = $_FILES[$file_upload]['tmp_name'];

  if(!isset($_FILES[$file_upload])) {
    $errors[$file_upload] = MESSAGES[UPLOAD_ERR_NO_FILE];
    return;
  }

  // an error occurs
  if($status !== UPLOAD_ERR_OK) {
    $errors[$file_upload] = MESSAGES[$status];
    return;
  }

  // validate the file size
  $filesize = filesize($tmp);
  if($filesize > MAX_SIZE) {
    $errors[$file_upload] = MESSAGES[UPLOAD_ERR_INI_SIZE];
    return;
  }

  // validate the file type
  $mime_type = get_mime_type($tmp);
  if(!in_array($mime_type, array_keys($file_condition))) {
    $errors[$file_upload] = MESSAGES[UPLOAD_ERR_EXTENSION];
    return;
  }

  // set the filename as the basename + extension
  switch($file_upload) {
    case 'submission_path':
      $filepath = pathinfo($filename, PATHINFO_DIRNAME).'/uploads/submission/'.$filename;
      break;
    case 'assignment_path':
      $filepath = pathinfo($filename, PATHINFO_DIRNAME).'/uploads/assignment/'.$filename;
      break;
    case 'avatar_path':
      $filepath = pathinfo($filename, PATHINFO_DIRNAME).'/uploads/ava/'.$_SESSION['username'].'.'.ALLOWED_FILES_IMG[$mime_type];
      break;
    case 'challenge_path':
      $filepath = pathinfo($filename, PATHINFO_DIRNAME).'/uploads/challenges/'.$filename;
      break;
  }

  // move the file to the upload dir
  $success = move_uploaded_file($tmp, $filepath);
  if(!$success) {
    $errors[$file_upload] = MESSAGES[UPLOAD_ERR_CANT_WRITE];
    return;
  }
  $inputs[$file_upload] = $filepath;
  return;
}





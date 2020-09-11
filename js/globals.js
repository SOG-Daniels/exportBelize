//global variables
var initialPreview = {};
var initialPreviewConfig = {};
var BASE_URL = '';
var uploadExtraData = {};

function setInitialPreview(val){
    initialPreview = val;
}
function setInitialPreviewConfig(val){
    initialPreviewConfig = val;
}
function setBaseUrl(val){
    BASE_URL = val;
}
function setUploadExtraData(val){
    uploadExtraData = val;
}
function getUploadExtraData(){
    return uploadExtraData;
}
function getInitialPreview(){
    return initialPreview;
}
function getInitialPreviewConfig(){
    return initialPreviewConfig;
}
function getBaseUrl(){
    return BASE_URL;
}

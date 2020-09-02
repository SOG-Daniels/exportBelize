//global variables
var initialPreview = {};
var initialPreviewConfig = {};
var BASE_URL = '';

function setInitialPreview(val){
    initialPreview = val;
}
function setInitialPreviewConfig(val){
    initialPreviewConfig = val;
}
function setBaseUrl(val){
    BASE_URL = val;
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

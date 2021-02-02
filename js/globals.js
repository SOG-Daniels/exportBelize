//global variables
var initialPreview = {};
var initialPreviewConfig = {};
var BASE_URL = '';
var uploadExtraData = {};
var paginationTotalCount = 0;



//set functions
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
function setPaginationTotalCount(num){
    paginationTotalCount = num;
}


//get functions
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
function getPaginationTotalCount(){
    return paginationTotalCount;
}

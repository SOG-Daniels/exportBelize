// This managed file uploads in the client management page

$(function () {
    $("#input-fa").fileinput({
        theme: "fas",
        uploadUrl: BASE_URL+"index.php/",
        uploadExtraData: getUploadExtraData(),
        uploadAsync: false,
        initialPreviewAsData: true, // identify if you are sending preview data only and not the raw markup
        initialPreviewFileType: 'image', // image is the default and can be overridden in config below
        maxFileCount: 3,
        maxTotalFileCount: 5,
        overwriteInitial: false,
        allowedFileExtensions: ["jpg", "jpeg", "png"],
        maxFileSize: 50000,
        initialCaption: "Acceptable files are, \"jpg\", \".jpeg\" and \".png\"",//,
        initialPreview: getInitialPreview(), //existingDocs.initialPreview,
        initialPreviewConfig: getInitialPreviewConfig(),//existingDocs.initialPreviewConfig       
        showUpload: false
    });
    
    // Confirmation prompt for removing an uploaded file
    $("#input-fa").on("filepredelete", function(jqXHR) {
        var abort = true;
        if (confirm("Are you sure you want to delete this file?")) {
            abort = false;
        }
        return abort; // you can also send any data/object that you can receive on `filecustomerror` event
    });
    $("#add-product-images").fileinput({
        theme: "fas",
        showUpload: false,
        fileActionSettings: { showZoom: false},
        indicatorNewTitle: 'Not uploaded yet',

        // uploadUrl: BASE_URL+"upload-file/",
        // uploadAsync: false,
        // initialPreviewAsData: true, // identify if you are sending preview data only and not the raw markup
        // initialPreviewFileType: 'image', // image is the default and can be overridden in config below
        maxFileCount: 5,
        maxTotalFileCount: 5,
        overwriteInitial: false,
        allowedFileExtensions: ["jpg", "jpeg", "png"],
        maxFileSize: 50000,
        initialCaption: "Acceptable files are, \"jpg\", \".jpeg\" and \".png\""//,
        // initialPreview: , //existingDocs.initialPreview,
        // initialPreviewConfig: //existingDocs.initialPreviewConfig       
    });
});

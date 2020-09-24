//allows the visibility of a password
function myFunction() {
    var x = document.getElementById("pass");
    if (x.type === "password") {
      x.type = "text";
    } else {
      x.type = "password";
    }
}
function displayBothPasswords(){
    var nPass = document.getElementById("newPass");
    var cPass = document.getElementById("confirmPass");

    if (nPass.type === "password") {
      nPass.type = "text";
      cPass.type = "text";

    } else {
      nPass.type = "password";
      cPass.type = "password";
    }

}
// //used for changing a password outside of the system
// function checkChangePassMatch() {
//   console.log('test');
//   //$('#submit-button').prop('disabled', true);
//   var decimal =  /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,16}$/;//rules that govern what is required of a password
//   var password = $("#newPassword").val();
//   var confirmPassword = $("#confirmPassword").val();

//   if (password != confirmPassword && confirmPassword != ''){
   
//       $('#change-pass-btn').prop('disabled', true);
//       $("#divCheckPasswordMatch").html("<span class='text-danger ml-4'>Passwords do not match!</span>");
  
//   }else if(password == '' || confirmPassword == ''){

//       $("#divCheckPasswordMatch").html(" ");
//       $("#passRequirement").html(" ");
//       $('#change-pass-btn').prop('disabled', true);
  
//   }else{
      
//       $("#divCheckPasswordMatch").html("<span class='text-success ml-4'> Passwords match.</span>");
      
//       if (password.match(decimal)){

//           $("#passRequirement").html(" ");
//           $('#change-pass-btn').prop('disabled', false);

//       }else{

//           $("#passRequirement").html("<span class='text-danger ml-4'>Please meet the requirement stated above!</span>");

//       }
//   }
// }
var readURL = function(input) {
  if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function (e) {
          $('.avatar').attr('src', e.target.result);
      }
      
      $('#upload-business-logo').show();
      $('#remove-company-logo').show();
      // $('#remove-client-img').show();//displaying remove image button for editing a client
      // $('#remove-appli-img').show(); //displaying remove image button for adding a client

      reader.readAsDataURL(input.files[0]);
  }
}

//on document ready

$(document).ready(function(){

  //Setting bootstrap tooltips 
  $('[data-toggle="tooltip"]').tooltip()

  //disables all inputs inside myprofile form
  $("#myProfile :input").prop("disabled", true);
  //diables all inputs in myCompanyProfile form
  $("#myCompanyProfile :input").prop("disabled", true);
  // $("#upload-business-logo").addClass("disabled");
  // $("#add-export-market").addClass("disabled");


  // for profile edit button clicked
  $('#editMyProfile').click(function(e){
    e.preventDefault();

    $(this).hide();
    $("#saveMyProfile").show();
    $("#myProfile :input").prop("disabled", false);

  });
  // for profile save button clicked
  $('#saveMyProfile').click(function(e){
    e.preventDefault();
    
    let url = $('#myProfile').attr('action');
    let data = $('#myProfile').serialize();

    $(this).hide();
    $("#editMyProfile").show();
    $("#myProfile :input").prop("disabled", true);

    $.post( url, data, function( data ) {
      alert(data);
      console.log(data);
    });
    
  });
  //for my company profile edit btn click
  $('#editCompanyProfile').click(function(){

    $(this).hide();
    $("#saveCompanyProfile").show();
    $("#myCompanyProfile :input").prop("disabled", false);
    $("#upload-business-logo").removeClass("disabled");
    $("#remove-company-logo").removeClass("disabled");
    $("#add-export-market").removeClass("disabled");

  });
  //for my company profile save btn clicked
  $('#saveCompanyProfile').click(function(){
    
    let url = $('#myCompanyProfile').attr('action');
    let data = new FormData(document.getElementById("myCompanyProfile"));

    console.log (data);

    $(this).hide();
    $("#editCompanyProfile").show();
    $("#myCompanyProfile :input").prop("disabled", true);
    $("#upload-business-logo").addClass("disabled");
    $("#remove-company-logo").addClass("disabled");
    $("#add-export-market").addClass("disabled");
    
    $.ajax({
      url: url,  
      type: 'POST',
      data: data,
      success:function(data){
          alert(data);
          // console.log(JSON.parse(data));
      },
      cache: false,
      contentType: false,
      processData: false
  });
    
    
  });

    // when uploading a profile picture of a client
  $(".file-upload").on('change', function(){
    readURL(this);

  });
  // triggered when uploading a clients pic on updating their profile
  $('#upload-business-logo').click(function (e){
      e.preventDefault();
      $('.file-upload').click();
      

  });
  $('#remove-company-logo').on('click', function (e){
      e.preventDefault();
      
      $('#business-logo').attr("src", "http://localhost/exportBelize/images/business_icon.png");
      $('#remove-company-logo').hide();

  });
  //adding more export market export
  $('#add-export-market').click(function (e){
    e.preventDefault();

    $newField = 
      '<div class="col-md-6 col-12 mb-3">'+
        '<label for="">Export Market <sub id="export-market-'+marketOptionCount+'">#'+marketOptionCount+'</sub></label>'+
          '<div class="input-group">'+
              '<input type="hidden" name="exportMarkets['+ marketOptionCount +'][companyId]" value="'+ companyId +'">'+
              '<select name="exportMarkets['+(marketOptionCount)+'][exportMarketId]" class="form-control">'+
              exportMarketOptions +
              '</select>'+
              '<div class="input-group-append">'+
                  '<button class="remove-export-market btn btn-danger"><i class="fa fa-minus"></i></button>'+
              '</div>'+
          '</div>' +
      '</div>';
    marketOptionCount++;
    $('#export-market-list').append($newField);
    
  });
  //removing a export market
  $('#export-market-list').on('click', '.remove-export-market', function (e){
    e.preventDefault();
    let exportMarketListId = $(this).val();

    if (exportMarketListId != ''){
      $.post(BASE_URL+'', {'id': exportMarketListId, 'ajaxRequest' : 'removeExportMarket'}, function (data){
        alert(data);
      });
    }    
    $(this).parent().parent().parent().remove();
  });

  //ADDS A PRODUCT CARD
  $('#add-product-card').click(function (e){
    e.preventDefault();
    
    let html = '<div class="row pb-3">' +
                  '<div class="col-12">'+
                      '<div class="card shadow">'+
                          '<div class="card-header">'+
                              '<a class="card-link" data-toggle="collapse" data-target="#product-2" href="#">'+
                              'Collapsible Group Item #1'+
                                '<span class="float-right"><i class="fa fa-chevron-down"></i><span>'+
                              '</a>'+
                          '</div>'+
                      '<div id="product-2" class="collapse show">'+
                              '<div class="card-body">'+
                                  'lorem ipsum.'+
                              '</div>'+
                          '</div>'+
                      '</div>'+
                  '</div>'+
              '</div>'
    ; 
    
    $('#product-list').append(html);

  });

  $('.remove-product').click(function(e){
    e.preventDefault();

    var deleteLink = $(this).attr('href');
    var productName = $(this).parent().parent()[0].children[2].innerText;
    
    $('#deleteProductHref').attr('href', deleteLink);
    $('#deleteProductTitle').text(productName);

    $('#deleteProductModal').modal('show');

  });
});
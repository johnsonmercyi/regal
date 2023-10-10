// M.AutoInit();
// $('#submit').click(function(e){
//     e.preventDefault();
//     //var _token = $("input[name='_token']").val();
//     var firstName = $("input[name='firstName']").val();
//     var lastName = $("input[name='lastName']").val();
//     var othername = $("input[name='otherName']").val();
//     var DOB = $("input[name='DOB']").val();
//     var gender = $("input[name='gender']:checked").val();
//     var stateOfOriginId = $("select[name='stateOfOriginId']").val();
//     var lgaOfOriginId = $("select[name='lgaOfOriginId']").val();
//     var homeTown = $("input[name='homeTown']").val();
//     var homeAddress = $("input[name='homeAddress']").val();
//     var password = $("input[name='password']").val();
//     var cPassword = $("input[name='cPassword']").val();
//     var email = $("input[name='email']").val();
//     var address = $("input[name='homeAddress']").val();
//     var schoolNameId = $("select[name='schoolId']").val();
//     var phoneNo = $("input[name='phoneNo']").val();
//     var positionId = $("select[name='position']").val();
//     var salaryGradeId = $("select[name='salaryGrade']").val();
//     var rankId = $("select[name='rankId']").val();
//     var appointmentDate = $("input[name='appointmentDate']").val();
//     var religionId = $("select[name='religion']").val();
//     var maritalStatusId = $("select[name='maritalStatus']").val();

//     if(password !== cPassword){
//         M.toast({html:'Passwords must Match'});
//     } else {
//         $.ajax({
//             url: "/staff",
//             type:'POST',
//             beforeSend: function (xhr, type) {
//                 if(!type.crossDomain) {
//                     xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
//                 }
//             },
//             data: {firstName:firstName, lastName:lastName, otherName:othername, email:email, address:address, DOB:DOB, gender:gender, stateOfOriginId:stateOfOriginId,
//                     lgaOfOriginId:lgaOfOriginId, homeTown:homeTown, homeAddress:homeAddress, password:password, cPassword:cPassword, schoolNameId:schoolNameId, phoneNo:phoneNo, positionId:positionId, salaryGradeId:salaryGradeId, rankId:rankId, appointmentDate:appointmentDate,
//                     religionId:religionId, maritalStatusId:maritalStatusId},
//             success: function(data) {
//                 if($.isEmptyObject(data.error)){
//                     M.toast({html:'You have successfully created a Staff !'});
//                     function goToStaff(){
//                         window.location.replace('/staff');/**Redirect to the homepage after succesful update*/
//                     }
//                     setTimeout(goToStaff(), 3000);
//                 }else{
//                     M.toast({html:'Please fill out all fields'});
//                     //printErrorMsg(data.error);
//                 }
//             }
        
//         });
//     }
// });
// $('.fetchTeachersData').click(function(e){
//     e.preventDefault();
//     /**
//      * I use the data attribute HTML to fetch all the pertaining data from Database
//      **/
//     let parent = $(this).parents('tr');
//     let id = parent.attr('id');

//     //console.log(parent, id);

//     let fName = $(this).attr('data-FName');
//     let lName = $(this).attr('data-lName');
//     let oName = $(this).attr('data-oName');
//     let school = $(this).attr('data-skool');
//     let schoolLga = $(this).attr('data-schoolLga');
//     let dob = $(this).attr('data-dob');
//     let state =  $(this).attr('data-state');
//     let lga =  $(this).attr('data-lgaOfOrignId');
//     let address =  $(this).attr('data-addrress');
//     let homeTown =  $(this).attr('data-hometown');
//     let email = $(this).attr('data-email');
//     let gender = $(this).attr('data-gender');
//     let religion = $(this).attr('data-religion');
//     let position =  $(this).attr('data-position');
//     let salary = $(this).attr('data-salary');
//     let rank = $(this).attr('data-rank');
//     let appoint = $(this).attr('data-appoint');
//     let phone = $(this).attr('data-phone');
//     let maritalStat = $(this).attr('data-maritalStat');
//     let img = $(this).attr('data-img');
//     let dateCreated = $(this).attr('data-create');
//     let dateModified = $(this).attr('data-modify');

//     $('.fetchEditStaffForm').attr('data-id', id);
    
//     /**
//      * Container to hold the data fetched from Database
//      **/
//     //$(' #iden').append('<span id="ide">'+id+'</span>')
//     $('#fetchMore #image').append(img);
//     $('#fetchMore #fName').append('<span id="FName">'+fName+'</span>');
//     $('#fetchMore #lName').append('<span id="LName">'+lName+'</span>');
//     $('#fetchMore #oName').append('<span id="OName">'+oName+'</span>');
//     $('#fetchMore #school').append('<span id="Skool">'+school+'</span>');
//     $('#fetchMore #states').append('<span id="State">'+state+'</span>');
//     $('#fetchMore #lga').append('<span id="LGA">'+lga+'</span>');
//     $('#fetchMore #DOB').append('<span id="dob">'+dob+'</span>');
//     $('#fetchMore #pAddress').append('<span id="PAddress">'+address+'</span>');
//     $('#fetchMore #hometown').append('<span id="HomeTown">'+homeTown+'</span>');
//     $('#fetchMore #email').append('<span id="Email">'+email+'</span>');
//     $('#fetchMore #schoolLga').append('<span id="SchoolLga">'+schoolLga+'</span>');
//     $('#fetchMore #phone').append('<span id="phony">'+phone+'</span>');
//     $('#fetchMore #gender').append('<span id="Gender">'+gender+'</span>');
//     $('#fetchMore #religion').append('<span id="Religion">'+religion+'</span>');
//     $('#fetchMore #maritalStat').append('<span id="marital">'+maritalStat+'</span>');
//     $('#fetchMore #position').append('<span id="pos">'+position+'</span>');
//     $('#fetchMore #salaryGrade').append('<span id="Salarygrade">'+salary+'</span>');
//     $('#fetchMore #rank').append('<span id="Rank">'+rank+'</span>');
//     $('#fetchMore #appointment').append('<span id="appoint">'+appoint+'</span>');
//     $('#fetchMore #dateCreated').append('<span id="DateCreated">'+dateCreated+'</span>');
//     $('#fetchMore #dateModified').append('<span id="DateModified">'+dateModified+'</span>');


//     $('#showTable').addClass('hide')
//     $('#fetchMore').removeClass('hide')
    
// });

// $('.fetchEditStaffForm').on('click', function(e){
//     e.preventDefault();

//     var grandP = $(this).parents('div.staff');
//     var id = $(this).attr('data-id');
//     var grandPSibling = grandP.next();
    
//     /**
//      * Find the corresponding ids.
//      */
//     var fName = grandPSibling.find('#FName').html();
//     var lName = grandPSibling.find('#LName').html();
//     var oName = grandPSibling.find('#OName').html();
//     var school = grandPSibling.find('#Skool').html();
//     var state = grandPSibling.find('#State').html();
//     var lga = grandPSibling.find('#LGA').html();
//     var schoolLga = grandPSibling.find('#SchoolLga').html();
//     var email = grandPSibling.find('#Email').html();
//     var Address = grandPSibling.find('#PAddress').html();
//     var hometown = grandPSibling.find('#HomeTown').html();
//     var dob = grandPSibling.find('#dob').html();
//     var maritalStat = grandPSibling.find('#marital').html();
//     var position = grandPSibling.find('#pos').html();
//     var religion = grandPSibling.find('#Religion').html();
//     var phone = grandPSibling.find('#phony').html();
//     var salary = grandPSibling.find('#Salarygrade').html();
//     var rank = grandPSibling.find('#Rank').html();
//     var appoint = grandPSibling.find('#appoint').html();
//     //var id = grandPSibling.find('#ide').html();
//     //console.log(id,school, fName, lName, oName,state,lga,schoolLga, email, Address,hometown,dob,maritalStat,position,religion, phone,salary, rank,appoint);
    
        
//     $('#editId').val(id);
//     $('#editFirstName').val(fName);
//     $('#editLastName').val(lName);
//     $('#editOtherName').val(oName);
   
//     $('#editDOB').val(dob);
//     $('#editEmail').val(email);
//     $('#editHomeTown').val(hometown);
//     $('#editPhoneNumber').val(phone);
//     $('#editAppointDate').val(appoint);
//     $('#schoolNameId').val(school);
//     $('#schoolNameId').formSelect();
    
//     $('#stateOfOriginId').val(state);
//     $('#stateOfOriginId').formSelect();
//     $('#maritalStatusId').val(maritalStat);
//     $('#maritalStatusId').formSelect();//Materialize(Form-Select Init.
//     $('#lgaOfOriginId').val(lga);
//     $('#lgaOfOriginId').formSelect();
//     $('#schoolLGAId').val(schoolLga);
//     $('#schoolLGAId').formSelect();//Materialize(Form-Select Init
//     $('#editPostalAddress').val(Address);
    
//     $('#positionId').val(position);
//     $('#positionId').formSelect();
//     $('#salaryGradeId').val(salary);
//     $('#salaryGradeId').formSelect();//Materialize(Form-Select Init.
//     $('#rankId').val(rank);
//     $('#rankId').formSelect();
//     $('#religionId').val(religion);
//     $('#religionId').formSelect();//Materialize(Form-Select Init.
//     $('#editId').val(id);


//     $('#fetchMore').addClass('hide')
//     $('#staffFormToEdit').removeClass('hide')

// })

// $('#editStaffForm').submit(function(e){
//     e.preventDefault();
   

//     const staffObj = {};

//     staffObj.id = $('#editId').val();
//     staffObj.firstName = $('#editFirstName').val();
//     staffObj.lastName = $('#editLastName').val();
//     staffObj.otherName = $('#editOtherName').val();
//     staffObj.DOB = $('#editDOB').val();
//     staffObj.maritalStatusId = $('#maritalStatusId').val();
//     staffObj.email = $('#editEmail').val();
//     staffObj.homeAddress = $('#editPostalAddress').val();
//     staffObj.homeTown = $('#editHomeTown').val();
//     staffObj.religionId = $('#religionId').val();
//     staffObj.stateOfOriginId = $('#stateOfOriginId').val();
//     staffObj.lgaOfOriginId = $('#lgaOfOriginId').val();
//     staffObj.schoolLGAId = $('#schoolLGAId').val();
//     staffObj.phoneNo= $('#editPhoneNumber').val();
//     staffObj.appointmentDate = $('#editAppointDate').val();
//     staffObj.schoolNameId =  $('#schoolNameId').val();
//     staffObj.positionId = $('#positionId').val();
//     staffObj.salaryGradeId = $('#salaryGradeId').val();
//     staffObj.rankId = $('#rankId').val();
    
//     $.ajax({
//         url:'/staff',
//         type:'PUT',
//         beforeSend: function (xhr, type) {
//             if(!type.crossDomain) {
//                 xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
//             }
//         },
//         data:{
//             data: JSON.stringify(staffObj)
//         },
//         success:function(data){
//             if(data == data) {
//                 M.toast({html:'Success'});
//                 function goToStaff(){
//                     window.location.replace('/staff');/**Redirect to the homepage after succesful update*/
//                 }
//                 setTimeout(goToStaff(), 3000);
//             } else {
//                 M.toast({html:'Not Success'});
//             }
//             console.log(data);
//         },error:function(xhr, status, error){
//             M.toast({html:error});
//         }
//     })  
// }) 

// $('#backToAllStaff').on('click', function(e){
//     e.preventDefault();

//     $('#showTable').removeClass('hide')
//     $('#fetchMore').addClass('hide')
// });


// $('#backToStaff').on('click', function(e){
//     e.preventDefault();

//     $('#fetchMore').removeClass('hide')
//     $('#staffFormToEdit').addClass('hide')
// });


// $('.deleteStaffData').on('click', function(e){
//     e.preventDefault();
    
//     var parent = $(this).parents('tr')
//     var id = parent.attr('id');

//     $('#deleteStaffId').val(id);
// });

//     //Button for delete, if yes was clicked
//  $('.delStaff').on('click', function(e){
//     e.preventDefault();
//     var id = $('#deleteStaffId').val();//Hiiden input in the form to collect the specific ID.
//     if(id !== null){
//         $.ajax(
//             {
//                 url: "staff/"+id,
//                 type: 'DELETE',
//                 beforeSend: function (xhr, type) {
//                     if(!type.crossDomain) {
//                         xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
//                     }
//                 },
//                 data: {
//                     "id": id,
//                 },
//                 success: function (data){
//                     if(data){
//                         M.toast({html:'Deletion Success'});
//                         setTimeout(() => {
//                             location.reload();
//                         }, 3500 )
//                     } else {
//                         M.toast({html:'Nothing to delete'});
//                     }
//                 }, error:function(){
//                         M.toast({html:'Error deleting!'});
//                 }, timeout: 1200,
//         })
//     } else {
//         M.toast({html:'ID Not FOUND!'});
//     }
// });



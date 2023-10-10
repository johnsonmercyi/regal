M.AutoInit();
$('#schoolForm').submit(function(e){
    e.preventDefault();

    var formData = new FormData(this);
    console.log(formData)
    $.ajax({
    
    type:'POST',
    url: '/schools',
    data:formData,
    cache:false,
    dataType:'JSON',
    contentType: false,
    processData: false,
    beforeSend: function (xhr, type) {
        if(!type.crossDomain) {
            xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
        }
    }, 
    success:function(data){
        if($.isEmptyObject(data.error)){

            M.toast({html:'School Created successfully'});
            function gotoSchool(){
                window.location.replace('/schools/');/**Redirect to the homepage after succesful update*/
            }
            setTimeout(gotoSchool(), 1000);
           
            console.log(data)
            
        } else{
            M.toast({html:'Please fill out the form properly.'+''+(data.error)});
        }
       
    },
    error: function(data){
        
        console.log(data)
       
    }
});

    
});
$('.schoolData').click(function(e){
    e.preventDefault();
    /**
     * I use the data attribute HTML to fetch all the pertaining data from Database
     **/
    let parent = $(this).parents('tr');
    let id = parent.attr('id');

    let imageLogo = $('#'+id+' .imageLogo').html();
    let schoolName = $('#'+id+' .schoolName').html();
    let principalName = $('#'+id+' .principalName').html();
    let regions = $('#'+id+' .regionId').html();
    let region = $(this).attr('data-region');
    let motto = $(this).attr('data-motto');
    let address = $(this).attr('data-address');
    let signature = $(this).attr('data-signature');
    let imgLogoData = $(this).attr('data-img');
    let signatures = $(this).attr('data-signatures');
    
    

    $('.fetchEditSchoolForm').attr({'data-id': id, 'data-regions': region, 'data-image': imgLogoData, 'data-sign': signatures});
    $('#moreData #imageLogo').append('<span id="schoolLogo">'+imageLogo+'</span>');
    $('#moreData #signatureLogo').append('<span id="skoolSignature">'+signature+'</span>');
    $('#moreData #nameOfSchool').append('<span id="skoolName">'+schoolName+'</span>');
    $('#moreData #addressOfSchool').append('<span id="skoolAddress">'+address+'</span>');
    $('#moreData #nameOfPrincipal').append('<span id="principal">'+principalName+'</span>');
    $('#moreData #schoolRegion').append(regions);
    $('#moreData #schoolMotto').append('<span id="mottoForSchool">'+motto+'</span>');

    $('#schoolTable').addClass('hide')
    $('#moreData').removeClass('hide')
    
});
//This function appends the current value of the form to itself 
$('.fetchEditSchoolForm').click(function(e){
    e.preventDefault();
    
    //On Image hover 
    $('a.imageLogos').hover(function(){
        $('#coverLogo').toggleClass('hide');
    });
    $('a.imageSignature').hover(function(){
        $('#coverSignature').toggleClass('hide');
    });

    var highestOrder = $(this).parents('div.school');
    var id = $(this).attr('data-id');
    var lowestOrder = highestOrder.next();
    
    /**
     * Find the corresponding ids.
     */
    var schoolName = lowestOrder.find('#skoolName').html();
    var schoolAddress = lowestOrder.find('#skoolAddress').html();
    var principal = lowestOrder.find('#principal').html();
    var motto = lowestOrder.find('#mottoForSchool').html();
    let region = $(this).attr('data-regions');
    let imglogodat = $(this).attr('data-image');
    let signature = $(this).attr('data-sign');

    $('#schoolName').val(schoolName);
    $('#schoolAddress').val(schoolAddress);
    $('#motto').val(motto);
    $('#principalName').val(principal);
    $('#region_id').val(region);
    $('#region_id').formSelect();
    $('#imageLogos img').attr('src', '/images/logo/'+imglogodat);
    $('#imageSignature img').attr('src', '/images/signature/'+signature);
    $('#schoolFormId').val(id);
   

    $('#moreData').addClass('hide')
    $('#schoolEditForm').removeClass('hide')
});

$('#editSchoolForm').submit(function(e){
    e.preventDefault();
    
    let formData = new FormData(this);
    let id = $('#schoolFormId').val();
    $.ajax({
        type:'POST',
        url: '/schools/'+ id,
        beforeSend: function (xhr, type) {
            if(!type.crossDomain) {
                xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
            }
        },
        data:formData,
        cache:false,
        dataType:'JSON',
        contentType: false,
        processData: false,
        success:function(data){
            if($.isEmptyObject(data.error)){
                M.toast({html:'School Updated successfully'});
                function gotoSchool(){
                    window.location.replace('/schools/');/**Redirect to the homepage after succesful update*/
                }
                setTimeout(gotoSchool(), 3000);
            }
        },
        error:function(xhr, status, error){
            M.toast({html:error});
        },
    })
});

$('.deleteSchoolData').on('click', function(e){
    e.preventDefault();
    
    var parent = $(this).parents('tr')
    var id = parent.attr('id');

    $('#deleteSchoolId').val(id);
});

//Button for delete, if yes was clicked
$('.delSchool').on('click', function(e){
    e.preventDefault();
    var id = $('#deleteSchoolId').val();//Hiiden input in the form to collect the specific ID.
    if(id !== null){
        $.ajax(
            {
                url: "/schools/"+id,
                type: 'DELETE',
                beforeSend: function (xhr, type) {
                    if(!type.crossDomain) {
                        xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
                    }
                },
                data: {
                    "id": id,
                },
                success: function (data){
                    if(data){
                        M.toast({html:'Deletion Success'});
                        setTimeout(() => {
                            location.reload();
                        }, 3500 )
                    } else {
                        M.toast({html:'Nothing to delete'});
                    }
                }, error:function(){
                        M.toast({html:'Error deleting!'});
                }, timeout: 1200,
        })
    } else {
        M.toast({html:'ID Not FOUND!'});
    }
});

function onKeyUp() {
    var input, filter, table, tr, td, txtValue;
    input = document.getElementById('search');
    filter = input.value.toUpperCase();
    table = document.getElementById('searcher');
    tr = table.getElementsByTagName('tr');
    console.log(table)
    //Loop through all List items, and hide those who don;t match the search query
    for(i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName('td')[2];
        if(td){
            txtValue = td.textContent || td.innerText;  
            if(txtValue.toUpperCase().indexOf(filter) > -1){
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            } 
        }
        
    }
}
//Onclick of change logo or signature, performm the tasks below
$('.imageLogos').click(function(e){
    e.preventDefault();

    $('#imageLogos').addClass('hide')
    $('#logoSchool').removeClass('hide')
});
$('.imageSignature').click(function(e){
    e.preventDefault();

    $('#imageSignature').addClass('hide')
    $('#imgSignature').removeClass('hide')
});
$('#backToMySchool').click(function(e){
    e.preventDefault();
    $('#schoolEditForm').addClass('hide');
    $('#moreData').removeClass('hide');
});

$('#backToAllSchool').click(function(e){
    e.preventDefault();
    $('#moreData').addClass('hide');
    $('#schoolTable').removeClass('hide');
    location.reload();
});

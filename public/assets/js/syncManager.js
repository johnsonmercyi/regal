document.addEventListener('DOMContentLoaded', function(){
    const tables = document.querySelector('#tableInput').value;
    const startBtn = document.querySelector('#startBtn');
    const picsBtn = document.querySelector('#picsBtn');
    const tableArr = JSON.parse(tables);
    const showCountSync = document.querySelector('#countSync');
    let countSync = 0;
    const syncStatus = document.querySelector('#syncStatus');
    const schoolId = document.querySelector('#schoolId').value;
    let pixCon = [];
    let originLen = 0;
    let processed = {offline:{"certificates":[], "passports":[]}};

    // document.querySelector('#lenTables').textContent = tableArr.length;

    startBtn.addEventListener('click', (e)=>{
        e.preventDefault();
        startBtn.setAttribute('disabled', 'true');
        syncStatus.classList.remove('hide');
        document.querySelector('.progress').classList.remove('hide');

        // tableArr.forEach((tb, i) => {
        //     console.log(i+1);
            
        //     AJAXJS({'table': tb}, 'POST', '/sync/table', moveOnline)
        // })
        let n = tableArr.length, m = 0;
        while (m < n) {
            AJAXJS({'table': tableArr[m]}, 'POST', '/sync/table', true, moveOnline)
            m++;
        }
    })
    
    
    /******FUNCTION TO MOVE DATA ONLINE***** */
    function moveOnline(res){
        if(res.data.length === 0){
            syncComplete()
            // console.log(res.table, 'empty');
            return false;
        } else {
            // console.log(res.table, res.data);
            
            AJAXJS(res, 'POST', 'https://tc.archeducomonitsha.org.ng/api/sync/receive', false, updateSync)    
            // AJAXJS(res, 'POST', 'http://127.0.0.1:8004/api/sync/receive', false, updateSync)    
        }
    }
    
    /****FUNCTION TO UPDATE SYNC STATUS OFFLINE**** */
    // function updateSync(res){
    //     if(res.status == 'Success'){
    //         AJAXJS(res, 'POST', '/sync/update', true, (res2)=>{
    //             if(res2.success){
    //                 syncComplete()
    //             }
    //         })
    //     } else {
    //         startBtn.setAttribute('disabled', 'false');
    //         // document.querySelector('.progress').classList.add('hide');
    //         console.log(res.table);
    //         M.toast({html: '<h5>An error occured. Please try again!</h5>', classes: 'red'}, 4000);
    //     }
    // }
    function updateSync(res){
        if(res.status == 'Success'){
            AJAXJS(res, 'POST', '/sync/update', true, (res2)=>{
                if(res2.success && res2.data.length == 0){
                    syncComplete()
                }
                if(res2.data.length > 0){
                    AJAXJS(res2, 'POST', 'https://tc.archeducomonitsha.org.ng/api/sync/receive', false, updateSync)    
                    // AJAXJS(res2, 'POST', 'http://127.0.0.1:8004/api/sync/receive', false, updateSync)    
                }
            })
        } else {
            startBtn.setAttribute('disabled', 'false');
            // document.querySelector('.progress').classList.add('hide');
            console.log(res.table);
            M.toast({html: '<h5>An error occured. Please try again!</h5>', classes: 'red'}, 4000);
        }
    }

    function syncComplete(){
        // let currCount = countSync  + 1;
        countSync++;
        let currCent = Math.ceil(( countSync / tableArr.length) * 100)  ;
        showCountSync.textContent = currCent;

        if(countSync == tableArr.length){
            M.toast({html: '<h5>Synchronization is complete!</h5>', classes: 'green'}, 5000);
            // startBtn.setAttribute('disabled', 'false');
            document.querySelector('.progress').classList.add('hide');
        }
    }

    
    // let sentCount = 0;
    picsBtn.addEventListener('click', (e)=>{
        e.preventDefault();
        // syncStatus.classList.remove('hide');
        // document.querySelector('.progress').classList.remove('hide');
        // let pixCon = document.querySelector('#pixCon');
        document.querySelector('.progress').classList.remove('hide');
        
        AJAXJS({}, 'POST', 'https://tc.archeducomonitsha.org.ng//api/sync/pix/check/'+schoolId, false, uploadCallBack)
        // AJAXJS({}, 'POST', 'http://127.0.0.1:8004/api/sync/pix/check/'+schoolId, false, uploadCallBack)  
    })

    function uploadCallBack(res){
        // console.log(res.offline);
        AJAXJS(res, 'POST', '/sync/pix/'+schoolId, true, (res2)=>{
            originLen = res2.notOnline.length;
            // console.log(res2.notOnline);
            if(res2.notOnline.length > 0) {
                let arrlen = res2.notOnline.length;
                res2.notOnline.forEach(pix => {
                    // console.log(pixCon.length)
                    let mimsplit = pix.filename.split('.');
                    let foldername = pix.filefolder == 'certificates' ? 'photo/school' : 'passports';
                    let mimtyp = mimsplit[mimsplit.length-1];
                    // Check if image and decrement else
                    // if(!['jpg', 'jpeg', 'png'].includes(mimtyp)){arrlen--;}
                    if(pix.filefolder == 'passport'){
                        let imgname = pix.filename;
                        pixCon.push({imgname, img: pix.baseImg, folder: pix.filefolder})
                        processed.offline.passports.push(imgname);
                        console.log(pix.baseImg);
                        if(pixCon.length == arrlen) uploadPhotos();
                    } else {
                        previewPassport(`/storage/images/${res2.prefix}/${foldername}/${pix.filename}`, 
                            mimtyp, arrlen, pix.filename, pix.filefolder)
                    }
                });
            } else {
                document.querySelector('.progress').classList.add('hide');
                M.toast({html: '<h5>Pictures are up to date!</h5>', classes: 'green'}, 4000);
            }
        })    
    }


    function previewPassport(file, mim, lent, imgname, folde){
        let compressedImg;
        // let preview = document.querySelector('#src_img');
        // let file = document.querySelector('#passportFile').files[0];
        let reader = new Image();
        reader.addEventListener('load', function(e){
            compressFile(reader, mim, lent, imgname, folde);
        }, false);
        if(file){
            try {
                reader.src = file;
            } catch (error) {
                console.log(error)
            }
        }
    }
      
      function compressFile(loadedData, mim, lent, imgname, folde){
        let quality = 10;
        let jpegArr = ['jpg', 'jpeg'];
        let mime_type = '';
        if(['jpg', 'jpeg', 'png'].includes(mim)){
            mime_type = jpegArr.includes(mim) ? "image/jpeg" : "image/png";
        } else {
            console.log('Not Picture')
            return;
        }
        let preview = document.querySelector('#preview_compress_img');
        
        try {
                
            let cvs = document.createElement('canvas');
            cvs.width = loadedData.width;
            cvs.height = loadedData.height;
            // console.log(loadedData)
            let ctx = cvs.getContext("2d").drawImage(loadedData, 0, 0);

            if(folde == 'passport'){
                let newImageData = cvs.toDataURL(mime_type, 0.1);
                let result_image_obj = new Image();
                result_image_obj.src = newImageData;
                preview.src = result_image_obj.src;
                pixCon.push({imgname, img: newImageData, folder: folde})
                processed.offline.passports.push(imgname);
            } else {
                let newImageData = cvs.toDataURL(mime_type, quality/100);
                let result_image_obj = new Image();
                result_image_obj.src = newImageData;
                preview.src = result_image_obj.src;
                pixCon.push({imgname, img: newImageData, folder: folde})
                processed.offline.certificates.push(imgname);
                // console.log(newImageData)
            }
        } catch (err) {
            console.log(err)
        }

        preview.onload = function(){

            console.log(pixCon.length, lent)
            if(pixCon.length == lent){
                console.log(pixCon, originLen);
                uploadPhotos()
            }
        }
      
      }

    function uploadPhotos(){
        AJAXJS(pixCon, 'POST', 'https://tc.archeducomonitsha.org.ng//api/sync/pix/upload/'+schoolId, false, (res)=>{
            // AJAXJS(pixCon, 'POST', 'http://127.0.0.1:8004/api/sync/pix/upload/'+schoolId, false, (res)=>{
                if(res.success){
                    pixCon.length = 0;

                    if(originLen < 20){
                        document.querySelector('.progress').classList.add('hide');
                        M.toast({html: '<h5>Pictures uploaded successfully!</h5>', classes: 'green'}, 4000);
                        return;
                    } else {
                        uploadCallBack(processed);
                    }
                    // AJAXJS({}, 'POST', 'http://127.0.0.1:8004/api/sync/pix/check/'+schoolId, false, uploadCallBack)  
                } else {
                    document.querySelector('.progress').classList.add('hide');
                    M.toast({html: '<h5>An error occured. Please try again!</h5>', classes: 'red'}, 4000);
                }
            })
    }
})





/*****************AJAX FUNCTION TO SELECT THE UNSYNCED TABLE DATA FROM DB****************** */
function AJAXJS(sendObj, actionMET, actionURL, offline, successFxn){
    let aj = new XMLHttpRequest();
    aj.open(actionMET, actionURL);
    aj.onload = ()=>{
        if(aj.status == 200){
            let returnObj = JSON.parse(aj.responseText);
            // console.log(returnObj);
            successFxn(returnObj);
        } else {
            document.querySelector('.progress').classList.add('hide');
            M.toast({html: "<h5>Error! Please Contact Admin</h5>", classes: "red"}, 4000);
        }
    };
    aj.setRequestHeader('Content-Type', 'application/json');
    if(offline){
        aj.setRequestHeader('X-CSRF-Token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    }
    aj.send(JSON.stringify(sendObj));
}
/*************END OF AJAX************** */




// function previewPassport(file, mim, lent, imgname, folde){
//     let compressedImg;
//     let previewCompress = document.querySelector('#preview_compress_img');
//     let preview = document.querySelector('#src_img');
//     // let file = document.querySelector('#passportFile').files[0];
//     let reader = new Image();
//     reader.addEventListener('load', function(e){
//         preview.onload = function(){
//             console.log(mim);
//             compressFile(this, previewCompress, mim, lent, imgname, folde);
//         }
//         preview.onerror = function(err){
//             console.log(err, reader.src)
//         }
//         preview.src = reader.src;
//         // console.log(preview.src)
//     }, false);
//     if(file){
//       reader.src = file;
//     }
// }
  
//   function compressFile(loadedData, preview, mim, lent, imgname, folde){
//     let quality = 10;
//     let jpegArr = ['jpg', 'jpeg'];
//     let mime_type = jpegArr.includes(mim) ? "image/jpeg" : "image/png";
//     console.log(loadedData.width, loadedData.height)
    
//     try {
            
//         let cvs = document.createElement('canvas');
//         cvs.width = loadedData.width;
//         cvs.height = loadedData.height;
//         // console.log(loadedData)
//         let ctx = cvs.getContext("2d").drawImage(loadedData, 0, 0);

//         // if(folde == 'passport'){
//         //     let newImageData = cvs.toDataURL(mime_type);
//         //     let result_image_obj = new Image();
//         //     result_image_obj.src = newImageData;
//         //     preview.src = result_image_obj.src;
//         //     pixCon.push({imgname, img: newImageData, folder: folde})
//         // } else {
//             let newImageData = cvs.toDataURL(mime_type, quality/100);
//             let result_image_obj = new Image();
//             result_image_obj.src = newImageData;
//             preview.src = result_image_obj.src;
//             pixCon.push({imgname, img: newImageData, folder: folde})
//         // }
//     } catch (err) {
//         console.log(err)
//     }
//         // document.querySelector('#cameraIcon').classList.add('hide');
//     // document.querySelector('#preview_compress_img').classList.remove('hide');
//     preview.onload = function(){
//         if(pixCon.length == lent){
//             console.log(pixCon);
//             AJAXJS(pixCon, 'POST', 'http://127.0.0.1:8004/api/sync/pix/upload/'+schoolId, false, (res)=>{
//                 if(res.success){
//                     document.querySelector('.progress').classList.add('hide');

//                     M.toast({html: '<h5>Pictures uploaded successfully!</h5>', classes: 'green'}, 4000);
//                 } else {
//                     M.toast({html: '<h5>An error occured. Please try again!</h5>', classes: 'red'}, 4000);
//                 }
//             })
//         }
//     }
  
//   }

// if(res2.notOnline.length > 0) {
    // let splitArr = [];
    // if(res2.notOnline.length < 10){
    //     splitArr = [res2.notOnline]
    // } else {
    //     let notCount = res2.notOnline.length;
    //     let tempArr = [];
    //     for(let i = 0; i < notCount; i++){
    //         if(i == notCount -1){
    //             tempArr.push(res2.notOnline[i])
    //             splitArr.push(tempArr);
    //         } else {
    //             if(tempArr.length < 10){
    //                 tempArr.push(res2.notOnline[i])
    //             } else {
    //                 splitArr.push(tempArr);
    //                 tempArr = []
    //                 tempArr.push(res2.notOnline[i])
    //             }
    //         }
    //     }
    // }
    // console.log(splitArr);

    // splitArr.forEach(sect => {
        // pixCon.length = 0;
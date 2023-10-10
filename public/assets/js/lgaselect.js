document.addEventListener('DOMContentLoaded', ()=>{
    const lgasjson = document.querySelector('#lgasControl') ? JSON.parse(document.querySelector('#lgasControl').value) : '';
   // const lgas = lgasjson.json();
    const stateSelect = document.querySelector('#selectState');
    const stateLgas = document.querySelector('#stateLgas');

    // if(stateSelect){

    // }
    selectState(stateSelect, lgasjson, stateLgas)


, false});

function selectState(stateSelect, lgasjson, stateLgas) {
    if(stateSelect){
        return stateSelect.addEventListener('change', () => {
            let newLgas = getlga(lgasjson);
            const lgaOption = newLgas.map(lga => `<option value=${lga['id']}> ${(lga['lga']).toLowerCase()} </option>`);
            stateLgas.innerHTML = `<option value=''>LGA Of Origin</option>${lgaOption.toString()}`;
            $('select').formSelect();
        });
    }
}

function getlga(arr){
    let stateId = document.querySelector('#selectState').value;
   // return stateId;
    // Below refactored 22/1/2020
    // for(let i =0, len=arr.length; i<len; i++){
    //     if (arr[i]['stateId'] == stateId){
    //         matchLgas.push(arr[i]);
    //     }
    // }
    const matchLgas = arr.filter(lga => lga.state_id == stateId);

    return matchLgas;
}
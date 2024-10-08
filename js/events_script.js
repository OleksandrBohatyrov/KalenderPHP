function disableBtn(){
    document.getElementById('events-btn').disabled=true;

}
function fieldsValidation(){
    let title  =document.getElementById('title');
    let desc  =document.getElementById('description');

    let time1=document.getElementById('start_time');
    let time2=document.getElementById('end_time');

    let btn=document.getElementById('events-btn');

    if(title.value.trim() !== "" &&
        desc.value.trim()!== "" &&
        time1.value.trim()!== "" &&
        time2.value.trim()!== ""){
        btn.disabled=false;
    }
    else {
        btn.disabled=true;

    }
}
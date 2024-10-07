function disableBtn(){
    document.getElementById('rem-btn').disabled=true;

}
function fieldsValidation(){


    let id=document.getElementById('event_id');
    let time=document.getElementById('reminder_time');

    let btn=document.getElementById('rem-btn');

    if(id.value.trim() !== "" &&
        time.value.trim()!== ""){
        btn.disabled=false;
    }
    else {
        btn.disabled=true;

    }
}
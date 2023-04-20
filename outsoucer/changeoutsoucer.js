

function inputfield(buttonid,count){
    let increasebutton = document.getElementById(buttonid+'increasetext');
    let decreasebutton = document.getElementById(buttonid+'decreasetext');
    increasebutton.addEventListener('click', increasebutotnClick = function(event){
        var lasttext = document.getElementById(buttonid+(count-1));
        var newtext = document.createElement('input');
        newtext.setAttribute('name', buttonid+count);
        newtext.setAttribute('type', 'text');
        newtext.setAttribute('id', buttonid+count);
        lasttext.after(newtext);
        count++;
    });
    decreasebutton.addEventListener('click', decreasebutotnClick = function(event){
        if(count > 0){
            var removeobject = document.getElementById(buttonid+(count-1));
            removeobject.remove();
            count--;
        }
    });
    return count;
}




function inputfield(buttonid,count){
    let increasebutton = document.getElementById(buttonid+'increasetext');
    let decreasebutton = document.getElementById(buttonid+'decreasetext');
    increasebutton.addEventListener('click', increasebutotnClick = function(event){
        var lasttext = document.getElementById(buttonid+(count-1));
        var newtext = document.createElement('input');
        var brelement = document.createElement('br')
        newtext.setAttribute('name', buttonid+count);
        newtext.setAttribute('type', 'text');
        newtext.setAttribute('id', buttonid+count);
        brelement.setAttribute('id',buttonid+'br'+count);
        lasttext.after(brelement);
        brelement.after(newtext);
        count++;
    });
    decreasebutton.addEventListener('click', decreasebutotnClick = function(event){
        if(count > 0){
            var removeobject = document.getElementById(buttonid+(count-1));
            removeobject.remove();
            var removebr = document.getElementById(buttonid+"br"+(count-1));
            removebr.remove();
            count--;
        }
    });
    return count;
}


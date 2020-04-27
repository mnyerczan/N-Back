window.addEventListener('load', init);

function $(id)
{
    return document.getElementById(id);
}

function init()
{    
    $('update-img').addEventListener('change', changeImageOnUserForm);
}

function changeImageOnUserForm(e)
{   
    var img = $('output');       
    img.src = URL.createObjectURL(e.target.files[0]);    
}

window.addEventListener('load', init);

function $(id)
{
    return document.getElementById(id);
}
function changeImageOnUserForm(e)
{   
    var img = $('output');       
    img.src = URL.createObjectURL(e.target.files[0]);    
}

function init()
{    
    $('update-img').addEventListener('change', changeImageOnUserForm);    
    $('opn-usr-mdl').addEventListener('click', openModal);
    window.addEventListener('click',closeModal);
    $('usr-mdl-content').addEventListener('click', (e)=>{e.stopPropagation();}); 
}



function openModal(e)
{
    $('usr-modal').style.display = 'block';
    setInputs();
    e.preventDefault();
    e.stopPropagation();
}
function closeModal(e)
{   
    var modal = $('usr-modal');
    
    if (modal.style.display == 'block')
    {
        setInputs();
        $('usr-modal').style.display = 'none';
    }    
}


function setInputs()
{
    var dataInputs = document.getElementsByClassName('persona-data-input');
    var controlInputs = document.getElementsByClassName('controlInputs');
    var isReadOnly = false;

    for (let i = 0; i < dataInputs.length; i++) 
    {              
        if (dataInputs[i].readOnly === false)
        {
            isReadOnly = false;
            dataInputs[i].readOnly = true;
            dataInputs[i].style.backgroundColor = 'transparent';
        }                    
        else
        {
            isReadOnly = true;
            if (dataInputs[i].value != 'Admin') 
            {                        
                dataInputs[i].readOnly = false;
                dataInputs[i].style.backgroundColor = '#fffcc8';
            }
        }    
                
    }


    for (let i = 0; i < controlInputs.length; i++) 
    {
        if (isReadOnly == true)
        {
            controlInputs[i].style.display = 'block';
        }
        else
        {
            controlInputs[i].style.display = 'none';
        }        
    }
}
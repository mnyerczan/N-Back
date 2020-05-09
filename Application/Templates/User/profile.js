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
    $('show-pw').addEventListener('click', changePasswordSeems)
}

function changePasswordSeems()
{
    input = $('update-user-pw');

    if (input.type == 'password')
    {
        $('show-pw').style.color = 'blue';
        input.type = 'text';
    }        
    else
    {
        $('show-pw').style.color = 'inherit';
        input.type = 'password';
    }        
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


    for (let i = 0; i < dataInputs.length; i++) 
    {              
        if (dataInputs[i].readOnly === false)
        {
            /** inputok kikapcsolása */
            isReadOnly = false;
            dataInputs[i].readOnly = true;
            dataInputs[i].style.cursor = 'auto';
            dataInputs[i].style.backgroundColor = 'transparent';

            if (dataInputs[i].id == "update-user-pw")
            {   
                if (dataInputs[i].type !== 'password')
                {
                    dataInputs[i].type = 'password'
                }                    
                /** Ha a jelszó csupa csillag karakter, állítsa az értéket  */
                if (/[ ]+/.test(dataInputs[i].value))
                {
                    dataInputs[i].value = passValue;                
                }                
            }
        }                    
        else
        {
            isReadOnly = true;
            /** inputok bekapcsolása */
            if (dataInputs[i].value != 'Admin') 
            {                        
                dataInputs[i].readOnly = false;
                dataInputs[i].style.cursor = 'pointer';
                dataInputs[i].style.backgroundColor = '#fffcc8';
            }

            
            /** password input értékének visszaállítása */           
            if (dataInputs[i].id == "update-user-pw")
            {
                passValue = dataInputs[i].value;
                /** Ha a jelszó csupa csillag karakter, állítsa az értéket üresre */
                if (/\*+/.test(dataInputs[i].value))
                {
                    dataInputs[i].value = '';                
                }                
            }
        }    
                
    }

    if (isReadOnly) 
    {
        $('show-pw').style.display = 'block';        
    }
    else
    {
        $('show-pw').style.display = 'none';
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
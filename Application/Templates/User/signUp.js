function showImage()
{
    if( this.value )    
    {        
        _$('show-file-path').innerHTML = this.value;
    }
}

function Validattor(e)
{            

    if ( !CheckDate() ) e.preventDefault();
    if ( !CheckMail() ) e.preventDefault();
    if ( !CheckName() ) e.preventDefault();
    if ( !CheckPass() ) e.preventDefault();
   
             
}

function CheckDate()
{
    var date = _$('cu-date');   

    console.log(date.value);
    if ( date.value == '' ) 
    {
        date.previousElementSibling.innerHTML = 'Please select date!';
        date.previousElementSibling.style.color = 'darkred';    
        return false;
    }
    

    date.previousElementSibling.innerHTML = 'Date of birth';
    date.previousElementSibling.style.color = 'inherit'; 
    return true;
}

function CheckMail()
{    
    var mail = _$('cu-mail');

    if ( !/\w+@\w+\.\w+/i.test( mail.value ) )
    {
        mail.previousElementSibling.innerHTML = 'E-mail address is invalid!!';
        mail.previousElementSibling.style.color = 'darkred';  
        return false;      
    }    
    
    mail.previousElementSibling.innerHTML = 'E-mail';
    mail.previousElementSibling.style.color = 'inherit'; 
        
    return true;
}

function CheckPass()
{
    var pass = _$('cu-pass');

    if ( pass.value.length == '' ) 
    {
        pass.previousElementSibling.innerHTML = 'Password is empty!';
        pass.previousElementSibling.style.color = 'darkred';
        return false;
    }

    if ( pass.value.length < 6 ) 
    {
        pass.previousElementSibling.innerHTML = 'Password is to short!';
        pass.previousElementSibling.style.color = 'darkred';
        return false;
    }

    pass.previousElementSibling.innerHTML = 'Password';
    pass.previousElementSibling.style.color = 'inherit';

    return true;
}

function CheckName()
{      
    var name = _$('cu-name');

    /**it's simple working */    
    if ( name.value == 'Admin' ) return true;

    if ( name.value.length < 6 )
    {   
        name.previousElementSibling.innerHTML = 'Name is to short!';
        name.previousElementSibling.style.color = 'darkred';

        return false;
    }

    name.previousElementSibling.innerHTML = 'Name';
    name.previousElementSibling.style.color = 'inherit';

    return true;
}




function init()
{
    _$('cu-file').addEventListener( 'change', showImage );
    _$('create-user-form').addEventListener( 'submit', Validattor );
    _$('cu-name').addEventListener( 'keyup', CheckName );
    _$('cu-mail').addEventListener( 'keyup', CheckMail );
    _$('cu-pass').addEventListener( 'keyup', CheckPass );    
    _$('cu-date').addEventListener( 'change', CheckDate);   
}

window.addEventListener('load', init);
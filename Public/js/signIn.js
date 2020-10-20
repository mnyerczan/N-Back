function Validattor(e)
{                
    if ( !CheckMail() ) e.preventDefault();
    if ( !CheckPass() ) e.preventDefault();   

    return false;
             
}

function CheckMail()
{    
    var mail = _$('signIn-email');

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
    var pass = _$('signIn-pass');

    if ( pass.value.length == '' ) 
    {
        pass.previousElementSibling.innerHTML = 'Password is empty!';
        pass.previousElementSibling.style.color = 'darkred';
        return false;
    }

    if ( pass.value.length < 6 ) 
    {
        pass.previousElementSibling.innerHTML = 'Password is too short!';
        pass.previousElementSibling.style.color = 'darkred';
        return false;
    }

    pass.previousElementSibling.innerHTML = 'Password';
    pass.previousElementSibling.style.color = 'inherit';

    return true;
}




function init()
{    
    _$('signIn-form').addEventListener( 'submit', Validattor );    
    _$('signIn-email').addEventListener( 'keyup', CheckMail );
    _$('signIn-pass').addEventListener( 'keyup', CheckPass );     
}



window.addEventListener( 'load', init );
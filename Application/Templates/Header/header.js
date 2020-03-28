function init(e)
{
    _$('header-list').addEventListener('click', displayMenu, false);
    document.getElementsByTagName('body')[0].addEventListener('click', hideMenu, false);
}

function displayMenu(e)
{
    if ( e.target.tagName === 'IMG' )
        e.target.nextElementSibling.style.display = "table";
    e.stopPropagation();
}

function hideMenu(e)
{

    console.log(e);

    if(e.target.className !== 'hdr-icons')
    {
        var elements = document.getElementsByClassName('drop-down-container');

        for ( let i = 0; i < elements.length; i++ )
        {
            elements[i].style.display = 'none';
        }
    }

}

window.addEventListener('load', init);
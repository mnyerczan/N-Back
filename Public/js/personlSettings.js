window.addEventListener('load', () => {
    
    // Képmegjelenítő függvény feliratkozása
    $('update-img').addEventListener('change', async function () {         
        // A kiválasztott 'output' <img> tag src tulajdonságának egyenlővé
        // tesszük az URL objektum createObjectURL metódusa visszetérési értékét.
        //
        // A this az eseményt hívó node(input ebben az esetben), a files[] pedig
        // a képtartalma.
        $('output').src = URL.createObjectURL(this.files[0]);        
    });

    // Kép kiszolgáló felé elküldő és választ fogadó függvény feliratkozása
    $('update-img').addEventListener('change', async  function(e){           
        // Buborékolás megállítása
        e.preventDefault();
    
        
        var xhr = new XMLHttpRequest();
        
        
        // Üzenettőr adatobjektuma
        var formData = new FormData();
        
        
        // Fájl adatszerkezethez adása
        formData.append('image', this.files[0]);
    
    
        /// Feliratkozunk a betöltést figyelő eseményre.    
        xhr.onload = () => {
            console.log(xhr.response);
        };
    
                
        // a meghívandó URL, metódus, szinkron futás beállítása.
        xhr.open('POST', 'settings/imageUpdate', true); 
        // Headerek megadása
        //xhr.setRequestHeader('Content-Type', this.files[0].type);                              
    
        xhr.send(formData);
    });    
});

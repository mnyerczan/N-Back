window.addEventListener("load", async function(){    
    window.addEventListener("keydown", keyListener);
    engine = new Nback(12);    
    engine.load();    
    engine.start();     
});




/**
 * Sajnos az eseménykezelő nem látja az osztálymetódust inicializlás előtt.
 * @param {*} e 
 */
function keyListener(e)
{
    if(e.keyCode == 65)
        engine.checkMatch();
}


class Nback
{
    // A trials értékének lementése
    fullTrials
    // A rossz nyomásokat számolja
    wrongHit
    // A helyes találatokat számolja
    correctHit
    // A n darab indexet tartja számon a táblázatból.
    // ha van n-ik egyezés, emlékezni kell rá.
    tableItemIdxs
    // Az aktuális index
    currentIdx
    // játékos neve, nem biztos, hogy kelleni fog
    name
    // A játék szintje, ugyan az...
    level
    // A játék módja .. (süti)
    gameMode
    // események közti időtáv
    seconds
    // esemény száma
    trials    
    // esemény hossza
    eventLength
    // 
    serverUrl
    // A kocka színe
    color
    // a ciklus
    interval
    // Figyelő, hogy volt-e ebben az eventben már lenyomva az A gomb? 
    isChecked
    

    constructor()
    {
        this.wrongHit = 0;
        this.correctHit = 0;
        this.tableItemIdxs = [];
        this.currentIdx = 0;
    }



    /**
     * Megfelelő index generálásához
     * [0,3] U [5,8]
     */
    generateIdx()
    {
        var idx = 0;
        // Ha az utolsó eventnél ját az algoritmus, de még nem volt egyezés 
        if(this.trials - 1 == 0 &&  this.wrongHit == 0 && this.correctHit == 0)		
            idx =  this.tableItemIdxs[1];
        else
            do
            {
                idx =  Math.floor( Math.random() * 10 );
                
            }while( idx == 4 || idx >= 9 );
        
        this.currentIdx = idx;
        
    }

    /**
     * Start engine
     */
    start()
    {
        this.interval = setInterval( () => {
                this.engine()
            }, 
            this.seconds * 1000
        );
        this.displayLoad();
    }

    /**
     * tableItemIdxs kezelő függvény
     * Ha a tároló mérete kisebb mint a level(szint), akkor csak 
     * egyesével hozzáadja az új elemet (currentIdx), ha már ugyan akkora,
     * akkor az utolsót mindig kitörli és csak utána adja hozzá.
     */
    itemIdxsHandler()
    {         
        // A tárolónak egyel több elemet kell tárolnia
        if (this.tableItemIdxs.length < parseInt(this.level) + 1)
            this.tableItemIdxs.push(this.currentIdx);         
        else {
            this.tableItemIdxs.shift();
            this.tableItemIdxs.push(this.currentIdx);
        }
    }


    /**
     *  A keyEventre a függvény megvizsgálja, hogy a tömb első eleme
     * egyezik-e az utolsóval(helyes-e a találat). Ennek függvényében növeli a helyes,
     * vagy a hibás tatálatok számát.
     */
    checkMatch()
    {
        // A tárolónak egyel több elemet kell tárolnia
        if (this.tableItemIdxs.length != parseInt(this.level) + 1) {
            $("nback-feedback").style.color = "#66f";
            return; 
        }
            
        // még nem checkelt az event
        if ( this.isChecked) return;         
        
        if (this.tableItemIdxs[0] == this.tableItemIdxs[this.tableItemIdxs.length - 1]) {            
            this.correctHit++;
            $("nback-feedback").style.color = "#6f6";
        }
        else {
            $("nback-feedback").style.color = "#f66";
            this.wrongHit++;
        }                    

        // Beállítjuk a figyelőt. Ha az evenhez már volt checkelés, nem engedjük újra
        // növelni a számlálókat.
        this.isChecked = 1;
    }


    /**
     * Az algoritmus automatikusan felülvizsgálja a váltás előtt,
     * hogy volt-e egyezés és a játékos reagált-e rá.
     */
    automateCheck()
    {        
        if (this.tableItemIdxs.length != parseInt(this.level) + 1 || this.isChecked) {
            return;
        } 

        if (this.tableItemIdxs[0] == this.tableItemIdxs[this.tableItemIdxs.length - 1]){
            $("nback-feedback").style.color = "#f66";
            this.wrongHit++;        
        }                 
    }

    /**
     * Mellékhatás i/o
     */
    engine()
    {
        // Kiírjuk a képernyőre, a hátralévő eventeket.
        $("time-left-feedback").innerHTML = "Left " + this.trials;
        
        setTimeout(() => {
            $("grid-image" + this.currentIdx).style.backgroundImage = ""             
        }, this.eventLength * 1000);
        
        
        if (this.trials-- == 0) {
            clearInterval(this.interval);
            var response = this.saveSession();         

            if (response.responseText.update == 1) {
                window.location = "/NBack/nback/feedback";
            } else {                             
                $("upper-feedback").innerHTML = "<span style=\"color:red;text-align:center;padding:5px\">Error: Cant't upload result to server... :(</span>";
            }
        }                    
        // Megvizsgáljuk, hogy volt-e egyezés és rá adott reakció
        this.automateCheck();
        // Legeneráltatjuk az új indexet
        this.generateIdx();
        // És hozzáadatjuk a tárolóhoz
        this.itemIdxsHandler();
        // Itt jelenítjük meg a megfelelő indexű mezőben a képet
        $("grid-image" + this.currentIdx).style.backgroundImage = "url(\"Public/images/Squares/spr_square_"+this.color+".png\")";        
        // Feloldjuk a keyEvent figyelőt.
        this.isChecked = 0;             
        setTimeout(()=>{$("nback-feedback").style.color = "#000"; }, 50); 
    }

    displayLoad()
    {      
        var timer = 0;    
        console.log(this.seconds);
        var interval = setInterval(function(){
            if (timer == 100) {
                clearInterval(interval);
                $("nback-modal").style.display = "none";
            }                
            $("progress-value").innerHTML = timer + "%";       
            $("progress-bar").style.width = timer + "%"; 
            timer++;            
        }, this.seconds * this.seconds);
    }

    /**
     * Felküldés előtt lementjük az adatokat a kliensnél.
     */
    saveSession()
    {            
        var xhr= new XMLHttpRequest();      
        // Üzenettest adatobjektuma
        var formData = new FormData();
        // Fájl adatszerkezethez adása
        formData.append("correctHit", this.correctHit);
        formData.append("wrongHit", this.wrongHit);  
        /// Feliratkozunk a betöltést figyelő eseményre.    
        xhr.onload = () => {
            console.log(xhr.response);
        };                
        // a meghívandó URL, metódus, szinkron futás beállítása.        
        xhr.open('POST', 'nback', false); 
        // Headerek megadása
        //xhr.setRequestHeader('Content-Type', this.files[0].type);                                  
        xhr.send(formData);

        var response = JSON.parse(xhr.responseText);

        return response;
    }

    load()
    {             
        var params = JSON.parse($("options").innerHTML);     
     
        this.color = params.color;
        this.gameMode = params.gameMode;
        this.level = params.level;
        this.trials = params.trials;
        this.seconds = params.seconds;
        this.eventLength = params.eventLength;
        this.fullTrials = this.trials;

        $("game-mode").innerHTML = " " + this.gameMode + " ";
        $("level").innerHTML = " " + this.level + " ";
        
    }
}


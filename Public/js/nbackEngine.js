window.addEventListener("load", init );
window.addEventListener("keydown", keyListener);



function init()
{
    nback = new Nback(12);    
    nback.load();
    
    nback.start();
    // keyHandler();
    // session();    
}
/**
 * Sajnos az eseménykezelő nem látja az osztálymetódust inicializlás előtt.
 * @param {*} e 
 */
function keyListener(e)
{
    if(e.keyCode == 65)
        nback.checkMatch();
}


class Nback
{

    // A felvillanásokat számolja
    numOfVisibility;
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
        this.numOfVisibility = 0;
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
        if(this.numOfVisibility == this.trials - 1 &&  this.wrongHit == 0 && this.correctHit == 0)		
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
        
        if (this.trials-- == this.numOfVisibility)
        {
            clearInterval(this.interval);
            this.saveSession();
 
            window.replace(this.serverUrl);
        }            
        
        // Megvizsgáljuk, hogy volt-e egyezés és rá adott reakció
        this.automateCheck();

        // Legeneráltatjuk az új indexet
        this.generateIdx();
        // És hozzáadatjuk a tárolóhoz
        this.itemIdxsHandler();

        $("grid-image" + this.currentIdx).style.backgroundImage = "url(\"Public/Images/Squares/spr_square_"+this.color+".png\")";
        
        // Feloldjuk a keyEvent figyelőt.
        this.isChecked = 0;
        setTimeout(()=>{$("nback-feedback").style.color = "#000"; }, 50); 
    }

    /**
     * Felküldés előtt lementjük az adatokat a kliensnél.
     */
    saveSession()
    {            
        var data = {
            "correcHit" : this.correctHit,
            "wrongHit" : this.wrongHit,
            "gameMode" : this.gameMode,
            "level" : this.level,
            "trials" : this.trials,
            "seconds" : this.seconds,                
        };

        fetch(
            "POST",
            []
        );

    }

    load()
    {                
        this.color = this.getCookie("color");
        this.gameMode = this.getCookie("gameMode");
        this.level = this.getCookie("level");
        this.trials = this.getCookie("trials");
        this.seconds = this.getCookie("seconds");    
        this.eventLength = this.getCookie("eventLength");
    }


    getCookie(cname) {
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for(var i = 0; i <ca.length; i++) {
          var c = ca[i];
          while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }
}

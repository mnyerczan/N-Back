window.addEventListener('load', () => {
    /* Ha változik a level szintje, vonja maga után a Trials minimális számát is,
     * ezen felül módosítsa a trials minimális értékét! 
     * 
     * level * 5 + 20
     */
    $('nback-level-value').addEventListener('change', async function () {        
        var min = parseInt(this.value) * 5 + 20;       
        if (min > parseInt($('nback-trials-value').value)) {
            $('nback-trials-value').value = min;
        }    
        $('nback-trials-value').min = min;
    });
    /*
     * Ha a játékmód Position-ra lett kapcsolva, tiltsa le a level     
     * inputot, különben oldja fel!
     */ 
    $('nback-game-mode-value').addEventListener('change', async function () {          
        if ($('nback-game-mode-value').value === 'Position') {
            $('nback-level-value').readOnly = true;
            return;
        }        
        $('nback-level-value').readOnly = false;  
    });
    /*
     * Két semény között eltelt idő nem lehet hosszabb, mint az egyes eseményeké (átlaolás)
     * Ha a köztes idő az esemény hossza alá megy, csökkentse azt. Minden változásnál
     * állítsa be az esemény hosszára vonatkozó maximumot!
     * 
     * seconds >= eventLength
     */
    $('nback-seconds-value').addEventListener('change', async function () {
        $('nback-eventlength-value').max = $('nback-seconds-value').value;
        if ($('nback-eventlength-value').value > $('nback-seconds-value').value) {
            $('nback-eventlength-value').value = $('nback-seconds-value').value;
        }        
    });
})



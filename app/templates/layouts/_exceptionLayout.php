<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $views->title ?></title>
    <style>
        body {
            margin          :0;
            padding         :0;
            background-color:#444;
            max-width       :100vw;
            min-height      :100vh;
            font-size       :10px
        }
        main{
            background-color:#fafafadd;
            padding         :50px; 
            margin          :20px;                   
            width           :calc(50vw - 2 * 50px - 2 * 30px);
            margin          :auto;
            box-shadow      :0 0 10px #333;
            font-size       : 15px;
            display         :block;
            display         :table;
        }        
        .red {color:red;}
        .bold{font-weight:bold}
        .tab{margin-left:20px;width: calc(100% - 20px) !important;}
        .dense{line-height:20px; font-size:1em;}
        .dense * {margin:0}
        .table{width:100%;background-color: #f6f6f6;}
        .table td{min-width:20%;text-align:left;border-bottom:1px solid #eee}  
        .table .header{font-weight: bold}
        .segment{height:50px}
        .short-attr td:first-child{width:100px}
        .left{float:left;width:50%}
        .clear{clear:both}
        .code{
            font-family     : 'Courier New', Courier, monospace;                       
            padding         : 10px;
            background-color: #eef;
            color           : #444;   
            display         : table;
            min-width       : 1000px;
        }
        .code div {
            display         : inline-block;            
        }
        .line {
            white-space     : pre;  
            line-height     : 20px;
        }
        .yellow-bg {background-color: yellow;}  
        .orange-bg {background-color: #da5; color: white}
        .red-bg {background-color: red; color: white}
    </style>
</head>
<body>
    <main>
        <h1><?=$exception?></h1>
        <h2><?=$message?></h2>        
        <p>In: <span class=""><?=$file." :".$line?></span></p>
        <br>
        Trace:        
        <div class="tab dense">                
            <table class="table">
                <tr>
                    <td class="header">File</td>   
                    <td class="header">Class</td>                  
                </tr>
            <?php for($i=0;$i<count($traces); $i++):?> 
                <?php $trace = explode(" ", $traces[$i]) ?>
                <tr <?php if($i == 0) echo "class=\"red bold\"" ?>>
                    <?php for($j=0;$j<count($trace); $j++):?>
                        <td><pre><?=$trace[$j]?></pre></td>
                    <?php endfor ?>       
                </tr>
            <?php endfor ?>                           
            </table>                
        </div> 
        <div class="segment"></div>
        <div class="tab">
            <div class="left">
                <h4>POST variables</h4>
                <div class="">
                    <?php if(isset($_POST)): ?>
                        <?php foreach($_POST as $key => $value): ?>                    
                        <p>"<?=$key?>":"<?=$value?>"</p>
                        <?php endforeach ?>
                    <?php endif?>                    
                </div>            
            </div>              
            <div class="left">
                <div class="">
                    <h4>GET variables</h4>
                    <div class="">
                        <?php if(isset($_GET)): ?>
                            <?php foreach($_GET as $key => $value): ?>                    
                            <p>"<?=$key?>":"<?=$value?>"</p>
                            <?php endforeach ?>
                        <?php endif?>
                    </div>            
                </div>             
            </div>
        </div>   
        <div class="segment clear"></div>     
        <div class="tab">
            <div class="left">
                <h4>Session variables</h4>
                <div class="">
                    <?php if(isset($_SESSION)): ?>
                        <?php foreach($_SESSION as $key => $value): ?>                    
                        <p>"<?=$key?>":"<?=$value?>"</p>
                        <?php endforeach ?>
                    <?php endif?>
                </div>
                </table>
            </div>           
            <div class="left">
                <h4>Cookie variables</h4>
                <div class="">
                    <?php if(isset($_COOKIE)): ?>
                        <?php foreach($_COOKIE as $key => $value): ?>                    
                        <p>"<?=$key?>":"<?=strlen($value) > 30 ? substr($value,0, 30)." ..." : $value?>"</p>
                        <?php endforeach ?>
                    <?php endif ?>
                </div>            
            </div> 
        </div> 
        <div class="segment clear"></div>
        <h3><?= str_replace(FSROOT, "", $file) ?></h3>
        <div class="code">
            <?php
                $handle = fopen($file, "r");
                $i = 2;
                fgets($handle);
                while (!feof($handle)) {      
                    $strlen = strlen($strIn = fgets($handle)); 
                    $str = $strlen < 100 ? $strIn : substr($strIn, 0, 50)."...";
                    if ($i >= $line - 20 && $i <= $line + 20) {
                        $num = "<span class=\"bold\">".sprintf("%3d",($i + 1))."</span>";
                        if ($i == $line)
                            echo "<div class=\"line\">".$num."  <span class=\"red-bg\">".$str."</span></div><br>";
                        else 
                            echo "<div class=\"line\">".$num."  ".$str."</div><br>";
                    }                               
                    $i++;
                }
                fclose($handle);
            ?>  
        </div>        
    </main>
</body>
</html>
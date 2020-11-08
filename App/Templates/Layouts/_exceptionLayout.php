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
            background-color:#55b;
            max-width       :100vw;
            min-height      :100vh;
            font-size       :10px
        }
        main{
            background-color:#fafafa;
            padding         :50px; 
            margin          :20px;                   
            width           :calc(50vw - 2 * 50px - 2 * 30px);
            margin          :auto;
            box-shadow      :0 0 10px #333;
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
            font-family     :'Courier New', Courier, monospace;           
            white-space     :pre;
            padding         :10px;
            background-color:#fff;
            border          :1px solid red;
        }
        .yellow {background-color: yellow;}  
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
                <tr <?php if($i == 2) echo "class=\"red bold\"" ?>>
                    <?php for($j=1;$j<count($trace); $j++):?>
                        <td><?=$trace[$j]?></td>             
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
                    <?php foreach($_POST as $key => $value): ?>                    
                    <p>"<?=$key?>":"<?=$value?>"</p>
                    <?php endforeach ?>
                </div>            
            </div>              
            <div class="left">
                <div class="">
                    <h4>GET variables</h4>
                    <div class="">
                        <?php foreach($_GET as $key => $value): ?>                    
                        <p>"<?=$key?>":"<?=$value?>"</p>
                        <?php endforeach ?>
                    </div>            
                </div>             
            </div>
        </div>   
        <div class="segment clear"></div>     
        <div class="tab">
            <div class="left">
                <h4>Session variables</h4>
                <div class="">
                    <?php foreach($_SESSION as $key => $value): ?>                    
                    <p>"<?=$key?>":"<?=$value?>"</p>
                    <?php endforeach ?>
                </div>
                </table>
            </div>           
            <div class="left">
                <h4>Cookie variables</h4>
                <div class="">
                    <?php foreach($_COOKIE as $key => $value): ?>                    
                    <p>"<?=$key?>":"<?=strlen($value) > 30 ? substr($value,0, 30)." ..." : $value?>"</p>
                    <?php endforeach ?>
                </div>            
            </div> 
        </div> 
        <div class="segment clear"></div>
        <div class="code">
            <?php
                $handle = fopen($file, "r");
                $i = 2;
                fgets($handle);
                while (!feof($handle)) {      
                    $strlen = strlen($strIn = fgets($handle)); 
                    $str = $strlen < 100 ? $strIn : substr($strIn, 0, 50)."...";
                    if ($i >= $line - 20 && $i <= $line + 20) {
                        $num = "<span class=\"bold\">".($i + 1)."</span>";
                        if ($i == $line)
                            echo $num." | <span class=\"yellow\">".$str."</span><br>";
                        else 
                            echo $num." | ".$str."<br>";
                    }                               
                    $i++;
                }
                fclose($handle);
            ?>  
        </div>        
    </main>
</body>
</html>
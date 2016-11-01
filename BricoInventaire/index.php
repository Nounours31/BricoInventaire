<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
 <?php
 include_once "PHPClass/cConnexion.php";
 include_once "PHPClass/cConstantes.php";
 include_once "PHPClass/cInitInventaireFromDb.php";
 ?>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="./css/SelectTable.css">
        <script src="js/actionOnTable.js"></script>
        <script src="js/AJAX_UpdateDB.js"></script>
        <meta charset="UTF-8">
        <title>Inventaire</title>
    </head>
    <body>
        AJAX: 
        <div id="AJAX">AJAX</div>
        Inventaire bricolage
        <!--
        <form action="index.php" method="POST">
            <input type="submit" name="CSVversionFinale" value="CSVversionFinale"></input> 
            <input type="submit" name="CSVversion" value="CSVversion"></input> 
            <input type="submit" name="printversion" value="printversion"></input> 
            <input type="submit" name="editversion" value="editversion"></input> 
        </form>
        -->
        <?php
            ini_set('max_execution_time', 1800);
            $constantes = new cConstantes();
            
            $cnx = new cConnexion();
            $sql = "select s.*, o.nom from sectionbrico_inventaire s left join outils o on ((s.uidBrico = o.uid) and (s.Ref_Interne like 'BRICO_O_%'))  order by s.Ref_Interne asc";
            
            /*
            if (isset($_POST) && (isset($_POST['CSVversionFinale']) || isset($_POST['CSVversion'])))
            {
                $printversion = 0;
                $aKeys = $constantes -> InventairePrintKeys;
                if (isset($_POST['CSVversion']))
                    array_push($aKeys, "nom");
                
                $cnx -> printSelectCSV($sql, $aKeys, $printversion);
                print ('<a href="print.csv" download/> Download CSV');
            }
            else if (isset($_POST) && isset($_POST['printversion']) )
            {
                $printversion = 1;
                $aKeys = $constantes -> InventairePrintKeys;            
                $cnx -> printSelect($sql, $aKeys, $printversion);                
            }
            else 
            */
            {
                $printversion = 0;
                $aKeys = $constantes -> InventaireKeys;
                $cnx -> newPrintSelect($aKeys, $printversion);                
            }
            
            
        ?>
    </body>
</html>

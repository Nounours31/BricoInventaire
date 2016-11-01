<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once '../PHPClass/cConnexion.php';
include_once '../PHPClass/cConstantes.php';

if (!isset($_POST))
     return;
 
$keys = array_keys($_POST);
$cst = new cConstantes();
$inventaire = new cConnexion();


$id = $_POST['id'];
$table = $_POST['natureOutil'];
$date = date("Y-m-d H:i:s");

$BricoType = 'kiki';
if (strcmp ($table, 'accessoire') == 0)
        $BricoType = 'Accessoire';
else if (strcmp ($table, 'outilsprive') == 0)
        $BricoType = 'Section';
else if (strcmp ($table, 'outils') == 0)
        $BricoType = 'Outil';

$sqlInventaire = "update z_inventaire set dateMAJ = '".date("Y-m-d H:i:s")."' ";

$sql = "update ".$table." set uid=".$id." ";
foreach ($keys as $keyIndex => $KeyValue)
{
    if (in_array($KeyValue, $cst -> InventaireKeys))
    {
        $mappingKeyValue = $KeyValue;

        if (strcmp ($mappingKeyValue, "Materiel") == 0)
                $mappingKeyValue = "DescriptionDetaillee";
        else if (strcmp ($mappingKeyValue, "Quantite") == 0)
        {
            $mappingKeyValue = "quantite";
            if (strcmp ($BricoType, "Accessoire") != 0)
                continue;
        }
        else if (strcmp ($mappingKeyValue, "QuantiteRestante") == 0)
                continue;
        else if (strcmp ($mappingKeyValue, "LieuStockage") == 0) 
        {
            $sqlInventaire .= ', '.$mappingKeyValue."='".addslashes ($_POST[$KeyValue])."'";
            continue;
        }
        else if ((strcmp ($mappingKeyValue, "PrixAchat") == 0) && (strcmp ($BricoType, "Accessoire") != 0))
           $mappingKeyValue = "prix";
        
        $sql .= ', '.$mappingKeyValue."='".addslashes ($_POST[$KeyValue])."'";
    }
}
$sqlInventaire .= " where ((BricoType = '".$BricoType."') and (uidBrico ='".$id."'))";
$sql .= " where (uid = ".$id.")";

print ($sqlInventaire."<br/>");
print ($sql."<br/>");

$inventaire ->insert($sqlInventaire);
$inventaire ->insert($sql); 
?>
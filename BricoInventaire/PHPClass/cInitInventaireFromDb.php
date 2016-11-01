<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cInitInventaireFromDb
 *
 * @author PFS
 */
class cInitInventaireFromDb 
{
    function InitInventaire()
    {
        // recup des accessoires
        $this -> InitInventaireAccessoire();
        
        // recup des outils
        $this -> InitInventaireOutils();
        
        // recup des outils interne
        $this -> InitInventaireOutilsPrive();
    }
    
    
    function InitInventaireAccessoire()
    {
        $accessoire = new cConnexion();
        $sql = "select * from accessoire";
        $aAccKeys = array ("uid",
            "quantite",
            "prix",
            "DescriptionDetaillee");

        // dump debug
        // $accessoire ->printSelect($sql, $aAccKeys);

        // Ajout dans la table inventaire
        $alstAcc = $accessoire ->select($sql, $aAccKeys);
        $constantes = new cConstantes();
        
        $inventaire = new cConnexion();
        foreach ($alstAcc as $key) 
        {
            // print_r($key);
            
            $uidBrico = $key['uid'];
            $Ref_Interne = cConstantes::PREFIX_REF_INTERNE_ACCESSOIRE.$uidBrico;
            $BricoType = $constantes -> BricoTypeKeys['Acc'];
            $dateMAJ = date("Y-m-d H:i:s");
            $Materiel = mysql_escape_string ($key['DescriptionDetaillee']);
            $Quantite = $key['quantite'];
            $PrixAchat = $key['prix'];
            $QuantiteRestante = $Quantite;
            
            $datapipo = mktime(0, 0, 0, 1, 1, 2000);
            $NumeroSerie = " ";
            $Type = " ";
            $Marque = " ";
            $DataAchat = date("Y-m-d H:i:s", $datapipo);
            $DateSortie = date("Y-m-d H:i:s", $datapipo);
            $QuantiteSortie = 0;
            $NatureSortie = "NA";

            $DureeUtilisationPrevisionelle = "10 ans";
            $FequenceUtilisation = "1/15 jours";
            $LieuStockage = "Air-RPBricolage";

            //---------------------------------------
            // est ce que cet outil existe deja ?
            //---------------------------------------
            $sql = "select uid from sectionbrico_inventaire where ((uidBrico = ".$uidBrico.") and (BricoType = '".$BricoType."'))";
            $selectkeys = array("uid");
            $ret = $inventaire -> select ($sql, $selectkeys);
            $exist = 0;
            if (is_array($ret))
            {
                if (array_key_exists(0, $ret))
                {
                    if (array_key_exists("uid", $ret[0]))
                    {    
                        if (!empty($ret[0]['uid'])) // oui
                        {
                            $exist = 1;
                        }
                    }
                }
            }
/*            if ($exist == 1) 
                print ("UPDATE<br/>");
            else
                 print ("INSERT<br/>"); */
            
            if ($exist == 0)
            {
                $sql = "insert into sectionbrico_inventaire ";
                $sql .= "(dateMAJ, uidBrico, Ref_Interne, BricoType, Materiel, Quantite, PrixAchat, QuantiteRestante, ";
                $sql .= "DureeUtilisationPrevisionelle, ";
                $sql .= "FequenceUtilisation, LieuStockage, NumeroSerie, Type, Marque, DataAchat, DateSortie, QuantiteSortie, NatureSortie) " ;
                $sql .= "values ";
                $sql .= "('".$dateMAJ."', '".$uidBrico."', '".$Ref_Interne."', '".$BricoType."',  '".$Materiel."', ".$Quantite.", ".$PrixAchat.", ".$QuantiteRestante;
                $sql .= ", '".$DureeUtilisationPrevisionelle."',";
                $sql .= "'".$FequenceUtilisation."', '".$LieuStockage."', '".$NumeroSerie."','".$Type."','".$Marque."','".$DataAchat."','".$DateSortie."',".$QuantiteSortie.",'".$NatureSortie."') ";
                // print ("<br/>" . $sql ."<br/>");
                $inventaire ->insert($sql);
            }
            
            // return;
        }      
    }

    function InitInventaireOutilsPrive()
    {
        return $this -> InitInventaireOutilsPourTous ('outilsprive', 1);
    }
    
    function InitInventaireOutils()
    {
        return $this -> InitInventaireOutilsPourTous ('outils', 0);
    }
    
    function InitInventaireOutilsPourTous($NomTable, $interne)
    {
        $outils = new cConnexion();
        $sql = "select uid, nom, localisation, fournisseur, prix, DescriptionDetaillee, numero_commande, RefCommandeCE, dateAchat ";
        $sql .= "from ".$NomTable;
        
        $aAccKeys = array ("uid",
            "prix",
            "DescriptionDetaillee",
            "nom", "localisation", "fournisseur", "numero_commande", "RefCommandeCE", "dateAchat");

        $aAccKeysForTest = array (
            "nom", "localisation", "fournisseur", "numero_commande", "RefCommandeCE", "dateAchat");

        // dump debug
        // $accessoire ->printSelect($sql, $aAccKeys);

        // Ajout dans la table inventaire
        $alstAcc = $outils ->select($sql, $aAccKeys);
        $constantes = new cConstantes();
        
        $DebugCount = 0;
        $inventaire = new cConnexion();
        foreach ($alstAcc as $UneLigne) 
        {
            $DebugCount++;
            
            // print_r($key);
            $dateMAJ = date("Y-m-d H:i:s");
            $uidBrico = $UneLigne['uid'];
            $Ref_Interne = cConstantes::PREFIX_REF_INTERNE_OUTIL.$uidBrico;
            $BricoType = $constantes -> BricoTypeKeys['Outil'];
            if ($interne === 1)
            {
                $Ref_Interne = cConstantes::PREFIX_REF_INTERNE_INTERNE_SECTION.$uidBrico;
                $BricoType = $constantes -> BricoTypeKeys['Interne'];
            }
            $Materiel = mysql_escape_string ($UneLigne['DescriptionDetaillee']);
            $Quantite = 1;
            $PrixAchat = $UneLigne['prix'];
            $QuantiteRestante = $Quantite;
            $DureeUtilisationPrevisionelle = "10 ans";
            $FequenceUtilisation = "1/15 jours";
            $LieuStockage = "Air-RPBricolage";
            $Type = "";
            
            
            $datapipo = mktime(0, 0, 0, 1, 1, 2000);
            $NumeroSerie = " ";
            $Marque = " ";
            
            $dateAchat = date("Y-m-d H:i:s", $datapipo);
            if (array_key_exists('dateAchat', $UneLigne))
                    $dateAchat = mysql_escape_string ($UneLigne['dateAchat']);
            
            $DateSortie = date("Y-m-d H:i:s", $datapipo);
            $QuantiteSortie = 0;
            $NatureSortie = "NA";

            
/*            foreach ($aAccKeysForTest as $unchamps => $value)
            {
                if (array_key_exists($value, $UneLigne))
                    $Type .= "[".$UneLigne[$value]."]";
                else
                    $Type .= "[".$value." undef]";
            }*/
            ////---------------------------------------
            // est ce que cet outil existe deja ?
            //---------------------------------------
            $sql = "select uid from sectionbrico_inventaire where ((uidBrico = ".$uidBrico.") and (BricoType = '".$BricoType."'))";
            $selectkeys = array("uid");
            $ret = $inventaire -> select ($sql, $selectkeys);
            $exist = 0;
            if (is_array($ret))
            {
                if (array_key_exists(0, $ret))
                {
                    if (array_key_exists("uid", $ret[0]))
                    {    
                        if (!empty($ret[0]['uid'])) // oui
                        {
                            $exist = 1;
                        }
                    }
                }
            }
            /*if ($exist == 1) 
                print ("UPDATE<br/>");
            else
                 print ("INSERT<br/>"); */
            
            if ($exist == 0)
            {
                $sql = "insert into sectionbrico_inventaire ";
                $sql .= "(dateMAJ, uidBrico, Ref_Interne, BricoType, Materiel, Quantite, PrixAchat, QuantiteRestante, ";
                $sql .= "DureeUtilisationPrevisionelle, ";
                $sql .= "FequenceUtilisation, LieuStockage, NumeroSerie, Type, Marque, DataAchat, DateSortie, QuantiteSortie, NatureSortie) " ;
                $sql .= "values ";
                $sql .= "('".$dateMAJ."', '".$uidBrico."', '".$Ref_Interne."', '".$BricoType."',  '".$Materiel."', ".$Quantite.", ".$PrixAchat.",  ".$QuantiteRestante;
                $sql .= ", '".$DureeUtilisationPrevisionelle."',";
                $sql .= "'".$FequenceUtilisation."', '".$LieuStockage."', '".$NumeroSerie."','".$Type."','".$Marque."','".$dateAchat."','".$DateSortie."',".$QuantiteSortie.",'".$NatureSortie."') ";
                $rc = $inventaire ->insert($sql);
                // print ("<br/>".$sql." <=> rc = ".$rc." <br/>");
            }
            
            /*if ($DebugCount > 10)
                 return;*/
        }      
    }

}

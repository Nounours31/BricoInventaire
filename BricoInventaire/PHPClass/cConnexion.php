<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cConnexion
 *
 * @author PFS
 */
class cConnexion 
{
    var $hostname = 'localhost';
    var $username = 'root';
    var $password = ''; 
    var $database = "cedssectbrico_inventaire";
    var $dbCnx;
    var $isLocalHost = FALSE;

    function cDB() 
    {
        $this -> isLocalHost = FALSE;
        
        $this -> dbCnx = NULL;
        if ($this -> isLocalHost == TRUE)
        {
            $this -> hostname = 'localhost';
            $this -> username = 'root'; 
            $this -> password = ''; 
            $this -> database = "cedssectbrico_inventaire";
        }
        else 
        {
            $this -> hostname = 'mysql5-6.perso';
            $this -> username = "cedssectbrico";
            $this -> password = "4WxyJJNH";
            $this -> database = "cedssectbrico";
        }
    }

    function initDB() 
    {
        $this -> cDB();
        $this -> dbCnx = mysql_connect($this -> hostname, $this ->username, $this ->password);
        if (!$this->dbCnx) 
        {
            die('Connexion impossible : ['.$this -> isLocalHost.']['.$this ->username.']' . mysql_error());
            return;
        }
    }

    function closeDB() 
    {
        if (!is_null($this ->dbCnx))
            mysql_close($this -> dbCnx);
        
        $this -> dbCnx = NULL;
    }

    
    function printSelectCSV ($sql, $aKeys, $printversion) 
    {
        $handle = fopen("./print.csv", "w");
        $constantes = new cConstantes();

        foreach ($aKeys as $i) 
        {
            if (strcmp ($i, "uid") == 0)
                continue;
            
            // print ($i."; ");
            fwrite($handle, $i.'; ');
        }
        print ('<br/>');
        fwrite($handle, "\r\n");

        
        $ret = $this -> select ($sql, $aKeys) ;
        foreach ($ret as $pipoKey => $UneLigne) 
        {
            foreach ($aKeys as $i) 
            {
                if (strcmp ($i, "uid") == 0)
                        continue;
                
                
                $isDefined = 0;
                $infoToPrint = " ";
                if (isset ($UneLigne[$i]))
                {
                    $isDefined = 1;
                    $infoToPrint = $UneLigne[$i];
                    $infoToPrint = preg_replace('/\s+/', ' ', trim($infoToPrint));
                    $infoToPrint = str_replace(';', '#', $infoToPrint);
                }
                
                // print ($infoToPrint.'; ');
                fwrite($handle, $infoToPrint.'; ');

            }
            // print ('<br/>');        
            fwrite($handle, "\r\n");
        }
        // print ('<br/>');
        fwrite($handle, "\r\n");
        fclose($handle);
        return;        
    }
    
    function printSelect ($sql, $aKeys, $printversion) 
    {
        $constantes = new cConstantes();
        print ('<form>');
        print ('<table class="SelectTable" id="IdOfTableForSelect">');
        print ('<tr class="SelectTable" id="TABLE_HEADER">');
        if ($printversion === 0)
            print ('<th class="SelectTable">Edit ?</th>');
        foreach ($aKeys as $i) 
        {
            if (strcmp ($i, "uid") == 0)
                continue;
            
            print ('<th class="SelectTable">'.$i.'</th>');
        }
        print ('</tr>');

        
        $ret = $this -> select ($sql, $aKeys) ;
        foreach ($ret as $pipoKey => $UneLigne) 
        {
            if (array_key_exists('uid', $UneLigne))
                print ('<tr class="SelectTable" id="LIGNE_'.$UneLigne['uid'].'">');
            else
                print ('<tr class="SelectTable" >');

            if ($printversion === 0)
                print ('<td class="SelectTable"><input type="radio" name="RADIO_'.$UneLigne['uid'].'" onclick="actionOnClickRadioButton('.$UneLigne['uid'].', \'IdOfTableForSelect\', \'TABLE_HEADER\', \'LIGNE_\');"></td>');

            foreach ($aKeys as $i) 
            {
                if (strcmp ($i, "uid") == 0)
                        continue;
                
                $isMandKey = 0;
                if (in_array($i, $constantes -> InventaireMandKeys))
                      $isMandKey = 1;
                
                $isDefined = 0;
                $infoToPrint = "undef";
                if (isset ($UneLigne[$i]))
                {
                    $isDefined = 1;
                    $infoToPrint = $UneLigne[$i];
                }
                
                if ($isMandKey == 0)
                    print ('<td class="SelectTable">'.$infoToPrint.'</td>');

                else 
                {
                    if ($isDefined == 1)
                        print ('<td class="SelectTableMandOK">'.$infoToPrint.'</td>');
                    else
                        print ('<td class="SelectTableMandKO">'.$infoToPrint.'</td>');
                }
            }
        }
        print ('</tr>');        
        print ('</table>');
        print ('</form>');
        return;
    }

    
    function newPrintSelect ($aKeys, $printversion) 
    {
        $constantes = new cConstantes();
        print ('<form>');
        print ('<table class="SelectTable" id="IdOfTableForSelect">');
        print ('<tr class="SelectTable" id="TABLE_HEADER">');
        if ($printversion === 0)
            print ('<th class="SelectTable">Edit ?</th>');
        foreach ($aKeys as $i) 
        {
            if (strcmp ($i, "uid") == 0)
                continue;
            
            print ('<th class="SelectTable">'.$i.'</th>');
        }
        print ('</tr>');

        
        $sqlAccessoire = "select o.uid, o.uid as uidBrico, 'N/A' as nom, 'Accessoire' as BricoType, i.dateMAJ, CONCAT('BRICO_A_', o.uid) as Ref_Interne, o.DescriptionDetaillee as Materiel, o.NumeroSerie, o.Type, o.Marque, o.quantite as Quantite, ";
        $sqlAccessoire .= " o.DateAchat, o.PrixAchat, o.DateSortie, o.QuantiteSortie, o.NatureSortie, (o.quantite - o.QuantiteSortie) as QuantiteRestante, o.DureeUtilisationPrevisionnelle, o.FrequenceUtilisation, i.LieuStockage";
        $sqlAccessoire .= " from z_inventaire i, accessoire o where ((i.uidBrico = o.uid) and (i.BricoType = 'Accessoire'))";
        
        $sqlOutils = "select o.uid, o.uid as uidBrico, o.nom, 'Outil' as BricoType, i.dateMAJ, CONCAT('BRICO_O_', o.uid) as Ref_Interne, o.DescriptionDetaillee as Materiel, o.NumeroSerie, o.Type, o.Marque, '1' as Quantite, ";
        $sqlOutils .= " o.DateAchat, o.Prix as PrixAchat, o.DateSortie, '0' as QuantiteSortie, o.NatureSortie, '1' as QuantiteRestante, o.DureeUtilisationPrevisionnelle, o.FrequenceUtilisation, i.LieuStockage";
        $sqlOutils .= " from z_inventaire i, outils o where ((i.uidBrico = o.uid) and (i.BricoType = 'Outil'))";

        $sqlOutilsPrive = "select o.uid, o.uid as uidBrico, o.nom, 'Outil section' as BricoType, i.dateMAJ, CONCAT('BRICO_I_', o.uid) as Ref_Interne, o.DescriptionDetaillee as Materiel, o.NumeroSerie, o.Type, o.Marque, '1' as Quantite, ";
        $sqlOutilsPrive .= " o.DateAchat, o.Prix as PrixAchat, o.DateSortie, '0' as QuantiteSortie, o.NatureSortie, '1' as QuantiteRestante, o.DureeUtilisationPrevisionnelle, o.FrequenceUtilisation, i.LieuStockage";
        $sqlOutilsPrive .= " from z_inventaire i, outilsprive o where ((i.uidBrico = o.uid) and (i.BricoType = 'Section'))";

        /*print ('<tr class="SelectTable" id="TABLE_HEADER">');
        print ('<td colspan="20">'.$sqlAccessoire.'</td>');
        print ('</tr>');
        print ('<tr class="SelectTable" id="TABLE_HEADER">');
        print ('<td colspan="20">'.$sqlOutils.'</td>');
        print ('</tr>');
        print ('<tr class="SelectTable" id="TABLE_HEADER">');
        print ('<td colspan="20">'.$sqlOutilsPrive.'</td>');
        print ('</tr>');*/
        
        

        $sqlarray = array();
        $sqlarray['TAG']['Accessoire'] = 'accessoire';
        $sqlarray['TAG']['Outils'] = 'outils';
        $sqlarray['TAG']['OutilsPrive'] = 'outilsprive';
        $sqlarray['SQL']['Accessoire'] = $sqlAccessoire;
        $sqlarray['SQL']['Outils'] = $sqlOutils;
        $sqlarray['SQL']['OutilsPrive'] = $sqlOutilsPrive;
        
        
        foreach ($sqlarray['SQL'] as $sqlArrayKey => $sql)
        {
            $ret = $this -> select ($sql, $aKeys) ;
            foreach ($ret as $pipoKey => $UneLigne) 
            {
                print ("\n");
                if (array_key_exists('uid', $UneLigne))
                    print ('<tr class="SelectTable" id="LIGNE_'.$sqlarray['TAG'][$sqlArrayKey].'_'.$UneLigne['uid'].'">');
                else
                    print ('<tr class="SelectTable" >');

                if ($printversion === 0)
                    print ('<td class="SelectTable"><input type="radio" name="RADIO_'.$sqlarray['TAG'][$sqlArrayKey].'_'.$UneLigne['uid'].'" onclick="actionOnClickRadioButton(\''.$sqlarray['TAG'][$sqlArrayKey].'\','.$UneLigne['uid'].', \'IdOfTableForSelect\', \'TABLE_HEADER\', \'LIGNE_\');"></td>');

                foreach ($aKeys as $i) 
                {
                    if (strcmp ($i, "uid") == 0)
                            continue;

                    $isMandKey = 0;
                    if (in_array($i, $constantes -> InventaireMandKeys))
                          $isMandKey = 1;

                    $isDefined = 0;
                    $infoToPrint = "undef";
                    if (isset ($UneLigne[$i]))
                    {
                        $isDefined = 1;
                        $infoToPrint = $UneLigne[$i];
                    }

                    if ($isMandKey == 0)
                        print ('<td class="SelectTable">'.$infoToPrint.'</td>');

                    else 
                    {
                        if ($isDefined == 1)
                            print ('<td class="SelectTableMandOK">'.$infoToPrint.'</td>');
                        else
                            print ('<td class="SelectTableMandKO">'.$infoToPrint.'</td>');
                    }
                }
                print ('</tr>');        
            }
        }
        print ('</table>');
        print ('</form>');
        return;
    }

    function select ($sql, $aKeys) 
    {
        $this-> initDB();
        $result = mysql_db_query($this -> database, $sql);
        if (!$result) {
            $message  = 'Requete invalide : ' . mysql_error() . "<br/>";
            $message .= 'Requete complete : -->'.$sql.'<--';
            die($message);
        }

        $resp = array();
        $indice = 0;
        $hasvalue = 0;
        
        while ($row = mysql_fetch_assoc($result)) {
            $uneligne = array();
            
            foreach ($aKeys as $unChamps) {
                if (isset($row[$unChamps]))
                {
                    $uneligne[$unChamps] = $row[$unChamps];
                    $hasvalue = 1;
                }
            }
            
            if ($hasvalue)
            {
                $resp[$indice] = $uneligne;
                $indice++;
            }
        }
        
        mysql_free_result($result);
        
        $this-> closeDB();
        return $resp;
    }
    
    function insert ($sql) 
    {
        $this-> initDB();
        $result = mysql_db_query($this -> database, $sql);
        if (!$result) {
            $message  = 'Requete invalide : ' . mysql_error() . "<br/>";
            $message .= 'Requete complete : -->'.$sql.'<--';
            die($message);
        }
        $retour = mysql_affected_rows();
        $this-> closeDB();
        return 'Insert :'.$retour;
    }

    function insertId ($sql) 
    {
        $this-> initDB();
        $result = mysql_db_query($this -> database, $sql);
        if (!$result) {
            $message  = 'Requete invalide : ' . mysql_error() . "<br/>";
            $message .= 'Requete complete : -->'.$sql.'<--';
            die($message);
        }
        $retour = mysql_insert_id();
        $this-> closeDB();
        return $retour;
    }
}

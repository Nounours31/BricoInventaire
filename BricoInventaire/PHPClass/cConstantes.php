<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cContantes
 *
 * @author PFS
 */
class cConstantes {
    const PREFIX_REF_INTERNE_ACCESSOIRE = "BRICO_A_";
    const PREFIX_REF_INTERNE_OUTIL = "BRICO_O_";
    const PREFIX_REF_INTERNE_INTERNE_SECTION = "BRICO_I_";
    
    public  $BricoTypeKeys = array(
                'Acc' => 'Accessoire',
                'Interne' => 'Section',
                'Outil' => 'Outil');

    
    public  $InventairePrintKeys = array(
                'Ref_Interne',
                'Materiel',
                'NumeroSerie',
                'Type',
                'Marque',
                'Quantite',
                'DataAchat',
                'PrixAchat',
                'DateSortie',
                'QuantiteSortie',
                'NatureSortie',
                'QuantiteRestante',
                'DureeUtilisationPrevisionelle',
                'FequenceUtilisation',
                'LieuStockage'
            );
    public  $InventaireKeys = array(
                'uid',
                'uidBrico',
                'nom',
                'dateMAJ',
                'BricoType',
                'Ref_Interne',
                'Materiel',
                'NumeroSerie',
                'Type',
                'Marque',
                'Quantite',
                'DateAchat',
                'PrixAchat',
                'DateSortie',
                'QuantiteSortie',
                'NatureSortie',
                'QuantiteRestante',
                'DureeUtilisationPrevisionnelle',
                'FrequenceUtilisation',
                'LieuStockage'
            );

    public  $InventaireMandKeys = array(
                'Materiel',
                'NumeroSerie',
                'DataAchat',
                'QuantiteRestante',
                'DureeUtilisationPrevisionelle',
                'LieuStockage'
            );
}

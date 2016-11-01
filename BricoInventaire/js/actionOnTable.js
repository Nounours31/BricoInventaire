/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function actionOnClickRadioButton(natureOutil, id, idOfTable, headerId, ligneId) 
{
   var idOfDomElem = ligneId +  natureOutil + "_" + id;
   var DOMElementTR = document.getElementById(idOfDomElem);
   
   // ai je deja afficher la ligne d'update ?
   var isUpdateEnCours = DOMElementTR.getAttribute("UpdateEnCours");
   if (isUpdateEnCours === "OUI")
   {
       DOMElementTR.setAttribute("UpdateEnCours", "NON");
       actionCleanUpdate(natureOutil, id, ligneId);
   }
   else
   {
        // non alors je taggue
        DOMElementTR.setAttribute("UpdateEnCours", "OUI");
        actionUpdate(natureOutil, id, idOfTable, headerId, ligneId);
   }   
}

function actionCleanUpdate(natureOutil, id, ligneId) 
{
   var idOfDomElem = ligneId + natureOutil + "_" + id;;
   var DOMElementTR = document.getElementById(idOfDomElem);
   var successeurX = DOMElementTR.nextSibling;
   DOMElementTR.parentNode.removeChild(successeurX);
   
    var arrayOfChild = DOMElementTR.children;
   var inputDomElem = arrayOfChild[0].children[0];
   inputDomElem.checked = false;
}


function actionUpdate(natureOutil, id, idOfTable, headerId, ligneId) 
{
    var idOfDomElem = ligneId + natureOutil + "_" + id;
   var DOMElementTR = document.getElementById(idOfDomElem);

   var DOMElementHeader = document.getElementById(headerId);
   var successeurX = DOMElementTR.nextSibling;
   
    var arrayOfChild = DOMElementTR.children;
    var arrayOfHEADERChild = DOMElementHeader.children;
     
    var indiceChild = 1;
   
    var newItemTR = document.createElement("tr"); 
    newItemTR.name = "UPDATE_LINE_" + natureOutil + "_" + id;
    newItemTR.id = "UPDATE_LINE_" + natureOutil + "_" + id;
    
    
    // Edit ?
    var textItemTD = createTDsubmit ("name inputtxt", idOfDomElem, headerId, id, natureOutil, "SendtoDB");    
    newItemTR.appendChild(textItemTD);  

    // uidBrico, Nom, DateMaj, BricoType, RefInterne
    var nbcolone = 5;
    var textItemTD = createTDvide (nbcolone);    
    newItemTR.appendChild(textItemTD);  

    // Materiel
    indiceChild = 6;
    var mappingNode = arrayOfChild[indiceChild++];
    var textItemTD = createTDInputText (arrayOfHEADERChild[indiceChild-1].innerText+"_"+id, id, mappingNode.innerText);    
    newItemTR.appendChild(textItemTD);                    

    
    // Num serie
    mappingNode = arrayOfChild[indiceChild++];
    var textItemTD = createTDInputText (arrayOfHEADERChild[indiceChild-1].innerText+"_"+id, id,  mappingNode.innerText);    
    newItemTR.appendChild(textItemTD);                    

    // type
    mappingNode = arrayOfChild[indiceChild++];
    var textItemTD = createTDInputText (arrayOfHEADERChild[indiceChild-1].innerText+"_"+id, id, mappingNode.innerText);    
    newItemTR.appendChild(textItemTD);                    

    // Marque
    mappingNode = arrayOfChild[indiceChild++];
    var textItemTD = createTDInputText (arrayOfHEADERChild[indiceChild-1].innerText+"_"+id, id, mappingNode.innerText);    
    newItemTR.appendChild(textItemTD);                    

    // Quantite
    mappingNode = arrayOfChild[indiceChild++];
    var textItemTD = createTDInputText (arrayOfHEADERChild[indiceChild-1].innerText+"_"+id, id, mappingNode.innerText);    
    newItemTR.appendChild(textItemTD);                    

    // Date achat
    mappingNode = arrayOfChild[indiceChild++];
    var textItemTD = createTDInputText (arrayOfHEADERChild[indiceChild-1].innerText+"_"+id, id, mappingNode.innerText);    
    newItemTR.appendChild(textItemTD);                    

    // prix achat
    mappingNode = arrayOfChild[indiceChild++];
    var textItemTD = createTDInputText (arrayOfHEADERChild[indiceChild-1].innerText+"_"+id, id, mappingNode.innerText);    
    newItemTR.appendChild(textItemTD);                    

    // date sortie
    mappingNode = arrayOfChild[indiceChild++];
    var textItemTD = createTDInputText (arrayOfHEADERChild[indiceChild-1].innerText+"_"+id, id, mappingNode.innerText);    
    newItemTR.appendChild(textItemTD);                    

    // quantite sortie
    mappingNode = arrayOfChild[indiceChild++];
    var textItemTD = createTDInputText (arrayOfHEADERChild[indiceChild-1].innerText+"_"+id, id, mappingNode.innerText);    
    newItemTR.appendChild(textItemTD);                    

    // nature sortie
    mappingNode = arrayOfChild[indiceChild++];
    var arrayComboSortie = ['NA', 'Mise au rebut','vente','vol','perte'];
    var textItemTD = createTDInputCombo (arrayOfHEADERChild[indiceChild-1].innerText+"_"+id, id, arrayComboSortie, mappingNode.innerText);    
    newItemTR.appendChild(textItemTD);                    

    // QuantiteRestante	
    mappingNode = arrayOfChild[indiceChild++];
    var textItemTD = createTDInputText (arrayOfHEADERChild[indiceChild-1].innerText+"_"+id, id, mappingNode.innerText);    
    newItemTR.appendChild(textItemTD);                    

    // DureeUtilisationPrevisionelle
    mappingNode = arrayOfChild[indiceChild++];
    var arrayComboUtilisation = ['-1 an','1 an','3 ans','5 ans','10 ans','20 ans','+20 ans'];
    var textItemTD = createTDInputCombo (arrayOfHEADERChild[indiceChild-1].innerText+"_"+id, id, arrayComboUtilisation, mappingNode.innerText);    
    newItemTR.appendChild(textItemTD);                    

    // 	FequenceUtilisation
    mappingNode = arrayOfChild[indiceChild++];
    var arrayComboFrequence = ['1/semaine','1/15 jours','1/mois','1/an'];
    var textItemTD = createTDInputCombo (arrayOfHEADERChild[indiceChild-1].innerText+"_"+id, id, arrayComboFrequence, mappingNode.innerText);    
    newItemTR.appendChild(textItemTD);                    

    // LieuStockage
    mappingNode = arrayOfChild[indiceChild++];
    var arrayComboLocal = ['Air-RPBricolage','Air-RPParkingBricolage'];
    var textItemTD = createTDInputCombo (arrayOfHEADERChild[indiceChild-1].innerText+"_"+id, id, arrayComboLocal, mappingNode.innerText);    
    newItemTR.appendChild(textItemTD);                    

   DOMElementTR.parentNode.insertBefore(newItemTR, successeurX);
}

function createTDsubmit (name, idOfDomElem, headerId, id, natureOutil, txt)
{
    var ItemTD = document.createElement("td");    
    var ItemInput = document.createElement("input");  
    ItemInput.type = "submit";
    ItemInput.name = name;
    ItemInput.id = id;
    ItemInput.value = txt;
    ItemInput.addEventListener("click", function(){AJAX_UpdateDB(idOfDomElem, headerId, id, natureOutil);}, false);
    ItemTD.appendChild(ItemInput);
    return ItemTD;
}

function createTDvide (nbcolone)
{
    var ItemTD = document.createElement("td");    
    ItemTD.colSpan = nbcolone;
    var ItemText = document.createTextNode("Non modifiable");  
    ItemTD.appendChild(ItemText);
    return ItemTD;
}
function createTDInputText (name, id, txt)
{
    var ItemTD = document.createElement("td");    
    var ItemInput = document.createElement("input");  
    ItemInput.type = "text";
    ItemInput.className = "SelectTable";
    ItemInput.name = name;
    ItemInput.id = id;
    ItemInput.value = txt;
    ItemTD.appendChild(ItemInput);
    return ItemTD;
}



function createTDInputCombo (name, id, arrayCombo, defaultvalue)
{
    var ItemTD = document.createElement("td");    
    var ItemSelect = document.createElement("select");
    ItemSelect.name = name;
    ItemSelect.id = id;


    var index;
    var texte = "";
    for	(index = 0; index < arrayCombo.length; index++) {
        texte = arrayCombo[index];
         var ItemOption = document.createElement("option");
         ItemOption.value = texte;

         if (defaultvalue === texte)
             ItemOption.selected = true;
         
         var ItemTexte = document.createTextNode(texte);
         ItemOption.appendChild(ItemTexte);
         ItemSelect.appendChild(ItemOption);
    }

    ItemTD.appendChild(ItemSelect);
    return ItemTD; 
}

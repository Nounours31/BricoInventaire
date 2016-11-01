/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function AJAX_UpdateDB(idOfDomElem, headerId, id, natureOutil) 
{
   var DOMElementTR = document.getElementById(idOfDomElem);
   var DOMElementHeader = document.getElementById(headerId);
   var successeurX = DOMElementTR.nextSibling;
   
   // liste des element td de la ligne
   var arrayOfChild = successeurX.children;
   var arrayOfHEADERChild = DOMElementHeader.children;
 
    var ContenuCellule;
    var NomCellule;
    var Params = "id="+id+"&natureOutil="+natureOutil;
    for	(index = 2; index < arrayOfChild.length; index++) 
    {
        ContenuCellule = arrayOfChild[index].children[0].value;
        NomCellule = arrayOfHEADERChild[index+4].innerText
                
        Params += "&" + NomCellule + "=" + ContenuCellule.replace("+","%2B");
    }
    
  
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() 
  {
    if (xhttp.readyState == 4 && xhttp.status == 200) 
    {
        document.getElementById("AJAX").innerHTML = xhttp.responseText;
    }
  }
  xhttp.open("POST", "./PHP/AJAX_UpdateDB.php", true);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.send(Params);
}


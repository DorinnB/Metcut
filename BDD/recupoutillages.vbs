' Script de recuperation des summary des essais depuis 759, 793 et TestSuite
' Crée le 23 Feb 2011

		'Initialisation des variables
Dim cheminData, cheminResult, fichier


cheminData = "I:\Rangement Outillages"
fichier = "enregistrement_outillage.xls"
cheminResult = "\\BDD\c$\wamp\www\Metcut\BDD"

Const ForReading = 1, ForWriting = 2, ForAppend = 8
Set oFSO = CreateObject("Scripting.FileSystemObject")
Set pile = oFSO.OpenTextFile(cheminResult & "\pile.txt", ForWriting,true)



'Ouverture de l'application
Set appExcel = CreateObject("Excel.Application")
'Ouverture d'un fichier Excel
Set wbExcel = appExcel.Workbooks.Open(cheminData & "\" & fichier)
'wsExcel correspond à la première feuille du fichier

nbonglet = wbExcel.sheets.count

For i=2 to nbonglet
	Set wsExcel = wbExcel.Worksheets(i)
	ligne = 9
	Do While wsExcel.Cells (ligne, 4).Value<>""
		id1 = wsExcel.Cells (ligne, 1).Value
		id2 = wsExcel.Cells (ligne, 2).Value
		id3 = wsExcel.Cells (ligne, 3).Value
		id4 = wsExcel.Cells (ligne, 4).Value
		id5 = wsExcel.Cells (ligne, 5).Value
		id12 = wsExcel.Cells (ligne, 12).Value
		
		nom = id1 & "-" & id2 & "-" & id3 & "-" & id4 & "-" & id5

		If id12="" Then
			actif = 1
		Else
			actif = 0
		End If
		
		sql = "INSERT INTO outillages (outillage,outillage_actif) VALUES ('" & nom & "','" & actif & "') ON DUPLICATE KEY UPDATE outillage_actif ='" & actif & "'"
		pile.writeline(sql)		
		ligne=ligne + 1
	Loop
Next

pile.Close

wbExcel.close()
appExcel.Quit 

Set IE=CreateObject("InternetExplorer.Application") 
ie.navigate "http://bdd/Metcut/index.php?page=envoioutillages#menu" 
ie.visible=1
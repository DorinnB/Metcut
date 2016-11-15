

datafolder = "I:\Computer\BDD\DataBDD\test\"
Set oFSo = CreateObject("Scripting.FileSystemObject")





Dim Con, Rec, MsG, A
Set Con = CreateObject("ADODB.Connection")
Set Rec = CreateObject("ADODB.Recordset")
 
On Error Resume Next
Con.Open "DRIVER={MySQL ODBC 5.3 Unicode Driver};SERVER=localhost;DATABASE=metcut;UID='root';PASSWORD='';"

If Err.Number <> 0 Then
    MsG = "Erreur N°" & Err.Number & vbCrLf _
    & "Description:" & vbCrLf & Err.Description & vbCrLf _
    & "Impossible d'ouvrire la BD"
    MsgBox MsG, vbCritical, "Erreur"
Else
    A = "SELECT * FROM chauffages"
    Rec.Open A, Con
    If Err.Number <> 0 Then
        Con.Close
        MsG = "Erreur N°" & Err.Number & vbCrLf _
        & "Description:" & vbCrLf & Err.Description & vbCrLf _
        & "Impossible d'ouvrire la table"
        MsgBox MsG, vbCritical, "Erreur"
	Else
        If Rec.EOF Then
            MsG = "Aucun enregistrement disponible pour cette requête"
            MsgBox MsG, vbInformation, ""
        Else
            MsgBox Rec.Fields("chauffage")
        End If
        Con.Close
        Rec.Close
    End If
End If
Set Rec = Nothing
Set Con = Nothing
<style>
	tr{
cursor:pointer;
}
</style>
<script language="javascript">
$(document).ready( function () {

    var table=$('#table_id').DataTable({
		"iDisplayLength": 10,
		"columnDefs": [ {
            targets: [ 0 ],
            orderData: [ 0, 1 ]
        }, {
            targets: [ 1 ],
            orderData: [ 1, 0 ]
        }, {
            targets: [ 4 ],
            orderData: [ 4, 0 ]
        }, {
                "targets": [ 5 ],
                "visible": false
            } ],
	});
	
	// Setup - add a text input to each footer cell
	$('#table_id tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="Search '+title+'" size="10"/>' );
    } );
	
	table.columns().every( function () {
        var that = this;
 
        $( 'input', this.footer() ).on( 'keyup change', function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        } );
    } );

	table.column(1)			//par defaut, on affiche pas les job fini (=100)
			.search(
				'^([0-9]|[1-9][0-9])$', 
				true, 
				false )
			.draw();
	
	
	
} );
</script>

<script language="javascript">
	function filtre(col,regex){
		var table=$('#table_id').DataTable();
//alert(regex);
		table.column(col)
			.search(
				regex, 
				true, 
				false )
			.draw();	

}
</script>		
		
<script language="javascript">
function popup(fic)
// on ouvre dans une fenêtre le fichier passé en paramètre.
// cette ouverture peut être améliorée en passant d'autres
// paramètres que la taille et la position de la fenêtre.
{ window.open(fic,'Choisir','toolbar=yes, width=1300,height=840,top=50,left=50,scrollbars=yes'); }
</script>

<script language="javascript">
function formfiltre(filtre)	{
	
	var f = document.createElement('form');
	f.setAttribute('method','post');
	f.setAttribute('action','index.php?page=tbljobs2');
	
	var i = document.createElement('input'); //input element, text
	i.setAttribute('type','hidden');
	i.setAttribute('name','filtre');
	i.setAttribute('value',filtre);

	f.appendChild(i);
	document.getElementsByTagName('body')[0].appendChild(f);
	
	f.submit();
}
</script>




    <div style="float:left">
		<button type="button" onclick="javascript:popup('pages/newSplit.php?id_tbljob=0')">
			<img src="./css/croix.png">
		</button>		
	</div>

<style>
	div.filtre	{
	float:right;
	border: 0 solid #f5c5c5;
	background-color: rgb(206, 227, 246);
    border-radius: 5px;
    box-shadow: 1px 1px 2px #c0c0c0 inset;
	padding : 0 20px;
}
</style>
<div class="filtre"><span onclick="javascript:filtre('1','');filtre('4','');filtre('5','')">Reset</span></div>
<div class="filtre"><span onclick="javascript:filtre('1','^100')">Job terminé</span></div>
<div class="filtre"><span onclick="javascript:filtre('5','^0')">ToBeCheck</span></div>
<div class="filtre"><span onclick="javascript:filtre('1','[2-5][0-9]')">en cours</span></div>
<div class="filtre"><span onclick="javascript:filtre('4','^[^-]');filtre('1','^([0-9]|[1-9][0-9])$')">Labo</span></div>

	
	
<?php
$titre=array(	'id_tbljob', 'statut',	'customer',		'job',	'split', 'checked',	'instruction',	'type','ST', 'temperature',		'matiere',	'drawing',	'comments',	'nb_specimen',  'tooling',	'MRI_req',	'MFG_qty',	'nb_MRI',	'sub_C',	'type_machine',	'nb_test_MRSAS',	'ordre',	'reception_eprouvette',	'retour_eprouvette',	'test_leadtime',	'test_start',	'test_end',	'test_leadtime',	'estimated_turn_over',	'estimated_testing',	'invoiced_turn_over',	'invoiced_testing');
$titresql=array('id_tbljob', 'id_statut',	'customer',	'job',	'split', 'checked',	'instruction',	'type_essai', 'type_soustraitance', 'id_condition_temps',	'matiere',	'drawing',	'comments',	'nb_specimen',  'tooling',	'MRI_req',	'MFG_qty',	'nb_MRI',	'sub_C',	'type_machine',	'nb_test_MRSAS',	'ordre',	'reception_eprouvette',	'retour_eprouvette',	'test_leadtime',	'test_start',	'test_end',	'test_leadtime',	'estimated_turn_over',	'estimated_testing',	'invoiced_turn_over',	'invoiced_testing');

//$req ajoutant les temperatures des ep mais temps chargement tres long (>7s)
$req = 'SELECT 
	id_tbljob, id_statut, customer, job, split, instruction, type_essai, id_condition_temps, matiere, drawing, comments, nb_specimen, type_feuille, nb_type_feuille, tooling, MRI_req, MFG_qty, nb_MRI, sub_C, type_machine, nb_test_MRSAS, ordre, reception_eprouvette, retour_eprouvette, test_leadtime, test_start, test_end, test_leadtime, estimated_turn_over, estimated_testing, invoiced_turn_over, invoiced_testing, checked,
		GROUP_CONCAT(DISTINCT Round(c_temperature,0) ORDER BY c_temperature ASC SEPARATOR " / ")
	FROM tbljobs 
	LEFT JOIN eprouvettes ON eprouvettes.id_job=tbljobs.id_tbljob
	LEFT JOIN matieres ON matieres.id_matiere=tbljobs.id_matiere 
	LEFT JOIN type_essais ON type_essais.id_type_essai=tbljobs.id_type_essai 
	LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
	LEFT JOIN contacts ON contacts.id_contact=info_jobs.id_contact	
	where tbljob_actif=1
    group by id_tbljob';
$req = 'SELECT id_tbljob, id_statut, customer, job, split, instruction, type_essai, type_soustraitance, id_condition_temps, matiere, drawing, comments, nb_specimen, type_feuille, nb_type_feuille, tooling, MRI_req, MFG_qty, nb_MRI, sub_C, type_machine, nb_test_MRSAS, ordre, reception_eprouvette, retour_eprouvette, test_leadtime, test_start, test_end, test_leadtime, estimated_turn_over, estimated_testing, invoiced_turn_over, invoiced_testing, checked
	FROM tbljobs 
	LEFT JOIN matieres ON matieres.id_matiere=tbljobs.id_matiere 
	LEFT JOIN type_essais ON type_essais.id_type_essai=tbljobs.id_type_essai 
	LEFT JOIN type_soustraitances ON type_soustraitances.id_type_soustraitance=tbljobs.id_type_essai
	LEFT JOIN info_jobs ON info_jobs.id_info_job=tbljobs.id_info_job
	LEFT JOIN contacts ON contacts.id_contact=info_jobs.id_contact	
	where tbljob_actif=1;';	
//echo $req;
	$req_tbljobs = $db->query($req);

	while ($tbl_req = mysqli_fetch_array($req_tbljobs)) {
		$tbljobs[]=$tbl_req;
	}	

echo '
<table id="table_id" class="cell-border" cellspacing="0" width="100%">
	<thead>
		<tr>';	
		for($i=0; $i<count($titre); $i++){
			echo '<th>'.$titre[$i].'</th>';
		}
	echo '</tr>
	</thead>
	<tfoot>
		<tr>';	
		for($i=0; $i<count($titre); $i++){
			echo '<th>'.$titre[$i].'</th>';
		}
	echo '</tr>
	</tfoot>
	<tbody>';
							


	


	if ($tbljobs) {
		for($k=0;$k < count($tbljobs);$k++)	{
echo '<tr style="font-size:12px"
onclick="javascript:popup(\'pages/job.php?id_tbljob='.$tbljobs[$k]['id_tbljob'].'\')">';
//			echo '<tr style="font-size:12px" onclick="document.location=\'index.php?page=job&id_tbljob='.$tbljobs[$k]['id_tbljob'].'\'">';



			
			$color="white";
			$color=($tbljobs[$k]['id_statut']>=00)? "#FA5858" : $color;
			$color=($tbljobs[$k]['id_statut']>=10)? "#F79F81" : $color;
			$color=($tbljobs[$k]['id_statut']>=20)? "#FF8000" : $color;
			$color=($tbljobs[$k]['id_statut']>=50)? "#F3F781" : $color;
			$color=($tbljobs[$k]['id_statut']>=70)? "#9FF781" : $color;
			$color=($tbljobs[$k]['id_statut']>=80)? "#04B404" : $color;
			
						
			for($j=0;$j < count($titresql);$j++)	{
				echo '<td bgcolor="'.$color.'">
				'.$tbljobs[$k][$titresql[$j]].'</td>
				';
			}
			echo '</tr>
			';
		}
	}

	

	
	
	
	?>

	</tbody>
</table>







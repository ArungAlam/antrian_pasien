///////////////////////////////
/*
Programer : Febryan Haris Anshori
Describe  : File Ajax untuk combobox -,-
*/
//////////////////////////////

var xmlhttp = false;

try {
	xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
} catch (e) {
	try {
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	} catch (E) {
		xmlhttp = false;
	}
}

if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
	xmlhttp = new XMLHttpRequest();
}

//untuk tampilkan poli
function fakultas(cust_usr_jenis){
	
	var obj=document.getElementById("jurusan-view");
	//var objJam=document.getElementById("jurusan-view-jam");
	
	var url='program.php?id_prog='+cust_usr_jenis;
	//var urlz='jam.php?id_poli='+id_poli;
	
	xmlhttp.open("GET", url);
	
	xmlhttp.onreadystatechange = function() {
		if ( xmlhttp.readyState == 4 && xmlhttp.status == 200 ) {
			obj.innerHTML = xmlhttp.responseText;
			//objJam.innerHTML = xmlhttp.responseText;
		} else {
			obj.innerHTML = "<div align ='center'><img src='waiting.gif' alt='Loading' /></div>";
			//objJam.innerHTML = "<div align ='center'><img src='waiting.gif' alt='Loading' /></div>";
		}
	}
	xmlhttp.send(null);

}

// untuk menampilkan jam jadwal
/*function jam(id_poli){
	
	var objJam=document.getElementById("jurusan-view-jam");
	var urls='jam.php?id_poli='+id_poli;
	
	xmlhttp.open("GET", urls);
	
	xmlhttp.onreadystatechange = function() {
		if ( xmlhttp.readyState == 4 && xmlhttp.status == 200 ) {
			objJam.innerHTML = xmlhttp.responseText;
		} else {
			objJam.innerHTML = "<div align ='center'><img src='waiting.gif' alt='Loading' /></div>";
		}
	}
	xmlhttp.send(null);

}
*/
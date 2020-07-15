$(function(){
	$('.jqte-test').jqte();
	
	// settings of status
	var jqteStatus = true;
	$(".status").click(function()
	{
		jqteStatus = jqteStatus ? false : true;
		$('.jqte-test').jqte({"status" : jqteStatus})
	});


});





function closedelete(path,id){
	
		var checkstr =  confirm('هل انت متأكد من عملية الحذف ؟');
		if(checkstr == true){
		  // do your code
		  location.href = path+"id="+id;
		}else{
		return false;
		}
}

function likes(dir,rsdata){
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
		document.getElementById(rsdata).innerHTML = this.responseText;
		}
	};
	xhttp.open("GET",dir, true);
	xhttp.send();
}


function search_for_novel(path,idres){
	var novel_name = $('#search_q_novel').val();

	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
		document.getElementById(idres).innerHTML = this.responseText;
		}
	};
	xhttp.open("GET",path+novel_name, true);
	xhttp.send();
}


function active_user(path,idres){
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
		document.getElementById(idres).innerHTML = this.responseText;
		}
	};
	xhttp.open("GET",path, true);
	xhttp.send();
}
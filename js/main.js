// Number format
function addCommas(nStr) {
    nStr += '';
    var x = nStr.split('.');
    var x1 = x[0];
    var x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}

//sideMenu
function openSlideMenu(){
	document.getElementById('side-menu').style.width='90px';
	document.getElementById('side-menu').marginleft='90px';
	document.getElementById('sidebarmodal').style.display='block';
	let element = document.querySelector('.fa-bars');
	element.classList.add('rotate-slide');
}

function closeSlideMenu(){
	document.getElementById('side-menu').style.width='0';
	document.getElementById('side-menu').marginleft='0';
	document.getElementById('sidebarmodal').style.display='none';
	let element = document.querySelector('.fa-bars');
	element.classList.remove('rotate-slide');
}

// Create modal
function modal(){
	// Get modal element
	var modal = document.getElementById('simpleModal');
	// Get open modal button
	var modalBtn = document.getElementById('modalBtn');
	//Get close Button
	var closeBtn = document.getElementById('closeBtn');

	// Listen for open click
	modalBtn.addEventListener('click',openModal);

	// Listen for close click
	closeBtn.addEventListener('click',closeModal);

	// Listen for outside click
	window/addEventListener('click',outsideClick);


	//function to open modal
	function openModal(){
		modal.style.display = 'block';
	}

	//function to close modal
	function closeModal(){
		modal.style.display = 'none';
	}

	function outsideClick(e){
		if(e.target==modal){
		modal.style.display = 'none';
		}
	}
}

function showLogin(){
	let modal = document.getElementById('simpleModal');

	modal.style.display='block';
}

function scrollOpacity(){
	let header = document.querySelector('.subheader');
	header.style.opacity=0;

	let body = document.getElementsByTagName("BODY")[0];
	if(body.scrollTop==0){
		header.style.opacity=1;
	}

	header.addEventListener('mouseover',function(){
		header.style.opacity=1;
	});
}

//No display if count is zero
var y = document.getElementById("notifnum");
var z = document.getElementById("pmnum");

if(y.innerText!=0){
	y.style.display="inline";
}
if(z.innerText!=0){
	z.style.display="inline";
}

function toggleNotif(){
	  var dropdown = document.getElementById("notifdrop");
	  var modal3 =  document.getElementById("modal3");

    if (dropdown.style.display === "none") {
        dropdown.style.display = "block";
       	modal3.style.display = "block";

    } else {
        dropdown.style.display = "none";
        modal3.style.display = "none";
    }

	var myRequest = new XMLHttpRequest();
	var url = 'edituserprocess.php';
	var checked = "1";
	
	var formData = "checked="+checked;
	
	myRequest.open('POST', url ,true);
	myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

	myRequest.onload = function(){
		var response= this.responseText;
		if(response){
			
		}
	}
	myRequest.send(formData);


}

// Index Page

function showCategory(){
	document.getElementById('category-slide').style.display='block';
	document.getElementById('category-slide').style.width='250px';
	document.getElementById('category-slide').marginleft='250px';
	document.getElementById('category-modal').style.display='block';
}

function hideCategory(){
	document.getElementById('category-slide').style.display='none';
	document.getElementById('category-slide').style.width='0';
	document.getElementById('category-slide').marginleft='0';
	document.getElementById('category-modal').style.display='none';
}

// Announcement

function addAnnounceComment(){
	let form = document.getElementById('comment-form');
	form.addEventListener('submit',add);

	function add(e){
		e.preventDefault();
		addSpinners();

		var myRequest = new XMLHttpRequest();
		var url = 'commentprocess.php';

		let announce = document.getElementById('announcement-text').value;
		let Auserid = document.getElementById('user-id').value;
		let announceid = document.getElementById('announce-id').value;
		if (!Auserid){
			showLogin();
			removeSpinners();
		}else{
		var formData = "announce="+announce+"&Auserid="+Auserid+"&announceid="+announceid;
		
		myRequest.open('POST', url ,true);
		myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

		myRequest.onload = function(){
			var response= this.responseText;
			if(response){
				form.reset();
				removeSpinners();
				let body = document.getElementById('announcement-comments');
				body.innerHTML=response;
			}
		}
		myRequest.send(formData);
		}

	}
}

function deleteComment(click){
	let id = click.getAttribute('value');
	let id2 = click.getAttribute('id');
	
	addSpinners();

	var myRequest = new XMLHttpRequest();
	var url = 'commentprocess.php';

	var formData = "delete="+id+"&id2="+id2;
	
	myRequest.open('POST', url ,true);
	myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

	myRequest.onload = function(){
		var response= this.responseText;
		if(response){
			removeSpinners();
			let body = document.getElementById('announcement-comments');
			body.innerHTML=response;
		}
	}
	myRequest.send(formData);	
}

// Search

function searchdropdown(){
	var crit = document.getElementById('criteria').value;
	var src = document.getElementById('search-dropdown');
	var input = document.getElementById('search-text');
	var modal =document.querySelector('.modal2');

	modal.addEventListener('click',hidesearch);

	if(input.value!=null){

		if(crit==1){
		modal.style.display='block';
		src.style.display='block';

		var myRequest = new XMLHttpRequest();
		var url = 'searchprocess.php';
		var search = document.getElementById('search-text').value;

		var formData = "search2="+search;
		
		myRequest.open('POST', url ,true);
		myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

		myRequest.onload = function(){
			var response= this.responseText;
			
			document.getElementById('search-dropdown').innerHTML = response;			
			
		}
		myRequest.send(formData);


		}else if(crit==2){
		modal.style.display='block';
		src.style.display='block';

		var myRequest = new XMLHttpRequest();
		var url = 'searchprocess.php';
		var search = document.getElementById('search-text').value;
		
		var formData = "search="+search;
		
		myRequest.open('POST', url ,true);
		myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

		myRequest.onload = function(){
			var dataArray= this.responseText.split('||');
				var output='';
				for(i=0;i<dataArray.length-1;i++){
					var itemArray = dataArray[i].split('|');
					output+='<ul class="drop-ul"><a href="profile.php?id='+itemArray[0]+'"><li><div class="drop-tn"><img src="'+itemArray[2]+'"></div><p>'+itemArray[1]+'</p><small>Joined: '+itemArray[3]+'</small><li></a></ul>';
				}
			document.getElementById('search-dropdown').innerHTML = output;
			
			
		}
		myRequest.send(formData);		
	} else if (crit==3){
		modal.style.display='block';
		src.style.display='block';

		var myRequest = new XMLHttpRequest();
		var url = 'searchprocess.php';
		var search = document.getElementById('search-text').value;

		var formData = "search3="+search;
		
		myRequest.open('POST', url ,true);
		myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

		myRequest.onload = function(){
			var response= this.responseText;
			
			document.getElementById('search-dropdown').innerHTML = response;			
			
		}
		myRequest.send(formData);

	
	}
	}

	function hidesearch(){
		modal.style.display='none';
		src.style.display='none';
	}
}

function searchProduct(){
	let panel = document.getElementById('main-search-panel');
	let modal = document.getElementById('main-search-modal');

	panel.style.display= 'block';
	modal.style.display= 'block';

	var myRequest = new XMLHttpRequest();
	var url = 'searchprocess.php';

	let mainsearch = document.getElementById('main-search').value;
	let criteria = document.getElementById('main-select').value;


	var formData = "mainsearch="+mainsearch+"&criteria="+criteria;
	
	myRequest.open('POST', url ,true);
	myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

	myRequest.onload = function(){
		var response= this.responseText;
		document.getElementById('main-search-panel').innerHTML=response;
	}
	myRequest.send(formData);
}

function hideSearchPanel(){
	let panel = document.getElementById('main-search-panel');
	let modal = document.getElementById('main-search-modal');

	panel.style.display= 'none';
	modal.style.display= 'none';
}

function browseCategory(click){
	let id = click.getAttribute('value');

	window.location.href = "searchproduct.php?search=&select="+id;
}

// friend

function friendprocess(){
	var fr=document.getElementById("fr-btn");
	fr.innerHTML='<i class="fas fa-user-plus"></i>Pending Request...';

	var myRequest = new XMLHttpRequest();
	var url = 'friendprocess.php';
	var fr =  fr.getAttribute('value');

	var formData = "fr="+fr;
	
	myRequest.open('POST', url ,true);
	myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

	myRequest.onload = function(){
		var response= JSON.parse(this.responseText);
		console.warn(response);
		if(response){
			console.log('oke-oke-okay');

		}
	}
	myRequest.send(formData);
}

function friendyes(clickedid){
	var nid = clickedid.getAttribute('value');
	var nid2='fr-'+nid;
	var fr= document.getElementById(nid2);
	fr.innerHTML="Request Accepted";

	var myRequest = new XMLHttpRequest();
	var url = 'friendprocess.php';
	var fryes =  nid;

	var formData = "fryes="+fryes;
	
	myRequest.open('POST', url ,true);
	myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

	myRequest.onload = function(){
		var response= JSON.parse(this.responseText);
		console.warn(response);
		if(response){
			console.log('oke-oke-okay');

		}
	}
	myRequest.send(formData);
}

function friendyesb(clickedid){
	var nid = clickedid.getAttribute('value');
	var nid2='fr-'+nid;
	var fr= document.getElementById(nid2);
	fr.innerHTML="Request Accepted";

	var myRequest = new XMLHttpRequest();
	var url = 'friendprocess.php';
	var fryes =  nid;

	var formData = "fryes="+fryes;
	
	myRequest.open('POST', url ,true);
	myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

	myRequest.onload = function(){
		var response= JSON.parse(this.responseText);
		console.warn(response);
		if(response){
			location.reload();
			console.log('oke-oke-okay');

		}
	}
	myRequest.send(formData);
}


function friendno(clickedid){
	var nid = clickedid.getAttribute('value');
	var nid2='fr-'+nid;
	var fr= document.getElementById(nid2);
	fr.innerHTML="Request Denied";

	var myRequest = new XMLHttpRequest();
	var url = 'friendprocess.php';
	var frno =  nid;

	var formData = "frno="+frno;
	
	myRequest.open('POST', url ,true);
	myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

	myRequest.onload = function(){
		var response= JSON.parse(this.responseText);
		console.warn(response);
		if(response){
			console.log('oke-oke-okay');

		}
	}
	myRequest.send(formData);
}

function friendnob(clickedid){
	var nid = clickedid.getAttribute('value');
	var nid2='fr-'+nid;
	var fr= document.getElementById(nid2);
	fr.innerHTML="Request Denied";

	var myRequest = new XMLHttpRequest();
	var url = 'friendprocess.php';
	var frno =  nid;

	var formData = "frno="+frno;
	
	myRequest.open('POST', url ,true);
	myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

	myRequest.onload = function(){
		var response= JSON.parse(this.responseText);
		console.warn(response);
		if(response){
			location.reload();
			console.log('oke-oke-okay');

		}
	}
	myRequest.send(formData);
}

function friendremove(){
	var rmv=document.getElementById('rmv-fr');

	rmv.innerText='Removing friend...';

	var fid = rmv.getAttribute('value');

	var myRequest = new XMLHttpRequest();
	var url = 'friendprocess.php';
	var rmv =  fid;

	var formData = "rmv="+rmv;
	
	myRequest.open('POST', url ,true);
	myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

	myRequest.onload = function(){
		var response= JSON.parse(this.responseText);
		console.warn(response);
		if(response){
			console.log('oke-oke-okay');

		}
	}
	myRequest.send(formData);
}

//Login AJAX
function ajaxLogin(){
	document.getElementById('log-form').addEventListener('submit', postName);

	function postName(e){
		e.preventDefault();

		addSpinners();

		var myRequest = new XMLHttpRequest();
		var url = 'loginprocess.php';

		//form data variables
		var username = document.getElementById('log-user').value;
		var password = document.getElementById('log-pass').value;
		var remember = document.getElementById('log-remember');

		if(remember.checked==true){
			remember=1;
		}else{
			remember=0;
		}

		var formData = "username="+username+"&password="+password+"&remember="+remember;
		
		myRequest.open('POST', url ,true);
		myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

		myRequest.onload = function(){
			var response= JSON.parse(this.responseText);
			
			if(response[0]==0){
				
				window.location.href = "loginsuccess.html";
			
			} else {
				removeSpinners();
				document.getElementById('error-message').innerHTML=response[1];
			}
		}
		myRequest.send(formData);
	}
}

// Register AJAX
function ajaxRegister(){
	document.getElementById('reg-form').addEventListener('submit', regName);

	function regName(e){
		e.preventDefault();

		addSpinners();			

		var myRequest = new XMLHttpRequest();
		var url = 'registerprocess.php';

		//form data variables
		var username = document.getElementById('reg-name').value;
		var password = document.getElementById('reg-password').value;
		var retype = document.getElementById('reg-retype').value;
		var email = document.getElementById('reg-email').value;
		
		var formData = "username="+username+"&password="+password+"&retype="+retype+"&email="+email;
		
		myRequest.open('POST', url ,true);
		myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

		myRequest.onload = function(){
			var response= this.responseText;
			console.warn(response);
			if(response=="success"){

				window.location.href = 'thankyou.html';
			} else {
				document.getElementById('error-message2').innerHTML = response;
				removeSpinners();
			}
		}
		myRequest.send(formData);
	}
}

function AjaxEditUser(){
	document.getElementById('edit-username').addEventListener('submit', editName);

	function editName(e){
		e.preventDefault();

		addSpinners();			

		var myRequest = new XMLHttpRequest();
		var url = 'edituserprocess.php';

		//form data variables
		var username = document.getElementById('edit-name').value;
		var update = document.getElementById('hidden').value;
		var time = document.getElementById('hidden2').value;

		var formData = "username="+username+"&update="+update+"&time="+time;
		
		myRequest.open('POST', url ,true);
		myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

		myRequest.onload = function(){
			var response= JSON.parse(this.responseText);
			if(response=="success"){

				window.location.href = 'changesuccess.html';
			} else {
				var output='';
					for(var i in response){
					output += '<ul>'+
						'<li>'+response[i]+'</li>'+
						'</ul>';
					}
				document.getElementById('error-message2').innerHTML = output;
				removeSpinners();
			}
		}
		myRequest.send(formData);
	}
}

function AjaxEditEmail(){
	document.getElementById('edit-email').addEventListener('submit', editEmail);

	function editEmail(e){
		e.preventDefault();

		addSpinners();
			

		var myRequest = new XMLHttpRequest();
		var url = 'edituserprocess.php';

		//form data variables
		var email = document.getElementById('editemail').value;

		var formData = "email="+email;
		
		myRequest.open('POST', url ,true);
		myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

		myRequest.onload = function(){
			var response= JSON.parse(this.responseText);
			console.warn(response);
			if(response=="success"){

				window.location.href = 'changeesuccess.html';
			} else {
				var output='';
					for(var i in response){
					output += '<ul>'+
						'<li>'+response[i]+'</li>'+
						'</ul>';
					}
				document.getElementById('error-message4').innerHTML = output;
				removeSpinners();
			}
		}
		myRequest.send(formData);
	}
}

function AjaxEditPass(){
	document.getElementById('edit-password').addEventListener('submit', editPass);

	function editPass(e){
		e.preventDefault();

		addSpinners();
			

		var myRequest = new XMLHttpRequest();
		var url = 'edituserprocess.php';

		//form data variables
		var oldpass = document.getElementById('edit-oldpassword').value;
		var newpass = document.getElementById('edit-newpassword').value;
		var retype = document.getElementById('edit-retype').value;
		var truepass = document.getElementById('hidden3').value;

		var formData = "oldpass="+oldpass+"&newpass="+newpass+"&retype="+retype+"&truepass="+truepass;
		
		myRequest.open('POST', url ,true);
		myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

		myRequest.onload = function(){
			var response= JSON.parse(this.responseText);
			console.warn(response);
			if(response=="success"){

				window.location.href = 'changepsuccess.html';
			} else {
				var output='';
					for(var i in response){
					output += '<ul>'+
						'<li>'+response[i]+'</li>'+
						'</ul>';
					}
				document.getElementById('error-message3').innerHTML = output;
				removeSpinners();
			}
		}
		myRequest.send(formData);
	}
}

// Chat

function ajaxinbox(){
	var form=document.getElementById('chatform');
	var formsend=document.getElementById('chatform').addEventListener('submit', sendmessage);

	function sendmessage(e){
		e.preventDefault();

		var myRequest = new XMLHttpRequest();
		var url = 'inboxprocess.php';

		//form data variables
		var message = document.getElementById('sendmsg').value;
		var name = document.getElementById('hidden').value;
		
		var formData = "message="+message+"&name="+name;
		
		myRequest.open('POST', url ,true);
		myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

		myRequest.onload = function(){
			var response= this.responseText;
			if(response){
				form.reset();
				
				document.querySelector('.right-inbox').innerHTML=response;
				var messageBody = document.querySelector(".right-inbox");
				messageBody.scrollTop = messageBody.scrollHeight - messageBody.clientHeight;
				if(name==71){
					botreply(message,name);
				}
			}
		}
		myRequest.send(formData);
	}
}

function loadInboxInterval(){
	setInterval(loadInbox, 2000);

	function loadInbox() {
    	
    	var myRequest = new XMLHttpRequest();
		var url = 'inboxprocess.php';

		//form data variables
		var load = "hello";
		var name = document.getElementById('hidden').value;
		
		
		var formData = "load="+load+"&name="+name;
		
		myRequest.open('POST', url ,true);
		myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

		myRequest.onload = function(){
			var response= this.responseText;
			if(response){
				
				document.querySelector('.right-inbox').innerHTML=response;
			}
		}
		myRequest.send(formData);
	
	}
}

function botreply(message,name){
	var form=document.getElementById('chatform');

	if(message=='!hello'){
		var myRequest = new XMLHttpRequest();
		var url = 'inboxprocess.php';

		//form data variables
		var hellobot = 'hello';		
		
		var formData = "hellobot="+hellobot;
		
		myRequest.open('POST', url ,true);
		myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

		myRequest.onload = function(){
			var response= this.responseText;
			if(response){

				form.reset(); 
				document.querySelector('.right-inbox').innerHTML=response;
				var messageBody = document.querySelector(".right-inbox");
				messageBody.scrollTop = messageBody.scrollHeight - messageBody.clientHeight;
			}
		}
		myRequest.send(formData);
	}else if(message=='!time'){
		var myRequest = new XMLHttpRequest();
		var url = 'inboxprocess.php';

		//form data variables
		var time = 'tell time';
		
		
		var formData = "time="+time;
		
		myRequest.open('POST', url ,true);
		myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

		myRequest.onload = function(){
			var response= this.responseText;
			if(response){

				form.reset();
				
				document.querySelector('.right-inbox').innerHTML=response;
				var messageBody = document.querySelector(".right-inbox");
				messageBody.scrollTop = messageBody.scrollHeight - messageBody.clientHeight;
			}
		}
		myRequest.send(formData);
	}else if(message=='!thanks'){
		var myRequest = new XMLHttpRequest();
		var url = 'inboxprocess.php';

		//form data variables
		var thanks = 'say thank you';
		
		
		var formData = "thanks="+thanks;
		
		myRequest.open('POST', url ,true);
		myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

		myRequest.onload = function(){
			var response= this.responseText;
			if(response){

				form.reset();
				
				document.querySelector('.right-inbox').innerHTML=response;
				var messageBody = document.querySelector(".right-inbox");
				messageBody.scrollTop = messageBody.scrollHeight - messageBody.clientHeight;
			}
		}
		myRequest.send(formData);
	}else if(message=='!bye'){
		var myRequest = new XMLHttpRequest();
		var url = 'inboxprocess.php';

		//form data variables
		var bye = 'say goodbye';
		
		
		var formData = "bye="+bye;
		
		myRequest.open('POST', url ,true);
		myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

		myRequest.onload = function(){
			var response= this.responseText;
			if(response){

				form.reset();
				
				document.querySelector('.right-inbox').innerHTML=response;
				var messageBody = document.querySelector(".right-inbox");
				messageBody.scrollTop = messageBody.scrollHeight - messageBody.clientHeight;
				setTimeout(function () {
					window.location.href="index.php";
				}, 3000);
			}
		}
		myRequest.send(formData);
	}
}

function showChatPanel(){
	let modal = document.getElementById('chat-modal');
	let panel = document.getElementById('chat-panel');

	if(panel.style.display=='block'){
		modal.style.display='none';
		panel.style.display='none';
	}else{
		modal.style.display='block';
		panel.style.display='block';
	}
}

function hideChatPanel(){
	let modal = document.getElementById('chat-modal');
	let panel = document.getElementById('chat-panel');

	modal.style.display='none';
	panel.style.display='none';
}

function searchChat(){
	let form = document.getElementById('chat-search-form');
	form.addEventListener('submit', stop);

	var myRequest = new XMLHttpRequest();
	var url = 'searchprocess.php';
	var search = document.getElementById('chat-search').value;

	var formData = "chatsearch="+search;
	
	myRequest.open('POST', url ,true);
	myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

	myRequest.onload = function(){
		var response= this.responseText;
		document.getElementById('chat-panel-body').innerHTML = response;			
		
	}
	myRequest.send(formData);

	function stop(e){
		e.preventDefault();		
	}
}

// Admin Panel

function adminLogin(){
	let form = document.getElementById('admin-login');
	form.addEventListener('submit',login);

	function login(e){
		e.preventDefault();

		var myRequest = new XMLHttpRequest();
		var url = 'adminprocess.php';

		let name = document.getElementById('admin-name').value;
		let password = document.getElementById('admin-password').value;

		var formData = "login="+name+"&password="+password;
		
		myRequest.open('POST', url ,true);
		myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

		myRequest.onload = function(){
			var response= this.responseText;
			if(response=='success'){
				window.location.replace('adminpanel.php');
			}else{
				document.getElementById('error-message3').innerHTML=response;
			}

		}
		myRequest.send(formData);

	}
}

function showReportTab(){
	let tab1 = document.getElementById('report-tab');
	let tab2 = document.getElementById('announcement-tab');
	let tab3 = document.getElementById('monitoring-tab');
	let tab4 = document.getElementById('sales-tab');

	let body1 = document.getElementById('admin-reports');
	let body1a = document.getElementById('get-users-div');
	let body1b = document.getElementById('fetch');
	let body2 = document.getElementById('announcement-div');
	let body3 = document.getElementById('monitoring');
	let body4 = document.getElementById('sales');

	tab1.classList.add('style-tab');
	tab2.classList.remove('style-tab');	
	tab3.classList.remove('style-tab');	
	tab4.classList.remove('style-tab');

	body1.style.display='block';
	body1a.style.display='block';
	body1b.style.display='block';
	body2.style.display='none';
	body3.style.display='none';
	body4.style.display='none';
}

function showAnnouncementTab(){
	let tab1 = document.getElementById('report-tab');
	let tab2 = document.getElementById('announcement-tab');
	let tab3 = document.getElementById('monitoring-tab');
	let tab4 = document.getElementById('sales-tab');

	let body1 = document.getElementById('admin-reports');
	let body1a = document.getElementById('get-users-div');
	let body1b = document.getElementById('fetch');
	let body2 = document.getElementById('announcement-div');
	let body3 = document.getElementById('monitoring');
	let body4 = document.getElementById('sales');

	tab1.classList.remove('style-tab');
	tab2.classList.add('style-tab');	
	tab3.classList.remove('style-tab');	
	tab4.classList.remove('style-tab');

	body1.style.display='none';
	body1a.style.display='none';
	body1b.style.display='none';
	body2.style.display='block';
	body3.style.display='none';
	body4.style.display='none';
}

function showMonitoringTab(){
	let tab1 = document.getElementById('report-tab');
	let tab2 = document.getElementById('announcement-tab');
	let tab3 = document.getElementById('monitoring-tab');
	let tab4 = document.getElementById('sales-tab');

	let body1 = document.getElementById('admin-reports');
	let body1a = document.getElementById('get-users-div');
	let body1b = document.getElementById('fetch');
	let body2 = document.getElementById('announcement-div');
	let body3 = document.getElementById('monitoring');
	let body4 = document.getElementById('sales');

	tab1.classList.remove('style-tab');
	tab2.classList.remove('style-tab');	
	tab3.classList.add('style-tab');	
	tab4.classList.remove('style-tab');

	body1.style.display='none';
	body1a.style.display='none';
	body1b.style.display='none';
	body2.style.display='none';
	body3.style.display='grid';
	body4.style.display='none';

	showOrders();
}

function showSalesTab(){
	let tab1 = document.getElementById('report-tab');
	let tab2 = document.getElementById('announcement-tab');
	let tab3 = document.getElementById('monitoring-tab');
	let tab4 = document.getElementById('sales-tab');

	let body1 = document.getElementById('admin-reports');
	let body1a = document.getElementById('get-users-div');
	let body1b = document.getElementById('fetch');
	let body2 = document.getElementById('announcement-div');
	let body3 = document.getElementById('monitoring');
	let body4 = document.getElementById('sales');

	tab1.classList.remove('style-tab');
	tab2.classList.remove('style-tab');	
	tab3.classList.remove('style-tab');
	tab4.classList.add('style-tab');

	body1.style.display='none';
	body1a.style.display='none';
	body1b.style.display='none';
	body2.style.display='none';
	body3.style.display='none';
	body4.style.display='grid';

	showDailyTab();
}

function sendAllUser(){
	var form = document.getElementById('sendtoallform');
	form.addEventListener('submit', postName);

	function postName(e){
		e.preventDefault();

		addSpinners();

		var myRequest = new XMLHttpRequest();
		var url = 'inboxprocess.php';

		//form data variables
		var sendall = document.getElementById('sendtoallmessage').value;

		var formData = "sendall="+sendall;
		
		myRequest.open('POST', url ,true);
		myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

		myRequest.onload = function(){

			var response= this.responseText;
			
			if(response){
				removeSpinners();
				form.reset();
				alert('Message is sent to all users');
			}
		}
		myRequest.send(formData);
	}
}

function fetchUser(){
	var myRequest = new XMLHttpRequest();
	var url = 'adminprocess.php';

	var fetch = document.getElementById('get-user').value;

	var formData = "fetch="+fetch;
	
	myRequest.open('POST', url ,true);
	myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

	myRequest.onload = function(){
		var response= this.responseText;
			
		document.getElementById('fetch').innerHTML = response;
		
		
	}
	myRequest.send(formData);
}

function resetfetch(){
	var input = document.getElementById('get-user');
	var form = document.getElementById('fetch');
	input.value='';
	form.innerHTML ='<div onclick="resetfetch()" class="closethis"><a><i class="fas fa-times"></i></a></div>'; 
}

function useraccess(clickedid){
	
	var user='user-'+clickedid;
	var myRequest = new XMLHttpRequest();

	var url = 'adminprocess.php';

	var formData = "status="+clickedid;
	
	myRequest.open('POST', url ,true);
	myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

	myRequest.onload = function(){
		var response= this.responseText;
		if(response){
			document.getElementById(user).innerHTML= response;

		}
	}
	myRequest.send(formData);
}

function removephoto(clickedid){
	var userid = clickedid.getAttribute('value');
	var divid='photo-'+userid;
	var div= document.getElementById(divid);
	div.innerHTML="Removing Photo...";

	var myRequest = new XMLHttpRequest();

	var url = 'adminprocess.php';

	var formData = "photo="+userid;
	
	myRequest.open('POST', url ,true);
	myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

	myRequest.onload = function(){
		var response= this.responseText;
	}
	myRequest.send(formData);
}

function settoSeller(clickedid){
	var userid = clickedid.getAttribute('value');
	var divid='type-'+userid;
	var div= document.getElementById(divid);
	div.innerHTML="Change to Seller...";

	var myRequest = new XMLHttpRequest();

	var url = 'adminprocess.php';

	var formData = "seller="+userid;
	
	myRequest.open('POST', url ,true);
	myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

	myRequest.onload = function(){
		var response= this.responseText;
		console.log(response);
	}
	myRequest.send(formData);
}

function settoUser(clickedid){
	var userid = clickedid.getAttribute('value');
	var divid='type-'+userid;
	var div= document.getElementById(divid);
	div.innerHTML="Change to User...";

	var myRequest = new XMLHttpRequest();

	var url = 'adminprocess.php';

	var formData = "notseller="+userid;
	
	myRequest.open('POST', url ,true);
	myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

	myRequest.onload = function(){
		var response= this.responseText;
		console.log(response);
	}
	myRequest.send(formData);
}

function addNewCategory(){
	let body1= document.getElementById('add-category');
	let body2= document.getElementById('set-price');
	let body3= document.getElementById('history');
	let body4= document.getElementById('order-body');
	let body5= document.getElementById('product-monitoring');
	let body6= document.getElementById('transaction-body');

	body1.style.display ='block';
	body2.style.display ='none';
	body3.style.display ='none';
	body4.style.display ='none';
	body5.style.display ='none';
	body6.style.display ='none';
}

function setPrice(){
	let body1= document.getElementById('add-category');
	let body2= document.getElementById('set-price');
	let body3= document.getElementById('history');
	let body4= document.getElementById('order-body');
	let body5= document.getElementById('product-monitoring');
	let body6= document.getElementById('transaction-body');

	body1.style.display ='none';
	body2.style.display ='block';
	body3.style.display ='none';
	body4.style.display ='none';
	body5.style.display ='none';
	body6.style.display ='none';
}

function priceHistory(){
	let body1= document.getElementById('add-category');
	let body2= document.getElementById('set-price');
	let body3= document.getElementById('history');
	let body4= document.getElementById('order-body');
	let body5= document.getElementById('product-monitoring');
	let body6= document.getElementById('transaction-body');

	body1.style.display ='none';
	body2.style.display ='none';
	body3.style.display ='block';
	body4.style.display ='none';
	body5.style.display ='none';
	body6.style.display ='none';

	var myRequest = new XMLHttpRequest();

	var url = 'adminprocess.php';

	let log = 'log';

	var formData = "log="+log;
	
	myRequest.open('POST', url ,true);
	myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

	myRequest.onload = function(){
		var response= this.responseText;
		let div = document.getElementById('whitepaper');
		div.innerHTML = response;
	}
	myRequest.send(formData);				
}

function showOrders(){
	let body1= document.getElementById('add-category');
	let body2= document.getElementById('set-price');
	let body3= document.getElementById('history');
	let body4= document.getElementById('order-body');
	let body5= document.getElementById('product-monitoring');
	let body6= document.getElementById('transaction-body');

	body1.style.display ='none';
	body2.style.display ='none';
	body3.style.display ='none';
	body4.style.display ='block';
	body5.style.display ='none';
	body6.style.display ='none';	

	var myRequest = new XMLHttpRequest();

	var url = 'transactionprocess.php';

	var formData = "showNewOrders='hello'";
	
	myRequest.open('POST', url ,true);
	myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

	myRequest.onload = function(){
		var response= this.responseText;
		console.log(response);
		document.getElementById('order-body-content').innerHTML=response;
	}
	myRequest.send(formData);
}

function showApproveProduct(){
	let body1= document.getElementById('add-category');
	let body2= document.getElementById('set-price');
	let body3= document.getElementById('history');
	let body4= document.getElementById('order-body');
	let body5= document.getElementById('product-monitoring');
	let body6= document.getElementById('transaction-body');

	body1.style.display ='none';
	body2.style.display ='none';
	body3.style.display ='none';
	body4.style.display ='none';
	body5.style.display ='block';
	body6.style.display ='none';

	var myRequest = new XMLHttpRequest();

	var url = 'adminprocess.php';

	var formData = "showApprove='hello'";
	
	myRequest.open('POST', url ,true);
	myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

	myRequest.onload = function(){
		var response= this.responseText;
		document.getElementById('product-monitoring-content').innerHTML=response;
	}
	myRequest.send(formData);
}

function showTransaction(){
	let body1= document.getElementById('add-category');
	let body2= document.getElementById('set-price');
	let body3= document.getElementById('history');
	let body4= document.getElementById('order-body');
	let body5= document.getElementById('product-monitoring');
	let body6= document.getElementById('transaction-body');

	body1.style.display ='none';
	body2.style.display ='none';
	body3.style.display ='none';
	body4.style.display ='none';
	body5.style.display ='none';
	body6.style.display ='block';

	var myRequest = new XMLHttpRequest();

	var url = 'transactionprocess.php';

	var formData = "showApproveOrders='hello'";
	
	myRequest.open('POST', url ,true);
	myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

	myRequest.onload = function(){
		var response= this.responseText;
		console.log(response);
		document.getElementById('transaction-body-content').innerHTML=response;
	}
	myRequest.send(formData);
}

function addCategoryAjax(){
	let form = document.getElementById('add-category-form');

	form.addEventListener('submit',add);

	function add(e){
		e.preventDefault();

		var myRequest = new XMLHttpRequest();

		var url = 'adminprocess.php';

		let add = document.getElementById('category-name').value;

		var formData = "addCat="+add;
		
		myRequest.open('POST', url ,true);
		myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

		myRequest.onload = function(){
			var response= this.responseText;
			if(response=='success'){
				alert(add+' Added!');
				form.reset();
			}else if(response=='Already exist'){
				document.getElementById('error-message2').innerHTML=add+ ' Already exist';
				form.reset();
			}
		}
		myRequest.send(formData);		
	}
}

function showCat(clicked){
	let id = clicked.getAttribute('value');
	let thid = 'cat-'+id;
	let th = document.getElementById(thid);
	th.innerHTML='<font style="color:green">Showing...</font>';


	var myRequest = new XMLHttpRequest();
	var url = 'adminprocess.php';

	var formData = "showcat="+id;
	
	myRequest.open('POST', url ,true);
	myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

	myRequest.onload = function(){
		var response= this.responseText;
			
		console.log(response);
	}
	myRequest.send(formData);	
}

function hideCat(clicked){
	let id = clicked.getAttribute('value');
	let thid = 'cat-'+id;
	let th = document.getElementById(thid);
	th.innerHTML='<font style="color:red">Hiding...</font>';

	var myRequest = new XMLHttpRequest();
	var url = 'adminprocess.php';

	var formData = "hidecat="+id;
	
	myRequest.open('POST', url ,true);
	myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

	myRequest.onload = function(){
		var response= this.responseText;
			
		console.log(response);
	}
	myRequest.send(formData);	
}

function updatePrice(clicked){
	addSpinners();

	let id = clicked.getAttribute('value');

	let liid = 'li-'+id;
	let lowid = 'low-'+id;
	let highid = 'high-'+id;
	let previd = 'prev-'+id;

	let li = document.getElementById(liid);
	let low = document.getElementById(lowid).value;
	let high = document.getElementById(highid).value;
	let prev = document.getElementById(previd).value;

	var myRequest = new XMLHttpRequest();
	var url = 'adminprocess.php';

	var formData = "low="+low+"&high="+high+"&prev="+prev+"&pid="+id;
	
	myRequest.open('POST', url ,true);
	myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

	myRequest.onload = function(){
		var response= this.responseText;
		removeSpinners();
		console.log(response);
		if(response=='success'){
			li.style.background='green';
			document.getElementById('error-message3').innerHTML='';
		}else{
			li.style.background='red';
			document.getElementById('error-message3').innerHTML=response;
		}
	}
	myRequest.send(formData);
}

function adminSetting(){
	let form = document.getElementById('update-admin');

	form.addEventListener('submit',update);

	function update(e){
		e.preventDefault();
		addSpinners();

		var myRequest = new XMLHttpRequest();
		var url = 'adminprocess.php';

		let name = document.getElementById('admin-name').value;
		let old = document.getElementById('old-pass').value;
		let newpass = document.getElementById('new-pass').value;
		let confirm = document.getElementById('confirm-pass').value;

		var formData = "changeadmin="+name+"&old="+old+"&new="+newpass+"&confirm="+confirm;
		
		myRequest.open('POST', url ,true);
		myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

		myRequest.onload = function(){
			var response= this.responseText;
			removeSpinners();

			if(response=='success'){
				form.reset();
				alert('admin name and/or password changed');
				window.location.href="adminpanel.php";
			}else{
				document.getElementById('error-message2').innerHTML=response;
			}
		}
		myRequest.send(formData);
	}
}

// Sales

function showFarmTab(){
	let body1 = document.getElementById('farms');
	let body2 = document.getElementById('weekly-report');
	let body3 = document.getElementById('monthly-report');

	body1.style.display = 'block';
	body2.style.display = 'none';
	body3.style.display = 'none';
}

function showDailyTab(){
	let body1 = document.getElementById('farms');
	let body2 = document.getElementById('weekly-report');
	let body3 = document.getElementById('monthly-report');

	body1.style.display = 'none';
	body2.style.display = 'block';
	body3.style.display = 'none';

}

function showMonthlyTab(){
	let body1 = document.getElementById('farms');
	let body2 = document.getElementById('weekly-report');
	let body3 = document.getElementById('monthly-report');

	body1.style.display = 'none';
	body2.style.display = 'none';
	body3.style.display = 'block';
}

function addFarm(){
	let form = document.getElementById('add-farm-form');
	form.addEventListener('submit',add);

	function add(e){
		e.preventDefault();

		var myRequest = new XMLHttpRequest();

		var url = 'adminprocess.php';

		let farm = document.getElementById('farm-name').value;
		let address = document.getElementById('farm-address').value;


		var formData = "farm="+farm+"&address="+address;
		
		myRequest.open('POST', url ,true);
		myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

		myRequest.onload = function(){
			var response= this.responseText;
			if(response=='success'){
				alert(farm+' is added to the database');
				form.reset();
			}else{
				alert(response);
			}
		}
		myRequest.send(formData);		
	}
}

function updateFarm(){
	let form = document.getElementById('update-farm-form');
	form.addEventListener('submit',update);

	function update(e){
		e.preventDefault();
		addSpinners();

		var myRequest = new XMLHttpRequest();

		var url = 'adminprocess.php';

		let id = document.getElementById('farm-id').value;
		let farm = document.getElementById('farm-name').value;
		let address = document.getElementById('farm-address').value;
		let status = document.getElementById('status');

		if(status.checked==true){
			status=1;
		}else{
			status=0;
		}

		var formData = "updatefarm="+farm+"&address="+address+"&fstatus="+status+"&id="+id;
		
		myRequest.open('POST', url ,true);
		myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

		myRequest.onload = function(){
			var response= this.responseText;
			if(response=='success'){
				window.location.href="adminpanel.php";
			}else{
				removeSpinners();
				document.getElementById('error-message2').innerHTML=response;
			}
		}
		myRequest.send(formData);		
	}
}

// Report User

function showreport(){
	
	var modal = document.getElementById('modal4');
	var report = document.getElementById('reportdiv');

	modal.style.display='block';
	report.style.display='block';
}

function hidereport(){
	var modal = document.getElementById('modal4');
	var report = document.getElementById('reportdiv');

	modal.style.display='none';
	report.style.display='none';
}

function reportuser(){
	var form = document.getElementById('reportform');
	form.addEventListener('submit', sendreport);

	function sendreport(e){
		e.preventDefault();

		addSpinners();

		var select = document.getElementById('select-reason').value;
		var reason = document.getElementById('report-reasons').value;
		var userid = document.getElementById('report-userid').value;

		var myRequest = new XMLHttpRequest();
		var url = 'adminprocess.php';
		
		var formData = "select="+select+"&reason="+reason+"&userid="+userid;
		
		myRequest.open('POST', url ,true);
		myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

		myRequest.onload = function(){
			var response= this.responseText;
			if(response){
				alert('Your report is sent for the review, thank you.');
				removeSpinners();
				hidereport();
				form.reset();
			}
		}
		myRequest.send(formData);
		}
}


function checkedreport(clicked){
	var id =clicked.getAttribute('id');
	
	var markid = 'rp-'+id;
	var marked = document.getElementById(markid);
	marked.innerHTML ='<p class="checkreport">Read</p>';

	var myRequest = new XMLHttpRequest();

	var url = 'adminprocess.php';

	var formData = "check="+id;
	
	myRequest.open('POST', url ,true);
	myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

	myRequest.onload = function(){
		var response= this.responseText;
	}
	myRequest.send(formData);
}

function sendAnnounce(){
	let form = document.getElementById('announce-form');
	form.addEventListener('submit',sendthis);

	function sendthis(e){
		e.preventDefault();

		var myRequest = new XMLHttpRequest();

		var url = 'adminprocess.php';

		let title = document.getElementById('announce-title').value;
		let content = document.getElementById('announce-content').value;
		let author = document.getElementById('announce-author').value;

		var formData = "title="+title+"&content="+content+"&author="+author;
		
		myRequest.open('POST', url ,true);
		myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

		myRequest.onload = function(){
			var response= this.responseText;
			form.reset();
			alert('Your announcement is sent to the home page');
		}
		myRequest.send(formData);		
	}
}

// Product

function getPrice(){
	var e = document.getElementById("category");
	var value = e.options[e.selectedIndex].value;
	var text = e.options[e.selectedIndex].text;

	var myRequest = new XMLHttpRequest();

	var url = 'productprocess.php';

	var formData = "select="+value;
	
	myRequest.open('POST', url ,true);
	myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

	myRequest.onload = function(){
		var response= this.responseText;

		let array = response.split('|');

		let low = document.getElementById('low');
		let prev = document.getElementById('prev');
		let high = document.getElementById('high');
		let priceinput = document.getElementById('price');

		low.innerHTML = array[0];
		prev.innerHTML = array[1];
		high.innerHTML = array[2];
		priceinput.value = array[1];
	}
	myRequest.send(formData);		
	
}

function addProductAjax(){
	let form = document.getElementById('add-product-form');
	form.addEventListener('submit',add);

	function add(e){
		e.preventDefault();
		var myRequest = new XMLHttpRequest();

		var url = 'productprocess.php';

		let add = document.getElementById('category').value;
		let name = document.getElementById('name').value;
		let desc = document.getElementById('desc').value;
		let farm = document.getElementById('farm').value;
		let price = document.getElementById('price').value;
		let Alow = document.getElementById('low').innerHTML;
		let Ahigh = document.getElementById('high').innerHTML;

		var formData = "add="+add+"&name="+name+"&desc="+desc+"&farm="+farm+"&price="+price+"&Alow="+Alow+"&Ahigh="+Ahigh;
		
		myRequest.open('POST', url ,true);
		myRequest.setRequestHeader('content-type','application/x-www-form-urlencoded');

		myRequest.onload = function(){
			var response= this.responseText;
			var array = response.split('|');
			removeSpinners();
			if(array[0]=='success'){
				form.reset();
				alert(name + ' is added to the database, please wait for the admin to approved your product');
				window.location.href = 'product.php?id='+array[1];
			}else{
				document.getElementById('error-message2').innerHTML=response;
			}
		}
		myRequest.send(formData);		
	}	
}

function showUpdateProductForm(){
	let form = document.querySelector('.edit-form');
	if(form.style.display=="none"){
		form.style.display="block";
	}else if(form.style.display=="block"){
		form.style.display="none";

		var myRequest = new XMLHttpRequest();
		var url = 'productprocess.php';

		var formData = "unsetUpdate='unset'";
		
		myRequest.open('POST', url ,true);
		myRequest.setRequestHeader('content-type','application/x-www-form-urlencoded');

		myRequest.send(formData);
	}
}

function approveProduct(clicked){
	let id = clicked.getAttribute('value');

	addSpinners();
	document.getElementById('approve-button').innerHTML='Approving...';
	var myRequest = new XMLHttpRequest();
	
	var url = 'productprocess.php';

	var formData = "approveProduct="+id;
	
	myRequest.open('POST', url ,true);
	myRequest.setRequestHeader('content-type','application/x-www-form-urlencoded');

	myRequest.onload = function(){
		var response= this.responseText;
		alert('You approved this product');
		removeSpinners();
	}
	myRequest.send(formData);
}

function removeProduct(clicked){
	let id = clicked.getAttribute('value');

	addSpinners();
	document.getElementById('remove-product').innerHTML='Removing...';
	var myRequest = new XMLHttpRequest();
	
	var url = 'productprocess.php';

	var formData = "removeProduct="+id;
	
	myRequest.open('POST', url ,true);
	myRequest.setRequestHeader('content-type','application/x-www-form-urlencoded');

	myRequest.onload = function(){
		var response= this.responseText;
		removeSpinners();
		alert('You removed this product');
	}
	myRequest.send(formData);
}

function activateProduct(clicked){
	let id = clicked.getAttribute('value');

	addSpinners();
	document.getElementById('activate-button').innerHTML='Adding...';
	var myRequest = new XMLHttpRequest();
	
	var url = 'productprocess.php';

	var formData = "activateProduct="+id;
	
	myRequest.open('POST', url ,true);
	myRequest.setRequestHeader('content-type','application/x-www-form-urlencoded');

	myRequest.onload = function(){
		var response= this.responseText;
		removeSpinners();
		alert('This product is now available');
	}
	myRequest.send(formData);
}

function deactivateProduct(clicked){
	let id = clicked.getAttribute('value');

	addSpinners();
	document.getElementById('deactivate-button').innerHTML='Removing...';
	var myRequest = new XMLHttpRequest();
	
	var url = 'productprocess.php';

	var formData = "deactivateProduct="+id;
	
	myRequest.open('POST', url ,true);
	myRequest.setRequestHeader('content-type','application/x-www-form-urlencoded');

	myRequest.onload = function(){
		var response= this.responseText;
		removeSpinners();
		alert('This product is now not available');
	}
	myRequest.send(formData);
}

// Cart

function showCartPanel(){
	let panel = document.getElementById('cart-panel');

	panel.style.display='block';

	var myRequest = new XMLHttpRequest();
	
	var url = 'productprocess.php';

	var formData = "showcart='Show'";
	
	myRequest.open('POST', url ,true);
	myRequest.setRequestHeader('content-type','application/x-www-form-urlencoded');

	myRequest.onload = function(){
		var response= this.responseText;
		let cart = document.getElementById('shopping-cart-content');
		cart.innerHTML = response;
		gotoBottomCart();
	}
	myRequest.send(formData);
}

function gotoBottomCart(){
		var messageBody = document.getElementById('cart-panel');
		messageBody.scrollTop = messageBody.scrollHeight - messageBody.clientHeight;
}

function hideCartPanel(){
	let panel = document.getElementById('cart-panel');
	
	panel.style.display='none';
}

function addThistoCart(click){
	let id = click.getAttribute('value');
	addSpinners();
	var myRequest = new XMLHttpRequest();

	var url = 'productprocess.php';

	var formData = "cart="+id;
	
	myRequest.open('POST', url ,true);
	myRequest.setRequestHeader('content-type','application/x-www-form-urlencoded');
	myRequest.onload = function(){
		removeSpinners();
		var response= this.responseText;
		showCartPanel();
	}
	myRequest.send(formData);
}

function addWeight(click){ 
	let key = click.getAttribute('value');
	let id = click.getAttribute('id');

	let inputid = 'input-'+id;
	let input = document.getElementById(inputid);
	let weight = input.value;

	if(weight<=0 || weight==''){

	}else{

	let priceid = 'price-'+id;
	let price = document.getElementById(priceid).value;

	let total = weight * price;

	let ftotal = document.getElementById('total').innerHTML;
	let ftotalint = parseFloat(ftotal.replace(/,/g, ''));
	let ftotalval = ftotalint + total;

	let foutput = document.getElementById('total');
	foutput.innerHTML=addCommas(ftotalval.toFixed(2));

	var myRequest = new XMLHttpRequest();

	var url = 'productprocess.php';

	var formData = "weight="+weight+"&price="+price+"&listid="+id+"&total="+ftotalval+"&key="+key;
	
	myRequest.open('POST', url ,true);
	myRequest.setRequestHeader('content-type','application/x-www-form-urlencoded');
	myRequest.onload = function(){
		var response= this.responseText;
		document.getElementById('top-total').innerHTML='₱'+addCommas(ftotalval.toFixed(2));
		showCartPanel();
	}
	myRequest.send(formData);
	}
}

function deleteCart(){
	var myRequest = new XMLHttpRequest();

	var url = 'productprocess.php';

	var formData = "delete='delete'";
	
	myRequest.open('POST', url ,true);
	myRequest.setRequestHeader('content-type','application/x-www-form-urlencoded');
	myRequest.onload = function(){
		response = this.responseText;
		let cart = document.getElementById('shopping-cart-content');
		cart.innerHTML = '<p>Shopping Cart is empty...</p>';
		document.getElementById('top-total').innerHTML='';
	}
	myRequest.send(formData);
}

function removeList(click){
	let id = click.getAttribute('value');
	let listid = 'list-'+id;
	let list = document.getElementById(listid);
	list.style.display='none';
	
	var myRequest = new XMLHttpRequest();

	var url = 'productprocess.php';

	var formData = "remove="+id;
	
	myRequest.open('POST', url ,true);
	myRequest.setRequestHeader('content-type','application/x-www-form-urlencoded');

	myRequest.onload = function(){
		var response= this.responseText;
	}
	myRequest.send(formData);
}

function undoList(clicked){
	let id = clicked.getAttribute('value');
	let listid = 'flist-'+id;
	let list = document.getElementById(listid);
	list.style.display='none';

	let unitpriceid = 'flist-unit-price-'+id;
	let unitprice = document.getElementById(unitpriceid).innerHTML;
	let unitpriceint = parseFloat(unitprice.replace(/,/g, ''));

	let ftotal = document.getElementById('total').innerHTML;
	let ftotalint = parseFloat(ftotal.replace(/,/g, ''));
	let ftotalval = ftotalint - unitpriceint;

	let foutput = document.getElementById('total');
	foutput.innerHTML=addCommas(ftotalval.toFixed(2));
	document.getElementById('top-total').innerHTML='₱'+addCommas(ftotalval.toFixed(2));

	var myRequest = new XMLHttpRequest();
	var url = 'productprocess.php';

	var formData = "undo="+id+"&undoTotal="+ftotalval;
	
	myRequest.open('POST', url ,true);
	myRequest.setRequestHeader('content-type','application/x-www-form-urlencoded');

	myRequest.onload = function(){
		var response= this.responseText;
	}
	myRequest.send(formData);
}

function checkoutCart(clicked){
	let login = clicked.getAttribute('value');
	let ftotal = document.getElementById('total').innerHTML;
	let ftotalint = parseFloat(ftotal.replace(/,/g, ''));
	if(ftotalint<500){
		let errorString = 'Orders should be a minimum of ₱500.00 worth of purchase.';
		document.getElementById('error-message5').innerHTML=errorString;
	}else{
		if(login==1){

			var myRequest = new XMLHttpRequest();
			var url = 'productprocess.php';

			var formData = "checkout='oke-oke-okay'";
			
			myRequest.open('POST', url ,true);
			myRequest.setRequestHeader('content-type','application/x-www-form-urlencoded');

			myRequest.onload = function(){
				var response= this.responseText;
				window.location.href='checkout.php';
			}
			myRequest.send(formData);
		
		}else{
			showLogin();
		}
	}
}

// Order

function placeOrder(clicked){
	let final = clicked.getAttribute('final').trim();
	let form = document.getElementById('place-order-form');
	let address = document.getElementById('address').value;
	let email = document.getElementById('email').value;
	let phone = document.getElementById('phone').value;


	form.addEventListener('submit',(e)=>{
		e.preventDefault();
		var myRequest = new XMLHttpRequest();
		var url = 'transactionprocess.php';

		var formData = "placeOrder="+final+"&address="+address+"&email="+email+"&phone="+phone;
		
		myRequest.open('POST', url ,true);
		myRequest.setRequestHeader('content-type','application/x-www-form-urlencoded');

		myRequest.onload = function(){
			var response= this.responseText;
			if(response=='success'){
				window.location.href='ordersuccess.php';
			}else{
				document.getElementById('error-message5').innerHTML=response;
			}
		}
		myRequest.send(formData);
	});
}

function approveOrder(clicked){
	let id = clicked.getAttribute('value');
	let number = clicked.getAttribute('number');
	let receiver = clicked.getAttribute('receiver');
	let buttonid = 'order-approve-'+id;
	let button = document.getElementById(buttonid);
	let button2id = 'order-reject-'+id;
	let button2 = document.getElementById(button2id);

	addSpinners();

	button.innerHTML = 'Approving...';
	button2.style.display = 'none';

	var myRequest = new XMLHttpRequest();
	var url = 'transactionprocess.php';

	var formData = "approve="+id+"&approveNum="+number+"&approveRec="+receiver;
	
	myRequest.open('POST', url ,true);
	myRequest.setRequestHeader('content-type','application/x-www-form-urlencoded');

	myRequest.onload = function(){
		var response= this.responseText;
		removeSpinners();
	}
	myRequest.send(formData);
}

function rejectOrder(clicked){
	let id = clicked.getAttribute('value');
	let number = clicked.getAttribute('number');
	let receiver = clicked.getAttribute('receiver');
	let buttonid = 'order-reject-'+id;
	let button = document.getElementById(buttonid);
	let button2id = 'order-approve-'+id;
	let button2 = document.getElementById(button2id);

	button.innerHTML = 'Rejecting...';
	button2.style.display = 'none';

	var myRequest = new XMLHttpRequest();
	var url = 'transactionprocess.php';

	var formData = "reject="+id+"&rejectNum="+number+"&rejectRec="+receiver;
	
	myRequest.open('POST', url ,true);
	myRequest.setRequestHeader('content-type','application/x-www-form-urlencoded');

	myRequest.onload = function(){
		var response= this.responseText;
		removeSpinners();
	}
	myRequest.send(formData);
}

function cancelOrder(clicked){
	let id = clicked.getAttribute('value');
	let number = clicked.getAttribute('number');
	let receiver = clicked.getAttribute('receiver');
	let buttonid = 'order-complete-'+id;
	let button = document.getElementById(buttonid);
	let button2id = 'order-cancel-'+id;
	let button2 = document.getElementById(button2id);

	button.style.display = 'none';
	button2.innerHTML = 'Cancelling...';

	var myRequest = new XMLHttpRequest();
	var url = 'transactionprocess.php';

	var formData = "cancel="+id+"&cancelNum="+number+"&cancelRec="+receiver;
	
	myRequest.open('POST', url ,true);
	myRequest.setRequestHeader('content-type','application/x-www-form-urlencoded');

	myRequest.onload = function(){
		var response= this.responseText;
		removeSpinners();
	}
	myRequest.send(formData);
}

function completeOrder(clicked){
	let id = clicked.getAttribute('value');
	let number = clicked.getAttribute('number');
	let receiver = clicked.getAttribute('receiver');
	let buttonid = 'order-complete-'+id;
	let button = document.getElementById(buttonid);
	let button2id = 'order-cancel-'+id;
	let button2 = document.getElementById(button2id);

	button.innerHTML = 'Saving...';
	button2.style.display = 'none';

	var myRequest = new XMLHttpRequest();
	var url = 'transactionprocess.php';

	var formData = "complete="+id+"&completeNum="+number+"&completeRec="+receiver;
	
	myRequest.open('POST', url ,true);
	myRequest.setRequestHeader('content-type','application/x-www-form-urlencoded');

	myRequest.onload = function(){
		var response= this.responseText;
		removeSpinners();
	}
	myRequest.send(formData);
}

// Review

function star1hover(){
	let star1 = document.getElementById('star1');
	let star2 = document.getElementById('star2');
	let star3 = document.getElementById('star3');
	let star4 = document.getElementById('star4');
	let star5 = document.getElementById('star5');

	star1.style.color='gold';
	star2.style.color='black';
	star3.style.color='black';
	star4.style.color='black';
	star5.style.color='black';
}

function star2hover(){
	let star1 = document.getElementById('star1');
	let star2 = document.getElementById('star2');
	let star3 = document.getElementById('star3');
	let star4 = document.getElementById('star4');
	let star5 = document.getElementById('star5');

	star1.style.color='gold';
	star2.style.color='gold';
	star3.style.color='black';
	star4.style.color='black';
	star5.style.color='black';
}

function star3hover(){
	let star1 = document.getElementById('star1');
	let star2 = document.getElementById('star2');
	let star3 = document.getElementById('star3');
	let star4 = document.getElementById('star4');
	let star5 = document.getElementById('star5');

	star1.style.color='gold';
	star2.style.color='gold';
	star3.style.color='gold';
	star4.style.color='black';
	star5.style.color='black';
}

function star4hover(){
	let star1 = document.getElementById('star1');
	let star2 = document.getElementById('star2');
	let star3 = document.getElementById('star3');
	let star4 = document.getElementById('star4');
	let star5 = document.getElementById('star5');

	star1.style.color='gold';
	star2.style.color='gold';
	star3.style.color='gold';
	star4.style.color='gold';
	star5.style.color='black';
}

function star5hover(){
	let star1 = document.getElementById('star1');
	let star2 = document.getElementById('star2');
	let star3 = document.getElementById('star3');
	let star4 = document.getElementById('star4');
	let star5 = document.getElementById('star5');

	star1.style.color='gold';
	star2.style.color='gold';
	star3.style.color='gold';
	star4.style.color='gold';
	star5.style.color='gold';
}

function starLeave(){
	let star1 = document.getElementById('star1');
	let star2 = document.getElementById('star2');
	let star3 = document.getElementById('star3');
	let star4 = document.getElementById('star4');
	let star5 = document.getElementById('star5');

	star1.style.color='#222';
	star2.style.color='#222';
	star3.style.color='#222';
	star4.style.color='#222';
	star5.style.color='#222';	
}

function clickstar(clicked){
	let userid = clicked.getAttribute('value');
	let productid = clicked.getAttribute('name');
	let star = clicked.getAttribute('rating');

	addSpinners();
	var myRequest = new XMLHttpRequest();

	var url = 'productprocess.php';

	var formData = "star="+userid+"&rateproduct="+productid+"&rating="+star;
	
	myRequest.open('POST', url ,true);
	myRequest.setRequestHeader('content-type','application/x-www-form-urlencoded');

	myRequest.onload = function(){
		var response= this.responseText;
		removeSpinners();
		document.getElementById('rate-this').innerHTML="Thank you for rating!";
	}
	myRequest.send(formData);
}

function updatestar(clicked){
	let userid = clicked.getAttribute('value');
	let productid = clicked.getAttribute('name');
	let star = clicked.getAttribute('rating');
	
	addSpinners();
	var myRequest = new XMLHttpRequest();

	var url = 'productprocess.php';

	var formData = "updatestar="+userid+"&updaterateproduct="+productid+"&updaterating="+star;
	
	myRequest.open('POST', url ,true);
	myRequest.setRequestHeader('content-type','application/x-www-form-urlencoded');

	myRequest.onload = function(){
		var response= this.responseText;
		removeSpinners();
		console.log(response);
		document.getElementById('rate-this').innerHTML="Thank you for rating!";
	}
	myRequest.send(formData);	
}

function ratedThis(rating){
	if(rating==1){
		let star1 = document.getElementById('star1');
		let star2 = document.getElementById('star2');
		let star3 = document.getElementById('star3');
		let star4 = document.getElementById('star4');
		let star5 = document.getElementById('star5');

		star1.style.color='gold';
		star2.style.color='#222';
		star3.style.color='#222';
		star4.style.color='#222';
		star5.style.color='#222';
	}else if(rating==2){
		let star1 = document.getElementById('star1');
		let star2 = document.getElementById('star2');
		let star3 = document.getElementById('star3');
		let star4 = document.getElementById('star4');
		let star5 = document.getElementById('star5');

		star1.style.color='gold';
		star2.style.color='gold';
		star3.style.color='#222';
		star4.style.color='#222';
		star5.style.color='#222';
	}else if(rating==3){
		let star1 = document.getElementById('star1');
		let star2 = document.getElementById('star2');
		let star3 = document.getElementById('star3');
		let star4 = document.getElementById('star4');
		let star5 = document.getElementById('star5');

		star1.style.color='gold';
		star2.style.color='gold';
		star3.style.color='gold';
		star4.style.color='#222';
		star5.style.color='#222';
	}else if(rating==4){
		let star1 = document.getElementById('star1');
		let star2 = document.getElementById('star2');
		let star3 = document.getElementById('star3');
		let star4 = document.getElementById('star4');
		let star5 = document.getElementById('star5');

		star1.style.color='gold';
		star2.style.color='gold';
		star3.style.color='gold';
		star4.style.color='gold';
		star5.style.color='#222';
	}else if(rating==5){
		let star1 = document.getElementById('star1');
		let star2 = document.getElementById('star2');
		let star3 = document.getElementById('star3');
		let star4 = document.getElementById('star4');
		let star5 = document.getElementById('star5');

		star1.style.color='gold';
		star2.style.color='gold';
		star3.style.color='gold';
		star4.style.color='gold';
		star5.style.color='gold';
	}
}

function sendReview(){
	let form = document.getElementById('review-form');
	form.addEventListener('submit',send);

	function send(e){
		e.preventDefault();

		let review = document.getElementById('review-text').value;
		let reviewproduct = document.getElementById('review-product').value;
		let reviewuser = document.getElementById('review-user').value;

		addSpinners();

		var myRequest = new XMLHttpRequest();

		var url = 'productprocess.php';

		let formData = 'review='+review+'&reviewproduct='+reviewproduct+'&reviewuser='+reviewuser;
		
		myRequest.open('POST', url ,true);
		myRequest.setRequestHeader('content-type','application/x-www-form-urlencoded');

		myRequest.onload = function(){
			var response= this.responseText;
			location.reload();
		}
		myRequest.send(formData);
	}
}

function deleteReview(clicked){
	let id = clicked.getAttribute('value');

	var myRequest = new XMLHttpRequest();
	var url = 'productprocess.php';
	
	addSpinners();

	var formData = "deleteReview="+id;
	
	myRequest.open('POST', url ,true);
	myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

	myRequest.onload = function(){
		var response= this.responseText;
		location.reload();
	}
	myRequest.send(formData);
}

function likeReview(clicked){
	let id = clicked.getAttribute('value');
	let helpfulid = 'helpful-'+id;
	let helpful = document.getElementById(helpfulid);
	helpful.innerHTML = 'Thanks for your feedback!';

	var myRequest = new XMLHttpRequest();
	var url = 'productprocess.php';
	
	addSpinners();

	var formData = "likeReview="+id;
	
	myRequest.open('POST', url ,true);
	myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

	myRequest.onload = function(){
		var response= this.responseText;
		removeSpinners();
	}
	myRequest.send(formData);
}

function undoLike(clicked){
	let id = clicked.getAttribute('value');
	let helpfulid = 'helpful-'+id;
	let helpful = document.getElementById(helpfulid);
	helpful.innerHTML = 'Your helpful vote was removed';

	var myRequest = new XMLHttpRequest();
	var url = 'productprocess.php';
	
	addSpinners();

	var formData = "undoLike="+id;
	
	myRequest.open('POST', url ,true);
	myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

	myRequest.onload = function(){
		var response= this.responseText;
		removeSpinners();
	}
	myRequest.send(formData);
}

// Photo

function removePhoto(){
	let form = document.getElementById('profile-pic-form');

	form.addEventListener('submit',remove);

	function remove(e){
		e.preventDefault();

		addSpinners();

		var myRequest = new XMLHttpRequest();

		var url = 'photoprocess.php';

		let remove = 'remove';

		var formData = "remove="+remove;
		
		myRequest.open('POST', url ,true);
		myRequest.setRequestHeader('content-type','application/x-www-form-urlencoded');

		myRequest.onload = function(){
			var response= this.responseText;
			removeSpinners();
			let profile = "profile.php?id="+response;
			let profile2 = profile.split('<');			
			window.location.replace(profile2[0]);
		}
		myRequest.send(formData);
	}		
}

function submitPhoto(){
	let form = document.getElementById('profile-pic-form');

	form.addEventListener('submit',insert);

	function insert(e){
		e.preventDefault();
		
		var myRequest = new XMLHttpRequest();

		var url = 'photoprocess.php';

		var upload = document.getElementById('img');
		var property = upload.files[0];
		var image_name = property.name;
		var image_extension = image_name.split('.').pop().toLowerCase();

		console.log(property);
		
		var formData = 'img='+property;

		myRequest.upload.addEventListener("progress", progressHandler, false);
		myRequest.addEventListener("load", completeHandler, false);
		myRequest.addEventListener("error", errorHandler, false);
		myRequest.addEventListener("abort", abortHandler, false);
		myRequest.open('POST', url ,true);
		myRequest.setRequestHeader('content-type','application/x-www-form-urlencoded');

		myRequest.onload = function(){
			var response= this.responseText;
			console.log(response);
			document.getElementById('error-message2').innerHTML=response;
			removeSpinners();			
			//window.location.replace("profile.php?name="+response);
		}
		myRequest.send(formData);		
	}
}

function progressHandler(event){
	document.getElementById("loaded_n_total").innerHTML = "Uploaded "+event.loaded+" bytes of "+event.total;
	var percent = (event.loaded / event.total) * 100;
	document.getElementById("progressBar").value = Math.round(percent);
	document.getElementById("status").innerHTML = Math.round(percent)+"% uploaded... please wait";
}
function completeHandler(event){
	document.getElementById("status").innerHTML = event.target.responseText;
	document.getElementById("progressBar").value = 100;
}
function errorHandler(event){
	document.getElementById("status").innerHTML = "Upload Failed";
}
function abortHandler(event){
	document.getElementById("status").innerHTML = "Upload Aborted";
}

//Redirect Page
function redirectPage(){
	document.getElementById('redirectlink').addEventListener('click',redirect);

	function redirect(){
		window.location.replace("index.php");
	}
	setTimeout(function () {
		   
		window.location.replace("index.php");
	
	}, 3000);
}

function goBackPage(){
	document.getElementById('redirectlink').addEventListener('click',historyback);

	function historyback(){
    	window.history.back();
	}
	setTimeout(function () {
		   
    	window.history.back();
	
	}, 3000);
}

function redirectOrder(){
	document.getElementById('redirectlink').addEventListener('click',redirect);

	function redirect(){
		window.location.replace("ordertracking.php");
	}
	setTimeout(function () {
		   
		window.location.replace("ordertracking.php");
	
	}, 3000);
}

function redirectProfile(){
	
	var myRequest = new XMLHttpRequest();
	var url = 'edituserprocess.php';
	var session = "$_SESSION['id']";
	
	var formData = "session="+session;
	
	myRequest.open('POST', url ,true);
	myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

	myRequest.onload = function(){
		var response= JSON.parse(this.responseText);
		console.warn(response);
		if(response){
			document.getElementById('redirectlink').addEventListener('click',historyback);

					
			function historyback(){
				window.location.replace("profile.php?id="+response);
			}
			setTimeout(function () {
				   
				window.location.replace("profile.php?id="+response);
			
			}, 3000);

		}
	}
	myRequest.send(formData);
}

function activepage(thispage){
	document.querySelector(thispage).style.color='var(--contrast)';
}

// Spinners
function pageReload(){
	document.querySelector('.main-container').style.opacity='0.7';
	document.querySelector('body').classList.add('spinner');

	setTimeout(()=>{
		document.querySelector('body').classList.remove('spinner');
		document.querySelector('.main-container').style.opacity='1';
		
	}, 2000)
}

function addSpinners(){
	document.querySelector('.main-container').style.opacity='0.7';
	document.querySelector('body').classList.add('spinner');
}

function removeSpinners(){
	document.querySelector('body').classList.remove('spinner');
	document.querySelector('.main-container').style.opacity='1';
}

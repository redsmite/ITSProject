//sideMenu
function openSlideMenu(){
	document.getElementById('side-menu').style.width='90px';
	document.getElementById('side-menu').marginleft='90px';
	document.getElementById('sidebarmodal').style.display='block';
}

function closeSlideMenu(){
	document.getElementById('side-menu').style.width='0';
	document.getElementById('side-menu').marginleft='0';
	document.getElementById('sidebarmodal').style.display='none';
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
	document.getElementById('category-slide').style.width='20%';
	document.getElementById('category-slide').marginleft='20%';
	document.getElementById('category-modal').style.display='block';
}

function hideCategory(){
	document.getElementById('category-slide').style.display='none';
	document.getElementById('category-slide').style.width='0';
	document.getElementById('category-slide').marginleft='0';
	document.getElementById('category-modal').style.display='none';
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
					output+='<ul class="drop-ul"><li><a href="profile.php?name='+itemArray[0]+'"><div class="drop-tn"><img src="'+itemArray[1]+'"></div><p>'+itemArray[0]+'</a></p><small>Joined: '+itemArray[2]+'</small><li></ul>';
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
			console.warn(response);
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
				if(name=='TuturuBot'){
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
		var hellobot = '♪ tu tu ru Mayushi-desu!';
		
		
		var formData = "hellobot="+hellobot;
		
		myRequest.open('POST', url ,true);
		myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

		myRequest.onload = function(){
			var response= this.responseText;
			if(response){

				form.reset();
				var tuturump3 = document.getElementById("myAudio"); 
				tuturump3.play(); 
	
				document.querySelector('.right-inbox').innerHTML=response;
				var messageBody = document.querySelector(".right-inbox");
				messageBody.scrollTop = messageBody.scrollHeight - messageBody.clientHeight;
			}
		}
		myRequest.send(formData);
	}else if(message=='!music'){
		var myRequest = new XMLHttpRequest();
		var url = 'inboxprocess.php';

		//form data variables
		var song = 'bgmusic';
		
		
		var formData = "song="+song;
		
		myRequest.open('POST', url ,true);
		myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

		myRequest.onload = function(){
			var response= this.responseText;

			if(response){

				form.reset();
				var bgmusic = document.getElementById("mySong"); 
				bgmusic.play(); 
				document.querySelector('.right-inbox').innerHTML=response;
				var messageBody = document.querySelector(".right-inbox");
				messageBody.scrollTop = messageBody.scrollHeight - messageBody.clientHeight;
			}
		}
		myRequest.send(formData);
	}else if(message=='!stop'){
			form.reset();
			var bgmusic = document.getElementById("mySong"); 
			bgmusic.pause();
			bgmusic.currentTime = 0;
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
					window.location.replace("index.php");;
				}, 3000);
			}
		}
		myRequest.send(formData);
	}else if(message=='!chat'){
		var messages = [
		'We should work towards world peace. Like giving everyone in the world an Upa cushion...',
		'Finally... Mayushii was... useful.',
		'Mayushii here! I\'ve become more wonderful! Kyaha',
		'Tuturuuuu',
		'Mayushii\'s in a good mood~♪',
		'Hrm. The next job\'s location.. wha, today\'s an off day... I see...',
		'Mayushii belongs to everyone, so don\'t touch her too much, okay?',
		'Even if this is boring, please don\'t hate Mayushii!',
		'I worked pretty hard right? praise me! Praise me!',
		'What would you like me to do next?',
		'I\'ll be resting for a bit.',
		'Let\'s be cheerful and go!',
		'I\'ll try my best again today!',
		'I-I can still keep going!',
		'Today feels like a good day.',
		'Yes! I\'ll work even harder',
		'I\'ll send a smile to everyone\'s hearts. ♪',
		'Living should mean no do-overs. This is for the best.',
		'Pa... pa... pa... What sound does a panda make...?',
		'I like this clothes because they\'re easy to move.',
		'*pant, pant* ...I\'m so tired... B-But it\'s not like I\'m not athletic. I\'m just suited to short distances... probably.',
		'D-Do you mind staying with me for a while?',
		'Don\'t you think that the pink and yellow of the flowers fit me too well?',
		'I\'m a sweet angel after all, so I\'m sure I can easily get to your heart~ ♪',
		'Putting chocolate on dry fruit seems to give it an adult taste. Mayushii likes it quite a bit.',
		'I\'m getting tired of acting like a good girl~ ...Wh-what, isn\'t Mayushii just being honest?',
		'That’s enough work. I’m ready for a snack and a break!',
		'What\'ll I do if I get too popular!?',
		'I\'m just doing what I always do~ ♪',
		'How would I describe myself? I\'m pretty honest and innocent, right?',
		'It\'s weird. When I see your lonely expression, why do I feel sad too?',
		'On days when there\'s a cold wind, I want to walk home together with someone~',
		'Did something happen?',
		'I have pretty good luck you know',
		'Mimosa punica, or sensitive plant, will actually fold up its leaves when it is touched. Crazy isn\'t it?',
		'Has my cheer beam reached you all? Bzzt☆" ...Never mind, please look away.',
		'How are you feeling? I\'m in perfect shape.',
		'Do you want to go out somewhere today?',
		'You\'re under arrest! Just kidding... Did that fit me?',
		'Whenever you\'re troubled by something, do some exercise. Surprisingly, it calms you down.',
		'Spring flowers have such beautiful colors; they\'re very soothing.',
		'I\'m not a human being, so I can\'t show you my true form... Just kidding.',
		'Occasionally it would be nice to try a different hairstyle sometime... Does it suit me well?',
		'Just watching your appearance makes me happy. It is strange.',
		'I don’t know until I try. I want to challenge myself without fear.',
		'I am learning about the history of fashion as of now... ...because I don’t know much',
		'I think we should use our past experiences to make the most out of this year',
		'Do you like the ocean?',
		'Do you need something?',
		'How are you feeling? I\'m of course feeling perfectly fine.',
		'Be sure to take care of your health.',
		'I like going to the library. The quiet and peaceful atmosphere makes me feel refreshed.',
		'White rice is amazing... I wonder why it is so white...',
		'This may come as a surprise... but I\'m pretty good at origami.',
		'Surprisingly, February is the chilliest month of the year...',
		'Leave it to me! You get away from here. Hurry!" ...Just kidding. Did that fit me?',
		'The role of scarry jack-o\'-lanterns is to ward off mischevious spirits who come to play pranks.',
		'If there\'s something I can do to help, just say the word!',
		'I want to live a life free of regrets.',
		'If you want to grant your own wish, then you should clear your own path to it.',
		'The "present" is a leaf floating on top of the river. It moves along with the flow from past to future.',
		'Everyone gets help from someone else at some point in their lives. So someday, you should help someone too.',
		'Theories are nothing more than words. Accept what you\'ve seen.',
		'Fortune telling is one of my hobbies. Want me to read your fortune?',
		'Today\'s fortune is... Hmm... Got it!',
		'Things like being under an umbrella together, it\'s romantic, right?',
		'God has a big heart, so there\'s no need to be so nervous.',
		'Don\'t I look fashionable today?',
		'Ah! Th-The smell of ramen! ...I\'m hungry...',
		'Cotton candy is so soft and fluffy~ I want to try making it myself next time ♪ All you have to do is spin it around and around, right~??',
		'Even without an umbrella, if you have a raincoat, you won\'t get wet. ♪ The person who invented the raincoat is amazing.',
		'Ramen will save the world! ...I think!',
		'I\'ll be sure to support you~',
		'Being active every day seems to put me in a good mood. Let\'s make tomorrow just as good!',
		'I get excited when I get to wear such a really beautiful costume.',
		'I\'m still feeling a little shy, but I\'m okay now!',
		'Welcome home, Master! Hee, hee. Do I make a good maid?',
		'Do I really have to say, "I want to capture your heart"?!',
		'Unifying your heart with another is such a wonderful thing.',
		'T-Time travel? It\'s not for real, you know...or is it?',
		'I\'m going to do my best all year, starting from day one. It\'s important to start off on the right foot!',
		'I hope we\'ll be in each other\'s lives for years and years to come.',
		'I am your guide to the world of dreams. *Giggle* It\'s fun to be a clown.',
		'Everyone has their own way of seeing the world... I\'d be so happy if our points of view were similar.',
		'I could never handle a sea cucumber, but I don\'t mind touching penguins and dolphins.',
		'Argh, what should I do? I want to ask them, but I\'m afraid I\'ll get rejected...',
		'I guess I\'m the happiest when I\'m able to do things I want at my own pace.',
		'The English word "angel" also means "herald." How neat is that?',
		'Oh, lost lambs, a pure and cute angel shall take thee to heaven... Ugh... Do I really have to say this?',
		'Can I come see you later?',
		'I heard Germany is famous for beer! Wait, I can\'t drink yet... Maybe I\'ll have some wurst and pretzels then!',
		'Huh? I-I guess I am pretty boring after all.',
		'My super lucky powers will give you good luck!',
		'You want a hug? Sure come over',
		'The future remains uncertain, but I believe it\'ll all work out. Let\'s bide our time and carry on!',
		'The sky and the sea are both so vast. It really reminds me how small I am in the grand scheme of things.',
		'People can\'t live in solitude. We need to support one another.',
		'When you just can\'t think of an answer to your problems, it\'s best to clear your mind and go to bed.',
		'My colleagues are always so tough on me. *Sniffle*',
		'If only I had some special skill…',
		'Sunflower is the only flower with flower in its name!'

		]
		var rand = Math.floor((Math.random() * messages.length-1) + 1);

		var myRequest = new XMLHttpRequest();
		var url = 'inboxprocess.php';

		//form data variables
		var chat = messages[rand];
		
		
		var formData = "chat="+chat;
		
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

	setPrice();
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

	showFarmTab();
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
				alert('Message has been sent to all users');
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

	body1.style.display ='block';
	body2.style.display ='none';
	body3.style.display ='none';	
}

function setPrice(){
	let body1= document.getElementById('add-category');
	let body2= document.getElementById('set-price');
	let body3= document.getElementById('history');

	body1.style.display ='none';
	body2.style.display ='block';
	body3.style.display ='none';	
}

function priceHistory(){
	let body1= document.getElementById('add-category');
	let body2= document.getElementById('set-price');
	let body3= document.getElementById('history');

	body1.style.display ='none';
	body2.style.display ='none';
	body3.style.display ='block';

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
		var username = document.getElementById('report-username').value;

		var myRequest = new XMLHttpRequest();
		var url = 'adminprocess.php';
		
		var formData = "select="+select+"&reason="+reason+"&username="+username;
		
		myRequest.open('POST', url ,true);
		myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

		myRequest.onload = function(){
			var response= this.responseText;
			if(response){
				alert('Your report has been sent for review, thank you.');
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
	marked.innerHTML ='<p class="checkreport">Checked</p>';

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
			alert('Your announcement has been sent to the home page');
		}
		myRequest.send(formData);		
	}
}

// Cart

function showCartPanel(){
	let panel = document.getElementById('cart-panel');
	let modal = document.getElementById('cart-modal');

	panel.style.display='block';
	modal.style.display='block';
}

function hideCartPanel(){
	let panel = document.getElementById('cart-panel');
	let modal = document.getElementById('cart-modal');

	
	panel.style.display='none';
	modal.style.display='none';	
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
			let profile = "profile.php?name="+response;
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
	document.getElementById('redirectlink').addEventListener('click',historyback);

	function historyback(){
		window.location.replace("index.php");
	}
	setTimeout(function () {
		   
		window.location.replace("index.php");
	
	}, 3000);
}

function redirectProfile(){
	
	var myRequest = new XMLHttpRequest();
	var url = 'edituserprocess.php';
	var session = "$_SESSION['name']";
	
	var formData = "session="+session;
	
	myRequest.open('POST', url ,true);
	myRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');

	myRequest.onload = function(){
		var response= JSON.parse(this.responseText);
		console.warn(response);
		if(response){
			document.getElementById('redirectlink').addEventListener('click',historyback);

					
			function historyback(){
				window.location.replace("profile.php?name="+response);
			}
			setTimeout(function () {
				   
				window.location.replace("profile.php?name="+response);
			
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

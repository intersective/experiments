<html>
<head>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</head>
<body>
	<div class='container'>
		<div class='jumbotron'>
			<h1>Take the test!</h1>
			<P>Which internship project would you enjoy more?</P>
			<form class='form-horizontal' id='templateform' action="" method="post" enctype="multipart/form-data">
				<input type='hidden' name='uuid' id='uuid'>
				<input type='hidden' name='key' id='key'>
				<input type='hidden' name='template_a' id='template_a'>
				<input type='hidden' name='template_b' id='template_b'>
				<div class='row'>
					<div class='col-sm-6'>
						<div class='radio'>
							<label>
								<input type='radio' value='a' name='templatevalue' id='templatevalue'> <div ID='template_a_text'>Template A here</div>
							</label>
						</div>
					</div>

					<div class='col-sm-6'>
						<div class='radio'>
							<label>
								<input type='radio' value='b' name='templatevalue' id='templatevalue'> <div id='template_b_text'>Template B here</div>
							</label>
						</div>
					</div>
				</div>	
			</form>
			<a href='results.csv' class='btn btn-primary'>download results</a>
		</div>
	</div>
	
	<script>
// MAD QUIZ

var templates = [
	'A job at <company> where you will work <hours> hours a week.',
	'A job at <company> where you will work on <projects>.',
	'A job at <company> with <perks>.',
	'A job at a company with <perks> where you will work on <projects>',
	'A job at a company with <perks> where you will work <hours> hours a week.',
	'A job at a company where you will work <hours> hours a week on <projects>'
];

var params = [ 
	'company', 'perks', 'projects', 'hours'
];

var company = {
	"A": [
		'Google',
		'Facebook',
		'Uber',
		'Twitter',
		'Yahoo',
		'IBM',
		'GE',
		'Goldman Sachs'
	],

	"B": [
		'a hospital',
		'an accountancy',
		'a law firm',
		'a non-profit organisation',
		'a 100 person tech startup',
		'a 50 person tech startup',
		'a 5 person tech startup'
	]
};

var perks = {
	"A": [
		'open plan offices',
		'free lunches',
		'a fitness center',
		'a game room',
		'flexible work policies',
		'a central location',
		'new facilities'
	],

	"B": [
		'cubicles',
		'9-5 hours',
		'a long commute',
		'a suburban office',
		'an old building'
	]
};

var hours = {
	"A": [
		"5", "10", "15", "20"
	],

	"B": [
		"40", "60", "80", "100"
	]
};

var projects = {
	"A": [
		'innovative technologies',
		'socially beneficial projects',
		'exciting projects',
		'our latest products',
		'products in development',
		'your own ideas'
	],

	"B": [
		'customer support',
		'market research',
		'sales support',
		'testing',
		'supporting our team'
	]
};

var seen = [];

function generateTemplate() {
	var i = Math.round(Math.random() * (templates.length - 1));
	var template_a = templates[i];
	var template_b = templates[i];
	var first = true;
	var k, j = 0;
	var key = "";
	var seen = []; 
	for (p in params) {
		param = params[p];

		do  {
			j = Math.round(Math.random() * (window[param]["A"].length - 1)); 
			k = Math.round(Math.random() * (window[param]["B"].length - 1)); 
			key = "" + i + ":" + j + ":" + k;
		}

		while (seen.indexOf(key) != -1);
		seen.push(key);
		if (template_a.indexOf(param) != -1) {
			if (first) {
				template_a = template_a.replace("<" + param + ">", window[param]["A"][j]);
				template_b = template_b.replace("<" + param + ">", window[param]["B"][k]);
				first = false;
				continue;
			} else {
				template_a = template_a.replace("<" + param + ">", window[param]["B"][k]);
				template_b = template_b.replace("<" + param + ">", window[param]["A"][j]);
			}
		}
		console.log("param: " + param + " template_a: " + template_a);		
	}
	$('#template_a_text').html(template_a);
	$('#template_b_text').html(template_b);
	$('#template_a').val(template_a);
	$('#template_b').val(template_b);
	$('#key').val(key);
	if ($('#uuid').val() == '') {
	    $('#uuid').val('<?=uniqid("id_", true)?>');
	}
}

generateTemplate();

$('#templateform').on('click', '#templatevalue', function(e) {
	var form = $('#templateform');
	$.ajax({
		url: form.attr('action'), 
		data: form.serialize(), 
		dataType: "html",
		method: "POST",
		success: function() {
			$('input:radio').prop("checked", false);
			generateTemplate();
		},
		// xhrFields: {
  //     		withCredentials: true
  // 		}
	});
});
	</script>
</body>
</html>
<?php

if (!empty($_POST)) {
  $line = [$_POST['uuid'], $_POST['key'], $_POST['templatevalue'], $_POST['template_a'], $_POST['template_b']];
  file_put_contents('results.csv', implode(',',$line) . "\n", FILE_APPEND);
}

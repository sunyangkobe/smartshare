window.addEvent('domready', function() {
	var today = new Date();
	window.startdate = new Picker.Date($('start_date'), {
		timePicker : true,
		format: 'db',
		minDate: today,
		positionOffset : {
			x : 5,
			y : 0
		},
		pickerClass : 'datepicker_dashboard',
		useFadeInOut : !Browser.ie
	});

	window.enddate = new Picker.Date($('end_date'), {
		timePicker : true,
		format: 'db',
		minDate: today,
		positionOffset : {
			x : 5,
			y : 0
		},
		pickerClass : 'datepicker_dashboard',
		useFadeInOut : !Browser.ie
	});
});

function IsNumeric(strString)
// check for valid numeric strings
{
	var strValidChars = "0123456789.-";
	var strChar;
	var blnResult = true;

	if (strString.length == 0)
		return false;

	// test strString consists of valid characters listed above
	for ( var i = 0; i < strString.length && blnResult == true; i++) {
		strChar = strString.charAt(i);
		if (strValidChars.indexOf(strChar) == -1) {
			blnResult = false;
		}
	}
	return blnResult;
}


function pad(number, length) {
	   
    var str = '' + number;
    while (str.length < length) {
        str = '0' + str;
    }
   
    return str;

}

function validateForm() {
	var now = new Date();
	var dtstring = now.getFullYear()
	    + '-' + pad(now.getMonth()+1, 2)
	    + '-' + pad(now.getDate(), 2)
	    + ' ' + pad(now.getHours(), 2)
	    + ':' + pad(now.getMinutes(), 2)
	    + ':' + pad(now.getSeconds(), 2);
	if (document.getElementById("name").value.length == 0) {
		alert("Name is required");
		return false;
	} else if (document.getElementById("name").value.length > 64) {
		alert("Name must be less than 64 characters");
		return false;
	}

	if (!IsNumeric(document.getElementById("price").value)) {
		alert("Price is required and has to be numeric");
		return false;
	}
	
	if (document.getElementById("start_date").value.length == 0) {
		alert("Start Date is required");
		return false;
	} else if (document.getElementById("end_date").value.length == 0) {
		alert("End Date is required");
		return false;
	} else if (document.getElementById("end_date").value <= document.getElementById("start_date").value) {
		alert("Start date must be before end date.");
		return false;
	} else if (document.getElementById("start_date").value <= dtstring) {
		alert("Start date/time must be after current date/time.");
		return false;
	}
	
	return validateAddr();
}

function validateAddr() {
	var addrs = [ "home" ];
	for ( var i = 1; i <= window.counter; i++) {
		addrs.push("route" + i);
	}
	validateAddHelper(addrs);
	return false;
}

function validateAddHelper(addrs) {
	if (addrs.length == 0) {
		var counter = document.createElement('input');
		counter.type = "hidden";
		counter.id = "counter";
		counter.name = "counter";
		counter.value = window.counter;
		document.getElementById("trip_form").appendChild(counter);
		document.getElementById("trip_form").submit();
		return;
	}

	var geocoder = new google.maps.Geocoder();
	var addr = document.getElementById(addrs[0]).value;
	geocoder.geocode({
		'address' : addr
	},
			function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					// revise the address
					if (confirm("Do you mean " + results[0].formatted_address 
							+ " ? If not, please includes more information such as city and province.")) {
						document.getElementById(addrs[0]).value = results[0].formatted_address;
						addrs.shift();
						validateAddHelper(addrs);
					}
				} else {
					alert("The address of " + addrs[0]
							+ " you specified was invalid.");
				}
			});
}

function addWayStop() {
	window.counter++;
	if (window.counter > window.init) {
		document.getElementById("removeWaystop").style.cssText = "display: block; float: right;";
	}
	var waystop = document.createElement('input');
	var blank = document.createElement('br');
	var routes = document.getElementById("destination");
	waystop.type = "text";
	waystop.id = "route" + window.counter;
	waystop.name = "route" + window.counter;
	waystop.style.cssText = "width: 300px;";
	routes.appendChild(blank);
	routes.appendChild(waystop);
}

function removeWayStop() {
	window.counter--;
	if (window.counter < window.init + 1) {
		document.getElementById("removeWaystop").style.cssText = "display: none; float: right;";
	}
	var routes = document.getElementById("destination");
	routes.removeChild(routes.lastChild);
	routes.removeChild(routes.lastChild);
}
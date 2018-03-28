var alpha=['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B',  'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
var randomLetter = alpha[Math.floor(Math.random() * alpha.length)];
function captcha()
{
	for(var i = 0;i<7;i++)
		{
			var first = alpha[Math.floor(Math.random() * alpha.length)];
			var second = alpha[Math.floor(Math.random() * alpha.length)];
			var third = alpha[Math.floor(Math.random() * alpha.length)];
			var fourth = alpha[Math.floor(Math.random() * alpha.length)];
			var fifth = alpha[Math.floor(Math.random() * alpha.length)];
			var sixth = alpha[Math.floor(Math.random() * alpha.length)];
			var seventh = alpha[Math.floor(Math.random() * alpha.length)];
		}
		var code = first + ' ' + second + ' ' + third + ' ' + fourth + ' ' + fifth + ' ' + sixth + ' ' + seventh;
		document.getElementById("mainCaptcha").value = code;
}
function validateForm()
{
	var captcha = removeSpaces(document.getElementById('mainCaptcha').value);
	var userInput = removeSpaces(document.getElementById('userInput').value);
	if(captcha != userInput)
	{
		return false;
	}
	else
	{
		return true;
	}
	function removeSpaces(string)
	{
		return string.split(' ').join('');
	}
}
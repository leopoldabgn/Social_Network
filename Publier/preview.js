
function setHidden(name) {
	document.getElementById(name).style.display = "none";
}

// Source : https://stackoverflow.com/questions/4459379/preview-an-image-before-it-is-uploaded
var loadFile = function(event) {
	var output = document.getElementById('output');
	
	output.onload = function() {
		URL.revokeObjectURL(output.src) // free memory
		output.style.display = "block";
		// On fait disparaître les labels (l'image et le petit texte) :
		setHidden('upload_container');
	}
	
	output.src = URL.createObjectURL(event.target.files[0]);

};
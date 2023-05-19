$(document).ready(function() {
    $('#generatePDF').click(function() {
        var doc = new jsPDF();
        var text = $('#readme').text();
        var lines = doc.splitTextToSize(text, 180); // Adjust the width (180) as needed

        doc.text(10, 10, lines); // Start at position (10, 10) and use the lines array
        doc.save('readme.pdf');
    });
});


$(document).ready(function() {
    $('#generatePDF').click(function() {
        var doc = new jsPDF();
        var text = $('#readme').text();
        doc.text(text, 10, 10);
        doc.save('readme.pdf');
    });
});

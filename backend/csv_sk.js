$(document).ready(function () {
    $("#export").click(function () {
        $.ajax({
            url: '../backend/export.php',
            method: 'GET',
            success: function (data) {
                let csv = 'Meno, Priezvisko , Login,Studentske ID,Spravne odpovede,Zo vsetkych\n';
                data.forEach(function(row) {
                    csv += row.first_name+',';
                    csv += row.last_name+',';
                    csv += row.username+',';
                    csv += row.studentID+',';
                    csv += row.right_answer+',';
                    csv += row.answered+'\n';
                });
                let hiddenElement = document.createElement('a');
                hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(csv);
                hiddenElement.target = '_blank';
                hiddenElement.download = 'studenti.csv';
                hiddenElement.click();
            }
        });
    });
});
$(document).ready(function() {
    $('#search-input').on('keyup', function() {
        var query = $(this).val();
        if (query.length >= 1) { // ne déclenche la recherche que si la requête contient au moins 3 caractères
            search(query);
        }
    });
});

function search(query) {
    $.ajax({
        url: "http://127.0.0.1:8000/club/search",
        type: "GET",
        data: { q: query },
        dataType: "json",
        success: function(data) {
           console.log(data.resultss);
           
            // mettre à jour la vue avec les résultats de la recherche
         // vider les résultats de la recherche précédente
         $('#club-table').html(data.resultss);
       },
        error: function(xhr, status, error) {
            console.log(xhr.responseText);
        }
    });

}

function updateSearchResults(data) {
    var tableBody = $('#club-table');
    var resultsDiv = $('#search-results');

    // Effacer les résultats de la recherche précédente
    resultsDiv.empty();

    // Afficher les résultats de la recherche
    if (Array.isArray(data) && data.length > 0) {
        $.each(data, function(index, club) {
            var row = '<tr>';
            row += '<td>' + club.id + '</td>';
            row += '<td>' + club.name + '</td>';
            row += '<td>' + club.location + '</td>';
            row += '<td>' + club.terrain + '</td>';
            row += '<td>';
            
            row += '</td>';
            row += '</tr>';
            console.log(row);

            tableBody.html(row);
        });
    } else {
        // Aucun résultat trouvé, afficher un message d'erreur
        resultsDiv.html('Aucun résultat trouvé.');
    }
}

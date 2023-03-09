$(document).ready(function() {
    $('#sort-by').on('change', function() {
      var selectedValue = $(this).val();
      
      $.ajax({
        url: "http://127.0.0.1:8000/club/sort",
        data:{sort: selectedValue},
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            $('#club-table').html(data.resultss);
        }
      });
    });
  });
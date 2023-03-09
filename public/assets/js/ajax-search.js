$(function () {
  $('#filter-form').on('submit', function (event) {
    event.preventDefault();
    var startDate = $('#start-date').val();
    var endDate = $('#end-date').val();
    var url = `http://127.0.0.1:8000/order/filter?start_date=${startDate}&end_date=${endDate}`;
    console;
    $.get(url, function (data) {
      var orderList = $('#order-list');
      orderList.html('');
      $.each(data, function (key, order) {
        var html = '<div class="card">';
        html += '<div class="card-body">';
        html += '<h5 class="card-title">Order #' + order.id + '</h5>';
        html += '<p class="card-text">Address: ' + order.Adresse + '</p>';
        html += '<p class="card-text">Time: ' + order.time + '</p>';
        html += '</div>';
        html += '</div>';
        orderList.append(html);
      });
    });
  });
});

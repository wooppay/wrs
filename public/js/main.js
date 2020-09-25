$(document).ready(function() {
    slickInit();

    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        $(".slider").slick("unslick");
        slickInit();
    });
});

function slickInit() {
    $('.slider').slick({
        draggable: false,
        infinite: false,
        slidesPerRow: 3,
        slidesToShow: 3
    });

}

$(function(){
    $('#user_report_dateFrom').daterangepicker({
        singleDatePicker: true,
        startDate: moment(),
        endDate: moment(),
        locale: {
            format: 'YYYY-MM-DD'
        }
    });
});
$(function(){
    $('#user_report_dateTo').daterangepicker({
        singleDatePicker: true,
        startDate: moment(),
        endDate: moment(),
        locale: {
            format: 'YYYY-MM-DD'
        }
    });
});
function getUsersForSelect() {
    let selectList = $('#user_report_user');
    document.getElementById('nav-report-tab').removeAttribute('onclick');
    $.ajax({
        url: routeGetUsers,
        method: 'GET',
        success: function (data) {
            $.each(data, function (key, value) {
                selectList.append('<option value="' + value['id'] + '">' + value['email'] + '</option>');
            });
        }
    })
}

function displayReport() {
    let data = $('#user_report_form').serializeArray();

    $.ajax({
        url: routeGetReport,
        method: 'POST',
        data: data,
        success: function (res) {
            if (res['errors']) {
                $.each(res['errors'], function (index, error) {
                    $('#' + index).html(error);
                });
            } else {
                $('#report').modal('show');
                $('#report-time').html('от ' + res['dateFrom'] + ' до ' + res['dateTo']);
                $('#username').html(res['userEmail']);

                let userEmail = document.getElementById('user-email');
                userEmail.innerHTML = res['userEmail'];
                userEmail.setAttribute('href', 'mailto:' + res['userEmail']);

                let reportTableHtml = getReportTableHtml(res['tasks']);
                $('#tasks-rates').html(reportTableHtml);

                let rates = res['rates'];
                $('#countTasks').html(res['tasks'].length);
                $('#positiveSoft').html(rates.positiveSoft);
                $('#negativeSoft').html(rates.negativeSoft);

                $('#countRates').html(res['countRates']);
                $('#positiveHard').html(rates.positiveHard);
                $('#negativeHard').html(rates.negativeHard);
            }

        }
    })
}

function getReportTableHtml(tasks)
{
    let reportTableHtml = '';

    $.each(tasks, function (index, task) {
        let taskName = '<td>' + task.name + '</td>';
        if (task.rates.length) {
            $.each(task.rates, function (index, rate) {
                let rateData = '';
                !index ? rateData += taskName : rateData += '<td style="border-right: ; border-bottom: solid white;"></td>';

                let iconOfResult = '';
                rate.value ? iconOfResult = "fa fa-check" : iconOfResult = "fa fa-times";


                rateData += '<td>' + rate.question + '</td>' +
                    '<td>' + task.created_at + '</td>' +
                    '<td>' + task.author + '</td>' +
                    '<td class="text-center">' + '<span class="' + iconOfResult + '"></span>' + '</td>';

                reportTableHtml += '<tr>' + rateData + '</tr>';
            });
        } else {
            let rateData = '';
            rateData +=
                taskName +
                '<td></td>' +
                '<td>' + task.created_at + '</td>' +
                '<td>' + task.author + '</td>' +
                '<td class="text-center">' + '<span>Оценки отсутствуют</span>' + '</td>';
            reportTableHtml += '<tr>' + rateData + '</tr>';
        }
    });

    return reportTableHtml;
}

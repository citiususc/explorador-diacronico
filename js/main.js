Handlebars.registerHelper('if_eq', function (a, b, opts) {
    return a == b ? opts.fn(this) : opts.inverse(this);
});

function esUrlImagen(image_url){
    return image_url.match(/clouds\//) != null
}

var numbFormatter = {
    to: function (value) {
        return parseInt(value);
    },
    from: function (value) {
        return value.replace("", '');
    }
};

var highchartsDataBase = {
    chart: {
        type: 'column',
        backgroundColor: '#f5f5f5',
    },
    title: {
        text: ""
    },
    yAxis: {
        title: {
            text: ""
        }
    },
    xAxis: {
        type: 'category',
        tickInterval: 1
    },
    legend: {
        enabled: false
    },
    plotOptions: {
        series: {
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                format: '{point.y:.1f}%'
            }
        }
    },

    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.4f}%</b><br/>'
    },

    series: [{
        name: 'Palabras relacionadas',
        colorByPoint: true,
        data: []
    }]
}

function isInt(n) {
    return !isNaN(n) && n % 1 === 0;
}

function isWord(palabra) {
    var stringReg = /^[a-zA-ZÁÉÍÓÚáéíóúñÑ]+( [a-zA-ZÁÉÍÓÚáéíóúñÑ]+)*$/
    var res = true;

    if ((typeof palabra == "undefined" || palabra == "") || !stringReg.test(palabra)) {
        res = false;
    }

    return res
}

function isEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

function calcularAltos() {
    var win = $(window).height();
    var mc = $("#main-container").outerHeight();
    var pie = $("#pie-container").outerHeight();
    var diff = win - (mc + pie);


    if (diff > 0) {
        diff = diff - 30;
    }else{
        diff = 20;
    }

    $("#pie-container").css("margin-top", diff + "px");
}


function redimensionar() {
    if ($("body").width() < 601) {
        $(".valign-wrapper-not-s").removeClass("valign-wrapper");
    } else {
        $(".valign-wrapper-not-s").addClass("valign-wrapper");
    }

    if ($("body").width() < 992) {
        $("#slide-out").removeClass("fixed");
    } else {
        $("#slide-out").addClass("fixed");
    }

    calcularAltos();
}

function setLocale(lang) {
    var cookie = document.cookie.split("locale=");

    if (typeof lang === "undefined" || lang === "" || !lang) {
        if (typeof cookie[1] === "undefined" || cookie[1] === "" || !cookie[1]){
            lang = window.navigator.userLanguage || window.navigator.language;
        }else{
            lang = cookie[1].split(";")[0];
        }
    }


    var locale = lang.split("-")[0];

    if (locale != "en" && locale != "es" && locale != "gl") {
        locale = "es";
    }

    document.cookie = "locale=" + locale + "; expires=Thu, 17 Dec 2025 12:00:00 UTC";
    $("html").attr("lang", locale)

    return locale;
}

$(window).resize(redimensionar);

$(document).ready(function () {
    setTimeout(redimensionar, 10);
});

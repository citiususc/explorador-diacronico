<!DOCTYPE html>
<html lang="es" class="full-height">

<?php require_once("head.php");
date_default_timezone_set('Europe/Madrid');
?>

<body class="full-height grey lighten-4">
<!--Import jQuery before materialize.js-->
<script type="text/javascript" src="js/jquery-3.1.0.min.js"></script>
<script type="text/javascript" src="js/materialize.min.js"></script>
<script src="js/highcharts.js"></script>
<script src="js/exporting.js"></script>
<script src="js/nouislider.js"></script>
<script src="js/handlebars-v4.0.5.js"></script>


<script type="text/javascript" src="js/main.js"></script>

<?php
$url = array_pop(explode("/", $_SERVER['REQUEST_URI']));
?>


<script>

    var locale = setLocale();

    $(function () {
        var theTemplateScript = $("#head-template").html();

        var theTemplate = Handlebars.compile(theTemplateScript);

        var context_es = {
            "menu1": "Explorador diacrónico",
            "menu2": "Información",
            "menu3": "Contacto",
            "idioma":"Idioma",
            "idiomas": [{cod: "en", nombre: "Inglés"},
                {cod: "gl", nombre: "Gallego"},
                {cod: "es", nombre: "Español"}]
        };

        var context_gl = {
            "menu1": "Explorador diacrónico",
            "menu2": "Información",
            "menu3": "Contacto",
            "idioma" : "Idioma",
            "idiomas": [{cod: "en", nombre: "Inglés"},
                {cod: "gl", nombre: "Galego"},
                {cod: "es", nombre: "Español"}]
        };

        var context_en = {
            "menu1": "Diachronic explorer",
            "menu2": "Information",
            "menu3": "Contact",
            "idioma": "Language",
            "idiomas": [{cod: "en", nombre: "English"},
                {cod: "gl", nombre: "Galician"},
                {cod: "es", nombre: "Spanish"}]
        }

        context = eval("context_" + locale);
        var theCompiledHtml = theTemplate(context);

        $('body').append(theCompiledHtml);

        $(".button-collapse").sideNav();

        $(".button-collapse").click(function () {
            if ($("#sidenav-overlay").length > 0) {
                $("#slide-out").css("left", "240px");
            }
        });

        $(".drag-target").click(function () {
            $("#slide-out").css("left", "0");
        });

        $("input[name='tipo']").change(function (e) {
            if ($(this).val() == 'pares') {
                $("#scnd-word-container").slideDown();
            } else {
                $("#scnd-word-container").slideUp();
            }

        });

        $('.dropdown-button').dropdown({});

        $("#idioma li a").click(function(evt){
            setLocale($(this).data("cod"));
            location.reload();
        })
    });
</script>


<script id="head-template" type="text/x-handlebars-template">

    <div class="fixed-action-btn  click-to-toggle hide-on-large-only" data-activates="slide-out" id="nav-opener">
        <a class="btn button-collapse btn-floating btn-large orange darken-3">
            <i class="material-icons">menu</i>
        </a>
    </div>


    <div class="no-margin-bottom">

        <nav id="slide-out" class="side-nav fixed orange accent-3">
            <ul>
                <li class="<?= (($url == "index.php") ? "active" : "") ?>">
                    <a href="./index.php">
                        {{menu1}}
                    </a>
                </li>
                <li class="<?= (($url == "info.php") ? "active" : "") ?>"><a href="./info.php">{{menu2}}</a></li>
                <li class="<?= (($url == "contacto.php") ? "active" : "") ?>"><a href="./contacto.php">{{menu3}}</a>
                </li>
            </ul>
        </nav>

        <section id="main-container" class="seccion col s12 m8 l9 full-height">

            <a id="btn-idioma" class='dropdown-button btn orange darken-3' href='#' data-activates='idioma'>{{idioma}}</a>

            <ul id='idioma' class='dropdown-content'>
                {{#each idiomas}}
                <li><a class="orange-text text-darken-3" data-cod="{{this.cod}}" href="#!">{{this.nombre}}</a></li>
                {{/each}}
            </ul>

</script>

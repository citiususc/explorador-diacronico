<?php
require_once("header.php")
?>
    <script>
        $(function () {
            var grafico = null;
            var theTemplateScript = $("#main-template").html();

            var theTemplate = Handlebars.compile(theTemplateScript);

            var context_es = {
                "titulo": "Explorador diacrónico",
                "texto": 'Bienvenido al explorador diacrónico. Puedes encontrar más información sobre la utilidad de la herramienta',
                "link": {href: "info.php", title: "Más información", texto: "aquí"},
                "keywordLabel": "Palabra a buscar",
                "enviarLabel": "Búsqueda",
                "radio1": "Simple",
                "radio2": "Pares",
                "radio3": "Similaridad transitiva",
                "radio4": "Histórico",
                "radio5": "Nube",
                "titulo2": "Escoja la segunda palabra",
                "segundaPalabraLabel": "Segunda palabra",
                "rangeLabel": "Escoja el período a consultar",
                "errorTipo": "Tipo inválido",
                "errorPalabra2": "Introduzca una segunda palabra válida",
                "errorPalabra": "Introduzca una palabra válida",
                "errorInterno": "Se ha producido un error interno. Vuelva a intentarlo más tarde",
                "corrMedia": "Las similaridades medias con ",
                "corrNivel": "Nivel de similaridad",
                "corr": "Las similaridades",
                "corrMejor": "Las palabras con mejor índice de similaridad entre ",
                "noResults": "No hay resultados. Prueba con otra palabra.",
                "errorTimeout": "Su consulta se está procesando...",
                "config": "Búsqueda avanzada",
                "longProcess": "Este es un proceso costo y por lo tanto llevará varios minutos. Por favor, espere."
            };

            var context_gl = {
                "titulo": "Explorador diacrónico",
                "texto": 'Benvido ao explorador diacrónico. Podes atopar máis información sobre a utilidade da ferramenta',
                "link": {href: "info.php", title: "Máis información", texto: "aquí"},
                "keywordLabel": "Palabra a buscar",
                "enviarLabel": "Búsqueda",
                "radio1": "Simple",
                "radio2": "Pares",
                "radio3": "Similaridad transitiva",
                "radio4": "Histórico",
                "radio5": "Nube",
                "titulo2": "Escolla a segunda palabra",
                "segundaPalabraLabel": "Segunda palabra",
                "rangeLabel": "Escolla o período a consultar",
                "errorTipo": "Tipo inválido",
                "errorPalabra2": "Introduza unha segunda palabra válida",
                "errorPalabra": "Introduza unha palabra válida",
                "errorInterno": "Produciuse un erro interno. Volva a intentalo máis tarde",
                "corrMedia": "As similaridades medias con ",
                "corrNivel": "Nivel de similaridad",
                "corr": "As similaridades",
                "corrMejor": "As palabras con mellor índice de similaridad entre ",
                "noResults": "Non hai resultados. Proba con outra palabra.",
                "errorTimeout": "A súa consulta estase a procesar...",
                "config": "Búsqueda avanzada"  ,
                "longProcess": "Este é un proceso custo e polo tanto levará varios minutos. Por favor, espere."
            };

            var context_en = {
                "titulo": "Diachronic explorer",
                "texto": 'Welcome to de diachronic explorer. You can find further information about the tool',
                "link": {href: "info.php", title: "More info", texto: "here"},
                "keywordLabel": "Word to search",
                "enviarLabel": "Search",
                "radio1": "Simple",
                "radio2": "Pairs",
                "radio3": "Transitive similarity",
                "radio4": "Historic",
                "radio5": "Cloud",
                "titulo2": "Choose the second word",
                "segundaPalabraLabel": "Second word",
                "rangeLabel": "Choose the year range to see",
                "errorTipo": "Invalid selection",
                "errorPalabra2": "Introduce a second valid word",
                "errorPalabra": "Introduce a valid word",
                "errorInterno": "Something happened. Try again later",
                "corrMedia": "The mean similarities with ",
                "corrNivel": "Similarity level",
                "corr": "The similarities",
                "corrMejor": "The words with the highest similarity level between",
                "noResults": "No results. Try another search.",
                "errorTimeout": "Your query is being processed...",
                "config": "Advanced search",
                "longProcess": "This is a heavy process, so it's going to take several minutes. Please, wait.   "

            };

            var context = eval("context_" + locale);
            var theCompiledHtml = theTemplate(context);

            $('#main-container').append(theCompiledHtml);

            function pintarGrafico() {
                var palabra = $("#keyword").val().trim();
                var palabra2 = $("#keyword2").val().trim();
                var tipo = $("input[name='tipo']:checked").val();
                var error = false

                if (tipo != "simple" && tipo != "transitiva" && tipo != "pares" && tipo != "historico" && tipo != "nube") {
                    error = true;
                    Materialize.toast(context.errorTipo, 3000, 'red')
                }

                if (tipo == "pares" && !isWord(palabra2)) {
                    error = true
                    Materialize.toast(context.errorPalabra2, 3000, 'red')
                }

                if (!isWord(palabra)) {
                    error = true;
                    Materialize.toast(context.errorPalabra, 3000, 'red')
                }

                if (!error) {
                    var slider = document.getElementById("year-range");

                    var sliderValues = ( slider.noUiSlider.get() );
                    var ano = 2009
                    var ano2 = 2009

                    if (isInt(sliderValues[0])) {
                        ano = parseInt(sliderValues[0]);
                    }

                    if (isInt(sliderValues[1])) {
                        ano2 = parseInt(sliderValues[1]);
                    }

                    if (tipo == "pares") {
                        palabra = palabra + "--" + palabra2;
                    }

                    $(".preloader-wrapper.big").addClass("active");
                    //$('#chart-container').showLoading();

                    try {
                        $.ajax({
                            url: 'https://tec.citius.usc.es/buscador-diacronico/busca/' + tipo + '/' + palabra + '/' + ano + '/' + ano2,
                            dataType: "json",
                            timeout: 30000,
                            error: function (x, t, m) {
                                if (t === "timeout") {
                                    $(".preloader-wrapper.big").removeClass("active");
                                    Materialize.toast(context.errorTimeout, 3000)
                                    pintarGrafico();
                                } else {
                                    $(".preloader-wrapper.big").removeClass("active");
                                    Materialize.toast(context.errorInterno, 3000, 'red')
                                }
                            },
                            success: function (data) {
                                if (tipo === "nube") {
                                    $('#chart-container').show();
                                    $('#chart-container').empty();

                                    if (esUrlImagen(data[0])) {
                                        $('#chart-container').append("<img class='nube-palabras' src=" + data[0] + " />");
                                        $('#chart-container').css("height", "auto");
                                    } else {
                                        $('#chart-container').empty();
                                        $('#chart-container').text(context.noResults);
                                        $('#chart-container').css("height", "50px");
                                    }


                                    $(".preloader-wrapper.big").removeClass("active");
                                } else {
                                    $('#chart-container').show();
                                    $("#main-container").animate({"padding-top": 0}, "slow");

                                    if (data.length > 0 || Object.keys(data).length > 0) {
                                        if (Object.keys(data).length == 1 && Object.keys(data)[0] === "waiting" && data.waiting == 1) {
                                            Materialize.toast(context.longProcess, 3000)
                                            setTimeout(function(){
                                                pintarGrafico();
                                            }, 30000);
                                        } else {
                                            $('#chart-container').css("height", "600px");
                                            var highchartsData = jQuery.extend(true, {}, highchartsDataBase);

                                            if (tipo == "simple" || tipo == "transitiva") {
                                                highchartsData["series"][0]["data"] = data;
                                                highchartsData["title"]["text"] = context.corrMedia + palabra;
                                            } else if (tipo == "pares") {
                                                highchartsData["series"][0]["data"] = data;
                                                highchartsData["xAxis"] = {};
                                                highchartsData["chart"]["type"] = "";
                                                highchartsData["series"][0]["colorByPoint"] = false;
                                                highchartsData["series"][0]["name"] = context.corrNivel;
                                                highchartsData["colors"] = ['#f47431']
                                                highchartsData["title"]["text"] = context.corr + " " + palabra.split("--")[0] + ' - ' + palabra2;
                                            } else if ("historico") {

                                                var myData = $.map(data, function (value, index) {
                                                    return [value];
                                                });

                                                highchartsData["series"] = myData;

                                                highchartsData["xAxis"] = {};
                                                highchartsData["legend"] = {"enabled": true, "maxHeight": 160};
                                                highchartsData["chart"]["type"] = "";
                                                highchartsData["title"]["text"] = context.corrMejor + " " + ano + ' - ' + ano2;
                                            }

                                            grafico = $('#chart-container').highcharts(highchartsData);

                                            $(".preloader-wrapper.big").removeClass("active");
                                        }
                                    } else {
                                        $(".preloader-wrapper.big").removeClass("active");
                                        $('#chart-container').empty();
                                        $('#chart-container').text(context.noResults);
                                        $('#chart-container').css("height", "50px");
                                    }
                                }
                                redimensionar();
                            }
                        });
                    } catch (e) {
                        console.log(e);
                    }
                }

            }

            $("#searcher").submit(function (evt) {
                evt.preventDefault();
                evt.stopPropagation();
                $('.texto-inicio').hide();
                $('#chart-container').empty();

                if (grafico != null) {
                    ($('#chart-container').highcharts().destroy())
                    grafico = null;
                }
                pintarGrafico();

                return false;
            });

            $('.collapsible').collapsible({
                accordion: false // A setting that changes the collapsible behavior to expandable instead of the default accordion style
            });

            var slider = document.getElementById("year-range");

            if (slider) {
                noUiSlider.create(slider, {
                    start: [2005, 2009],
                    connect: true,
                    step: 1,
                    range: {
                        'min': 1900,
                        'max': 2009
                    },
                    tooltips: [numbFormatter, numbFormatter]
                });
            }

            $("input[name='tipo']").change(function (e) {
                if ($(this).val() == 'pares') {
                    $("#scnd-word-container").slideDown();
                } else {
                    $("#scnd-word-container").slideUp();
                }

            });
        });
    </script>

    <script id="main-template" type="text/x-handlebars-template">

        <header>
            <h1 class="titulo">{{titulo}}</h1>
        </header>

        <p class="container texto-inicio">{{texto}} <a href="{{link.href}}" title="{{link.title}}">{{link.texto}}</a>.
        </p>

        <form id="searcher" class="container" autocomplete="off">
            <div class="row valign-wrapper valign-wrapper-not-s">
                <div class="col valign s12 m9">
                    <div class="input-field">
                        <i class="material-icons prefix">search</i>
                        <input id="keyword" type="text" class="validate">
                        <label for="keyword">{{keywordLabel}}</label>
                    </div>
                </div>

                <div class="col valign right-align s12 m3">
                    <button id="enviar" class="btn orange darken-3 waves-effect waves-light s12" type="submit">
                        {{enviarLabel}}
                        <i class="material-icons right">send</i>
                    </button>
                </div>

            </div>
            <div class="row valign-wrapper valign-wrapper-not-s">
                <div class="col valign s12">
                    <ul id="conf" class="collapsible" data-collapsible="accordion">
                        <li>
                            <div class="collapsible-header grey lighten-4">
                                <i id="gear" class="material-icons">settings</i>{{config}}
                            </div>
                            <div class="collapsible-body container">
                                <div class="line row">
                                    <p id="tipo" class="titulo-linea col s12"> Seleccione el tipo de búsqueda:</p>
                                    <p class="input-line col s12 m6 l4">
                                        <input class="with-gap" name="tipo" type="radio" id="tipo-1" checked="checked"
                                               value="simple"/>
                                        <label for="tipo-1">{{radio1}}</label>

                                        <a title="{{ayuda}}" href="info.php#simple"><i class="help material-icons">live_help</i></a>
                                    </p>
                                    <p class="input-line  col s12 m6 l4">
                                        <input class="with-gap" name="tipo" type="radio" id="tipo-2" value="pares"/>
                                        <label for="tipo-2">{{radio2}}</label>

                                        <a title="{{ayuda}}" href="info.php#pares"><i class="help material-icons">live_help</i></a>
                                    </p>
                                    <p class="input-line  col s12 m6 l4">
                                        <input class="with-gap" name="tipo" type="radio" id="tipo-3" value="transitiva"/>
                                        <label for="tipo-3">{{radio3}}</label>

                                        <a title="{{ayuda}}" href="info.php#transitiva"><i class="help material-icons">live_help</i></a>
                                    </p>
                                    <p class="input-line  col s12 m6 l4">
                                        <input class="with-gap" name="tipo" type="radio" id="tipo-4" value="historico"/>
                                        <label for="tipo-4">{{radio4}}</label>

                                        <a title="{{ayuda}}" href="info.php#historico"><i class="help material-icons">live_help</i></a>
                                    </p>
                                    <p class="input-line  col s12 m6 l4">
                                        <input class="with-gap" name="tipo" type="radio" id="tipo-5" value="nube"/>
                                        <label for="tipo-5">{{radio5}}</label>

                                        <a title="{{ayuda}}" href="info.php#nube"><i class="help material-icons">live_help</i></a>
                                    </p>
                                </div>

                                <div id="scnd-word-container" class="line row oculto">
                                    <p class="titulo-linea col s12">{{titulo2}}:</p>
                                    <div class="input-field s12">
                                        <label for="keyword2"
                                               class="titulo-linea oculto">{{segundaPalabraLabel}}</label>
                                        <input placeholder="{{segundaPalabraLabel}}" id="keyword2" type="text"
                                               class="validate">
                                    </div>
                                </div>

                                <div class="line row">
                                    <p class="titulo-linea">{{rangeLabel}}:</p>
                                    <div id="year-range"></div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </form>

        <div id="loader-chart-containter">
            <div class="preloader-wrapper big">
                <div class="spinner-layer spinner-blue">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="gap-patch">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>

                <div class="spinner-layer spinner-red">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="gap-patch">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>

                <div class="spinner-layer spinner-yellow">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="gap-patch">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>

                <div class="spinner-layer spinner-green">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="gap-patch">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>

            <div id="chart-container" class="container"></div>
        </div>
    </script>

<?php
require_once("footer.php")
?>

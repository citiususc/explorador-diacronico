<?php
require_once("header.php")
?>

    <script>
        $(function () {
            var theTemplateScript = $("#contacto-template").html();

            var theTemplate = Handlebars.compile(theTemplateScript);

            var context_gl = {
                "titulo": "Contacto",
                "texto1": "O grupo ProLNat@GE ten unha amplia experiencia en proxectos relacionados co procesamento e análise da linguaxe. Entre os seus traballos podemos atopar:",
                "lista": ["Concordancer (kwic)",
                    "Conxugador de verbos en galego", "Traductor de galego a portugués", "PoS Tree-Tagger para portugués e galego", "PoS FreeLing para portugués e galego",
                    "CitiusTagger e CitiusNec, un POS-tagger e Named Entity Recognizer para portugués, inglés, galego e español",
                    "CitiusSentiment, unha ferramenta de  Sentiment Analysis para portugués, inglés, galego e español",
                    "Multilingual Term Extractor para portugués, inglés, francés, galego e español",
                    "DepPattern un conxunto de ferramentas para xerar analizadores de dependencias multilingües parapara portugués, inglés, francés, galego e español",
                    "Lingua Toolkit (con Thesaurus Constructor) para portugués, inglés, francés, galego e español",
                    "QueLingua identificador de linguaxe",
                    "Linguakit o proxecto de máis relevancia e onde se poden ver multitude das ferramentas anteriores traballando de forma conxunta"
                ],
                "texto2": "Se precisa máis información pode remitirse á <a href='http://gramatica.usc.es/pln' title=\"web do grupo\">páxina web do grupo de investigación</a> ou porse en contacto por correo electrónico no enderezo: pablo.gamallo [at] usc.es"
            };

            var context_es = {
                "titulo": "Contacto",
                "texto1": "El grupo ProLNat@GE tiene una extensa experiencia en proyectos relacionados con el procesamiento y el análisis del lenguaje. Entre sus trabajos se encuentran:",
                "lista": ["Concordancer (KWIC)",
                    "Conjugador de verbos en gallego",
                    "Traductor de gallego a portugués",
                    "PoS Tree-Tagger para el portugués y el gallego",
                    "PoS FreeLing para el portugués y el gallego",
                    "CitiusTagger y CitiusNec, un POS-Tagger y con Named Entity Recognizer para el portugués, inglés, español y gallego",
                    "CitiusSentiment, una herramienta de Sentiment Analysis para el portugués, Inglés, español y gallego",
                    "Multilingual Term Extractor para el portugués, Inglés, Francés, Español y Gallego",
                    "DepPattern un conjunto de herramientas para la generación de analizadores de dependencia multilingüe parapara portugués, Inglés, Francés, Español y Gallego",
                    "Lingua Toolkit (con Thesaurus Constructor) para el portugués, Inglés, Francés, Español y Gallego",
                    "Identificador de lenguaje QueLingua",
                    "Linguakit el proyecto más relevante y donde se pueden ver muchas de las herramientas anteriores trabajando de forma conjunta."],
                "texto2": "Si necesita más información puede remitirse a la <a href='http://gramatica.usc.es/pln' title=\"web del grupo\">página web del grupo de investigación</a> o ponerse en contacto por correo electrónico a: pablo.gamallo [at] usc.es"
            };

            var context_en = {
                "titulo": "Contact",
                "texto1": "The group ProLNat@GE has wide experience in proyects related to the language processing and analysis. Amongst its jobs you can find:",
                "lista": ["Concordancer (kwic)",
                    "Galician Verb Conjugator",
                    "Galician-Portuguese translator",
                    "PoS Tree-Tagger for Portuguese and Galician",
                    "PoS FreeLing for Portuguese and Galician",
                    "CitiusTagger and CitiusNec, a POS-tagger and Named Entity Recognizer for Portuguese, English, Galician and Spanish",
                    "CitiusSentiment, a Sentiment Analysis tool for Portuguese, English, Galician, and Spanish",
                    "Multilingual Term Extractor for Galician, Spanish, English, French, and Portuguese.",
                    "DepPattern a toolkit to generate multilingual dependency parsers for Galician, Spanish, English, French, and Portuguese",
                    "Lingua Toolkit (with a Thesaurus Constructor) for Galician, Spanish, English, French, and Portuguese",
                    "QueLingua language identifier"],
                "texto2": "If you need further information, you can go to the <a href='http://gramatica.usc.es/pln' title=\"group's web\">investigation group's web</a> or get in touch mailing to: pablo.gamallo [at] usc.es"
            };

            context = eval("context_" + locale);
            var theCompiledHtml = theTemplate(context);

            $('#main-container').append(theCompiledHtml);
        });
    </script>

    <script id="contacto-template" type="text/x-handlebars-template">
        <header>
            <h1 class="titulo">{{titulo}}</h1>
        </header>
        <p class="container texto">
            {{texto1}}
        </p>
        <ul class="container lista">
            {{#each lista}}
            <li><span class="espacio"></span>{{this}}</li>
            {{/each}}
        </ul>
        <p class="container texto">
            {{{texto2}}}
        </p>
    </script>


<?php
require_once("footer.php")
?>
<?php
require_once("header.php")
?>

    <script>
        $(function () {
            var theTemplateScript = $("#info-template").html();

            var theTemplate = Handlebars.compile(theTemplateScript);

            var context_gl = {
                "titulo": "Información",
                "textos": ["Trátase dun sistema que permite buscar e visualizar os cambios léxicos de decenas de millares de palabras do castelán ao longo do tempo, concretamente no eixo temporal 1900-2009, usando como fonte de datos as representacións semánticas construídas cos n-gramas de Google en castelán (45 mil millóns). O usuario busca por unha palabra e un período de tempo (entre 1 e N anos) e o sistema devolve o sentido da palabra en cada ano do intervalo buscado. O sentido dunha palabra represéntase mediante o conxunto de palabras mais similares en termos semánticos e distribucionais. Por exemplo, a palabra “cáncer” relaciónase estreitamente en 1910 con “tuberculosis” e “sífilis” peroxa en 1960 os termos máis próximos son “tumor” e “carcinoma”.","A entrada do sistema é unha estrutura de datos en que as palabras son asociadas mediante graos de similaridade (Coseno) con outras palabras e por ano. Estes datos foron xerados recentemente polo equipo PronLNat@GE (Pablo Gamallo, Marcos Garcia) mediante técnicas e módulos de Procesamento da Linguaxe Natural. Concretamente, efectuamos o procesamento semántico de 45 mil millóns de n-gramas, disponíbeis tras o escaneo de máis de 1 millón de libros do proxecto “Google Books”. O procesamento semántico consistiu en transformar os n-gramas en matrices distribucionais 'palabra-contexto'. Xerouse unha matriz por ano, onde cada palabra é un vector de contextos. Finalmente, calculouse a similaridade entre vectores (palabras) e seleccionáronse, para cada palabra, as 20 máis similares por ano. En total, xerouse unha estrutura de datos de máis de 300M, que é o input do demostrador."],
                "subtitulo1":"Qué é",
                "subtitulo":"Tipos de búsqueda",
                "texto2": "Actualmente, o explorador da soporte a catro tipos diferentes de búsqueda:",
                "tipos" : [
                    {"name":"Simple",
                        "id":"simple",
                        "text":"O usuario introduce unha palabra de búsqueda e especifica un período de tempo. O sistema devolve as 20 palabras cunha similaridade media máis alta no período de anos especificado."},
                    {"name":"Pares",
                        "id":"pares",
                        "text":"Se facemos unha búsqueda para encontrar a similariade entre pares de palabras, o sistema ofrece un novo campo para introducir una segunda palabra. Devolve o valor de similaridade entre as dúas palabras comparadas durante o intervalo de tempo escollido."},
                    {"name":"Histórico",
                        "id":"historico",
                        "text":"Ao igual que na búsqueda simple, o usuario introduce unha palabra e un período de tempo. Sen embargo, o sistema especifica os valores de similaridade ano a ano en lugar de dar a media de todo o período. Por outro lado, visualizanse todas as palabras relacionadas coa palabra de búsqueda durante o período buscado en lugar das 20 primeras. Por defecto, visualízase o histórico de similaridade das tres con valores de similaridade media máis alta."},
                    {"name":"Nube",
                        "id":"nube",
                        "text":"Este tipo de búsqueda xera unha imaxen formada a partir das palabras con maior grao de similiaridade. A maior tamaño, maior similaridade."},
                    {"name":"Transitiva",
                        "id":"transitiva",
                        "text":"Esta búsqueda baséase na propiedade transitiva. Para clarificar isto imaxinemos unha estructura de árbore. No primero nivel encuentrase a raíz, é dicir aa palabra buscada, mentras que no segundo nivel están as 20 palabras cun nivel de similaridade máis alto. Tense tamén en conta un terceiro nivel, formado polas palabras que son similares ás do segundo nivel. Usando a propiedade transitiva, as palabras do primero nivel terán un grao de similariad coas palabras do 3 nivel calculado mediante a suma dos valores de similariade e aplicando unha normalización dos resultados, obtendo así valores entre 0 e 100."}
                ]

            };

            var context_es = {
                "titulo": "Información",
                "textos": ["Se trata de un sistema que permite buscar y visualizar los cambios léxicos de decenas de miles de palabras del castellano a lo largo del tiempo, concretamente en el eje temporal 1900-2009, utilizando como fuente de datos las representaciones semánticas construidas con los n-gramas de Google en español (45 mil millones). El usuario busca por una palabra y un período de tiempo (entre 1 y N años) y el sistema devuelve el sentido de la palabra en cada año del rango buscado. El sentido de una palabra se representa por el conjunto de palabras más similares en términos semánticos y distribucionales. Por ejemplo, la palabra “cáncer“ está estrechamente vinculada en 1910 con “tuberculosis“ y “sífilis“ pero ya en 1960 los términos más próximos son “tumor“ y “carcinoma“. ","La entrada del sistema es una estructura de datos en la que las palabras están asociadas mediante grados de similaridad (Coseno) con otras palabras y por año. Estos datos fueron generados recientemente por el equipo PronLNat@GE (Pablo Gamallo, Marcos Garcia) a través de técnicas y módulos de Procesamiento del Lenguaje Natural. Específicamente, efectuamos el procesamiento semántico de 45 mil millones de n-gramas, disponibles después del escaneo de más de 1 millón de libros del proyecto “Google Books“. El procesamiento semántico consiste en transformar los n-gramas en matrices distribucionales 'palabra-contexto'. Se generó una matriz por año, donde cada palabra es un vector de contextos. Finalmente, se calcula la similaridad entre vectores (palabras) y se selecciona, para cada palabra, las 20 más similares por año. En total, se generó una estructura de datos de más de más de 300M, que es la entrada del demostrador."],
                "subtitulo1":"Qué es",
                "subtitulo":"Tipos de búsqueda",
                "texto2": "Actualmente, el explorador da soporte a cuatro tipos diferentes de búsqueda:",
                "tipos" : [
                    {"name":"Simple",
                        "id":"simple",
                        "text":"El usuario introduce una palabra de búsqueda y especifica un período de tiempo. El sistema devuelve las 20 palabras con una similaridad media más alta en el período de años especificado."},
                    {"name":"Pares",
                        "id":"pares",
                        "text":"Si hacemos una búsqueda para encontrar la similariad entre pares de palabras, el sistema ofrece un nuevo campo para introducir una segunda palabra. Devuelve el valor de similaridad entre las dos palabras comparadas durante el intervalo de tiempo escogido."},
                    {"name":"Histórico",
                        "id":"historico",
                        "text":"Al igual que en la búsqueda simple, el usuario introduce una palabra y un período de tiempo. Sin embargo, el sistema especifica los valores de similaridad año a año en lugar de dar la media de todo el período. Por otro lado, se visualizan todas las palabras relacionadas con la palabra de búsqueda durante el período buscado en lugar de las 20 primeras. Por defecto, se visualiza el histórico de similaridad de las tres con valores de similaridad medio más alto."},
                    {"name":"Nube",
                        "id":"nube",
                        "text":"Este tipo de búsqueda genera una imagen formada a partir de las palabras con mayor grado de similiaridad. A mayor tamaño, mayor similaridad."},
                    {"name":"Transitiva",
                        "id":"transitiva",
                        "text":"Esta búsqueda se basa en la propiedad transitiva. Para clarificar esto imaginemos una estructura de árbol. En el primer nivel se encuentra la raíz, es decir la palabra buscada, mientras que en el segundo nivel están las 20 palabras con un nivel de similaridad más alto. Se toma también en cuenta un tercer nivel, formado por las palabras que son similares a las del segundo nivel. Usando la propiedad transitiva, las palabras del primer nivel tendrán un grado de similariad con las palabras del 3 nivel calculado mediante la suma de los valores de similariad y aplicando una normalización de los resultados, obteniendo así valores entre 0 y 100."}
                ]

            };

            var context_en = {
                "titulo": "Information",
                "textos": ["This system allows searching and visualizing lexical changes on dozens of thousands of Spanish words along the time, specifically on the 1900-2009 range of years, using as data source semantic representations built from Google n-grams for Spanish (45 billion). The user searches a word in a range of time (between 1 and N years) and the system returns the word sense for each year from the interval searched. The word's sense is represented by the set of words that are the most similar in semantic and distributional terms. For example, the word “cancer“ was closely related in 1910 with “tuberculosis“ and “syphilis“ but in 1960 the closest terms are “tumor“ and “carcinoma“. ", "The system's input is a data structure which has words related to other words using distributional similarity score (Cosine) for each year. Distributional data were generated using Natural Language Processing techniques and modules generated by the team PronLNat@GE (Pablo Gamallo, Marcos Garcia).  More precisely, we performed the semantic process for more than 45 billion n-grams, which were scanned from more than 1 million books belonging to the “Google Books“ project. The semantic process consists in transforming the n-grams into distributional 'word-context' matrices. A matrix was generated for each year, where each word is a context array. Finally, the similarity between arrays (words) was calculated and, for each word, the 20 most similar ones were selected by year. Total, a data structure with more than 300 million words were generated and used as the input of our Diachronic Explorer. "],
                "subtitulo1":"What it is",
                "subtitulo":"Types of search",
                "texto2": "At present, the explorer offers four different ways of making a diachronic search:",
                "tipos" : [
                    {"name":"Simple",
                        "id":"simple",
                        "text":"The user enters a target word and the system returns the 20 most similar terms (in average) within a specific period of type."},
                    {"name":"Pairs",
                        "id":"pares",
                        "text":"If the user is interested in a search focused on a pair of words, the system provides the user with a new field to insert the second word. The explorer returns the similarity score obtained for the two words given a selected period of time."},
                    {"name":"Track record",
                        "id":"historico",
                        "text":"As in the simple search, the user inserts a word and a time period. However, the track record search returns the specific similarity scores for each year of the period, instead of the similarity average. In addition, the system allows the user to select any word from the set of all words (and not just the top 20) semantically related to the target one in the searched time period. By default, the explorer visualizes the similarity track record of the three words with the highest average score. "},
                    {"name":"Cloud",
                        "id":"nube",
                        "text":"This type of search generates an image built from the words with highest similarity score. The more the size the more similarity."},
                    {"name":"Transitive",
                        "id":"transitiva",
                        "text":"This kind of search is based on the transitivity property. To explain the idea, lets us start with a tree structure at three levels. At the first level, we find the root, i.e. the search word, while at the second one we find its 20 most similar words. The third level is also considered and consists of the words that are similar to those from the second level. By considering the transitivity property of similarity, we compute a new similarity value between the root word (first level) and those found at the third level. This is done by adding the direct similarity scores and by normalizing the results. The final values range between 0 and 100."}
                ]
            };

            context = eval("context_" + locale);
            var theCompiledHtml = theTemplate(context);

            $('#main-container').append(theCompiledHtml);

            id = window.location.hash;

            if(id != ""){
            $('html,body').animate({
                scrollTop: $("li"+id).offset().top
            });

            $("li"+id).addClass("seleccionado");

            }
        });
    </script>

    <script id="info-template" type="text/x-handlebars-template">
            <header>
                <h1 class="titulo">{{titulo}}</h1>
            </header>

            <h2 class="container sub-titulo">
                {{subtitulo1}}
            </h2>
            {{#each textos}}
            <p class="container texto">
                {{this}}
            </p>
            {{/each}}

            <h2 class="container sub-titulo">
                {{subtitulo}}
            </h2>

            <p class="container texto">
                {{texto2}}
            </p>

            <ul class="container lista">
                {{#each tipos}}
                    <li id="{{this.id}}"><span class="espacio"></span><span class="name">{{{this.name}}}</span>: {{{this.text}}}</li>
                {{/each}}
            </ul>

    </script>


<?php
require_once("footer.php")
?>
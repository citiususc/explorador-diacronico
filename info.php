<?php
require_once("header.php")
?>

    <script>
        $(function () {
            var theTemplateScript = $("#info-template").html();

            var theTemplate = Handlebars.compile(theTemplateScript);

            var context_gl = {
                "titulo": "Información",
                "textos": ["Trátase dun sistema que permite buscar e visualizar os cambios léxicos de decenas de millares de palabras do castelán ao longo do tempo, concretamente no eixo temporal 1900-2009, usando como fonte de datos as representacións semánticas construídas cos n-gramas de Google en castelán (45 mil millóns). O usuario busca por unha palabra e un período de tempo (entre 1 e N anos) e o sistema devolve o sentido da palabra en cada ano do intervalo buscado. O sentido dunha palabra represéntase mediante o conxunto de palabras mais similares en termos semánticos e distribucionais. Por exemplo, a palabra “cáncer” relaciónase estreitamente en 1910 con “tuberculosis” e “sífilis” peroxa en 1960 os termos máis próximos son “tumor” e “carcinoma”.", "A entrada do sistema é unha estrutura de datos en que as palabras son asociadas mediante graos de similaridade (Coseno) con outras palabras e por ano. Estes datos foron xerados recentemente polo equipo PronLNat@GE (Pablo Gamallo, Marcos Garcia) mediante técnicas e módulos de Procesamento da Linguaxe Natural. Concretamente, efectuamos o procesamento semántico de 45 mil millóns de n-gramas, disponíbeis tras o escaneo de máis de 1 millón de libros do proxecto “Google Books”. O procesamento semántico consistiu en transformar os n-gramas en matrices distribucionais 'palabra-contexto'. Xerouse unha matriz por ano, onde cada palabra é un vector de contextos. Finalmente, calculouse a similaridade entre vectores (palabras) e seleccionáronse, para cada palabra, as 20 máis similares por ano. En total, xerouse unha estrutura de datos de máis de 300M, que é o input do demostrador."],
                "subtitulo1":"Qué es",
                "subtitulo":"Tipos de búsqueda",
                "texto2": "Actualmente, el explorador da soporte a cuatro tipos diferentes de búsqueda:",
                "tipos" : [
                    {"name":"Simple",
                        "id":"simple",
                        "text":"Con esta búsqueda se obtienen las 20 palabras con una similaridad media más alta en el período de años especificado para la palabra buscada. Los resultados se presentan en forma de un array compuesto por objetos, todos ellos constan de dos atributos. El valor de la similaridad, definido por <span class='mark'>y</span>, y el nombre de la palabra, definido por <span class='mark'>name</span>."},
                    {"name":"Pares",
                        "id":"pares",
                        "text":"Si hacemos una búsqueda para encontrar la similariad entre pares de palabras, es necesario introducir las dos palabras separadas por dos \"-\" consecutivos. El resultado de la consulta es un array conformado a su vez por diferentes arrays en los que el primer elemento es el año, mientras que el segundo es el nivel de similaridad entre el par de palabras en dicho año."},
                    {"name":"Histórico",
                        "id":"historico",
                        "text":"En este caso, obtendremos los datos de los índices de similaridad a través de los años para las palabras que se relacionan con el término buscado. Es similar al método de búsqueda simple, pero seleccionando todas las palabras en lugar de las 20 primeras y teniendo en cuenta el valor de similaridad en cada año en lugar de una media. Los datos son devueltos en formato JSON. Cada atributo corresponde con otro JSON, que cuenta con tres atributos, <span class='mark'>name</span>, indicando el nombre de la palabra, <span class='mark'>visible</span>, que valerá `true` o `false` en función de si la palabra está entre las tres con mayor índice de similaridad, y, por último, <span class='mark'>data</span>, que es otro array de objetos. Estes objetos denotan la similaridad en un año, y para ello tienen dos atributos, <span class='mark'>x</span>, que corresponde al año e <span class='mark'>y</span> que corresponde a la similaridad."},
                    {"name":"Nube",
                        "id":"nube",
                        "text":"Este tipo de búsqueda servirá para generar una imagen formada a partir de las palabras con mayor grado de similiaridad. Se recibe como resultado la ruta de ésta."},
                    {"name":"Transitiva",
                        "id":"transitiva",
                        "text":"Esta búsqueda se basa en la propiedad transitiva. Para clarificar esto imaginemos una estructura de árbol, en el primer nivel estaría la <span class='mark'>raíz</span>, es decir la palabra buscada, mientras que en el segundo nivel estarían las 20 palabras con un nivel de similaridad más alto. Por otro lado tendríamos un tercer nivel, formado por las palabras que son similares a las del segundo nivel. Usando la propiedad transitiva, la palabra del primer nivel tendría un grado de similariad con las palabras del 3 nivel, calculado mediante la suma de los grados de similariad y aplicando una normalización de los resultados; obteniendo así valores entre 0 y 100 para la similaridad transitiva."}
                ]
            };

            var context_es = {
                "titulo": "Información",
                "textos": ["Se trata de un sistema que permite buscar y visualizar los cambios léxicos de decenas de miles de palabras del castellano a lo largo del tiempo, concretamente en el eje temporal 1900-2009, utilizando como fuente de datos las representaciones semánticas construidas con los n-gramas de Google en español (45 mil millones). El usuario busca por una palabra y un período de tiempo (entre 1 y N años) y el sistema devuelve el sentido de la palabra en cada año del rango buscado. El sentido de una palabra se representa por el conjunto de palabras más similares en términos semánticos y distribucionales. Por ejemplo, la palabra “cáncer“ está estrechamente vinculada en 1910 con “tuberculosis“ y “sífilis“ pero ya en 1960 los términos más próximos son “tumor“ y “carcinoma“.","La entrada del sistema es una estructura de datos en la que las palabras están asociadas mediante grados de similaridad (Coseno) con otras palabras y por año. Estos datos fueron generados recientemente por el equipo PronLNat@GE (Pablo Gamallo, Marcos Garcia) a través de técnicas y módulos de Procesamiento del Lenguaje Natural. Específicamente, efectuamos el procesamiento semántico de 45 mil millones de n-gramas, disponibles después del escaneo de más de 1 millón de libros del proyecto “Google Books“. El procesamiento semántico consiste en transformar los n-gramas en matrices distribucionales 'palabra-contexto'. Se generó una matriz por año, donde cada palabra es un vector de contextos. Finalmente, se calcula la similaridad entre vectores (palabras) y se selecciona, para cada palabra, las 20 más similares por año. En total, se generó una estructura de datos de más de más de 300M, que es la entrada del demostrador."],
                "subtitulo1":"Qué es",
                "subtitulo":"Tipos de búsqueda",
                "texto2": "Actualmente, el explorador da soporte a cuatro tipos diferentes de búsqueda:",
                "tipos" : [
                    {"name":"Simple",
                        "id":"simple",
                        "text":"Con esta búsqueda se obtienen las 20 palabras con una similaridad media más alta en el período de años especificado para la palabra buscada. Los resultados se presentan en forma de un array compuesto por objetos, todos ellos constan de dos atributos. El valor de la similaridad, definido por <span class='mark'>y</span>, y el nombre de la palabra, definido por <span class='mark'>name</span>."},
                    {"name":"Pares",
                        "id":"pares",
                        "text":"Si hacemos una búsqueda para encontrar la similariad entre pares de palabras, es necesario introducir las dos palabras separadas por dos \"-\" consecutivos. El resultado de la consulta es un array conformado a su vez por diferentes arrays en los que el primer elemento es el año, mientras que el segundo es el nivel de similaridad entre el par de palabras en dicho año."},
                    {"name":"Histórico",
                        "id":"historico",
                        "text":"En este caso, obtendremos los datos de los índices de similaridad a través de los años para las palabras que se relacionan con el término buscado. Es similar al método de búsqueda simple, pero seleccionando todas las palabras en lugar de las 20 primeras y teniendo en cuenta el valor de similaridad en cada año en lugar de una media. Los datos son devueltos en formato JSON. Cada atributo corresponde con otro JSON, que cuenta con tres atributos, <span class='mark'>name</span>, indicando el nombre de la palabra, <span class='mark'>visible</span>, que valerá `true` o `false` en función de si la palabra está entre las tres con mayor índice de similaridad, y, por último, <span class='mark'>data</span>, que es otro array de objetos. Estes objetos denotan la similaridad en un año, y para ello tienen dos atributos, <span class='mark'>x</span>, que corresponde al año e <span class='mark'>y</span> que corresponde a la similaridad."},
                    {"name":"Nube",
                        "id":"nube",
                        "text":"Este tipo de búsqueda servirá para generar una imagen formada a partir de las palabras con mayor grado de similiaridad. Se recibe como resultado la ruta de ésta."},
                    {"name":"Transitiva",
                        "id":"transitiva",
                        "text":"Esta búsqueda se basa en la propiedad transitiva. Para clarificar esto imaginemos una estructura de árbol, en el primer nivel estaría la <span class='mark'>raíz</span>, es decir la palabra buscada, mientras que en el segundo nivel estarían las 20 palabras con un nivel de similaridad más alto. Por otro lado tendríamos un tercer nivel, formado por las palabras que son similares a las del segundo nivel. Usando la propiedad transitiva, la palabra del primer nivel tendría un grado de similariad con las palabras del 3 nivel, calculado mediante la suma de los grados de similariad y aplicando una normalización de los resultados; obteniendo así valores entre 0 y 100 para la similaridad transitiva."}
                ]

            };

            var context_en = {
                "titulo": "Information",
                "textos": ["This is a system that allows searching and visualizing lexical changes on douzens of thousands of Spanish words along the time, specifcally on the 1900-2009 range, using as data source semantic representations built with Google n-grams for Spanish (45 billion). The user searchs a word in time range (between 1 and N years) and the system returns the word sense for each year from the interval searched. The word's sense is represented by  the set of words that are the most similar in semantic and distributional terms. For example, the word “cancer“ was closely related in 1910 with “tuberculosis“ and “syphilis“ but in 1960 the closest words are “tumor“ and “carcinoma“.", "The system's input is a data structure which has words related to other words using similarity degrees (Cosine) and the year. This data was generated using Natural Language Processing techniques and modules. Specifcally, we made the semantic process for more than 45 billion n-grams, availables after scanning more than 1 million from the “Google Books“ project. The semantic process is about transforming the n-grams in distributional 'word-context'  matrix. A matrix was generated for each year, where each word is a context array. Finally, the similarity amongst arrays (words) was calculated and, for each word, were selected the 20 more similar by year. Total, a data structure which more than 300 million was generated, and this is the demonstrator input."],
                "subtitulo1":"Qué es",
                "subtitulo":"Tipos de búsqueda",
                "texto2": "Actualmente, el explorador da soporte a cuatro tipos diferentes de búsqueda:",
                "tipos" : [
                    {"name":"Simple",
                        "id":"simple",
                        "text":"Con esta búsqueda se obtienen las 20 palabras con una similaridad media más alta en el período de años especificado para la palabra buscada. Los resultados se presentan en forma de un array compuesto por objetos, todos ellos constan de dos atributos. El valor de la similaridad, definido por <span class='mark'>y</span>, y el nombre de la palabra, definido por <span class='mark'>name</span>."},
                    {"name":"Pares",
                        "id":"pares",
                        "text":"Si hacemos una búsqueda para encontrar la similariad entre pares de palabras, es necesario introducir las dos palabras separadas por dos \"-\" consecutivos. El resultado de la consulta es un array conformado a su vez por diferentes arrays en los que el primer elemento es el año, mientras que el segundo es el nivel de similaridad entre el par de palabras en dicho año."},
                    {"name":"Histórico",
                        "id":"historico",
                        "text":"En este caso, obtendremos los datos de los índices de similaridad a través de los años para las palabras que se relacionan con el término buscado. Es similar al método de búsqueda simple, pero seleccionando todas las palabras en lugar de las 20 primeras y teniendo en cuenta el valor de similaridad en cada año en lugar de una media. Los datos son devueltos en formato JSON. Cada atributo corresponde con otro JSON, que cuenta con tres atributos, <span class='mark'>name</span>, indicando el nombre de la palabra, <span class='mark'>visible</span>, que valerá `true` o `false` en función de si la palabra está entre las tres con mayor índice de similaridad, y, por último, <span class='mark'>data</span>, que es otro array de objetos. Estes objetos denotan la similaridad en un año, y para ello tienen dos atributos, <span class='mark'>x</span>, que corresponde al año e <span class='mark'>y</span> que corresponde a la similaridad."},
                    {"name":"Nube",
                        "id":"nube",
                        "text":"Este tipo de búsqueda servirá para generar una imagen formada a partir de las palabras con mayor grado de similiaridad. Se recibe como resultado la ruta de ésta."},
                    {"name":"Transitiva",
                        "id":"transitiva",
                        "text":"Esta búsqueda se basa en la propiedad transitiva. Para clarificar esto imaginemos una estructura de árbol, en el primer nivel estaría la <span class='mark'>raíz</span>, es decir la palabra buscada, mientras que en el segundo nivel estarían las 20 palabras con un nivel de similaridad más alto. Por otro lado tendríamos un tercer nivel, formado por las palabras que son similares a las del segundo nivel. Usando la propiedad transitiva, la palabra del primer nivel tendría un grado de similariad con las palabras del 3 nivel, calculado mediante la suma de los grados de similariad y aplicando una normalización de los resultados; obteniendo así valores entre 0 y 100 para la similaridad transitiva."}
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

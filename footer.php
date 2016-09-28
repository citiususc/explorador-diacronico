
<script>
    $(function () {
        var theTemplateScript = $("#foot-template").html();

        var theTemplate = Handlebars.compile(theTemplateScript);

        var context_es = {
            "buscador": "Explorador diacrónico"
        };

        var context_gl = context_es;

        var context_en = {
            "buscador": "Diachronic explorer"
        };

        context = eval("context_" + locale);
        var theCompiledHtml = theTemplate(context);

        $('body').append(theCompiledHtml);
    });
</script>

<script id="foot-template" type="text/x-handlebars-template">

    </section>
    <footer id="pie-container" class="grey lighten-4">
        <div class="row valign-wrapper container">
            <p id="copyright" class="col valign s12 m6">©<?= date("Y") ?> {{buscador}}. <a
                    href="http://gramatica.usc.es/pln" title="Grupo de investigación ProLNat@GE">ProLNat@GE</a>.</p>
            <a class="col valign s12 m6 logo right-align" href="http://citius.usc.es" title="web del CiTIUS">
                <!--<p>
                    Made with <i class="material-icons">loyalty</i> in
                </p>-->
                <figure class="right">
                    <img src="./images/logo_citius.png" alt="Logo CiTIUS"/>
                </figure>
            </a>
        </div>

    </footer>

    </div>
    </
</script>

</body>

</html>

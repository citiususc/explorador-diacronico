import cherrypy
import json
from random import randint
from pymongo import MongoClient
from operator import itemgetter, attrgetter, methodcaller
from Word import *
from functions import *
import os
import time
import sys
import urllib
from cherrypy.process.plugins import Monitor

# seleccion de la ruta raiz
d = sys.argv[1]

def removeFilesByFolderSize(path, limit):
    if size(path) > limit:
        for f in os.listdir(path):
            os.remove(os.path.join(path, f))

def cleanFilesBySize():
    folders = ["cache", "clouds"]

    for f in folders:
        path = d + f
        removeFilesByFolderSize(path, 500000000)

def consultaTransitivaBackground():
    client = MongoClient()
    db = client.mydb


    entries = {}

    # Se recupera la primera linea del archivo y se deja el resto.
    with open(d + "tasks", 'r') as fin:
        content = fin.readlines()

    with open(d + "tasks", 'w') as fout:
        fout.writelines(content[1:])

    if len(content) > 0:
        word, start, end = content[0].rstrip('\n').split(";")
        end = int(end)
        start = int(start)
        nombreFichero = word + "_" + str(start) + "_" + str(end) + ".json";

        if not os.path.exists(d + "cache/" + nombreFichero):
            # Consultamos los datos recibidos
            query = {"word": word, "year": {
                "$lte": end, "$gte": start
            }}
            cursor = db.correlations.find(query)

            count = 0
            for elem in cursor:
                cursor2 = db.correlations.find(
                    {"word": elem["word_related"], "year": {"$gte": start, "$lte": end}})

                for w2 in cursor2:
                    entries = processSimpleSearch(entries, w2, operacion="+")

                count += 1

            if len(entries) > 0:
                entries = formatearNombreValor(entries, int(end), int(start))

                max = float(entries[0]["y"])
                min = float(entries[-1]["y"])

                for e in entries:
                    e["y"] = normalizar(e["y"], max, min) * 100

                listaSinPalabra = []
                for s in entries:
                    try:
                        if s["name"].encode("utf-8") != word:
                            listaSinPalabra.append(s)
                    except AttributeError:
                        pass



                with open(d + "cache/" + nombreFichero, 'w') as outfile:
                    json.dump(listaSinPalabra[:20], outfile)



class Busca:
    "Recibe los parametros: Tipo de busqueda, palabra, anho inicio y anho de fin"
    exposed = True

    #Conexion con la BD
    client = MongoClient()

    # seleccion de la ruta raiz
    d = sys.argv[1]


    @cherrypy.tools.accept(media='text/plain')
    def GET(self, tipo=None, word=None, start=None, end=None):
        
        with open(self.d + "log", 'a') as outfile:
            outfile.write(str(cherrypy.request.remote.ip) + " ["+ time.strftime("%d/%m/%Y %H:%M:%S") + "]" + " - "+ tipo + " - " + word +" - " +start+ " - " + end + '\n')
            outfile.close()
        
        db = self.client.mydb
        year_start = 1899
        year_end = 2010

        entries = {}
        cursor = []
        first_results = []
        word2 = ""

        if tipo is not None and word is not None:
            word = word.lower()
            query = {"word": word, "year":{}}

#limpiamos los parametros de entrada
            if tipo == "pares":
                word, word2 = word.split("--")
                query['word'] = word

            if start == None or not isInt(start):
                start = year_start
            query['year']["$gte"] = int(start)

            if end == None or not isInt(end):
                end = year_end
            query['year']["$lte"] = int(end)


            nombreFoto = word + "_" + str(start) + "_" + str(end) + ".png";
            nombreFichero = word + "_" + str(start) + "_" + str(end) + ".json";

#en funcion del tipo de busqueda se decide:

            #Si el tipo es transitiva y ya existe un archivo creado con los resultados, se devuelve dicho archivo
            if tipo == "transitiva" and os.path.exists(self.d + "cache/" + nombreFichero):
                ruta = self.d + "cache/" + nombreFichero;
                with open(ruta) as json_data:
                    entries = json.load(json_data)
                entries = json.dumps(entries)

            #Si el tipo es transitiva, pero no existe un archivo de resultados, se anade la tarea al archivo.
            elif tipo == "transitiva" and not os.path.exists(self.d + "cache/" + nombreFichero):
                cursor = db.correlations.find(query)


                if cursor.count() > 0:
                    with open(self.d+"tasks", 'a') as outfile:
                        outfile.write(word+";"+start+";"+end+"\n")

                    entries = json.dumps({"waiting": 1})
                else:
                    entries = json.dumps({})

            #En caso de cualquier otro tipo de busqueda, se consulta en el mismo proceso
            else:
                cursor = db.correlations.find(query)

                #formateamos cada documento recuperado de la BD
                count = 0

                for elem in cursor:
                    if tipo == "simple" or tipo == "nube" and not os.path.isfile(self.d + "clouds/" + nombreFoto):
                        entries = processSimpleSearch(entries, elem)
                    elif tipo == "maximo-anual":
                        entries = processMaxObject(entries, elem)
                    elif tipo == "historico" or tipo == "pares":
                        word = { "word_related": elem["word_related"], "year": elem["year"],
                                    "correlation": elem["correlation"], "word":elem["word"]}

                        if word["year"] not in entries:
                            entries[word["year"]] = [word]
                        else:
                            entries[word["year"]].append(word)


                    count += 1

                #formateamos el conjunto de los resultados
                if tipo == "simple":
                    entries = formatearNombreValor(entries, int(end), int(start))[:20]

                elif tipo == "historico" or tipo == "pares":
                    entriesAux = {}

                    for y in entries:
                        entriesAux[str(y)] = normalizarHistorico(entries[y])
                    entries = {}

                    for y in entriesAux.keys():
                        for palabra in entriesAux[y]:
                            if palabra["word_related"] in entries.keys():
                                entries[palabra["word_related"]]["data"].append({"y": palabra["correlation"], "x": int(y)})
                            else:
                                entries[palabra["word_related"]] = {"name": palabra["word_related"],
                                                                 "data": [{"y": palabra["correlation"], "x": int(y)}],
                                                                 "visible": False}

                    if tipo == "historico":
                        for palabra in entries.keys():
                            entries[palabra]['data'].sort(key=lambda x: x["x"], reverse=False)

                        if len(entries) >0:
                            url = "http://localhost:8080/busca/simple/"+word["word"].encode("utf-8")+"/"+ start + "/"+ end
                            response = urllib.urlopen(url)
                            data = json.loads(response.read())
                            data = data[:3]

                            for d in data:
                                entries[d["name"]]["visible"] = True

                    else:
                        try:
                            entries = entries[word2.decode("utf-8")] 
                            listaFormateada = []

                            for e in entries["data"]:
                                listaFormateada.append((e["x"], e["y"]))

                            entries = sorted(listaFormateada, key=lambda x: x[0], reverse=False)
                        except KeyError:
                            entries = {}



                elif tipo == "nube":
                    if len(entries) > 0 or os.path.isfile(self.d + "clouds/" + nombreFoto):
                        if not os.path.isfile(self.d + "clouds/" + nombreFoto):
                            newCloud(entries, nombreFoto, self.d + "clouds/")

                        entries = {}
                        entries[0] = "clouds/" + nombreFoto
                    else:
                        entries[0] = ""



                entries = json.dumps(entries)

        self.client.close()
        return (entries)


if __name__ == '__main__':
    def CORS():
        cherrypy.response.timeout = 6000
        cherrypy.response.headers["Access-Control-Allow-Origin"] = "*"
        cherrypy.response.headers["Access-Control-Allow-Methods"] = "GET, POST, HEAD, PUT, DELETE"
        cherrypy.response.headers[
            "Access-Control-Allow-Headers"] = "Cache-Control, X-Proxy-Authorization, X-Requested-With"
        cherrypy.response.headers["Access-Control-Max-Age"] = "604800"


    cherrypy.tools.CORS = cherrypy.Tool('before_handler', CORS)

    cherrypy.tree.mount(
        Busca(), '/busca',
        config={
            '/': {
                'request.dispatch': cherrypy.dispatch.MethodDispatcher(),
                'tools.response_headers.on': True,
                'tools.CORS.on': True,
                'tools.response_headers.headers': [('Content-Type', 'text/plain')],
            }
        }
    )

    cherrypy.config.update(
        {'server.socket_host': '0.0.0.0'})

    Monitor(cherrypy.engine, consultaTransitivaBackground, frequency=1).subscribe()
    Monitor(cherrypy.engine, cleanFilesBySize, frequency=10000).subscribe()

    cherrypy.engine.start()
    cherrypy.engine.block()

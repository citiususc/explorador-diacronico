import os
from os import path
import matplotlib.pyplot as plt
from wordcloud import WordCloud, STOPWORDS

def isInt(s):
    try:
        int(s)
        return True
    except ValueError:
        return False

#Extrae del objeto inicial la informacion pertinente
def extractObj(elem):
    obj = {}
    obj["word_related"] = elem["word_related"]
    obj["correlation"] = elem["correlation"]

    return obj

#inserta en el diccionario un elemento usando como clace la palabra con la que se relaciona, si ya existe se usara la
#operacion aritmetica que corresponda para manejar la similaridad asociada
def processSimpleSearch(entries, elem, *args, **kwargs):
    obj = extractObj(elem)
    operacion = kwargs.get("operacion", None)

    try:
        if operacion is None or operacion != "*":
            entries[elem["word_related"]] = entries[elem["word_related"]] + obj["correlation"]
        else:
            entries[elem["word_related"]] = entries[elem["word_related"]] * obj["correlation"]
    except Exception:
        entries[elem["word_related"]] = obj["correlation"]

    return entries

#Inserta en el diccionario un elemento usando como clabe el ano
def processPairSearch(entries, elem):
    entries[elem["year"]] = extractObj(elem)
    return entries

#Comprueba si existe un indice en un diccionario
def indexExist(set, index):
    try:
        t = set[index]
    except Exception:
        return 0

    return 1

#Inserta en el diccionario usando como clave el ano. Por cada ano se guarda el elemento de mayor similaridad
def processMaxObject(entries, elem):
    obj = extractObj(elem)

    if not indexExist(entries, elem["year"]) or entries[elem["year"]]["correlation"] < obj["correlation"] :
        entries[elem["year"]] = (obj)

    return entries

#Genera una nube de palabras a partir de una lista de palabras con pesos
def newCloud(palabras, nombre, d):
    text = palabras.items()
    wc = WordCloud(background_color="white", width=1240, height=720)

    wc.generate_from_frequencies(text)
    wc.to_file(path.join(d, nombre))

#Dado un valor, lo normaliza en funcion de un maximo y un minimo
def normalizar(elemento, max, min):
    return (elemento-min)/(max-min)

#Normaliza la correlacion en funcion de los elementos que haya. La similaridad mas alta sera el 100
def normalizarHistorico(lista):
    count = 0
    size = len(lista)

    lista.sort(key=lambda x: x["correlation"], reverse=True)


    for l in lista:
        l["correlation"] = (float(size - count) / float(size)) * 100

        count = 1 + count

    return lista

#Formatea un diccionario {nombre:palabra} al formato highcharts haciendo la media
def formatearNombreValor(entries, yearLt, yearGt):
    factor = 100
    wordDict = entries
    entries = []

    for k, w in wordDict.iteritems():

        try:
            entries.append(
                {
                    "name": k,
                    "y": float(w / (yearLt - yearGt + 1)) * factor
                })
        except ZeroDivisionError:
            entries.append({"name": k, "y": float(w) * factor})

    return sorted(entries, key=lambda x: x['y'], reverse=True)

#Devuelve el tamano de los archivos de una carpeta en KB
def size(start_path = '.'):
    total_size = 0
    for dirpath, dirnames, filenames in os.walk(start_path):
        for f in filenames:
            fp = os.path.join(dirpath, f)
            total_size += os.path.getsize(fp)
    return total_size

#ordena archivos por tiempo dando los mas antiguos primero
def listdirTime(path):
    mtime = lambda f: os.stat(os.path.join(path, f)).st_mtime
    return list(sorted(os.listdir(path), key=mtime))
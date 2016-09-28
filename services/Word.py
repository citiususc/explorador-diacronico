class Word:
    'Common base class for all words'

    def __init__(self, word, word_rel, year, corr):
        self.word = word
        self.word_related = word_rel
        self.year = year
        self.correlation = corr

    def getWord(self):
        return self.word

    def getWord_rel(self):
        return self.word_rel

    def getYear(self):
        return self.year

    def getCorr(self):
        return self.corr
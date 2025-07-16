# istoreco.fc
Pannello di amministrazione

---

## Testo e foto introduttivi
Il testo e la foto visualizzate nella pagine principale sono quelle dell'articolo con `id testuale` **presentazione** ([link](#article/edit/3)).
Il testo visualizzato è quello del sommario e l'immagine è quella dell'articolo.

## Servizi
La lista dei **servizi** nella pagina principale è controllata dal menu `servizi` ([link](#menu/all)).

Per ogni elemento è richiesto:

- un breve testo da visualizzare sotto l'icona
- l'`id testuale` all'articolo di riferimento
- e come titolo il nome dell'icona da visualizzare (<a href="http://fontawesome.io/icons" target="_blank">Lista delle icone disponibili</a>)

Inoltre, per ogni servizio è stato creato un `tag` apposito, per creare delle pagine di riassunto. Per ogni servizio esiste un articolo indice ([lista degli aricoli indice di servizi](#article/all/servizi)) e una serie di articoli di sezione.

I tag creati sono i seguenti:
- `archivi` ([lista degli articoli](#article/all/archivi), [articolo introduttivo](#article/edit/9))
- `biblioteca` ([lista degli articoli](#article/all/biblioteca), [articolo introduttivo](#article/edit/6))
- `didatticadigitale` ([lista degli articoli](#article/all/didatticadigitale), [articolo introduttivo](#article/edit/15))
- `documentazione` ([lista degli articoli](#article/all/documentazione), [articolo introduttivo](#article/edit/14))
- `formazione` ([lista degli articoli](#article/all/formazione), [articolo introduttivo](#article/edit/20))
- `fotografia` ([lista degli articoli](#article/all/fotografia), [articolo introduttivo](#article/edit/21))
- `memoria` ([lista degli articoli](#article/all/memoria), [articolo introduttivo](#article/edit/13))
- `mostre` ([lista degli articoli](#article/all/mostre), [articolo introduttivo](#article/edit/22))
- `ricerca` ([lista degli articoli](#article/all/ricerca), [articolo introduttivo](#article/edit/23))

## Ultimi eventi e novità
In questa sezione vengono visualizzati e pubblicati i primi quattro articoli taggati con il tag `news` ([lista](#article/all/news)).
I primi quattro significa i quattro più recenti o i quattro con ordinamento più alto, se è stato modificato manualmente l'ordine degli articoli.

## Search Engine Optimization (SEO)
È molto importante che ogni risorsa, ogni URL sia ottimizzata per i motori di ricerca.
Per ogni articolo, quindi, decono essere compilati i campi `Sommario` e `Keywords`, fondamentali per la corretta indicizzazione della risorsa.
Inotre, di seguito alcune buone norme:
- Il titolo dell'articolo deve essere significativo dei contenuti del'articolo stesso. Le parole usate nel titolo **è consiglieto** che siano presenti (anche più volte) nel corpo di testo dell'articolo
- La stessa regola vale anche per le parole chiave, anche se **meno importanti** del titolo.
- L'Id testuale dell'articolo deve essere il più possibile parlante e rispecchiare i contenuti, senza però che diventi troppo lungo. **Non devono essere usati caratteri speciali (es. lettere accentate)**.

**Le URL delle liste di articoli**, quelle che di norma finiscono in `.all` possono anch'esse essere arrichite di metadati utili per la corretta indicizzazione nei motori di ricerca. Per questo esiste un plugin apposito, chiamato `Gestione SEO`, raggiungibile da `Menu principale` > `Plugin` > `Gestione SEO` o direttamente [da questo link](#seo/all).
Il funzinamento di questo plugin è spiegato in dettaglio nella [pagina apposita della documentazione](#docs/read/seo) (solo in inglese).

## Collegamenti, risorse, gallerie
Per creare un collegamento (link) ad una risorsa interna (URL) del sito è consigliato **non** usare l'icona integrata nel sistema dell'editor integrato di testo, ma di usare il sistema dei cosidetti `customtag` del CMS ([maggiori informazioni sui customtag](#docs/read/customtags), in inglese.
Nel caso specifico il `customtag` da usare è quello chiamato `link`.

### Perché non usare i link diretti per le risorse interne?
Se si usano i link diretti (assoluti, che comprendono la stringa `http://` e il nome del dominio) tutta la stringa verrà salvata nella banca dati. In caso di spostamento del sito, del cambio di dominio o cambio di parti di dominio (alcuni host, per esempio impongono l'uso del `www` in altri è possibile disattivarlo) o in caso dell'aggiunta di una nuova lingua oltre a quella standard, tutti questi collegamente smetteranno di funzionare. Il customtag `link`, invece, crea il collegamento all'articolo o al (ai) tag al momento della richiesta, tenendo presente tutte le variabili del sistema. Il collegamento in questo caso sarà sempre garantito.

Lo stesso discordo vale anche per altre risorse quali immagini. È consigliato sempre fornire l'immagine di articolo per ogni articolo. In caso di altre immagini nel corpo dell'articolo è consigliato caricarle come `File dell'articolo` e usare il customtag `fig` per includerle nel corpo di testo. Nell'attributo `path` del customtag `fig` è consigliato usare **solo il nome del file compresi di estensione** e non altre indicazioni relative al percorso.


È consigliato il ricorso ai custom tag anche per le risorse da scaricare. In questo caso, oltre al creare al volo e in modo corretto il percorso al file, il custom tag `dwnl` ([info](#docs/read/customtags)) permette anche di tenere un conteggio degli scaricamenti. Ogni scaricamento verrà registrato e sarà possibile monitorare il tutto usando il plugin `Scaricamenti`, raggiungibile dal `Menu principale` > `Plugin` > `Scaricamenti`. La documentazione di questa nuova funzione (disponibile dalla v. 3.14.31) sarà presto disponibile [qui](#docs/read/downloadcount).

Anche per le gallerie fotografiche è consigliato l'uso della funzione apposita (`Menu principale` > `Media` > `Gallerie`, [link diretto](#galleries/all)) e del relativo custom tag: `gallery` ([info](docs/read/customtags)).

---
Le [sezione della documentazione](#docs/read/index) contiene informazioni utili, in inglese, sul funzionamento del CMS.

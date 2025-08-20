<?php
class DatabaseHelper{
    private $db;

    public function __construct(){

        $host = getenv('DB_HOST') ?: 'localhost';
        $user = getenv('DB_USER') ?: 'root';
        $password = getenv('DB_PASSWORD') ?: '';
        $database = getenv('DB_DATABASE') ?: 'databaseistitutoperprove';

        $this->db = new mysqli($host, $user, $password, $database);
        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }        
    }

    /**
     * Verifica l'esistenza dell'amministratore che sta cercando di effettuare il login.
     * @param string $adminEmail E-Mail da verificare.
     * @return string[] Array contentente E-Mail e password dell'admin (se presente)
     */
    public function checkLogin($adminEmail) {
        $stmt = $this->db->prepare("SELECT adminEmail, password FROM administrators WHERE adminEmail = ?");
        $stmt->bind_param('s', $adminEmail);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    private function isPageA($table, $pageID) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM $table WHERE Page_idPage = ?");
        $stmt->bind_param('i', $pageID);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_row()[0] > 0;
    }

    private function isPageAnArchive($pageID) {
        return $this->isPageA("archivepage", $pageID);
    }

    private function isPageAResourceCollector($pageID) {
        return $this->isPageA("raccoltadirisorse", $pageID);
    }

    private function getPageType($pageID) {
        return $this->isPageAnArchive($pageID) 
                ? "Pagina di Archivio" 
                : ($this->isPageAResourceCollector($pageID)
                    ? "Raccolta di Risorse" 
                    : ""); 
    }

    /**
     * Restituisce tutte le pagine presenti, ordinate secondo un determinato criterio.
     * @param string $orderBy Specifica per quale attributo devono essere ordinate le pagine. Il valore di default è "nessuno" (utilizzato per non effettuare un ordinamento specifico).
     * @return array{ idPage: int, title: string, creationDate: string, updatedDate: string, type: string }[]
     * Array contenente le pagine presenti. Ognuno degli elementi contiene:
     * - idPage: ID della pagina
     * - title: titolo della pagina
     * - creationDate: data di creazione
     * - updatedDate: data dell'ultima modifica
     * - type: tipo della pagina
     */
    public function getPages($orderBy = "nessuno") {
        $orderByString = "";
        switch ($orderBy) {
            case "titolo":
                $orderByString = " ORDER BY title";
                break;
            case "titoloDesc":
                $orderByString = " ORDER BY title DESC";
                break;
            case "ultimaModifica":
                $orderByString = " ORDER BY updatedDate";
                break;
            case "ultimaModificaDesc":
                $orderByString = " ORDER BY updatedDate DESC";
                break;
        }
        $stmt = $this->db->prepare("SELECT idPage, title, creationDate, updatedDate FROM page" . $orderByString);
        $stmt->execute();
        $result = $stmt->get_result();
        $pages = $result->fetch_all(MYSQLI_ASSOC);

        foreach ($pages as &$page) {
            $page['type'] = $this->getPageType($page['idPage']);
        }
        unset($page);

        return $pages;
    }

    /**
     * In base al tipo di contenuto, viene restituita la query che verrà utilizzata per filtrare gli elementi appartenenti a quest'ultimo.
     * @param string $content Tipo di contenuto da filtrare.
     * @return string Query di ricerca appropriata.
     */
    private function getResearchQuery($content) {
        $concatString = "LIKE CONCAT('%', ?, '%')";
        switch ($content) {
            case "pagine":
                return "SELECT idPage, title, creationDate, updatedDate FROM page WHERE title " . $concatString;
            case "menù":
                return "SELECT idMenu as ID, menuName as name FROM menu WHERE menuName " . $concatString;
            case "tag":
                return "SELECT idTag as ID, tagName as name FROM tag WHERE tagName " . $concatString;
            case "articoli d'inventario":
                return "SELECT idInventoryItem as ID, inventoryItemName as name FROM inventoryitem WHERE inventoryItemName " . $concatString;
            case "strumenti di corredo":
                return "SELECT idReferenceTool as ID, nameReferenceTool as name FROM referencetool WHERE nameReferenceTool " . $concatString;
        }
    }

    /**
     * Restituisce gli elementi appartenenti a uno specifico tipo di contenuto, filtrati per una stringa di ricerca.
     * @param string $content Tipo di contenuto da filtrare.
     * @param string $researchString Stringa di ricerca.
     * @return array<string, mixed>[] Array degli elementi filtrati appartenenti al tipo di contenuto specificato.
     */
    public function getContentByName($content, $researchString) {
        $query = $this->getResearchQuery($content);
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $researchString);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($content != "pagine") {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        $pages = $result->fetch_all(MYSQLI_ASSOC);
        foreach ($pages as &$page) {
            $page['type'] = $this->getPageType($page['idPage']);
        }
        unset($page);
        return $pages;
    }

    /**
     * Funzione template per recuperare gli elementi appartenenti a un tipo di contenuti diverso dalle pagine.
     * @param string $table Tabella su cui si esegue la ricerca nel database.
     * @param int $idField Nome del campo nella tabella che rappresenta l'ID dell'elemento.
     * @param int $nameField Nome del campo nella tabella che rappresenta il nome dell'elemento.
     * @param string $orderBy Criterio di ordinamento degli elementi.
     * @return array{ ID: int, name: string }[]
     * Array contenente gli elementi richiesti, ognuno dei quali contiene:
     * - ID: ID del contenuto
     * - name: nome del contenuto
     */
    private function getNonPages($table, $idField, $nameField, $orderBy) {
        $orderByString = "";
        switch ($orderBy) {
            case "nome":
                $orderByString = " ORDER BY name";
                break;
            case "nomeDesc":
                $orderByString = " ORDER BY name DESC";
                break;
        }
        $stmt = $this->db->prepare("SELECT $idField as ID, $nameField as name FROM $table" . $orderByString);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Restituisce tutti i menù presenti, eventualmente ordinati secondo uno specifico criterio.
     * @param string $orderBy Criterio di ordinamento.
     * @return array{ ID: int, name: string }[]
     * Array contenente i menù presenti, ognuno dei quali contiene:
     * - ID: ID del menù
     * - name: nome del menù
     */
    public function getMenus($orderBy = "nessuno") {
        return $this->getNonPages("menu", "idMenu", "menuName", $orderBy);
    }

    /**
     * Restituisce le voci del menù principale del sito (ovvero quelle che non hanno un padre all'interno della struttura gerarchica).
     * @return array{ ID: int, name: string }[]
     * Array contenente le voci del menù principale, ognuna delle quali contiene:
     * - ID: ID della voce
     * - name: nome della voce
     */
    public function getPrimaryMenu() {
        $stmt = $this->db->prepare("SELECT idMenuItem as ID, menuItemName as name FROM menuitem WHERE Menu_idMenu = 1 AND MenuItem_idMenuItem is NULL ORDER BY menuItemOrderedPosition");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Restituisce le voci di un menù figlie della voce specificata come parametro.
     * @param int $fatherID ID della voce madre.
     * @return array{ ID: int, name: string, slug: string }[]
     * Array contenente le voci del menù figlie della voce specificata, ognuna delle quali contiene:
     * - ID: ID della voce
     * - name: nome della voce
     * - slug: slug della pagina a cui punta la voce (può essere anche null nel caso sia madre di altre voci)
     */
    public function getMenuItemsByFather($fatherID) {
        $stmt = $this->db->prepare("SELECT idMenuItem as ID, menuItemName as name, slug FROM menuitem LEFT JOIN page ON Page_idPage = idPage WHERE MenuItem_idMenuItem = ? ORDER BY menuItemOrderedPosition");
        $stmt->bind_param('i', $fatherID);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Restituisce tutti i tag presenti, eventualmente ordinati secondo uno specifico criterio.
     * @param string $orderBy Criterio di ordinamento.
     * @return array{ ID: int, name: string }[]
     * Array contenente i tag presenti, ognuno dei quali contiene:
     * - ID: ID del tag
     * - name: nome del tag
     */
    public function getTags($orderBy = "nessuno") {
        return $this->getNonPages("tag", "idTag", "tagName", $orderBy);
    }

    /**
     * Funzione template che restituisce il numero di elementi presenti in una tabella relativa a un tipo di contenuto.
     * @param string $table Nome della tabella su cui effettuare il conteggio.
     * @return int Numero degli elementi presenti nella tabella.
     */
    private function getNumOfContents($table) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM $table");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_row()[0];
    }

    /**
     * Restituisce quanti tag sono presenti al momento della richiesta.
     * @return int Il numero di tag presenti.
     */
    public function getNumOfTags() {
        return $this->getNumOfContents("tag");
    }

    /**
     * Restituisce tutti gli articoli d'inventario presenti, eventualmente ordinati secondo uno specifico criterio.
     * @param string $orderBy Criterio di ordinamento.
     * @return array{ ID: int, name: string }[]
     * Array contenente gli articoli d'inventario presenti, ognuno dei quali contiene:
     * - ID: ID dell'articolo
     * - name: nome dell'articolo
     */
    public function getInventoryItems($orderBy = "nessuno") {
        return $this->getNonPages("inventoryitem", "idInventoryItem", "inventoryItemName", $orderBy);
    }

    /**
     * Restituisce quanti articoli d'inventario sono presenti al momento della richiesta.
     * @return int Il numero di articoli presenti.
     */
    public function getNumOfInventoryItems() {
        return $this->getNumOfContents("inventoryitem");
    }

    /**
     * Restituisce tutti gli strumenti di corredo presenti, eventualmente ordinati secondo uno specifico criterio.
     * @param string $orderBy Criterio di ordinamento.
     * @return array{ ID: int, name: string }[]
     * Array contenente gli strumenti di corredo presenti, ognuno dei quali contiene:
     * - ID: ID dello strumento
     * - name: nome dello strumento
     */
    public function getReferenceTools($orderBy = "nessuno") {
        return $this->getNonPages("referencetool", "idReferenceTool", "nameReferenceTool", $orderBy);
    }

    /**
     * Restituisce quanti strumenti di corredo sono presenti al momento della richiesta.
     * @return int Il numero di strumenti presenti.
     */
    public function getNumOfReferenceTools() {
        return $this->getNumOfContents("referencetool");
    }

    /**
     * Restituisce le informazioni su una pagina dato il suo ID (funzione utile agli amministratori).
     * @param int $pageID ID della pagina.
     * @return array{ title: string, slug: string, text: string|null, isVisibile: boolean, seoTitle: string|null, seoText: string|null, seoKeywords: string|null, author: string|null, type: string }
     * Array contenente le informazioni sulla pagina richiesta:
     * - title: titolo della pagina
     * - slug: slug della pagina
     * - text: contenuto HTML della pagina
     * - isVisibile: flag che rappresenta la visibilità della pagina
     * - seoTitle: titolo della pagina per il Search Of Engine
     * - seoText: testo per il Search Of Engine
     * - seoKeywords: parole chiave per il Search Of Engine
     * - author: autore della pagina
     * - type: tipo della pagina
     */
    public function getPageFromID($pageID) {
        $stmt = $this->db->prepare("SELECT title, slug, text, isVisibile, seoTitle, seoText, seoKeywords, author FROM page WHERE idPage = ?");
        $stmt->bind_param('i', $pageID);
        $stmt->execute();
        $result = $stmt->get_result();
        $page = $result->fetch_assoc();
        $page['type'] = $this->getPageType($pageID);
        return $page;
    }

    /**
     * Restituisce le informazioni su una pagina dato il suo slug (funzione utile alla navigazione di utente normale).
     * @param string $pageSlug Slug della pagina.
     * @return array{ title: string, slug: string, text: string|null, isVisibile: boolean, author: string|null }
     * Array contenente le informazioni sulla pagina richiesta:
     * - title: titolo della pagina
     * - slug: slug della pagina
     * - text: contenuto HTML della pagina
     * - isVisibile: flag che rappresenta la visibilità della pagina
     * - author: autore della pagina
     */
    public function getPageFromSlug($pageSlug) {
        $stmt = $this->db->prepare("SELECT title, slug, text, isVisibile, author FROM page WHERE slug = ?");
        $stmt->bind_param('s', $pageSlug);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Restituisce tutti i tag posseduti da una specifica pagina.
     * @param int $pageID ID della pagina di cui si vogliono ottenere i tag.
     * @return int[] Array contenente tutti gli ID dei tag della pagina.
     */
    public function getTagsFromPageID($pageID) {
        $stmt = $this->db->prepare("SELECT tag_idTag as tagId FROM page_has_tag WHERE page_idPage = ?");
        $stmt->bind_param('i', $pageID);
        $stmt->execute();
        $result = $stmt->get_result();
        $tagsIds = [];
        while ($row = $result->fetch_assoc()) {
            $tagsIds[] = $row['tagId'];
        }
        return $tagsIds;
    }

    /**
     * Restituisce tutti i tag posseduti dalle pagine contenute nella pagina specificata.
     * @param int $containerID ID della pagina contenitrice.
     * @return int[] Array contenente tutti gli ID dei tag delle pagine contenute.     
    */
    public function getContainedPagesTagsFromContainerID($containerID) {
        $stmt = $this->db->prepare("SELECT tag_idTag as tagId FROM page_displays_pages_of_tag WHERE page_idPage = ?");
        $stmt->bind_param('i', $containerID);
        $stmt->execute();
        $result = $stmt->get_result();
        $tagsIds = [];
        while ($row = $result->fetch_assoc()) {
            $tagsIds[] = $row['tagId'];
        }
        return $tagsIds;
    }

    /**
     * Restituisce tutte le voci dell'indice contenuto nella pagina specificata.
     * @param int $pageID ID della pagina.
     * @return array{ ID: int, title: string, position: int }[]
     * Array contenente le voci dell'indice contenuto nella pagina richiesta, ognuna delle quali ha:
     * - ID: ID della voce
     * - title: titolo della voce
     * - position: posizione della voce all'interno dell'indice
    */
    public function getIndexItemsFromPageID($pageID) {
        $stmt = $this->db->prepare("SELECT idIndexItem as ID, indexItemTitle as title, orderedPosition as position FROM indexitem WHERE shownInPageId = ?");
        $stmt->bind_param('i', $pageID);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Restituisce tutte le note contenute nella pagina specificata.
     * @param int $pageID ID della pagina.
     * @return array{ ID: int, text: string }[]
     * Array contenente le note contenute nella pagina richiesta, ognuna delle quali ha:
     * - ID: ID della nota
     * - text: contenuto della nota
    */
    public function getNotesFromPageID($pageID) {
        $stmt = $this->db->prepare("SELECT noteId as ID, noteText as text FROM note WHERE page_idPage = ?");
        $stmt->bind_param('i', $pageID);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Restituisce le informazioni su una voce dell'indice, dato il suo ID.
     * @param int $indexItemID ID della voce.
     * @return array{ position: int, anchor: string|null, page: int|null, title: string }
     * Array contenente le informazioni sulla voce richiesta:
     * - position: posizione della voce all'interno dell'indice in cui è contenuta
     * - anchor: ancora alla quale si lega la voce
     * - page: pagina alla quale si lega la voce
     * - title: titolo della voce
     */
    public function getIndexItemFromID($indexItemID) {
        $stmt = $this->db->prepare("SELECT orderedPosition as position, targetAnchor as anchor, linkToPage as page, indexItemTitle as title FROM indexitem WHERE idIndexItem = ?");
        $stmt->bind_param('i', $indexItemID);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);  
    }

    /**
     * Restituisce le informazioni su una nota, dato il suo ID.
     * @param int $noteID ID della nota.
     * @return array{ text: string, anchor: string|null, page: int, author: string|null }
     * Array contenente le informazioni sulla nota richiesta:
     * - text: contenuto della nota
     * - anchor: ancora alla quale si lega la nota
     * - page: pagina nella quale è contenuta la nota
     * - author: autore della nota
     */
    public function getNoteFromID($noteID) {
        $stmt = $this->db->prepare("SELECT noteText as text, noteAnchor as anchor, page_idPage as page, author FROM note WHERE noteId = ?");
        $stmt->bind_param('i', $noteID);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);  
    }

    /**
     * Restituisce le informazioni su una pagina d'archivio, dato il suo ID.
     * @param int $pageID ID della pagina.
     * @return array{ start: int, end: int }
     * Array contenente le informazioni sulla pagina d'archivio richiesta:
     * - start: data d'inizio della cronologia
     * - end: data di fine della cronologia
     */
    public function getArchivePageFromPageID($pageID) {
        $stmt = $this->db->prepare("SELECT chronologyStartYear as start, chronologyEndYear as end FROM archivepage WHERE Page_idPage = ?");
        $stmt->bind_param('i', $pageID);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Restituisce tutti gli strumenti di corredo posseduti dalla pagina d'archivio specificata.
     * @param int $archivePageID ID della pagina.
     * @return int[] Array contenente tutti gli ID degli strumenti di corredo posseduti dalla pagina.     
    */
    public function getReferenceToolsFromArchivePageID($archivePageID) {
        $stmt = $this->db->prepare("SELECT ReferenceTool_idReferenceTool as rToolId FROM archivepage_has_referencetool WHERE ArchivePage_Page_idPage = ?");
        $stmt->bind_param('i', $archivePageID);
        $stmt->execute();
        $result = $stmt->get_result();
        $refToolsIds = [];
        while ($row = $result->fetch_assoc()) {
            $refToolsIds[] = $row['rToolId'];
        }
        return $refToolsIds;
    }

    /**
     * Restituisce tutti gli articoli d'inventario posseduti dalla pagina d'archivio specificata.
     * @param int $archivePageID ID della pagina.
     * @return array{ ID: int, quantity: int }[]
     * Array contenente gli articoli della pagina d'archivio, ognuno dei quali contiene:
     * - ID: ID dell'articolo
     * - quantity: quantità presente nell'archivio     
    */
    public function getInventoryItemsFromArchivePageID($archivePageID) {
        $stmt = $this->db->prepare("SELECT inventoryItem_idInventoryItem as invItemId, itemQuantity FROM archivepage_has_inventoryitem WHERE ArchivePage_Page_idPage = ?");
        $stmt->bind_param('i', $archivePageID);
        $stmt->execute();
        $result = $stmt->get_result();
        $invItemsIds = [];
        while ($row = $result->fetch_assoc()) {
            $invItemsIds[] = [
                'ID' => $row['invItemId'],
                'quantity' => $row['itemQuantity']
            ];
        }
        return $invItemsIds;
    }

    /**
     * Restituisce tutte le collezioni di risorse contenute nella pagina specificata.
     * @param int $pageID ID della pagina.
     * @return array{ nome: string, ID: int }[]
     * Array contenente le collezioni della pagina, ognuna della quale contiene:
     * - nome: nome della collezione
     * - ID: ID della collezione
    */
    public function getResourceCollectionsFromPageID($pageID) {
        $stmt = $this->db->prepare("SELECT nomeRaccolta as nome, idRaccoltaDiRisorse as ID FROM raccoltadirisorse WHERE Page_idPage = ?");
        $stmt->bind_param('i', $pageID);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Restituisce le informazioni su una collezione di risorse, dato il suo ID.
     * @param int $resourceCollectionID ID della collezione.
     * @return array{ nome: string, pathRaccolta: string|null }
     * Array contenente le informazioni sulla collezione richiesta:
     * - nome: nome della collezione
     * - pathRaccolta: percorso della directory dove è contenuta la collezione
     */
    public function getResourceCollectionFromID($resourceCollectionID) {
        $stmt = $this->db->prepare("SELECT nomeRaccolta as nome, pathRaccolta as path FROM raccoltadirisorse WHERE idRaccoltaDiRisorse = ?");
        $stmt->bind_param('i', $resourceCollectionID);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Restituisce gli elementi di bibliografia di una collezione di risorse, dato il suo ID.
     * @param int $collectionID ID della collezione.
     * @return array{ ID: int, cit: string }[]
     * Array contenente gli elementi di bibliografia della collezione richiesta, ognuno dei quali contiene:
     * - ID: ID dell'elemento
     * - cit: citazione bibliografica dell'elemento
     */
    public function getBibliographyElementsFromCollectionID($collectionID) {
        $stmt = $this->db->prepare("SELECT b.elementoDiRaccolta_idelementoDiRaccolta as ID, b.citazioneBibliografia as cit FROM elementobibliografia b WHERE b.elementoDiRaccolta_RaccoltaDiRisorse_idRaccoltaDiRisorse = ?");
        $stmt->bind_param('i', $collectionID);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Restituisce gli elementi di cronologia di una collezione di risorse, dato il suo ID.
     * @param int $collectionID ID della collezione.
     * @return array{ ID: int, data: string, loc: string|null }[]
     * Array contenente gli elementi di cronologia della collezione richiesta, ognuno dei quali contiene:
     * - ID: ID dell'elemento
     * - data: data di riferimento dell'elemento
     * - loc: località di riferimento dell'elemento
     */
    public function getChronologyElementsFromCollectionID($collectionID) {
        $stmt = $this->db->prepare("SELECT c.elementoDiRaccolta_idelementoDiRaccolta as ID, c.idElementoCronologia as data, c.localita as loc FROM elementocronologia c INNER JOIN raccoltadirisorse r ON r.idRaccoltaDiRisorse = c.elementoDiRaccolta_RaccoltaDiRisorse_idRaccoltaDiRisorse WHERE r.idRaccoltaDiRisorse = ?");
        $stmt->bind_param('i', $collectionID);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Restituisce gli elementi di emeroteca di una collezione di risorse, dato il suo ID.
     * @param int $collectionID ID della collezione.
     * @return array{ ID: int, giornale: string, titolo: string }[]
     * Array contenente gli elementi di emeroteca della collezione richiesta, ognuno dei quali contiene:
     * - ID: ID dell'elemento
     * - giornale: testata giornalistica che ha scritto l'articolo dell'elemento
     * - titolo: titolo dell'articolo
     */
    public function getNewsPaperLibraryElementsFromCollectionID($collectionID) {
        $stmt = $this->db->prepare("SELECT e.elementoDiRaccolta_idelementoDiRaccolta as ID, e.nomeTestataGiornalistica as giornale, e.titoloArticolo as titolo FROM elementoemeroteca e INNER JOIN raccoltadirisorse r ON r.idRaccoltaDiRisorse = e.elementoDiRaccolta_RaccoltaDiRisorse_idRaccoltaDiRisorse WHERE r.idRaccoltaDiRisorse = ?");
        $stmt->bind_param('i', $collectionID);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Restituisce gli elementi di fototeca di una collezione di risorse, dato il suo ID.
     * @param int $collectionID ID della collezione.
     * @return array{ ID: int, descrizione: string }[]
     * Array contenente gli elementi di fototeca della collezione richiesta, ognuno dei quali contiene:
     * - ID: ID dell'elemento
     * - descrizione: descrizione dell'elemento
     */
    public function getPhotoLibraryElementsFromCollectionID($collectionID) {
        $stmt = $this->db->prepare("SELECT f.elementoDiRaccolta_idelementoDiRaccolta as ID, f.descrizioneElementoFototeca as descrizione FROM elementofototeca f INNER JOIN raccoltadirisorse r ON r.idRaccoltaDiRisorse = f.elementoDiRaccolta_RaccoltaDiRisorse_idRaccoltaDiRisorse WHERE r.idRaccoltaDiRisorse = ?");
        $stmt->bind_param('i', $collectionID);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Restituisce le risorse di rete di una collezione di risorse, dato il suo ID.
     * @param int $collectionID ID della collezione.
     * @return array{ ID: int, titolo: string|null, fonte: string|null }[]
     * Array contenente le risorse di rete della collezione richiesta, ognuna delle quali contiene:
     * - ID: ID della risorsa
     * - titolo: titolo della risorsa
     * - fonte: fonte della risorsa
     */
    public function getNetworkResourcesFromCollectionID($collectionID) {
        $stmt = $this->db->prepare("SELECT n.elementoDiRaccolta_idelementoDiRaccolta as ID, n.titoloRisorsa as titolo, n.fonte as fonte FROM elementorisorsa n INNER JOIN raccoltadirisorse r ON r.idRaccoltaDiRisorse = n.elementoDiRaccolta_RaccoltaDiRisorse_idRaccoltaDiRisorse WHERE r.idRaccoltaDiRisorse = ?");
        $stmt->bind_param('i', $collectionID);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Funzione template per restituire un elemento di raccolta, dato il suo ID e la query appropriata.
     * @param int $elementID ID dell'elemento.
     * @param string $query Query per ricavare l'elemento desiderato.
     * @return array<string, mixed> Array contenente le informazioni sull'elemento richiesto.
     */
    private function getElementFromID($elementID, $query) {
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $elementID);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Restituisce un elemento di raccolta, dato il suo ID e il suo tipo.
     * @param int $elementID ID dell'elemento.
     * @param string $type Tipo dell'elemento desiderato, indispensabile per ricavare la query corretta.
     * @return array<string, mixed> Array contenente le informazioni sull'elemento richiesto.
     */
    public function getCollectionElementFromID($elementID, $type) {
        $query = "";
        switch($type) {
            case "bibliografia":
                $query = "SELECT citazioneBibliografia as cit, elementoDiRaccolta_RaccoltaDiRisorse_idRaccoltaDiRisorse as raccolta, href, DOI FROM elementobibliografia WHERE elementoDiRaccolta_idelementoDiRaccolta = ?";
                break;
            case "cronologia":
                $query = "SELECT idElementoCronologia as data, elementoDiRaccolta_RaccoltaDiRisorse_idRaccoltaDiRisorse as raccolta, localita, descrizioneElementoCronologia as descr FROM elementocronologia WHERE elementoDiRaccolta_idelementoDiRaccolta = ?";
                break;
            case "emeroteca":
                $query = "SELECT nomeTestataGiornalistica as giornale, elementoDiRaccolta_RaccoltaDiRisorse_idRaccoltaDiRisorse as raccolta, dataPubblicazione as data, href, titoloArticolo as titolo FROM elementoemeroteca WHERE elementoDiRaccolta_idelementoDiRaccolta = ?";
                break;
            case "fototeca":
                $query = "SELECT descrizioneElementoFototeca as descr, elementoDiRaccolta_RaccoltaDiRisorse_idRaccoltaDiRisorse as raccolta FROM elementofototeca WHERE elementoDiRaccolta_idelementoDiRaccolta = ?";
                break;
            case "rete":
                $query = "SELECT tipologiaRisorsa as tipo, elementoDiRaccolta_RaccoltaDiRisorse_idRaccoltaDiRisorse as raccolta, titoloRisorsa as titolo, hrefRisorsa as href, fonte, DOI FROM elementorisorsa WHERE elementoDiRaccolta_idelementoDiRaccolta = ?";
                break;
        };
        return $this->getElementFromID($elementID, $query);
    }

    /**
     * Funzione template per restituire le informazioni su un contenuto diverso da menù e pagina.
     * @param string $table Tabella su cui viene effettuata la ricerca.
     * @param int $contentID ID del contenuto.
     * @param string $idField Nome del campo all'interno della tabella che rappresenta l'ID del contenuto.
     * @param string $nameField Nome del campo all'interno della tabella che rappresenta il nome del contenuto.
     * @return array<string, mixed> Array contenente le informazioni sul contenuto richiesto.
     */
    private function getNonPageContentFromID($table, $contentID, $idField, $nameField) {
        $query = "SELECT $nameField as name" . ($table == "tag" ? ", tagDescription as description" : "") . " FROM $table WHERE $idField = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $contentID);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Restituisce le informazioni su un tag, dato il suo ID.
     * @param int $tagID ID del tag.
     * @return array{ name: string, description: string|null }
     * Array contenente le informazioni sul tag richiesto:
     * - name: nome del tag
     * - description: descrizione del tag
     */
    public function getTagFromID($tagID) {
        return $this->getNonPageContentFromID("tag", $tagID, "idTag", "tagName");
    }

    /**
     * Restituisce le informazioni su un articolo d'inventario, dato il suo ID.
     * @param int $inventoryItemID ID dell'articolo.
     * @return array{ name: string }
     * Array contenente le informazioni sull'articolo richiesto:
     * - name: nome dell'articolo
     */
    public function getInventoryItemFromID($inventoryItemID) {
        return $this->getNonPageContentFromID("inventoryitem", $inventoryItemID, "idInventoryItem", "inventoryItemName");
    }

    /**
     * Restituisce le informazioni su uno strumento di corredo, dato il suo ID.
     * @param int $referenceToolID ID dello strumento.
     * @return array{ name: string }
     * Array contenente le informazioni sullo strumento richiesto:
     * - name: nome dello strumento
     */
    public function getReferenceToolFromID($referenceToolID) {
        return $this->getNonPageContentFromID("referencetool", $referenceToolID, "idReferenceTool", "nameReferenceTool");
    }

    /**
     * Restituisce le informazioni su un menù, dato il suo ID.
     * @param int $menuID ID del menù.
     * @return array{ ID: int, name: string }
     * Array contenente le informazioni sul menù richiesto:
     * - ID: ID del menù
     * - name: nome del menù
     */
    public function getMenuFromID($menuID) {
        $stmt = $this->db->prepare("SELECT idMenu as ID, menuName as name FROM menu WHERE idMenu = ?");
        $stmt->bind_param('i', $menuID);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Restituisce le voci di un menù, dato il suo ID.
     * @param int $menuID ID del menù.
     * @return array{ ID: int, name: string, position: int, page: int|null, father: int|null }[]
     * Array contenente le voci del menù richiesto, ognuna delle quali contiene:
     * - ID: ID della voce
     * - name: nome della voce
     * - position: posizione della voce all'interno del menù
     * - page: ID della pagina legata alla voce
     * - father: ID della voce madre
     */
    public function getMenuItems($menuID) {
        $stmt = $this->db->prepare("SELECT idMenuItem as ID, menuItemName as name, menuItemOrderedPosition as position, Page_idPage as page, MenuItem_idMenuItem as father FROM menuitem WHERE Menu_idMenu = ?");
        $stmt->bind_param('i', $menuID);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Restituisce le informazioni su una voce del menù, dato il suo ID.
     * @param int $menuItemID ID della voce.
     * @return array{ ID: int, name: string, position: int, page: int|null, father: int|null }
     * Array contenente le informazioni sulla voce del menù richiesta:
     * - ID: ID della voce
     * - name: nome della voce
     * - position: posizione della voce all'interno del menù
     * - page: ID della pagina legata alla voce
     * - father: ID della voce madre
     */
    public function getMenuItemFromID($menuItemID) {
        $stmt = $this->db->prepare("SELECT idMenuItem as ID, menuItemName as name, menuItemOrderedPosition as position, Page_idPage as page, MenuItem_idMenuItem as father FROM menuitem WHERE idMenuItem = ?");
        $stmt->bind_param('i', $menuItemID);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Aggiunge una nuova pagina.
     * @param string $title Titolo della nuova pagina.
     * @param string $slug Slug della nuova pagina.
     * @param string $html Contenuto HTML della nuova pagina.
     * @param boolean $isVisibile Flag che rappresenta la visibilità della nuova pagina.
     * @param string $seoTitle Titolo per la Search Of Engine della nuova pagina.
     * @param string $seoText Testo per la Search Of Engine della nuova pagina.
     * @param string $seoKeywords Parole chiave per la Search Of Engine della nuova pagina.
     * @return int ID della nuova pagina.
     */
    public function addPage($title, $slug, $html, $isVisible, $seoTitle, $seoText, $seoKeywords) {
        $creationDate = date('Y-m-d H:i:s');
        $stmt = $this->db->prepare("INSERT INTO page (title, slug, text, creationDate, updatedDate, isVisibile, seoTitle, seoText, seoKeywords) VALUES (?, ?, ?, '$creationDate', '$creationDate', ?, ?, ?, ?)");
        $stmt->bind_param('sssisss', $title, $slug, $html, $isVisible, $seoTitle, $seoText, $seoKeywords);
        $stmt->execute();
        return $stmt->insert_id;
    }

    /**
     * Aggiunge dei tag a una pagina, dato il suo ID.
     * @param int $pageID ID della pagina.
     * @param int[] $tagsID ID dei tag da collegare alla pagina.
     */
    public function connectTagsToPage($pageID, $tagsID) {
        foreach ($tagsID as $tagID) {
            $stmt = $this->db->prepare("INSERT INTO page_has_tag (page_idPage, tag_idTag) VALUES (?, ?)");
            $stmt->bind_param('ii', $pageID, $tagID);
            $stmt->execute();
        }
    }

    /**
     * Aggiunge le pagine che devono essere contenute in una pagina, dato il suo ID.
     * @param int $viewerPageID ID della pagina contenitrice.
     * @param int[] $tagsID ID dei tag le cui pagine saranno contenute nella pagina specificata.
     */
    public function addDisplayedPages($viewerPageID, $tagsID) {
        //inserimento dentro la tabella 'page_displays_pages_of_tag'
        foreach ($tagsID as $tagID) {
            $stmt = $this->db->prepare("INSERT INTO page_displays_pages_of_tag (page_idPage, tag_idTag) VALUES (?, ?)");
            $stmt->bind_param('ii', $viewerPageID, $tagID);
            $stmt->execute();
        }
        $displayedPages = $this->getAllDisplayedPagesFromTags($tagsID);
        //inserimento dentro la tabella 'page_displays_page'
        foreach ($displayedPages as $displayedPage) {
            if ($displayedPage['ID'] != $viewerPageID) {
                $stmt = $this->db->prepare("INSERT INTO page_displays_page (page_idPageContainer, page_idPageContained) VALUES (?, ?)");
                $stmt->bind_param('ii', $viewerPageID, $displayedPage['ID']);
                $stmt->execute();
            }
        }
    }

    /**
     * Restituisce tutti gli ID delle pagine che appartengono ad almeno uno dei tag passati come parametro.
     * @param int[] $tagsID ID dei tag.
     * @return array{ ID: int }[] Array delle pagine restituite, ognuna delle quali contiene solo il proprio ID.
     */
    private function getAllDisplayedPagesFromTags($tagsID) {
        if (count($tagsID) > 0) {
            $query = "SELECT DISTINCT page_idPage as ID FROM page_has_tag";
            for ($i=0; $i < count($tagsID); $i++) { 
                $query .= $i != 0 ? " OR " : " WHERE ";
                $query .= "tag_idTag = " . $tagsID[$i];
            }
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }

    /**
     * Aggiunge una nuova voce a un indice.
     * @param int $position Posizione della nuova voce all'interno dell'indice.
     * @param int $shownInPageID ID della pagina dove è contenuto l'indice.
     * @param string|null $targetAnchor Ancora alla quale è legata la nuova voce.
     * @param int|null $linkToPage ID della pagina alla quale è legata la nuova voce.
     * @param string $title Titolo della nuova voce.
     */
    public function addIndexItem($position, $shownInPageID, $targetAnchor, $linkToPage, $title) {
        $stmt = $this->db->prepare("INSERT INTO indexitem (orderedPosition, shownInPageId, targetAnchor, linkToPage, indexItemTitle) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('iisis', $position, $shownInPageID, $targetAnchor, $linkToPage, $title);
        $stmt->execute();
    }

    /**
     * Aggiunge una nuova voce a un indice.
     * @param string|null $anchor Ancora alla quale è legata la nuova nota.
     * @param string $text Contenuto della nuova nota.
     * @param int $pageID ID della pagina in cui è contenuta la nuova nota.
     * @param string|null $author Autore della nuova nota.
     */
    public function addNote($anchor, $text, $pageID, $author) {
        $stmt = $this->db->prepare("INSERT INTO note (noteAnchor, noteText, page_idPage, author) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('ssis', $anchor, $text, $pageID, $author);
        $stmt->execute();
    }

    /**
     * Aggiunge le informazioni di archivio a una pagina.
     * @param int $chronologyStartYear Data di inizio cronologico del nuovo archivio.
     * @param int $chronologyEndYear Data di fine cronologica del nuovo archivio.
     * @param int $pageID ID della pagina.
    */
    public function addArchivePage($chronologyStartYear, $chronologyEndYear, $pageID) {
        $stmt = $this->db->prepare("INSERT INTO archivepage (chronologyStartYear, chronologyEndYear, Page_idPage) VALUES (?, ?, ?)");
        $stmt->bind_param('ssi', $chronologyStartYear, $chronologyEndYear, $pageID);
        $stmt->execute();
    }

    /**
     * Aggiunge degli strumenti di corredo a una pagina, dato il suo ID.
     * @param int $pageID ID della pagina.
     * @param int[] $referenceToolsID ID degli struemnti di corredo da collegare alla pagina.
     */
    public function connectReferenceToolsToPage($pageID, $referenceToolsID) {
        foreach ($referenceToolsID as $referenceToolID) {
            $stmt = $this->db->prepare("INSERT INTO archivepage_has_referencetool (ArchivePage_Page_idPage, ReferenceTool_idReferenceTool) VALUES (?, ?)");
            $stmt->bind_param('ii', $pageID, $referenceToolID);
            $stmt->execute();
        }
    }

    /**
     * Aggiunge degli articoli d'inventario a una pagina, dato il suo ID.
     * @param int $pageID ID della pagina.
     * @param array{ articolo: int, quantita: int }[] $inventoryItems 
     * Array contenente l'ID e la quantità presente nella pagina degli articoli d'inventario da collegare a quest'ultima.
     */
    public function connectInventoryItemsToPage($pageID, $inventoryItems) {
        foreach ($inventoryItems as $inventoryItem) {
            $stmt = $this->db->prepare("INSERT INTO archivepage_has_inventoryitem (ArchivePage_Page_idPage, inventoryItem_idInventoryItem, itemQuantity) VALUES (?, ?, ?)");
            $stmt->bind_param('iii', $pageID, $inventoryItem['articolo'], $inventoryItem['quantita']);
            $stmt->execute();
        }
    }

    /**
     * Aggiunge una nuova raccolta di risorse.
     * @param string $collectionName Nome della nuova collezione.
     * @param string|null $path Percorso della nuova collezione.
     * @param int $pageID ID della pagina nella quale sarà contenuta.
     * @return int ID della nuova collezione.
     */
    public function addResourceCollection($collectionName, $path, $pageID) {
        $stmt = $this->db->prepare("INSERT INTO raccoltadirisorse (nomeRaccolta, pathRaccolta, Page_idPage) VALUES (?, ?, ?)");
        $stmt->bind_param('ssi', $collectionName, $path, $pageID);
        $stmt->execute();
        return $stmt->insert_id;
    }

    /**
     * Aggiunge un nuovo elemento di raccolta ad una collezione di risorse.
     * @param int $resourceCollectionID ID della collezione.
     * @return int ID del nuovo elemento.
     */
    public function addCollectionElement($resourceCollectionID) {
        $stmt = $this->db->prepare("INSERT INTO elementodiraccolta (RaccoltaDiRisorse_idRaccoltaDiRisorse) VALUES (?)");
        $stmt->bind_param('i', $resourceCollectionID);
        $stmt->execute();
        return $stmt->insert_id;
    }

    /**
     * Aggiunge un nuovo elemento di bibliografia.
     * @param string|null $cit Citazione bibliografica del nuovo elemento.
     * @param int $elementID ID dell'elemento di raccolta a cui si collega il nuovo elemento.
     * @param int $collectionID ID della collezione nella quale sarà contenuto il nuovo elemento.
     * @param string|null $href Link di collegamento del nuovo elemento.
     * @param string|null $doi Digital Object Identifier del nuovo elemento.
     */
    public function addBibliographyElement($cit, $elementID, $collectionID, $href, $doi) {
        $stmt = $this->db->prepare("INSERT INTO elementobibliografia (citazioneBibliografia, elementoDiRaccolta_idelementoDiRaccolta, elementoDiRaccolta_RaccoltaDiRisorse_idRaccoltaDiRisorse, href, DOI) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('siiss', $cit, $elementID, $collectionID, $href, $doi);
        $stmt->execute();
    }

    /**
     * Aggiunge un nuovo elemento di cronologia.
     * @param string $date Data cronologica del nuovo elemento.
     * @param int $elementID ID dell'elemento di raccolta a cui si collega il nuovo elemento.
     * @param int $collectionID ID della collezione nella quale sarà contenuto il nuovo elemento.
     * @param string|null $location Località di riferimento del nuovo elemento.
     * @param string|null $description Descrizione del nuovo elemento.
     */
    public function addChronologyElement($date, $elementID, $collectionID, $location, $description) {
        $stmt = $this->db->prepare("INSERT INTO elementocronologia (idElementoCronologia, elementoDiRaccolta_idelementoDiRaccolta, elementoDiRaccolta_RaccoltaDiRisorse_idRaccoltaDiRisorse, localita, descrizioneElementoCronologia) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('siiss', $date, $elementID, $collectionID, $location, $description);
        $stmt->execute();
    }

    /**
     * Aggiunge un nuovo elemento di emeroteca.
     * @param string $journal Testata giornalistica che ha scritto l'articolo del nuovo elemento.
     * @param string $publicationDate Data di pubblicazione dell'articolo del nuovo elemento.
     * @param int $elementID ID dell'elemento di raccolta a cui si collega il nuovo elemento.
     * @param int $collectionID ID della collezione nella quale sarà contenuto il nuovo elemento.
     * @param string $title Titolo dell'articolo del nuovo elemento.
     * @param string|null $href Link di collegamento del nuovo elemento.
     */
    public function addNewsPaperLibraryElement($journal, $publicationDate, $elementID, $collectionID, $title, $href) {
        $stmt = $this->db->prepare("INSERT INTO elementoemeroteca (nomeTestataGiornalistica, elementoDiRaccolta_idelementoDiRaccolta, elementoDiRaccolta_RaccoltaDiRisorse_idRaccoltaDiRisorse, href, dataPubblicazione, titoloArticolo) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('siisss', $journal, $elementID, $collectionID, $href, $publicationDate, $title);
        $stmt->execute();
    }

    /**
     * Aggiunge un nuovo elemento di fototeca.
     * @param string $description Descrizione del nuovo elemento.
     * @param int $elementID ID dell'elemento di raccolta a cui si collega il nuovo elemento.
     * @param int $collectionID ID della collezione nella quale sarà contenuto il nuovo elemento.
     */
    public function addPhotoLibraryElement($description, $elementID, $collectionID) {
        $stmt = $this->db->prepare("INSERT INTO elementofototeca (descrizioneElementoFototeca, elementoDiRaccolta_idelementoDiRaccolta, elementoDiRaccolta_RaccoltaDiRisorse_idRaccoltaDiRisorse) VALUES (?, ?, ?)");
        $stmt->bind_param('sii', $description, $elementID, $collectionID);
        $stmt->execute();
    }

    /**
     * Aggiunge una nuova risorsa di rete.
     * @param string $type Tipo della nuova risorsa.
     * @param int $elementID ID dell'elemento di raccolta a cui si collega la nuova risorsa.
     * @param int $collectionID ID della collezione nella quale sarà contenuta la nuova risorsa.
     * @param string|null $title Titolo della nuova risorsa.
     * @param string $href Link di collegamento della nuova risorsa.
     * @param string|null $source Fonte della nuova risorsa.
     * @param string|null $doi Digital Object Identifier della nuova risorsa.
     */
    public function addNetworkResource($type, $elementID, $collectionID, $title, $href, $source, $doi) {
        $stmt = $this->db->prepare("INSERT INTO elementorisorsa (tipologiaRisorsa, elementoDiRaccolta_idelementoDiRaccolta, elementoDiRaccolta_RaccoltaDiRisorse_idRaccoltaDiRisorse, titoloRisorsa, hrefRisorsa, fonte, DOI) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('siissss', $type, $elementID, $collectionID, $title, $href, $source, $doi);
        $stmt->execute();
    }

    /**
     * Aggiunge un nuovo menù.
     * @param string $name Nome del nuovo menù.
     * @return int ID del nuovo menù.
     */
    public function addMenu($name) {
        $stmt = $this->db->prepare("INSERT INTO menu (menuName) VALUES (?)");
        $stmt->bind_param('s', $name);
        $stmt->execute();
        return $stmt->insert_id;
    }

    /**
     * Aggiunge una nuova voce del menù.
     * @param int $id ID della nuova voce.
     * @param string $name Nome della nuova voce.
     * @param int $position Posizione della nuova voce all'interno del menù.
     * @param int $menu Menù nel quale sarà inserita la nuova voce.
     * @param int|null $pageToLink Pagina alla quale sarà legata la nuova voce (null se sarà madre di altre voci).
     * @param int|null $father ID della voce madre (null se la nuova voce appartiene al menù principale).
     * @return int ID della nuova voce.
     */
    public function addMenuItem($id, $name, $position, $menu, $pageToLink, $father) {
        $stmt = $this->db->prepare("INSERT INTO menuitem (idMenuItem, menuItemName, menuItemOrderedPosition, Menu_idMenu, Page_idPage, MenuItem_idMenuItem) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('isiiii', $id, $name, $position, $menu, $pageToLink, $father);
        $stmt->execute();
        return $stmt->insert_id;
    }

    /**
     * Restituisce l'ID che verrà assegnato alla prossima voce del menù.
     * @return int Prossimo ID da assegnare.
     */
    public function getMenuItemsNextID() {
        $stmt = $this->db->prepare("SELECT MAX(idMenuItem) FROM menuitem");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_row()[0] + 1;
    }

    /**
     * Aggiunge un nuovo tag.
     * @param string $name Nome del nuovo tag.
     * @param string|null $description Descrizione del nuovo tag.
     */
    public function addTag($name, $description) {
        $stmt = $this->db->prepare("INSERT INTO tag (tagName, tagDescription) VALUES (?, ?)");
        $stmt->bind_param('ss', $name, $description);
        $stmt->execute();
        return $stmt->insert_id;
    }

    /**
     * Aggiunge un nuovo strumento di corredo.
     * @param string $name Nome del nuovo strumento.
     */
    public function addReferenceTool($name) {
        $stmt = $this->db->prepare("INSERT INTO referencetool (nameReferenceTool) VALUES (?)");
        $stmt->bind_param('s', $name);
        $stmt->execute();
        return $stmt->insert_id;
    }

    /**
     * Aggiunge un nuovo articolo d'inventario.
     * @param string $name Nome del nuovo articolo.
     */
    public function addInventoryItem($name) {
        $stmt = $this->db->prepare("INSERT INTO inventoryitem (inventoryItemName) VALUES (?)");
        $stmt->bind_param('s', $name);
        $stmt->execute();
        return $stmt->insert_id;
    }

    /**
     * Modifica gli attributi di una pagina. Ognuno di questi viene confrontato con quello corrispondente passato come parametro e, se almeno uno è diverso, viene eseguita la query di update.
     * @param int $pageID ID della pagina.
     * @param string $newTitle (Nuovo) Titolo della pagina.
     * @param string $newSlug (Nuovo) Slug della pagina.
     * @param string $newContent (Nuovo) Contenuto HTML della pagina.
     * @param boolean $newIsVisibile (Nuovo) Flag che rappresenta la visibilità della pagina.
     * @param string $newSeoTitle (Nuovo) Titolo per la Search Of Engine della pagina.
     * @param string $newSeoText (Nuovo) Testo per la Search Of Engine della pagina.
     * @param string $newSeoKeywords (Nuove) Parole chiave per la Search Of Engine della pagina.
     * @param string $newAuthor (Nuovo) Autore della pagina.
     */
    public function updatePage($pageID, $newTitle, $newSlug, $newContent, $newIsVisible, $newSeoTitle, $newSeoText, $newSeoKeywords, $newAuthor) {
        $page = $this->getPageFromID($pageID);
        if ($page['title'] != $newTitle || $page['slug'] != $newSlug || $page['text'] != $newContent || $page['isVisibile'] != $newIsVisible || $page['seoTitle'] != $newSeoTitle || $page['seoText'] != $newSeoText || $page['seoKeywords'] != $newSeoKeywords || $page['author'] != $newAuthor) {
            $stmt = $this->db->prepare("UPDATE page SET title = ?, slug = ?, text = ?, isVisibile = ?, seoTitle = ?, seoText = ?, seoKeywords = ?, author = ? WHERE idPage = ?");
            $stmt->bind_param('sssissssi', $newTitle, $newSlug, $newContent, $newIsVisible, $newSeoTitle, $newSeoText, $newSeoKeywords, $newAuthor, $pageID);
            $stmt->execute();
        }
    }

    /**
     * Modifica i tag collegati a una pagina. Vengono confrontati quelli correnti con quelli passati come parametro e, se gli array non contengono gli stessi ID, viene eseguito l'update.
     * @param int $pageID ID della pagina.
     * @param int[] $newTags ID dei (nuovi) tag da collegare alla pagina.
     */
    public function updatePageTags($pageID, $newTags) {
        $tags = $this->getTagsFromPageID($pageID);
        sort($tags);
        sort($newTags);
        if ($tags !== $newTags) {
            $this->deleteContent("page_has_tag", "page_idPage", $pageID);
            $this->connectTagsToPage($pageID, $newTags);
        }
    }

    /**
     * Modifica le pagine contenute in una pagina.
     * @param int $pageID ID della pagina contenitrice.
     * @param int[] $newDisplayedTags Array contenente gli ID dei tag delle pagine contenute.
     */
    public function updateDisplayedPages($pageID, $newDisplayedTags) {
        $displayedTags = $this->getContainedPagesTagsFromContainerID($pageID);
        sort($displayedTags);
        sort($newDisplayedTags);
        if ($displayedTags !== $newDisplayedTags) {
            $this->deleteContent("page_displays_pages_of_tag", "page_idPage", $pageID);
            $this->deleteContent("page_displays_page", "page_idPageContainer", $pageID);
            $this->addDisplayedPages($pageID, $newDisplayedTags);
        }       
    }

    /**
     * Modifica le informazioni di archivio di una pagina. Vengono confrontate le info correnti con quelle passate come parametro e, se almeno una è diversa, viene eseguito l'update.
     * @param int $pageID ID della pagina di archivio.
     * @param int $newStartYear (Nuovo) Anno di inizio cronologico.
     * @param int $newEndYear (Nuovo) Anno di fine cronologica.
     */
    public function updateArchivePage($pageID, $newStartYear, $newEndYear) {
        $archivePage = $this->getArchivePageFromPageID($pageID);
        if ($archivePage[0]['start'] != $newStartYear || $archivePage[0]['end'] != $newEndYear) {
            $stmt = $this->db->prepare("UPDATE archivepage SET chronologyStartYear = ?, chronologyEndYear = ? WHERE Page_idPage = ?");
            $stmt->bind_param('iii', $newStartYear, $newEndYear, $pageID);
            $stmt->execute();
        }
    }

    /**
     * Modifica gli strumenti di corredo collegati a una pagina d'archivio. Vengono confrontati quelli correnti con quelli passati come parametro e, se gli array non contengono gli stessi ID, viene eseguito l'update.
     * @param int $pageID ID della pagina.
     * @param int[] $newReferenceTools ID dei (nuovi) strumenti di corredo da collegare alla pagina.
     */
    public function updateArchivePageReferenceTools($pageID, $newReferenceTools) {
        $refTools = $this->getReferenceToolsFromArchivePageID($pageID);
        sort($refTools);
        sort($newReferenceTools);
        if ($refTools !== $newReferenceTools) {
            $this->deleteContent("archivepage_has_referencetool", "ArchivePage_Page_idPage", $pageID);
            $this->connectReferenceToolsToPage($pageID, $newReferenceTools);
        }
    }

    /**
     * Modifica gli articoli d'inventario collegati a una pagina d'archivio. Vengono confrontati quelli correnti con quelli passati come parametro e, se gli array non contengono gli stessi ID, viene eseguito l'update.
     * @param int $pageID ID della pagina.
     * @return array{ ID: int, quantity: int }[] $newInventoryItems
     * Array contenente i (nuovi) articoli della pagina d'archivio, ognuno dei quali contiene:
     * - ID: ID dell'articolo
     * - quantity: quantità presente nell'archivio 
     */
    public function updateArchivePageInventoryItems($pageID, $newInventoryItems) {
        $invItems = $this->getInventoryItemsFromArchivePageID($pageID);
        sort($invItems);
        sort($newInventoryItems);
        if ($invItems !== $newInventoryItems) {
            $this->deleteContent("archivepage_has_inventoryitem", "ArchivePage_Page_idPage", $pageID);
            $this->connectInventoryItemsToPage($pageID, $newInventoryItems);
        }
    }

    /**
     * Modifica le informazioni di una raccolta di risorse. Vengono confrontate le info correnti con quelle passate come parametro e, se almeno una è diversa, viene eseguito l'update.
     * @param int $resourceCollectionID ID della raccolta.
     * @param string $newCollectionName (Nuovo) Nome della raccolta.
     * @param string $newPath (Nuovo) Percorso della raccolta.
     */
    public function updateResourceCollection($resourceCollectionID, $newCollectionName, $newPath) {
        $resourceCollection = $this->getResourceCollectionFromID($resourceCollectionID);
        if ($resourceCollection[0]['nome'] != $newCollectionName || $resourceCollection[0]['path'] != $newPath) {
            $stmt = $this->db->prepare("UPDATE raccoltadirisorse SET nomeRaccolta = ?, pathRaccolta = ? WHERE idRaccoltaDiRisorse = ?");
            $stmt->bind_param('ssi', $newCollectionName, $newPath, $resourceCollectionID);
            $stmt->execute();
        }
    }

    /**
     * Modifica le informazioni di un elemento di bibliografia. Vengono confrontate le info correnti con quelle passate come parametro e, se almeno una è diversa, viene eseguito l'update.
     * @param int $elementID ID dell'elemento di raccolta.
     * @param string|null $newCit (Nuova) Citazione bibliografica dell'elemento.
     * @param string|null $newHref (Nuovo) Link di collegamento dell'elemento.
     * @param string|null $newDoi (Nuovo) Digital Object Identifier dell'elemento.
     */
    public function updateBibliographyElement($elementID, $newCit, $newHref, $newDoi) {
        $element = $this->getCollectionElementFromID($elementID, "bibliografia");
        if ($element[0]['cit'] != $newCit || $element[0]['href'] != $newHref || $element[0]['DOI'] != $newDoi) {
            $stmt = $this->db->prepare("UPDATE elementobibliografia SET citazioneBibliografia = ?, href = ?, DOI = ? WHERE elementoDiRaccolta_idelementoDiRaccolta = ?");
            $stmt->bind_param('sssi', $newCit, $newHref, $newDoi, $elementID);
            $stmt->execute();
        }
    }

    /**
     * Modifica le informazioni di un elemento di cronologia. Vengono confrontate le info correnti con quelle passate come parametro e, se almeno una è diversa, viene eseguito l'update.
     * @param int $elementID ID dell'elemento di raccolta.
     * @param string $newDate (Nuova) Data cronologica dell'elemento.
     * @param string|null $newLocation (Nuova) Località di riferimento dell'elemento.
     * @param string|null $newDescription (Nuova) Descrizione dell'elemento.
     */
    public function updateChronologyElement($elementID, $newDate, $newLocation, $newDescription) {
        $element = $this->getCollectionElementFromID($elementID, "cronologia");
        if ($element[0]['data'] != $newDate || $element[0]['localita'] != $newLocation || $element[0]['descr'] != $newDescription) {
            $stmt = $this->db->prepare("UPDATE elementocronologia SET idElementoCronologia = ?, localita = ?, descrizioneElementoCronologia = ? WHERE elementoDiRaccolta_idelementoDiRaccolta = ?");
            $stmt->bind_param('sssi', $newDate, $newLocation, $newDescription, $elementID);
            $stmt->execute();
        }
    }

    /**
     * Modifica le informazioni di un elemento di emeroteca. Vengono confrontate le info correnti con quelle passate come parametro e, se almeno una è diversa, viene eseguito l'update.
     * @param int $elementID ID dell'elemento di raccolta.
     * @param string $newJournal (Nuova) Testata giornalistica che ha scritto l'articolo dell'elemento.
     * @param string $newDate (Nuova) Data di pubblicazione dell'articolo dell'elemento.
     * @param string|null $newHref (Nuovo) Link di collegamento dell'elemento.
     * @param string $newTitle (Nuovo) Titolo dell'articolo dell'elemento.
     */
    public function updateNewsPaperLibraryElement($elementID, $newJournal, $newDate, $newHref, $newTitle) {
        $element = $this->getCollectionElementFromID($elementID, "emeroteca");
        if ($element[0]['giornale'] != $newJournal || $element[0]['data'] != $newDate || $element[0]['href'] != $newHref || $element[0]['titolo'] != $newTitle) {
            $stmt = $this->db->prepare("UPDATE elementoemeroteca SET nomeTestataGiornalistica = ?, dataPubblicazione = ?, href = ?, titoloArticolo = ? WHERE elementoDiRaccolta_idelementoDiRaccolta = ?");
            $stmt->bind_param('ssssi', $newJournal, $newDate, $newHref, $newTitle, $elementID);
            $stmt->execute();
        }
    }

    /**
     * Modifica le informazioni di un elemento di fototeca. Vengono confrontate le info correnti con quelle passate come parametro e, se almeno una è diversa, viene eseguito l'update.
     * @param int $elementID ID dell'elemento di raccolta.
     * @param string $newDescription (Nuova) Descrizione dell'elemento.
     */
    public function updatePhotoLibraryElement($elementID, $newDescription) {
        $element = $this->getCollectionElementFromID($elementID, "fototeca");
        if ($element[0]['descr'] != $newDescription) {
            $stmt = $this->db->prepare("UPDATE elementofototeca SET descrizioneElementoFototeca = ? WHERE elementoDiRaccolta_idelementoDiRaccolta = ?");
            $stmt->bind_param('si', $newDescription, $elementID);
            $stmt->execute();
        }
    }

    /**
     * Modifica le informazioni di una risorsa di rete. Vengono confrontate le info correnti con quelle passate come parametro e, se almeno una è diversa, viene eseguito l'update.
     * @param int $elementID ID dell'elemento di raccolta.
     * @param string $newType (Nuovo) Tipo della risorsa.
     * @param string|null $newTitle (Nuovo) Titolo della risorsa. 
     * @param string $newHref (Nuovo) Link di collegamento della risorsa.
     * @param string|null $newSource (Nuova) Fonte della risorsa.
     * @param string|null $newDoi (Nuovo) Digital Object Identifier della risorsa.
     */
    public function updateNetworkResource($elementID, $newType, $newTitle, $newHref, $newSource, $newDoi) {
        $element = $this->getCollectionElementFromID($elementID, "rete");
        if ($element[0]['tipo'] != $newType || $element[0]['titolo'] != $newTitle || $element[0]['href'] != $newHref || $element[0]['fonte'] != $newSource || $element[0]['DOI'] != $newDoi) {
            $stmt = $this->db->prepare("UPDATE elementorisorsa SET tipologiaRisorsa = ?, titoloRisorsa = ?, hrefRisorsa = ?, fonte = ?, DOI = ? WHERE elementoDiRaccolta_idelementoDiRaccolta = ?");
            $stmt->bind_param('sssssi', $newType, $newTitle, $newHref, $newSource, $newDoi, $elementID);
            $stmt->execute();
        }
    }

    /**
     * Modifica le informazioni di una voce dell'indice. Vengono confrontate le info correnti con quelle passate come parametro e, se almeno una è diversa, viene eseguito l'update.
     * @param int $indexItemID ID della voce.
     * @param string $newTitle (Nuovo) Titolo della voce.
     * @param int $newPosition (Nuova) Posizione della voce all'interno dell'indice. 
     * @param int|null $newLinkToPage (Nuova) Pagina a cui è legata la voce.
     * @param string|null $newDestAnchor (Nuova) Ancora a cui è legata la voce.
     */
    public function updateIndexItem($indexItemID, $newTitle, $newPosition, $newLinkToPage, $newDestAnchor) {
        $item = $this->getIndexItemFromID($indexItemID);
        if ($item[0]['title'] != $newTitle || $item[0]['anchor'] != $newDestAnchor || $item[0]['position'] != $newPosition || $item[0]['page'] != $newLinkToPage) {
            $stmt = $this->db->prepare("UPDATE indexitem SET orderedPosition = ?, targetAnchor = ?, linkToPage = ?, indexItemTitle = ? WHERE idIndexItem = ?");
            $stmt->bind_param('isisi', $newPosition, $newDestAnchor, $newLinkToPage, $newTitle, $indexItemID);
            $stmt->execute();
        }
    }

    /**
     * Modifica le informazioni di una nota. Vengono confrontate le info correnti con quelle passate come parametro e, se almeno una è diversa, viene eseguito l'update.
     * @param int $noteID ID della nota.
     * @param string $newText (Nuovo) Contenuto della nota.
     * @param string|null $newAuthor (Nuovo) Autore della nota.
     * @param string|null $newAnchor (Nuova) Ancora a cui è legata la nota.
     */
    public function updateNote($noteID, $newText, $newAuthor, $newAnchor) {
        $note = $this->getNoteFromID($noteID);
        if ($note[0]['text'] != $newText || $note[0]['anchor'] != $newAnchor || $note[0]['author'] != $newAuthor) {
            $stmt = $this->db->prepare("UPDATE note SET noteAnchor = ?, noteText = ?, author = ? WHERE noteId = ?");
            $stmt->bind_param('sssi', $newAnchor, $newText, $newAuthor, $noteID);
            $stmt->execute();
        }
    }

    /**
     * Modifica le informazioni di un menù. Vengono confrontate le info correnti con quelle passate come parametro e, se almeno una è diversa, viene eseguito l'update.
     * @param int $menuID ID del menù.
     * @param string $newMenuName (Nuovo) Nome del menù.
     */
    public function updateMenu($menuID, $newMenuName) {
        $menu = $this->getMenuFromID($menuID);
        if ($menu[0]['name'] != $newMenuName) {
            $stmt = $this->db->prepare("UPDATE menu SET menuName = ? WHERE idMenu = ?");
            $stmt->bind_param('si', $newMenuName, $menuID);
            $stmt->execute();
        }
    }

    /**
     * Modifica le informazioni di una voce del menù. Vengono confrontate le info correnti con quelle passate come parametro e, se almeno una è diversa, viene eseguito l'update.
     * @param int $menuItemID ID della voce.
     * @param string $newMenuItemName (Nuovo) Nome della voce.
     * @param int|null $newMenuItemFather (Nuova) Voce madre della voce.
     * @param int $newMenuItemPosition (Nuova) Posizione della voce all'interno del menù.
     * @param int|null $newMenuItemLinkPage (Nuova) Pagina a cui è legata la voce.
     */
    public function updateMenuItem($menuItemID, $newMenuItemName, $newMenuItemFather, $newMenuItemPosition, $newMenuItemLinkPage) {
        $item = $this->getMenuItemFromID($menuItemID);
        if ($item[0]['name'] != $newMenuItemName || $item[0]['father'] != $newMenuItemFather || $item[0]['position'] != $newMenuItemPosition || $item[0]['page'] != $newMenuItemLinkPage) {
            $stmt = $this->db->prepare("UPDATE menuitem SET menuItemName = ?, menuItemOrderedPosition = ?, Page_idPage = ?, MenuItem_idMenuItem = ? WHERE idMenuItem = ?");
            $stmt->bind_param('siiii', $newMenuItemName, $newMenuItemPosition, $newMenuItemLinkPage, $newMenuItemFather, $menuItemID);
            $stmt->execute();
        }
    }
    
    /**
     * Modifica le informazioni di un tag. Vengono confrontate le info correnti con quelle passate come parametro e, se almeno una è diversa, viene eseguito l'update.
     * @param int $tagID ID del tag.
     * @param string $newTagName (Nuovo) Nome del tag.
     * @param string|null $newTagDescription (Nuova) Descrizione del tag.
     */
    public function updateTag($tagID, $newTagName, $newTagDescription) {
        $tag = $this->getTagFromID($tagID);
        if ($tag[0]['name'] != $newTagName || $tag[0]['description'] != $newTagDescription) {
            $stmt = $this->db->prepare("UPDATE tag SET tagName = ?, tagDescription = ? WHERE idTag = ?");
            $stmt->bind_param('ssi', $newTagName, $newTagDescription, $tagID);
            $stmt->execute();
        }
    }

    /**
     * Modifica le informazioni di uno strumento di corredo. Vengono confrontate le info correnti con quelle passate come parametro e, se almeno una è diversa, viene eseguito l'update.
     * @param int $referenceToolID ID dello strumento.
     * @param string $newReferenceToolName (Nuovo) Nome dello strumento.
     */
    public function updateReferenceTool($referenceToolID, $newReferenceToolName) {
        $refTool = $this->getReferenceToolFromID($referenceToolID);
        if ($refTool[0]['name'] != $newReferenceToolName) {
            $stmt = $this->db->prepare("UPDATE referencetool SET nameReferenceTool = ? WHERE idReferenceTool = ?");
            $stmt->bind_param('si', $newReferenceToolName, $referenceToolID);
            $stmt->execute();
        }
    }

    /**
     * Modifica le informazioni di un articolo d'inventario. Vengono confrontate le info correnti con quelle passate come parametro e, se almeno una è diversa, viene eseguito l'update.
     * @param int $inventoryItemID ID dell'articolo.
     * @param string $newInventoryItemName (Nuovo) Nome dell'articolo.
     */
    public function updateInventoryItem($inventoryItemID, $newInventoryItemName) {
        $invItem = $this->getInventoryItemFromID($inventoryItemID);
        if ($invItem[0]['name'] != $newInventoryItemName) {
            $stmt = $this->db->prepare("UPDATE inventoryitem SET inventoryItemName = ? WHERE idInventoryItem = ?");
            $stmt->bind_param('si', $newInventoryItemName, $inventoryItemID);
            $stmt->execute();
        }
    }

    /**
     * Funzione template per eliminare un contenuto.
     * @param string $table Tabella nella quale è presente il contenuto da eliminare.
     * @param string $idField Nome del campo nella tabella che rappresenta l'ID del contenuto.
     * @param int $contentID ID del contenuto da eliminare.
     */
    private function deleteContent($table, $idField, $contentID) {
        $stmt = $this->db->prepare("DELETE FROM $table WHERE $idField = ?");
        $stmt->bind_param('i', $contentID);
        $stmt->execute();
    }

    /**
     * Elimina un menù.
     * @param int $menuID ID del menù da eliminare.
     */
    public function deleteMenu($menuID) {
        $this->deleteContent("menu", "idMenu", $menuID);
    }
    
    /**
     * Elimina tutte le voci di un menù.
     * @param int $menuID ID del menù del quale si vogliono eliminare le voci.
     */
    public function deleteAllMenuItems($menuID) {
        $this->deleteContent("menuitem", "Menu_idMenu", $menuID);
    }

    /**
     * In caso dovesse essere eliminata una voce del menù che ha figli, questa funzione cancella il loro legame.
     * @param int $menuItemID ID della voce del menù da eliminare.
     */
    private function releaseItemChildren($menuItemID) {
        $stmt = $this->db->prepare("UPDATE menuitem SET MenuItem_idMenuItem = NULL WHERE MenuItem_idMenuItem = ?");
        $stmt->bind_param('i', $menuItemID);
        $stmt->execute();
    }

    /**
     * Elimina una voce del menù.
     * @param int $menuItemID ID della voce del menù da eliminare.
     */
    public function deleteMenuItem($menuItemID) {
        $this->releaseItemChildren($menuItemID);
        $this->deleteContent("menuitem", "idMenuItem", $menuItemID);
    }

    /**
     * Elimina un tag. Vengono eliminati anche tutti i riferimenti che esistono con lui.
     * @param int $tagID ID del tag da eliminare.
     */
    public function deleteTag($tagID) {
        $displayedPages = $this->getAllDisplayedPagesFromTags([$tagID]);  
        $this->deleteContent("page_has_tag", "tag_idTag", $tagID);
        $this->deleteContent("page_displays_pages_of_tag", "tag_idTag", $tagID);
        foreach ($displayedPages as $page) {
            $this->deleteContent("page_displays_page", "page_idPageContained", $page['ID']);
        }
        $this->deleteContent("tag", "idTag", $tagID);
    }

    /**
     * Elimina uno strumento di corredo. Vengono eliminati anche tutti i riferimenti che esistono con lui.
     * @param int $referenceToolID ID dello strumento da eliminare.
     */
    public function deleteReferenceTool($referenceToolID) {
        $this->deleteContent("archivepage_has_referencetool", "ReferenceTool_idReferenceTool", $referenceToolID);
        $this->deleteContent("referencetool", "idReferenceTool", $referenceToolID);
    }

    /**
     * Elimina un articolo d'inventario. Vengono eliminati anche tutti i riferimenti che esistono con lui.
     * @param int $inventoryItemID ID dell'articolo da eliminare.
     */
    public function deleteInventoryItem($inventoryItemID) {
        $this->deleteContent("archivepage_has_inventoryitem", "inventoryItem_idInventoryItem", $inventoryItemID);
        $this->deleteContent("inventoryitem", "idInventoryItem", $inventoryItemID);
    }

    /**
     * Elimina una voce dell'indice.
     * @param int $indexItemID ID della voce da eliminare.
     */
    public function deleteIndexItem($indexItemID) {
        $this->deleteContent("indexitem", "idIndexItem", $indexItemID);
    }

    /**
     * Elimina una nota.
     * @param int $noteID ID della nota da eliminare.
     */
    public function deleteNote($noteID) {
        $this->deleteContent("note", "noteId", $noteID);
    }

    /**
     * Elimina un elemento di raccolta.
     * @param int $elementID ID dell'elemento da eliminare.
     * @param string $type Tipo dell'elemento da eliminare.
     */
    public function deleteCollectionElement($elementID, $type) {
        $table = "";
        switch ($type) {
            case "bibliografia":
                $table = "elementobibliografia";
                break;
            case "cronologia":
                $table = "elementocronologia";
                break;
            case "emeroteca":
                $table = "elementoemeroteca";
                break;
            case "fototeca":
                $table = "elementofototeca";
                break;
            case "rete":
                $table = "elementorisorsa";
                break;
        }
        $this->deleteContent($table, "elementoDiRaccolta_idElementoDiRaccolta", $elementID);
        $this->deleteContent("elementodiraccolta", "idelementoDiRaccolta", $elementID);
    }

    /**
     * Elimina una raccolta di risorse. Vengono eliminati anche tutti gli elementi al suo interno.
     * @param int $collectionID ID della raccolta da eliminare.
     */
    public function deleteResourceCollection($collectionID) {
        $tables = ["elementobibliografia", "elementocronologia", "elementoemeroteca", "elementofototeca", "elementorisorsa", "elementodiraccolta"];
        foreach ($tables as $table) {
            $idField = $table != "elementodiraccolta" ? "elementoDiRaccolta_RaccoltaDiRisorse_idRaccoltaDiRisorse" : "RaccoltaDiRisorse_idRaccoltaDiRisorse";
            $this->deleteContent($table, $idField, $collectionID);
        }
        $this->deleteContent("raccoltadirisorse", "idRaccoltaDiRisorse", $collectionID);
    }

    /**
     * Rimuove tutti i riferimenti a una pagina che sta per essere eliminata.
     * @param int $pageID ID della pagina da eliminare.
     */
    private function removeAllTheLinksToAPage($pageID) {
        $stmt = $this->db->prepare("UPDATE menuitem SET Page_idPage = NULL WHERE Page_idPage = ?");
        $stmt->bind_param('i', $pageID);
        $stmt->execute();
        $stmt = $this->db->prepare("UPDATE indexitem SET linkToPage = NULL WHERE linkToPage = ?");
        $stmt->bind_param('i', $pageID);
        $stmt->execute();
    }

    /** Elimina una pagina. Vengono eliminati anche tutti i collegamenti di altre tabelle con lei, tutte le note e tutte le voci dell'indice che contiene.
     * @param int $pageID ID della pagina da eliminare.
     */
    public function deletePage($pageID) {
        $pageType = $this->getPageType($pageID);
        switch ($pageType) {
            case "Pagina di Archivio":
                $this->deleteContent("archivepage_has_inventoryitem", "ArchivePage_Page_idPage", $pageID);
                $this->deleteContent("archivepage_has_referencetool", "ArchivePage_Page_idPage", $pageID);
                $this->deleteContent("archivepage", "Page_idPage", $pageID);
                break;
            case "Raccolta di Risorse":
                $collections = $this->getResourceCollectionsFromPageID($pageID);
                foreach ($collections as $collection) {
                    $this->deleteResourceCollection($collection['ID']);
                }
                break;
            default:
                break;
        }
        $this->updateDisplayedPages($pageID, []);
        //elimino le righe che specificano in quali pagine è contenuta quella che si sta eliminando
        $this->deleteContent("page_displays_page", "page_idPageContained", $pageID);
        $this->updatePageTags($pageID, []);
        $notes = $this->getNotesFromPageID($pageID);
        $indexItems = $this->getIndexItemsFromPageID($pageID);
        foreach ($notes as $note) {
            $this->deleteNote($note['ID']);
        }
        foreach ($indexItems as $item) {
            $this->deleteIndexItem($item['ID']);
        }
        $this->removeAllTheLinksToAPage($pageID);
        $this->deleteContent("page", "idPage", $pageID);
    }

    /**
     * Controlla se un indirizzo email è già iscritto alla newsletter.
     * @param string $email E-Mail da verificare.
     * @return boolean True se l'email passata come parametro è già iscritta alla newsletter, false altrimenti.
     */
    private function isUserAlreadySubscribed($email) {
        $stmt = $this->db->prepare("SELECT * FROM newsletter_subscribers WHERE subscriberEmail = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    /**
     * Aggiunge un nuovo indirizzo email alla newsletter.
     * @param string $nameSurname Nome e cognome dell'utente che si vuole iscrivere.
     * @param string $email E-Mail dell'utente che si vuole iscrivere.
     * @return int ID della nuova tupla creata nella tabella 'newsletter_subscribers'.
     * @throws Exception Se esiste già un utente iscritto con la email passata come parametro.
     */
    public function addToTheNewsletter($nameSurname, $email) {
        if ($this->isUserAlreadySubscribed($email)) {
            throw new Exception("Ti sei già registrato con questa mail.");
        }
        $registrationDate = date('Y-m-d');
        $stmt = $this->db->prepare("INSERT INTO newsletter_subscribers (subscriberNameSurname, subscriberEmail, registrationDate) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $nameSurname, $email, $registrationDate);
        $stmt->execute();
        return $stmt->insert_id;
    }

    /**
     * Restituisce tutti gli indirizzi email iscritti alla newsletter.
     * @return array{ email: string }[] Array contenente le email iscritte.
     */
    public function getNewsletterSubscribers() {
        $stmt = $this->db->prepare("SELECT subscriberEmail as email FROM newsletter_subscribers");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
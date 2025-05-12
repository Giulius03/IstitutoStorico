<?php
class DatabaseHelper{
    private $db;

    public function __construct(){
        $this->db = new mysqli("localhost", "root", "", "databaseistitutoperprove");
        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }        
    }

    /**
     * Verifica l'esistenza dell'utente che sta cercando di effettuare il login
     * @param $userEmail E-Mail dell'utente
     * @return array E-Mail, password e flag admin dell'utente (se presente)
     */
    public function checkLogin($userEmail) {
        $stmt = $this->db->prepare("SELECT userEmail, password, adminYN FROM users WHERE userEmail = ?");
        $stmt->bind_param('s', $userEmail);
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
     * @return array ID, titolo, data di creazione, ultima data di aggiornamento e tipologia di tutte le pagine
     * presenti al momento della richiesta
     */
    public function getPages($orderedBy) {
        $stmt = $this->db->prepare("SELECT idPage, title, creationDate, updatedDate FROM page ORDER BY $orderedBy " . ($orderedBy == "updatedDate" ? "DESC" : ""));
        $stmt->execute();
        $result = $stmt->get_result();
        $pages = $result->fetch_all(MYSQLI_ASSOC);

        foreach ($pages as &$page) {
            $page['type'] = $this->getPageType($page['idPage']);
        }
        unset($page);

        return $pages;
    }

    private function getNonPages($table, $idField, $nameField) {
        $stmt = $this->db->prepare("SELECT $idField as ID, $nameField as name FROM $table");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * @return array ID e nome di tutti i menù presenti al momento della richiesta
     */
    public function getMenus() {
        return $this->getNonPages("menu", "idMenu", "menuName");
    }

    /**
     * @return array ID e nome di tutti i tag presenti al momento della richiesta
     */
    public function getTags() {
        return $this->getNonPages("tag", "idTag", "tagName");
    }

    private function getNumOfContents($table) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM $table");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_row()[0];
    }

    /**
     * @return int Il numero di tag presenti
     */
    public function getNumOfTags() {
        return $this->getNumOfContents("tag");
    }

    /**
     * @return array ID e nome di tutti gli articoli d'inventario presenti al momento della richiesta
     */
    public function getInventoryItems() {
        return $this->getNonPages("inventoryItem", "idInventoryItem", "inventoryItemName");
    }

    /**
     * @return array ID e nome di tutti gli strumenti di corredo presenti al momento della richiesta
     */
    public function getReferenceTools() {
        return $this->getNonPages("referencetool", "idReferenceTool", "nameReferenceTool");
    }

    public function getNumOfReferenceTools() {
        return $this->getNumOfContents("referencetool");
    }

    public function getNumOfInventoryItems() {
        return $this->getNumOfContents("inventoryitem");
    }

    public function getPageFromID($pageID) {
        $stmt = $this->db->prepare("SELECT title, slug, text, isVisibile, seoTitle, seoText, seoKeywords, author FROM page WHERE idPage = ?");
        $stmt->bind_param('i', $pageID);
        $stmt->execute();
        $result = $stmt->get_result();
        $page = $result->fetch_assoc();
        $page['type'] = $this->getPageType($pageID);
        return $page;
    }

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

    public function getIndexItemsFromPageID($pageID) {
        $stmt = $this->db->prepare("SELECT idIndexItem as ID, indexItemTitle as title, orderedPosition as position FROM indexitem WHERE shownInPageId = ?");
        $stmt->bind_param('i', $pageID);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getNotesFromPageID($pageID) {
        $stmt = $this->db->prepare("SELECT noteId as ID, noteText as text FROM note WHERE page_idPage = ?");
        $stmt->bind_param('i', $pageID);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getIndexItemFromID($indexItemID) {
        $stmt = $this->db->prepare("SELECT orderedPosition as position, targetAnchor as anchor, linkToPage as page, indexItemTitle as title FROM indexitem WHERE idIndexItem = ?");
        $stmt->bind_param('i', $indexItemID);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);  
    }

    public function getNoteFromID($noteID) {
        $stmt = $this->db->prepare("SELECT noteText as text, noteAnchor as anchor, page_idPage as page, author FROM note WHERE noteId = ?");
        $stmt->bind_param('i', $noteID);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);  
    }

    public function getArchivePageFromPageID($pageID) {
        $stmt = $this->db->prepare("SELECT chronologyStartYear as start, chronologyEndYear as end FROM archivepage WHERE Page_idPage = ?");
        $stmt->bind_param('i', $pageID);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

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

    public function getResourceCollectionFromPageID($pageID) {
        $stmt = $this->db->prepare("SELECT nomeRaccolta as nome, pathRaccolta as path FROM raccoltadirisorse WHERE Page_idPage = ?");
        $stmt->bind_param('i', $pageID);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getBibliographyElementsFromPageID($pageID) {
        $stmt = $this->db->prepare("SELECT b.elementoDiRaccolta_idelementoDiRaccolta as ID, b.citazioneBibliografia as cit FROM elementobibliografia b INNER JOIN raccoltadirisorse r ON r.idRaccoltaDiRisorse = b.elementoDiRaccolta_RaccoltaDiRisorse_idRaccoltaDiRisorse WHERE r.Page_idPage = ?");
        $stmt->bind_param('i', $pageID);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getChronologyElementsFromPageID($pageID) {
        $stmt = $this->db->prepare("SELECT c.elementoDiRaccolta_idelementoDiRaccolta as ID, c.idElementoCronologia as data, c.localita as loc FROM elementocronologia c INNER JOIN raccoltadirisorse r ON r.idRaccoltaDiRisorse = c.elementoDiRaccolta_RaccoltaDiRisorse_idRaccoltaDiRisorse WHERE r.Page_idPage = ?");
        $stmt->bind_param('i', $pageID);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getNewsPaperLibraryElementsFromPageID($pageID) {
        $stmt = $this->db->prepare("SELECT e.elementoDiRaccolta_idelementoDiRaccolta as ID, e.nomeTestataGiornalistica as giornale, e.titoloArticolo as titolo FROM elementoemeroteca e INNER JOIN raccoltadirisorse r ON r.idRaccoltaDiRisorse = e.elementoDiRaccolta_RaccoltaDiRisorse_idRaccoltaDiRisorse WHERE r.Page_idPage = ?");
        $stmt->bind_param('i', $pageID);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getPhotoLibraryElementsFromPageID($pageID) {
        $stmt = $this->db->prepare("SELECT f.elementoDiRaccolta_idelementoDiRaccolta as ID, f.descrizioneElementoFototeca as descrizione FROM elementofototeca f INNER JOIN raccoltadirisorse r ON r.idRaccoltaDiRisorse = f.elementoDiRaccolta_RaccoltaDiRisorse_idRaccoltaDiRisorse WHERE r.Page_idPage = ?");
        $stmt->bind_param('i', $pageID);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getNetworkResourcesFromPageID($pageID) {
        $stmt = $this->db->prepare("SELECT n.elementoDiRaccolta_idelementoDiRaccolta as ID, n.titoloRisorsa as titolo, n.fonte as fonte FROM elementorisorsa n INNER JOIN raccoltadirisorse r ON r.idRaccoltaDiRisorse = n.elementoDiRaccolta_RaccoltaDiRisorse_idRaccoltaDiRisorse WHERE r.Page_idPage = ?");
        $stmt->bind_param('i', $pageID);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    private function getNonPageContentFromID($table, $contentID, $idField, $nameField) {
        $query = "SELECT $nameField as name" . ($table == "tag" ? ", tagDescription as description" : "") . " FROM $table WHERE $idField = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $contentID);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getTagFromID($tagID) {
        return $this->getNonPageContentFromID("tag", $tagID, "idTag", "tagName");
    }

    public function getInventoryItemFromID($inventoryItemID) {
        return $this->getNonPageContentFromID("inventoryItem", $inventoryItemID, "idInventoryItem", "inventoryItemName");
    }

    public function getReferenceToolFromID($referenceToolID) {
        return $this->getNonPageContentFromID("referencetool", $referenceToolID, "idReferenceTool", "nameReferenceTool");
    }

    public function getMenuFromID($menuID) {
        $stmt = $this->db->prepare("SELECT idMenu as ID, menuName as name FROM menu WHERE idMenu = ?");
        $stmt->bind_param('i', $menuID);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getMenuItems($menuID) {
        $stmt = $this->db->prepare("SELECT idMenuItem as ID, menuItemName as name, menuItemOrderedPosition as position, Page_idPage as page, MenuItem_idMenuItem as father FROM menuitem WHERE Menu_idMenu = ?");
        $stmt->bind_param('i', $menuID);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getMenuItemFromID($menuItemID) {
        $stmt = $this->db->prepare("SELECT idMenuItem as ID, menuItemName as name, menuItemOrderedPosition as position, Page_idPage as page, MenuItem_idMenuItem as father FROM menuitem WHERE idMenuItem = ?");
        $stmt->bind_param('i', $menuItemID);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function addPage($title, $slug, $html, $isVisible, $seoTitle, $seoText, $seoKeywords) {
        $creationDate = date('Y-m-d H:i:s');
        $stmt = $this->db->prepare("INSERT INTO page (title, slug, text, creationDate, updatedDate, isVisibile, seoTitle, seoText, seoKeywords) VALUES (?, ?, ?, '$creationDate', '$creationDate', ?, ?, ?, ?)");
        $stmt->bind_param('sssisss', $title, $slug, $html, $isVisible, $seoTitle, $seoText, $seoKeywords);
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function connectTagsToPage($pageID, $tagsID) {
        foreach ($tagsID as $tagID) {
            $stmt = $this->db->prepare("INSERT INTO page_has_tag (page_idPage, tag_idTag) VALUES (?, ?)");
            $stmt->bind_param('ii', $pageID, $tagID);
            $stmt->execute();
        }
    }

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

    private function getAllDisplayedPagesFromTags($tagsID) {
        $query = "SELECT DISTINCT page_idPage as ID FROM page_has_tag WHERE ";
        for ($i=0; $i < count($tagsID); $i++) { 
            $query .= $i != 0 ? " OR " : "";
            $query .= "tag_idTag = " . $tagsID[$i];
        }
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function addIndexItem($position, $shownInPageID, $targetAnchor, $linkToPage, $title) {
        $stmt = $this->db->prepare("INSERT INTO indexitem (orderedPosition, shownInPageId, targetAnchor, linkToPage, indexItemTitle) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('iisis', $position, $shownInPageID, $targetAnchor, $linkToPage, $title);
        $stmt->execute();
    }

    public function addNote($anchor, $text, $pageID, $author) {
        $stmt = $this->db->prepare("INSERT INTO note (noteAnchor, noteText, page_idPage, author) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('ssis', $anchor, $text, $pageID, $author);
        $stmt->execute();
    }

    public function addArchivePage($chronologyStartYear, $chronologyEndYear, $pageID) {
        $stmt = $this->db->prepare("INSERT INTO archivepage (chronologyStartYear, chronologyEndYear, Page_idPage) VALUES (?, ?, ?)");
        $stmt->bind_param('ssi', $chronologyStartYear, $chronologyEndYear, $pageID);
        $stmt->execute();
    }

    public function connectReferenceToolsToPage($pageID, $referenceToolsID) {
        foreach ($referenceToolsID as $referenceToolID) {
            $stmt = $this->db->prepare("INSERT INTO archivepage_has_referencetool (ArchivePage_Page_idPage, ReferenceTool_idReferenceTool) VALUES (?, ?)");
            $stmt->bind_param('ii', $pageID, $referenceToolID);
            $stmt->execute();
        }
    }

    public function connectInventoryItemsToPage($pageID, $inventoryItems) {
        foreach ($inventoryItems as $inventoryItem) {
            $stmt = $this->db->prepare("INSERT INTO archivepage_has_inventoryitem (ArchivePage_Page_idPage, inventoryItem_idInventoryItem, itemQuantity) VALUES (?, ?, ?)");
            $stmt->bind_param('iii', $pageID, $inventoryItem['articolo'], $inventoryItem['quantita']);
            $stmt->execute();
        }
    }

    public function addResourceCollection($collectionName, $path, $pageID) {
        $stmt = $this->db->prepare("INSERT INTO raccoltadirisorse (nomeRaccolta, pathRaccolta, Page_idPage) VALUES (?, ?, ?)");
        $stmt->bind_param('ssi', $collectionName, $path, $pageID);
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function addCollectionElement($resourceCollectionID) {
        $stmt = $this->db->prepare("INSERT INTO elementodiraccolta (RaccoltaDiRisorse_idRaccoltaDiRisorse) VALUES (?)");
        $stmt->bind_param('i', $resourceCollectionID);
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function addBibliographyElement($cit, $elementID, $collectionID, $href, $doi) {
        $stmt = $this->db->prepare("INSERT INTO elementobibliografia (citazioneBibliografia, elementoDiRaccolta_idelementoDiRaccolta, elementoDiRaccolta_RaccoltaDiRisorse_idRaccoltaDiRisorse, href, DOI) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('siiss', $cit, $elementID, $collectionID, $href, $doi);
        $stmt->execute();
    }

    public function addChronologyElement($date, $elementID, $collectionID, $location, $description) {
        $stmt = $this->db->prepare("INSERT INTO elementocronologia (idElementoCronologia, elementoDiRaccolta_idelementoDiRaccolta, elementoDiRaccolta_RaccoltaDiRisorse_idRaccoltaDiRisorse, localita, descrizioneElementoCronologia) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('siiss', $date, $elementID, $collectionID, $location, $description);
        $stmt->execute();
    }

    public function addNewsPaperLibraryElement($journal, $publicationDate, $elementID, $collectionID, $title, $href) {
        $stmt = $this->db->prepare("INSERT INTO elementoemeroteca (nomeTestataGiornalistica, elementoDiRaccolta_idelementoDiRaccolta, elementoDiRaccolta_RaccoltaDiRisorse_idRaccoltaDiRisorse, href, dataPubblicazione, titoloArticolo) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('siisss', $journal, $elementID, $collectionID, $href, $publicationDate, $title);
        $stmt->execute();
    }

    public function addPhotoLibraryElement($description, $elementID, $collectionID) {
        $stmt = $this->db->prepare("INSERT INTO elementofototeca (descrizioneElementoFototeca, elementoDiRaccolta_idelementoDiRaccolta, elementoDiRaccolta_RaccoltaDiRisorse_idRaccoltaDiRisorse) VALUES (?, ?, ?)");
        $stmt->bind_param('sii', $description, $elementID, $collectionID);
        $stmt->execute();
    }

    public function addNetworkResource($type, $elementID, $collectionID, $title, $href, $source, $doi) {
        $stmt = $this->db->prepare("INSERT INTO elementorisorsa (tipologiaRisorsa, elementoDiRaccolta_idelementoDiRaccolta, elementoDiRaccolta_RaccoltaDiRisorse_idRaccoltaDiRisorse, titoloRisorsa, hrefRisorsa, fonte, DOI) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('siissss', $type, $elementID, $collectionID, $title, $href, $source, $doi);
        $stmt->execute();
    }

    public function addMenu($name) {
        $stmt = $this->db->prepare("INSERT INTO menu (menuName) VALUES (?)");
        $stmt->bind_param('s', $name);
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function addMenuItem($name, $position, $menu, $pageToLink, $father) {
        $stmt = $this->db->prepare("INSERT INTO menuitem (menuItemName, menuItemOrderedPosition, Menu_idMenu, Page_idPage, MenuItem_idMenuItem) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('siiii', $name, $position, $menu, $pageToLink, $father);
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function getMenuItemsNextID() {
        $stmt = $this->db->prepare("SHOW TABLE STATUS LIKE 'menuitem'");
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        return $rows[0]['Auto_increment'];
    }

    public function addTag($name, $description) {
        $stmt = $this->db->prepare("INSERT INTO tag (tagName, tagDescription) VALUES (?, ?)");
        $stmt->bind_param('ss', $name, $description);
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function addReferenceTool($name) {
        $stmt = $this->db->prepare("INSERT INTO referencetool (nameReferenceTool) VALUES (?)");
        $stmt->bind_param('s', $name);
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function addInventoryItem($name) {
        $stmt = $this->db->prepare("INSERT INTO inventoryitem (inventoryItemName) VALUES (?)");
        $stmt->bind_param('s', $name);
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function updatePage($pageID, $newTitle, $newSlug, $newContent, $newIsVisible, $newSeoTitle, $newSeoText, $newSeoKeywords, $newAuthor) {
        $page = $this->getPageFromID($pageID);
        if ($page['title'] != $newTitle || $page['slug'] != $newSlug || $page['text'] != $newContent || $page['isVisibile'] != $newIsVisible || $page['seoTitle'] != $newSeoTitle || $page['seoText'] != $newSeoText || $page['seoKeywords'] != $newSeoKeywords || $page['author'] != $newAuthor) {
            $stmt = $this->db->prepare("UPDATE page SET title = ?, slug = ?, text = ?, isVisibile = ?, seoTitle = ?, seoText = ?, seoKeywords = ?, author = ? WHERE idPage = ?");
            $stmt->bind_param('sssissssi', $newTitle, $newSlug, $newContent, $newIsVisible, $newSeoTitle, $newSeoText, $newSeoKeywords, $newAuthor, $pageID);
            $stmt->execute();
        }
    }

    public function updatePageTags($pageID, $newTags) {
        $tags = $this->getTagsFromPageID($pageID);
        sort($tags);
        sort($newTags);
        if ($tags !== $newTags) {
            $this->deleteContent("page_has_tag", "page_idPage", $pageID);
            $this->connectTagsToPage($pageID, $newTags);
        }
    }

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

    public function updateArchivePage($pageID, $newStartYear, $newEndYear) {
        $archivePage = $this->getArchivePageFromPageID($pageID);
        if ($archivePage[0]['start'] != $newStartYear || $archivePage[0]['end'] != $newEndYear) {
            $stmt = $this->db->prepare("UPDATE archivepage SET chronologyStartYear = ?, chronologyEndYear = ? WHERE Page_idPage = ?");
            $stmt->bind_param('iii', $newStartYear, $newEndYear, $pageID);
            $stmt->execute();
        }
    }

    public function updateArchivePageReferenceTools($pageID, $newReferenceTools) {
        $refTools = $this->getReferenceToolsFromArchivePageID($pageID);
        sort($refTools);
        sort($newReferenceTools);
        if ($refTools !== $newReferenceTools) {
            $this->deleteContent("archivepage_has_referencetool", "ArchivePage_Page_idPage", $pageID);
            $this->connectReferenceToolsToPage($pageID, $newReferenceTools);
        }
    }

    public function updateArchivePageInventoryItems($pageID, $newInventoryItems) {
        $invItems = $this->getInventoryItemsFromArchivePageID($pageID);
        sort($invItems);
        var_dump($invItems);
        sort($newInventoryItems);
        var_dump($newInventoryItems);
        if ($invItems !== $newInventoryItems) {
            $this->deleteContent("archivepage_has_inventoryitem", "ArchivePage_Page_idPage", $pageID);
            $this->connectInventoryItemsToPage($pageID, $newInventoryItems);
        }
    }

    public function updateResourceCollection($pageID, $newCollectionName, $newPath) {
        $resourceCollection = $this->getResourceCollectionFromPageID($pageID);
        if ($resourceCollection[0]['nome'] != $newCollectionName || $resourceCollection[0]['path'] != $newPath) {
            $stmt = $this->db->prepare("UPDATE raccoltadirisorse SET nomeRaccolta = ?, pathRaccolta = ? WHERE Page_idPage = ?");
            $stmt->bind_param('ssi', $newCollectionName, $newPath, $pageID);
            $stmt->execute();
        }
    }

    public function updateIndexItem($indexItemID, $newTitle, $newPosition, $newLinkToPage, $newDestAnchor) {
        $item = $this->getIndexItemFromID($indexItemID);
        if ($item[0]['title'] != $newTitle || $item[0]['anchor'] != $newDestAnchor || $item[0]['position'] != $newPosition || $item[0]['page'] != $newLinkToPage) {
            $stmt = $this->db->prepare("UPDATE indexitem SET orderedPosition = ?, targetAnchor = ?, linkToPage = ?, indexItemTitle = ? WHERE idIndexItem = ?");
            $stmt->bind_param('isisi', $newPosition, $newDestAnchor, $newLinkToPage, $newTitle, $indexItemID);
            $stmt->execute();
        }
    }

    public function updateNote($noteID, $newText, $newAuthor, $newAnchor) {
        $note = $this->getNoteFromID($noteID);
        if ($note[0]['text'] != $newText || $note[0]['anchor'] != $newAnchor || $note[0]['author'] != $newAuthor) {
            $stmt = $this->db->prepare("UPDATE note SET noteAnchor = ?, noteText = ?, author = ? WHERE noteId = ?");
            $stmt->bind_param('sssi', $newAnchor, $newText, $newAuthor, $noteID);
            $stmt->execute();
        }
    }

    public function updateMenu($menuID, $newMenuName) {
        $menu = $this->getMenuFromID($menuID);
        if ($menu[0]['name'] != $newMenuName) {
            $stmt = $this->db->prepare("UPDATE menu SET menuName = ? WHERE idMenu = ?");
            $stmt->bind_param('si', $newMenuName, $menuID);
            $stmt->execute();
        }
    }

    public function updateMenuItem($menuItemID, $newMenuItemName, $newMenuItemFather, $newMenuItemPosition, $newMenuItemLinkPage) {
        $item = $this->getMenuItemFromID($menuItemID);
        if ($item[0]['name'] != $newMenuItemName || $item[0]['father'] != $newMenuItemFather || $item[0]['position'] != $newMenuItemPosition || $item[0]['page'] != $newMenuItemLinkPage) {
            $stmt = $this->db->prepare("UPDATE menuitem SET menuItemName = ?, menuItemOrderedPosition = ?, Page_idPage = ?, MenuItem_idMenuItem = ? WHERE idMenuItem = ?");
            $stmt->bind_param('siiii', $newMenuItemName, $newMenuItemPosition, $newMenuItemLinkPage, $newMenuItemFather, $menuItemID);
            $stmt->execute();
        }
    }
    
    public function updateTag($tagID, $newTagName, $newTagDescription) {
        $tag = $this->getTagFromID($tagID);
        if ($tag[0]['name'] != $newTagName || $tag[0]['description'] != $newTagDescription) {
            $stmt = $this->db->prepare("UPDATE tag SET tagName = ?, tagDescription = ? WHERE idTag = ?");
            $stmt->bind_param('ssi', $newTagName, $newTagDescription, $tagID);
            $stmt->execute();
        }
    }

    public function updateReferenceTool($referenceToolID, $newReferenceToolName) {
        $refTool = $this->getReferenceToolFromID($referenceToolID);
        if ($refTool[0]['name'] != $newReferenceToolName) {
            $stmt = $this->db->prepare("UPDATE referencetool SET nameReferenceTool = ? WHERE idReferenceTool = ?");
            $stmt->bind_param('si', $newReferenceToolName, $referenceToolID);
            $stmt->execute();
        }
    }

    public function updateInventoryItem($inventoryItemID, $newInventoryItemName) {
        $invItem = $this->getInventoryItemFromID($inventoryItemID);
        if ($invItem[0]['name'] != $newInventoryItemName) {
            $stmt = $this->db->prepare("UPDATE inventoryitem SET inventoryItemName = ? WHERE idInventoryItem = ?");
            $stmt->bind_param('si', $newInventoryItemName, $inventoryItemID);
            $stmt->execute();
        }
    }

    private function deleteContent($table, $idField, $contentID) {
        $stmt = $this->db->prepare("DELETE FROM $table WHERE $idField = ?");
        $stmt->bind_param('i', $contentID);
        $stmt->execute();
    }

    public function deleteMenu($menuID) {
        $this->deleteContent("menu", "idMenu", $menuID);
    }
    
    public function deleteAllMenuItems($menuID) {
        $this->deleteContent("menuitem", "Menu_idMenu", $menuID);
    }

    private function releaseItemChildren($menuItemID) {
        $stmt = $this->db->prepare("UPDATE menuitem SET MenuItem_idMenuItem = NULL WHERE MenuItem_idMenuItem = ?");
        $stmt->bind_param('i', $menuItemID);
        $stmt->execute();
    }

    public function deleteMenuItem($menuItemID) {
        $this->releaseItemChildren($menuItemID);
        $this->deleteContent("menuitem", "idMenuItem", $menuItemID);
    }

    public function deleteTag($tagID) {
        $this->deleteContent("tag", "idTag", $tagID);
    }

    public function deleteReferenceTool($referenceToolID) {
        $this->deleteContent("referencetool", "idReferenceTool", $referenceToolID);
    }

    public function deleteInventoryItem($inventoryItemID) {
        $this->deleteContent("inventoryitem", "idInventoryItem", $inventoryItemID);
    }

    public function deleteIndexItem($indexItemID) {
        $this->deleteContent("indexitem", "idIndexItem", $indexItemID);
    }

    public function deleteNote($noteID) {
        $this->deleteContent("note", "noteId", $noteID);
    }
}
?>
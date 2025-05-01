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

    public function getTagFromID($tagID) {
        $stmt = $this->db->prepare("SELECT tagName as name, tagDescription as description FROM tag WHERE idTag = ?");
        $stmt->bind_param('i', $tagID);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * @return int Il numero di tag presenti
     */
    public function getNumOfTags() {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM tag");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_row()[0];
    }

    /**
     * @return array ID e nome di tutti gli articoli d'inventario presenti al momento della richiesta
     */
    public function getInventoryItems() {
        return $this->getNonPages("inventoryItem", "idInventoryItem", "inventoryItemName");
    }

    public function getInventoryItemFromID($inventoryItemID) {
        $stmt = $this->db->prepare("SELECT inventoryItemName as name FROM inventoryItem WHERE idInventoryItem = ?");
        $stmt->bind_param('i', $inventoryItemID);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * @return array ID e nome di tutti gli strumenti di corredo presenti al momento della richiesta
     */
    public function getReferenceTools() {
        return $this->getNonPages("referencetool", "idReferenceTool", "nameReferenceTool");
    }

    public function getReferenceToolFromID($referenceToolID) {
        $stmt = $this->db->prepare("SELECT nameReferenceTool as name FROM referencetool WHERE idReferenceTool = ?");
        $stmt->bind_param('i', $referenceToolID);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function addPage($title, $slug, $html, $isVisible, $seoTitle, $seoText, $seoKeywords) {
        $creationDate = date('Y-m-d H:i:s');
        $stmt = $this->db->prepare("INSERT INTO page (title, slug, text, creationDate, updatedDate, isVisibile, seoTitle, seoText, seoKeywords) 
                    VALUES (?, ?, ?, '$creationDate', '$creationDate', ?, ?, ?, ?)");
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

    public function addMenu($name) {
        $stmt = $this->db->prepare("INSERT INTO menu (menuName) VALUES (?)");
        $stmt->bind_param('s', $name);
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function addMenuItem($name, $position, $menu, $pageToLink, $father = null) {
        $stmt = $this->db->prepare("INSERT INTO menuitem (menuItemName, menuItemOrderedPosition, Menu_idMenu, Page_idPage, MenuItem_idMenuItem) 
                    VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('siiii', $name, $position, $menu, $pageToLink, $father);
        $stmt->execute();
        return $stmt->insert_id;
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
}
?>
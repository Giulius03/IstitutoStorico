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

    /**
     * @return array ID e nome di tutti gli strumenti di corredo presenti al momento della richiesta
     */
    public function getReferenceTools() {
        return $this->getNonPages("referencetool", "idReferenceTool", "nameReferenceTool");
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
        $stmt = $this->db->prepare("SELECT idMenuItem as ID, menuItemName as name, menuItemOrderedPosition as position,
            Page_idPage as page, MenuItem_idMenuItem as father FROM menuitem WHERE Menu_idMenu = ?");
        $stmt->bind_param('i', $menuID);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getMenuItemFromID($menuItemID) {
        $stmt = $this->db->prepare("SELECT idMenuItem as ID, menuItemName as name, menuItemOrderedPosition as position,
            Page_idPage as page, MenuItem_idMenuItem as father FROM menuitem WHERE idMenuItem = ?");
        $stmt->bind_param('i', $menuItemID);
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

    public function addMenuItem($name, $position, $menu, $pageToLink, $father) {
        $stmt = $this->db->prepare("INSERT INTO menuitem (menuItemName, menuItemOrderedPosition, Menu_idMenu, Page_idPage, MenuItem_idMenuItem) 
                    VALUES (?, ?, ?, ?, ?)");
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
}
?>
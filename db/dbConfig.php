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
    public function getPages() {
        $stmt = $this->db->prepare("SELECT idPage, title, creationDate, updatedDate FROM page");
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
}
?>
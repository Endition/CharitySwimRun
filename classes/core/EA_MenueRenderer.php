<?php

namespace EndeAuswertung\classes\core;

use EndeAuswertung\classes\model\EA_User;

class EA_MenueRenderer
{

    private $menueStruktur = array(
        "00-00-00" => array("name" => "Start", "doc" => "start", "icon" => "fas fa-house fa-fw", "necassaryRoleId"=>EA_User::USERROLE_ANMELDUNG),
        "01-00-00" => array("name" => "Dashboard", "doc" => "dashboard", "icon" => "fas fa-dashboard", "necassaryRoleId"=>EA_User::USERROLE_ANMELDUNG),
        "02-00-00" => array("name" => "Administration", "doc" => "admin", "icon" => "fas fa-wrench fa-fw", "necassaryRoleId"=>EA_User::USERROLE_ADMIN,
            "subMenue" => array(
                "02-01-00" => array("name" => "Datenbank", "doc" => "db", "icon" => "fa fa-database fa-fw", "necassaryRoleId"=>EA_User::USERROLE_ADMIN),
                "02-02-00" => array("name" => "Users", "doc" => "users", "icon" => "fa fa-user fa-fw", "necassaryRoleId"=>EA_User::USERROLE_ADMIN),
                "02-03-00" => array("name" => "Einstellungen", "doc" => "konfiguration", "icon" => "fa fa-cog fa-fw", "necassaryRoleId"=>EA_User::USERROLE_ADMIN),
                "02-04-00" => array("name" => "Altersklassen", "doc" => "altersklassen", "icon" => "fa fa-users fa-fw", "necassaryRoleId"=>EA_User::USERROLE_ADMIN),
                "02-05-00" => array("name" => "Strecken", "doc" => "strecken", "icon" => "fa fa-map-signs fa-fw", "necassaryRoleId"=>EA_User::USERROLE_ADMIN),
                "02-06-00" => array("name" => "Mannschaftskategorie", "doc" => "mannschaftskategorie", "icon" => "fa fa-tag fa-fw", "necassaryRoleId"=>EA_User::USERROLE_ADMIN),
                "02-07-00" => array("name" => "Sonderauswertungen", "doc" => "specialevaluation", "icon" => "fa flag-checkered fa-fw", "necassaryRoleId"=>EA_User::USERROLE_ADMIN),
                "02-08-00" => array("name" => "Urkunden", "doc" => "urkundengenerator", "icon" => "fa fa-file-alt fa-fw", "necassaryRoleId"=>EA_User::USERROLE_ADMIN),
                "02-09-00" => array("name" => "Starterimport", "doc" => "import", "icon" => "fa fa-exchange-alt fa-fw", "necassaryRoleId"=>EA_User::USERROLE_ADMIN),
                "02-11-00" => array("name" => "Buchungsübersicht", "doc" => "buchungsuebersicht", "icon" => "fa fa-bars fa-fw", "necassaryRoleId"=>EA_User::USERROLE_ADMIN),
                "02-12-00" => array("name" => "Teilnehmerübersicht", "doc" => "teilnehmeruebersicht", "icon" => "fa fa-address-book  fa-fw", "necassaryRoleId"=>EA_User::USERROLE_ADMIN),
            )
        ),
        "03-00-00" => array("name" => "Eingabe und Verwaltung", "doc" => "eingabe", "icon" => "fas fa-edit fa-fw",  "necassaryRoleId"=>EA_User::USERROLE_ANMELDUNG,
            "subMenue" => array(
                "03-01-00" => array("name" => "Teilnehmer", "doc" => "teilnehmer", "icon" => "fa fa-user fa-fw", "necassaryRoleId"=>EA_User::USERROLE_ANMELDUNG),
                "03-02-00" => array("name" => "Mannschaft", "doc" => "mannschaften", "icon" => "fa fa-users fa-fw", "necassaryRoleId"=>EA_User::USERROLE_ANMELDUNG),
                "03-03-00" => array("name" => "Vereine", "doc" => "vereine", "icon" => "fa fa-university fa-fw", "necassaryRoleId"=>EA_User::USERROLE_ANMELDUNG),
                "03-04-00" => array("name" => "Buchungen eines Starters", "doc" => "buchungenstarter", "icon" => "fa fa-book fa-fw", "necassaryRoleId"=>EA_User::USERROLE_ANMELDUNG),
                "03-05-00" => array("name" => "Startzeiten", "doc" => "startzeiten", "icon" => "fa fa-clock fa-fw", "necassaryRoleId"=>EA_User::USERROLE_ANMELDUNG),
                "03-06-00" => array("name" => "Transponderückgabe", "doc" => "transponderrueckgabe", "icon" => "fa fa-reply fa-fw", "necassaryRoleId"=>EA_User::USERROLE_ANMELDUNG),
                "03-07-00" => array("name" => "Fehlbuchungen", "doc" => "fehlbuchungen", "icon" => "fa fa-compress fa-fw", "necassaryRoleId"=>EA_User::USERROLE_ANMELDUNG),
                "03-08-00" => array("name" => "manuelle Eingabe", "doc" => "manuelleeingabe", "icon" => "fa fa-hand-paper fa-fw", "necassaryRoleId"=>EA_User::USERROLE_ANMELDUNG),
                "03-09-00" => array("name" => "Status verwalten", "doc" => "statusverwalten", "icon" => "fa fa-sitemap fa-fw", "necassaryRoleId"=>EA_User::USERROLE_ANMELDUNG),
            )
        ),
        "04-00-00" => array("name" => "Auswertung", "doc" => "auswertung", "icon" => "fas fa-chart-bar fa-fw", "necassaryRoleId"=>EA_User::USERROLE_ANMELDUNG,
            "subMenue" => array(
                "04-01-00" => array("name" => "Spezialabfragen", "doc" => "spezialabfragen", "icon" => "fa fa-tasks fa-fw", "necassaryRoleId"=>EA_User::USERROLE_ANMELDUNG),
                "04-04-00" => array("name" => "Ergebnisse", "doc" => "ergebnisse", "icon" => "fa fa-trophy fa-fw", "necassaryRoleId"=>EA_User::USERROLE_ANMELDUNG),
                "04-06-00" => array("name" => "Urkunden", "doc" => "urkunden", "icon" => "fa fa-file fa-fw", "necassaryRoleId"=>EA_User::USERROLE_ANMELDUNG),
                "04-07-00" => array("name" => "Meldelisten", "doc" => "meldelisten", "icon" => "fa fa-file-alt fa-fw", "necassaryRoleId"=>EA_User::USERROLE_ANMELDUNG),
                "04-08-00" => array("name" => "Statistik", "doc" => "statistik", "icon" => "fa fa-chart-line fa-fw", "necassaryRoleId"=>EA_User::USERROLE_ANMELDUNG),
            )
        ),
        "05-00-00" => array("name" => "Livebuchungen", "doc" => "livebuchungen.php", "href" => "special", "icon" => "fa fa-heart-pulse fa-fw", "necassaryRoleId"=>EA_User::USERROLE_REMOTE_DISPLAY),
        "06-00-00" => array("name" => "Simulator", "doc" => "simulator.php", "href" => "special", "icon" => "fa fa-robot fa-fw", "necassaryRoleId"=>EA_User::USERROLE_REMOTE_DISPLAY),
        "07-00-00" => array("name" => "Kurzauskunft", "doc" => "kurzauskunft", "icon" => "fa fa-circle-info fa-fw", "necassaryRoleId"=>EA_User::USERROLE_PUBLIC),
        "08-00-00" => array("name" => "Selbstanmeldung", "doc" => "selbstanmeldung", "icon" => "fa fa-id-card fa-fw", "necassaryRoleId"=>EA_User::USERROLE_PUBLIC),
        "09-00-00" => array("name" => "Logout", "doc" => "logout", "icon" => "fa fa-window-close fa-fw", "necassaryRoleId"=>EA_User::USERROLE_PUBLIC),
        "10-00-00" => array("name" => "Login", "doc" => "login", "icon" => "fa fa-enter fa-fw", "necassaryRoleId"=>EA_User::USERROLE_PUBLIC)
    );

    public function __construct()
    {

    }

    public function getMenueStructure(): array
    {
        return $this->menueStruktur;
    }

    public function getMenue(bool $isTransponderActive): string
    {
        if ($isTransponderActive === false) {
            // Transponderrückgabe ausblenden
            unset($this->menueStruktur['03-00-00']['subMenue']['03-06-00']);

            // Log für Transponder ausblenden
            unset($this->menueStruktur['05-00-00']);
        }

                    //if user is logged in, show logout button. Otherwise show login button
                    if(isset($_SESSION['loggedin'])){
                        unset($this->menueStruktur['10-00-00']);
                    }else{
                        unset($this->menueStruktur['09-00-00']);
                    }

        return $this->renderMenu2($this->menueStruktur, 0);
    }

    public function renderMenu2(array $menue, int $ebene = 0): string
    {
        $content = "";
        $aktuelleseite = (isset($_GET['doc'])) ? $_GET['doc'] : "";

        foreach ($menue as $key => $menueElement) {
            //if element is not public AND no session OR SESSION_USERROLE_ID (1 Admin) BIGGER NECASSARY (10 Anmeldung) -> jump over; if smaller than pass
            if($menueElement['necassaryRoleId'] !== EA_User::USERROLE_PUBLIC && (!isset($_SESSION['userroleId']) || $_SESSION['userroleId'] > $menueElement['necassaryRoleId'])){
                continue;
            }
            $isAktuelleSite = $aktuelleseite === $menueElement['doc'] ? 'active' : '';
            $hasSubMenue = (isset($menueElement['subMenue']) && $menueElement['subMenue'] !== []) ? "has-sub" : "";
            
            $isSiteFromOwnSubMenuActive = "";
            if($hasSubMenue !== ""){
                foreach ($menueElement['subMenue'] as $subKey => $subMenueElement) {
                    if($aktuelleseite === $subMenueElement['doc']){
                        $isSiteFromOwnSubMenuActive = "active submenu-open";
                        break;
                    }
                }
            }
            
            $content .= '<li class="sidebar-item '.$isAktuelleSite.' '.$hasSubMenue.' '.$isSiteFromOwnSubMenuActive.'">';
            if (isset($menueElement['href']) && $menueElement['href'] === "special") {
                $content .= '  <a target= "_blank" href="'.$menueElement['doc'].'" class="sidebar-link">';        
            } else {
                $content .= '  <a href="index.php?doc='.$menueElement['doc'].'" class="sidebar-link">';        
            }
            $content .= '       <i class="'.$menueElement['icon'] .'"></i>';    
            $content .= '       <span>'.$menueElement['name'].'</span>';        
            $content .= '  </a>';   
            if($hasSubMenue !== ""){
                $content .= '  <ul class="submenu">';   
                foreach ($menueElement['subMenue'] as $subKey => $subMenueElement) {
                    //if element is not public AND no session OR SESSION_USERROLE_ID (1 Admin) BIGGER NECASSARY (10 Anmeldung) -> jump over; if smaller than pass
                    if($menueElement['necassaryRoleId'] !== EA_User::USERROLE_PUBLIC && (!isset($_SESSION['userroleId']) || $_SESSION['userroleId'] > $subMenueElement['necassaryRoleId'])){
                        continue;
                    }
                    $isAktuelleSubSite = $aktuelleseite === $subMenueElement['doc'] ? 'active' : '';

                    $content .= '<li class="submenu-item '.$isAktuelleSubSite.' ">';     
                    $content .= '<a href="index.php?doc='.$subMenueElement['doc'].'" class="submenu-link">'.$subMenueElement['name'].'</a>';   
                    $content .= '  </li>';   
                }
                $content .= '  </ul>';   
            }    
            $content .= '</li>';       
        }
        return $content;
    }
}

<?php
namespace CharitySwimRun\classes\controller;

use Doctrine\ORM\EntityManager;
use CharitySwimRun\classes\helper\EA_PlacementHelper;
use CharitySwimRun\classes\model\EA_Starter;
use CharitySwimRun\classes\model\EA_Message;

/**
 * Generates a pre-filled press article based on live event data.
 * Fields that cannot be derived from the database must be entered via a form.
 */
class EA_PressArticleController extends EA_Controller
{
    private EA_PlacementHelper $EA_PlacementHelper;

    public function __construct(EntityManager $entityManager)
    {
        parent::__construct($entityManager);
        $this->EA_PlacementHelper = new EA_PlacementHelper($entityManager);
    }

    public function getPagePressartikel(): string
    {
        $content = '';
        $formData = $this->readFormData();
        $articleData = $this->buildArticleData($formData);
        $content .= $this->renderForm($formData);
        if ($articleData !== null) {
            $content .= $this->renderArticle($articleData);
        }
        return $content;
    }

    /**
     * Reads and sanitises all POST values from the press-article form.
     * Returns the form data as an associative array, falling back to sensible defaults.
     */
    private function readFormData(): array
    {
        $defaults = [
            'ausgabenummer' => '22',
            'vorsitzende' => 'Thomas Ende und Ilka Brümmer',
            'beschenkteName' => 'Maika Rettig und Nicole Terstegen',
            'naechsterBeguenstigter' => 'Jugendtreff Coppenbrügge',
            'anzahlSponsoren' => '36',
            'verwendungszweck' => 'Bau-Blöcke, ein Wald-Tipi und ein Faltzelt',
        ];

        if (!isset($_POST['sendPressartikel'])) {
            return $defaults;
        }

        return [
            'ausgabenummer' => htmlspecialchars(trim($_POST['ausgabenummer'] ?? '')),
            'vorsitzende' => htmlspecialchars(trim($_POST['vorsitzende'] ?? '')),
            'beschenkteName' => htmlspecialchars(trim($_POST['beschenkteName'] ?? '')),
            'naechsterBeguenstigter' => htmlspecialchars(trim($_POST['naechsterBeguenstigter'] ?? '')),
            'anzahlSponsoren' => htmlspecialchars(trim($_POST['anzahlSponsoren'] ?? '')),
            'verwendungszweck' => htmlspecialchars(trim($_POST['verwendungszweck'] ?? '')),
        ];
    }

    /**
     * Collects all data needed for the article: database values + form values.
     * Returns null if no event data exists yet (no participants with hits).
     */
    private function buildArticleData(array $formData): ?array
    {
        if ($this->konfiguration === null) {
            return null;
        }

        // Load sorted participant list and calculate placements
        $this->EA_StarterRepository->berechneStati();
        $teilnehmerList = $this->EA_StarterRepository->loadList(
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            'impulseCache',
            'DESC'
        );

        if (empty($teilnehmerList)) {
            return null;
        }

        $this->EA_PlacementHelper->berechnePlatzierung($teilnehmerList);

        // Global event performance figures
        $global = $this->EA_HitRepository->getGlobaleVeranstaltungsleistungsdaten(
            count($teilnehmerList),
            $this->konfiguration
        );

        // Top-3 placements per gender
        $topMaenner = $this->EA_StarterRepository->loadList(
            null,
            null,
            EA_Starter::GESCHLECHT_M,
            null,
            null,
            null,
            null,
            'impulseCache',
            'DESC',
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            3
        );
        $topFrauen = $this->EA_StarterRepository->loadList(
            null,
            null,
            EA_Starter::GESCHLECHT_W,
            null,
            null,
            null,
            null,
            'impulseCache',
            'DESC',
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            3
        );

        // Count participants over 10,000 meters
        $count10km = 0;
        foreach ($teilnehmerList as $tn) {
            if ($tn->getMeter() >= 10000) {
                $count10km++;
            }
        }

        // Medal statistics
        $medaillenspiegel = $this->EA_StarterRepository->loadMedaillenspiegel($teilnehmerList);
        $anzahlMedaillen = $this->countMedaillen($teilnehmerList);

        // Duration in hours
        $durationHours = ($this->konfiguration->getEnde()->getTimestamp() - $this->konfiguration->getStart()->getTimestamp()) / 3600;

        return [
            'config' => $this->konfiguration,
            'global' => $global,
            'topMaenner' => $topMaenner ?? [],
            'topFrauen' => $topFrauen ?? [],
            'count10km' => $count10km,
            'medaillenspiegel' => $medaillenspiegel,
            'anzahlMedaillen' => $anzahlMedaillen,
            'durationHours' => round($durationHours),
            'anzahlTeilnehmer' => count($teilnehmerList),
            // manual form fields
            'ausgabenummer' => $formData['ausgabenummer'],
            'vorsitzende' => $formData['vorsitzende'],
            'beschenkteName' => $formData['beschenkteName'],
            'naechsterBeguenstigter' => $formData['naechsterBeguenstigter'],
            'anzahlSponsoren' => $formData['anzahlSponsoren'],
            'verwendungszweck' => $formData['verwendungszweck'],
        ];
    }

    /**
     * Counts the total number of medal winners (Bronze, Silver, Gold) across all age groups.
     */
    private function countMedaillen(array $teilnehmerList): int
    {
        $count = 0;
        foreach ($teilnehmerList as $tn) {
            $wertung = $tn->getWertung('kurz');
            if (in_array($wertung, ['B', 'S', 'G'], true)) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * Renders the input form for the fields that cannot be derived from the database.
     */
    private function renderForm(array $formData): string
    {
        $ausgabenummer = htmlspecialchars($formData['ausgabenummer']);
        $vorsitzende = htmlspecialchars($formData['vorsitzende']);
        $beschenkteName = htmlspecialchars($formData['beschenkteName']);
        $naechsterBeguenstigter = htmlspecialchars($formData['naechsterBeguenstigter']);
        $anzahlSponsoren = htmlspecialchars($formData['anzahlSponsoren']);
        $verwendungszweck = htmlspecialchars($formData['verwendungszweck']);

        $beguenstigterHint = $this->konfiguration !== null
            ? '<small class="text-muted">Aktuell in Einstellungen: <strong>' . htmlspecialchars($this->konfiguration->getBeguenstigter()) . '</strong></small>'
            : '';

        return <<<HTML
        <div class="card shadow-sm mb-4">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="fa fa-newspaper text-primary"></i>
                <h5 class="mb-0">Pressemitteilung generieren</h5>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-3">
                    Ergänze die Felder, die nicht automatisch aus den Veranstaltungsdaten ermittelt werden können.
                    Alle anderen Werte (Teilnehmer, Meter, Bahnen, Spendensumme, Platzierungen) werden live aus der Datenbank geladen.
                </p>
                <form method="POST" action="index.php?doc=pressartikel">
                    <div class="row g-3">
                        <div class="col-md-2">
                            <label for="ausgabenummer" class="form-label fw-semibold">Ausgabe-Nr. <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="ausgabenummer" name="ausgabenummer"
                                   value="{$ausgabenummer}" placeholder="z.B. 21" min="1" required>
                            <div class="form-text">Nummer der Veranstaltung (z.B. 21 → "21.")</div>
                        </div>
                        <div class="col-md-5">
                            <label for="vorsitzende" class="form-label fw-semibold">Vorsitzende (DLRG) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="vorsitzende" name="vorsitzende"
                                   value="{$vorsitzende}" placeholder="z.B. Thomas Ende und Ilka Brümmer" required>
                        </div>
                        <div class="col-md-5">
                            <label for="beschenkteName" class="form-label fw-semibold">Namen der Beschenkten <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="beschenkteName" name="beschenkteName"
                                   value="{$beschenkteName}" placeholder="z.B. Maika Rettig und Nicole Terstegen" required>
                            {$beguenstigterHint}
                        </div>
                        <div class="col-md-12">
                            <label for="verwendungszweck" class="form-label fw-semibold">Verwendung der Spende <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="verwendungszweck" name="verwendungszweck"
                                   value="{$verwendungszweck}" placeholder="z.B. Bau-Blöcke, ein Wald-Tipi und ein Faltzelt" required>
                            <div class="form-text">Satzfortsetzung: "[Begünstigter] wird von dem Geld u.a. ... erwerben."</div>
                        </div>
                        <div class="col-md-8">
                            <label for="naechsterBeguenstigter" class="form-label fw-semibold">Nächster Begünstigter (Auslosung) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="naechsterBeguenstigter" name="naechsterBeguenstigter"
                                   value="{$naechsterBeguenstigter}" placeholder="z.B. Jugendtreff Coppenbrügge" required>
                        </div>
                        <div class="col-md-4">
                            <label for="anzahlSponsoren" class="form-label fw-semibold">Anzahl Sponsoren (gesamt) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="anzahlSponsoren" name="anzahlSponsoren"
                                   value="{$anzahlSponsoren}" placeholder="z.B. 36" min="1" required>
                            <div class="form-text">Inkl. Hauptsponsor</div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" name="sendPressartikel" class="btn btn-primary">
                            <i class="fa fa-refresh me-1"></i> Artikel generieren
                        </button>
                    </div>
                </form>
            </div>
        </div>
        HTML;
    }

    /**
     * Renders the full auto-generated press article based on all collected data.
     * The article text is structured analogously to the standard news template.
     */
    private function renderArticle(array $d): string
    {
        $config = $d['config'];
        $global = $d['global'];
        $ausgabe = (int) $d['ausgabenummer'];

        $veranstaltungsname = htmlspecialchars($config->getVeranstaltungsname());
        $beguenstigter = htmlspecialchars($config->getBeguenstigter());
        $sponsor = htmlspecialchars($config->getSponsor());
        $durationHours = (int) $d['durationHours'];
        $anzahlTeilnehmer = number_format($d['anzahlTeilnehmer'], 0, ',', '.');
        $streckenart = htmlspecialchars($global['streckenart']);
        $anzahlStreckenart = number_format((int) $global['anzahlStreckenart'], 0, ',', '.');
        $erreichteMeterKm = number_format($global['erreichteMeter'] / 1000, 0, ',', '.');
        $spendensumme = number_format($global['erreichtesGeld'], 0, ',', '.');
        $naechsteNummer = $ausgabe + 1;
        $beschenkteName = $d['beschenkteName'];
        $vorsitzende = $d['vorsitzende'];
        $naechsterBeg = htmlspecialchars($d['naechsterBeguenstigter']);
        $anzahlSponsoren = (int) $d['anzahlSponsoren'];
        $anzahlMedaillen = $d['anzahlMedaillen'];
        $count10km = $d['count10km'];
        $verwendungszweck = $d['verwendungszweck'];
        $nextYear = $config->getStart()->format('Y') + 1;

        // Organizer is always DLRG Coppenbrügge
        $veranstalter = "DLRG Coppenbrügge";
        $ort = "Coppenbrügge";

        // Headline & Subheadline
        $headline = htmlspecialchars("{$ort}r legten mehr als {$erreichteMeterKm}km für {$beguenstigter} zurück");
        $subheadline = htmlspecialchars("{$ausgabe}. {$veranstaltungsname} im Freibad {$ort} mit Teilnehmerrekord");

        // Quote
        $quote = "“Schwimm oder aquajogge, so weit du kannst. Für einen guten Zweck!”";

        // Paragraph 1: overall performance
        $teilnehmerRekordHinweis = $d['anzahlTeilnehmer'] >= $config->getTeilnehmerrekord()
            ? ' Dies stellt einen neuen Teilnehmerrekord dar.'
            : '';

        $para1 = "{$quote} Dieses Motto haben sich beim {$ausgabe}. {$veranstaltungsname}, organisiert von der {$veranstalter}, "
            . "rund {$anzahlTeilnehmer} Teilnehmer zu Herzen genommen.{$teilnehmerRekordHinweis} "
            . "Die Schwimmer und Aquajogger legten im beheizten Freibad {$ort} bei bestem Sommerwetter "
            . "in {$durationHours} Stunden rund {$anzahlStreckenart} {$streckenart} bzw. {$erreichteMeterKm}km zurück. "
            . "Mit dieser außerordentlichen Leistung wurde ein Spendenbetrag in Höhe von {$spendensumme} Euro erschwommen, "
            . "den {$beschenkteName} stellvertretend für den {$beguenstigter} "
            . "von den Vorsitzenden der {$veranstalter}, {$vorsitzende}, überreicht bekamen. "
            . "{$beguenstigter} wird von dem Geld u.a. {$verwendungszweck} erwerben. ";

        if ($sponsor !== '') {
            $nebensponsorenText = $anzahlSponsoren > 1 ? " sowie weiteren " . ($anzahlSponsoren - 1) . " Sponsoren, hauptsächlich aus dem Flecken {$ort}," : '';
            $para1 .= "Die Spendensumme wurde vom Hauptsponsor, der {$sponsor}{$nebensponsorenText} bereitgestellt.";
        }

        // Paragraph 2: individual placements
        $para2 = $this->buildPlacementParagraph($d['topMaenner'], $d['topFrauen'], $anzahlMedaillen, $count10km);

        // Paragraph 3: lottery & closing
        $para3 = "Am Ende der Veranstaltung wurde aus allen teilnehmenden Vereinen der Begünstigte für {$nextYear} ausgelost. "
            . "Das {$naechsteNummer}. {$veranstaltungsname} wird im nächsten Jahr zu Gunsten des {$naechsterBeg} stattfinden, "
            . "die natürlich auf eine ebenso große Schwimm- und Spendenleistung hoffen. "
            . "Die {$veranstalter} und der {$beguenstigter} danken allen Helfern und Sponsoren, "
            . "dem Flecken {$ort} sowie den Schwimmern für die tolle sportliche Leistung.";

        // Statistics summary boxes
        $statsHtml = $this->renderStatBoxes($global, $d);

        // Copy button JS
        $articleId = 'pressartikel-text';

        return <<<HTML
        <div class="card shadow-sm mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa fa-file-alt text-success"></i>
                    <h5 class="mb-0">Generierter Presseartikel</h5>
                </div>
                <button class="btn btn-sm btn-outline-secondary" onclick="copyArticle()">
                    <i class="fa fa-copy me-1"></i> Kopieren
                </button>
            </div>
            <div class="card-body">
                {$statsHtml}
                <div class="alert alert-warning small py-2 mb-3">
                    <i class="fa fa-exclamation-triangle me-1"></i>
                    <strong>Hinweis:</strong> Bitte überprüfe den generierten Text vor der Veröffentlichung.
                    Einige Formulierungen (Wetterhinweis, Begründung der Spendenverwendung etc.) müssen ggf. manuell angepasst werden.
                </div>
        <div id="{$articleId}" class="border rounded p-4 bg-light" style="white-space: pre-wrap; font-family: Georgia, serif; line-height: 1.5; font-size: 1.05rem; color: #1a1a1a;">
        <h3 style="font-weight: 800; margin-bottom: 0.2rem;">{$headline}</h3>
        <h5 style="font-weight: 400; font-style: italic; margin-bottom: 1.5rem; color: #6c757d;">{$subheadline}</h5>
        {$para1}

        {$para2}

        {$para3}
        </div>
            </div>
        </div>
        <script>
        function copyArticle() {
            const el = document.getElementById('{$articleId}');
            const text = el.innerText;
            navigator.clipboard.writeText(text).then(() => {
                const btn = document.querySelector('[onclick="copyArticle()"]');
                btn.innerHTML = '<i class="fa fa-check me-1"></i> Kopiert!';
                setTimeout(() => { btn.innerHTML = '<i class="fa fa-copy me-1"></i> Kopieren'; }, 2000);
            });
        }
        </script>
        HTML;
    }

    /**
     * Builds the placement paragraph following the user's template exactly.
     */
    private function buildPlacementParagraph(array $topMaenner, array $topFrauen, int $anzahlMedaillen, int $count10km): string
    {
        $mData = $this->extractTopThree($topMaenner);
        $wData = $this->extractTopThree($topFrauen);

        $para = '';

        // Men
        if (!empty($mData[0])) {
            $para .= "Dass Schwimmen eine fordernde Sportart sein kann, zeigte {$mData[0]['name']}, "
                . "der mit {$mData[0]['meter']} Metern die längste Strecke des Tages zurücklegte und die Männerwertung";
            if (!empty($mData[1])) {
                $para .= " vor {$mData[1]['name']} mit {$mData[1]['meter']} Metern";
            }
            if (!empty($mData[2])) {
                $para .= " sowie {$mData[2]['name']} mit {$mData[2]['meter']} Metern";
            }
            $para .= " gewann.";
        }

        // Women
        if (!empty($wData[0])) {
            $para .= " Bei den Frauen belegte {$wData[0]['name']} mit {$wData[0]['meter']} Metern den ersten Platz.";
            if (!empty($wData[1])) {
                $para .= " Den zweiten Platz erreichte {$wData[1]['name']} mit {$wData[1]['meter']} Metern";
                if (!empty($wData[2])) {
                    $para .= " vor {$wData[2]['name']} mit {$wData[2]['meter']} Metern";
                }
                $para .= ".";
            }
        }

        if ($count10km > 0) {
            $para .= " Insgesamt erreichten {$count10km} Teilnehmer eine Strecke über 10.000 Meter.";
        }

        if ($anzahlMedaillen > 0) {
            $para .= " Darüber hinaus erreichten {$anzahlMedaillen} Teilnehmer eine Medaille in Bronze, Silber oder Gold für eine besonders gute Schwimmleistung.";
        }

        return $para !== '' ? $para : '[Platzierungsdaten nicht verfügbar]';
    }

    /**
     * Extracts formatted name and distance for top participants.
     */
    private function extractTopThree(array $list): array
    {
        $result = [];
        foreach (array_slice($list, 0, 3) as $tn) {
            $result[] = [
                'name' => htmlspecialchars($tn->getVorname() . ' ' . $tn->getName()),
                'meter' => number_format($tn->getMeter(), 0, ',', '.'),
            ];
        }
        return $result;
    }

    /**
     * Renders small statistic summary boxes above the article for quick review.
     */
    private function renderStatBoxes(array $global, array $d): string
    {
        $km = number_format($global['erreichteMeter'] / 1000, 1, ',', '.');
        $bahnen = number_format((int) $global['anzahlStreckenart'], 0, ',', '.');
        $spende = number_format($global['erreichtesGeld'], 2, ',', '.');
        $tn = number_format($d['anzahlTeilnehmer'], 0, ',', '.');
        $medaillen = $d['anzahlMedaillen'];

        return <<<HTML
        <div class="row g-2 mb-3">
            <div class="col-6 col-md-3">
                <div class="border rounded p-2 text-center bg-white">
                    <div class="fw-bold fs-5 text-primary">{$tn}</div>
                    <small class="text-muted">Teilnehmer</small>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="border rounded p-2 text-center bg-white">
                    <div class="fw-bold fs-5 text-success">{$bahnen}</div>
                    <small class="text-muted">{$global['streckenart']}</small>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="border rounded p-2 text-center bg-white">
                    <div class="fw-bold fs-5 text-info">{$km} km</div>
                    <small class="text-muted">Gesamtstrecke</small>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="border rounded p-2 text-center bg-white">
                    <div class="fw-bold fs-5 text-warning">{$spende} €</div>
                    <small class="text-muted">Spendensumme</small>
                </div>
            </div>
        </div>
        HTML;
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Faq;
use App\Models\PageSection;
use App\Models\SeoSetting;
use App\Models\Testimonial;
use Illuminate\Console\Command;

class PopulateDutchContent extends Command
{
    protected $signature = 'dutch:populate {--dry-run : Preview changes without saving}';
    protected $description = 'Wrap existing English data and populate Dutch translations for all translatable content';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->warn('DRY RUN — no changes will be saved.');
        }

        $this->info('Step 1: Wrapping existing English content in {"en": ...} format...');
        $this->wrapEnglishContent($dryRun);

        $this->info('Step 2: Populating Dutch PageSection translations...');
        $this->populatePageSections($dryRun);

        $this->info('Step 3: Populating Dutch FAQ translations...');
        $this->populateFaqs($dryRun);

        $this->info('Step 4: Populating Dutch SEO translations...');
        $this->populateSeoSettings($dryRun);

        $this->info('Done!');
        return self::SUCCESS;
    }

    /**
     * Wrap existing English content that hasn't been wrapped yet.
     * spatie/laravel-translatable expects {"en": ...} format.
     */
    private function wrapEnglishContent(bool $dryRun): void
    {
        // PageSections
        $wrapped = 0;
        foreach (PageSection::all() as $section) {
            $raw = $section->getAttributes()['content'] ?? null;
            if ($raw !== null && !$this->isAlreadyWrapped($raw)) {
                $decoded = is_string($raw) ? json_decode($raw, true) : $raw;
                if (is_array($decoded) && !$this->hasLocaleKeys($decoded)) {
                    $wrapped_content = ['en' => $decoded];
                    if (!$dryRun) {
                        $section->setTranslation('content', 'en', $decoded);
                        $section->save();
                    }
                    $wrapped++;
                }
            }
        }
        $this->line("  PageSections wrapped: {$wrapped}");

        // FAQs
        $wrapped = 0;
        foreach (Faq::all() as $faq) {
            foreach (['question', 'answer'] as $col) {
                $raw = $faq->getAttributes()[$col] ?? null;
                if ($raw !== null && !$this->isAlreadyWrapped($raw)) {
                    if (!$dryRun) {
                        $faq->setTranslation($col, 'en', $raw);
                        $faq->save();
                    }
                    $wrapped++;
                }
            }
        }
        $this->line("  FAQ columns wrapped: {$wrapped}");

        // Testimonials
        $wrapped = 0;
        foreach (Testimonial::all() as $t) {
            foreach (['headline', 'body', 'quote'] as $col) {
                $raw = $t->getAttributes()[$col] ?? null;
                if ($raw !== null && !$this->isAlreadyWrapped($raw)) {
                    if (!$dryRun) {
                        $t->setTranslation($col, 'en', $raw);
                        $t->save();
                    }
                    $wrapped++;
                }
            }
        }
        $this->line("  Testimonial columns wrapped: {$wrapped}");

        // SeoSettings
        $wrapped = 0;
        foreach (SeoSetting::all() as $seo) {
            foreach (['meta_title', 'meta_description', 'og_title', 'og_description'] as $col) {
                $raw = $seo->getAttributes()[$col] ?? null;
                if ($raw !== null && !$this->isAlreadyWrapped($raw)) {
                    if (!$dryRun) {
                        $seo->setTranslation($col, 'en', $raw);
                        $seo->save();
                    }
                    $wrapped++;
                }
            }
        }
        $this->line("  SEO columns wrapped: {$wrapped}");
    }

    private function isAlreadyWrapped(mixed $value): bool
    {
        if (is_array($value)) {
            return $this->hasLocaleKeys($value);
        }
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (is_array($decoded)) {
                return $this->hasLocaleKeys($decoded);
            }
        }
        return false;
    }

    private function hasLocaleKeys(array $data): bool
    {
        $keys = array_keys($data);
        return !empty(array_intersect($keys, ['en', 'nl']));
    }

    /**
     * Populate Dutch translations for PageSection content.
     */
    private function populatePageSections(bool $dryRun): void
    {
        $translations = $this->getPageSectionTranslations();
        $count = 0;

        foreach ($translations as $sectionKey => $nlContent) {
            $section = PageSection::where('section_key', $sectionKey)->first();
            if ($section) {
                if (!$dryRun) {
                    $section->setTranslation('content', 'nl', $nlContent);
                    $section->save();
                }
                $count++;
                $this->line("  ✓ {$sectionKey}");
            } else {
                $this->warn("  ✗ {$sectionKey} — not found in DB");
            }
        }

        $this->line("  PageSections translated: {$count}");
    }

    /**
     * Populate Dutch translations for FAQs.
     */
    private function populateFaqs(bool $dryRun): void
    {
        $dutchFaqs = $this->getFaqTranslations();
        $count = 0;

        // Match by sort_order + category
        foreach ($dutchFaqs as $faqData) {
            $faq = Faq::where('category', $faqData['category'])
                ->where('sort_order', $faqData['sort_order'])
                ->first();

            if ($faq) {
                if (!$dryRun) {
                    $faq->setTranslation('question', 'nl', $faqData['question']);
                    $faq->setTranslation('answer', 'nl', $faqData['answer']);
                    $faq->save();
                }
                $count++;
                $this->line("  ✓ [{$faqData['category']}] #{$faqData['sort_order']}");
            } else {
                $this->warn("  ✗ [{$faqData['category']}] #{$faqData['sort_order']} — not found");
            }
        }

        $this->line("  FAQs translated: {$count}");
    }

    /**
     * Populate Dutch translations for SEO settings.
     */
    private function populateSeoSettings(bool $dryRun): void
    {
        $seoTranslations = $this->getSeoTranslations();
        $count = 0;

        foreach ($seoTranslations as $pageKey => $data) {
            $seo = SeoSetting::where('page_key', $pageKey)->first();
            if ($seo) {
                if (!$dryRun) {
                    foreach ($data as $col => $value) {
                        $seo->setTranslation($col, 'nl', $value);
                    }
                    $seo->save();
                }
                $count++;
                $this->line("  ✓ {$pageKey}");
            } else {
                $this->warn("  ✗ {$pageKey} — not found");
            }
        }

        $this->line("  SEO settings translated: {$count}");
    }

    /**
     * Dutch translations for all PageSection content.
     */
    private function getPageSectionTranslations(): array
    {
        return [
            // ── HOME ──────────────────────────────────────────────────────
            'home_hero' => [
                'heading' => 'Online therapie voor volwassenen die vooruit willen.',
                'subheading' => 'Psycholoog & Traumatherapeut',
                'body' => 'Online therapie voor volwassenen die vastlopen in de gevolgen van trauma, angst, een negatief zelfbeeld, somberheid of hardnekkige patronen die steeds blijven terugkeren. De trajecten die ik bied zijn wetenschappelijk onderbouwd, doelgericht en concreet afgestemd op wie jij bent en wat jij nodig hebt.',
                'image' => '/images/lysander-hero.jpg',
                'cta_primary_label' => 'Plan een gratis kennismakingsgesprek',
                'cta_primary_url' => '/booking',
                'cta_secondary_label' => 'Trauma & Mijn Aanpak',
                'cta_secondary_url' => '/trauma-approach',
            ],
            'home_intro' => [
                'heading' => 'Een psycholoog die zelf het pad heeft bewandeld',
                'body' => '<p>Soms loop je vast in patronen die je maar moeilijk lijkt te kunnen doorbreken.</p><p>Misschien voel je je voortdurend gespannen, pieker je veel, raak je snel overweldigd door emoties of heb je het gevoel het contact met jezelf te zijn kwijtgeraakt. Hoe hard je ook probeert om dingen anders te doen, je merkt dat je steeds weer tegen dezelfde problemen aanloopt.</p><p>Veel van de mensen die ik begeleid worstelen met de gevolgen van trauma en kampen daarnaast met angst, somberheid, perfectionisme, zelfkritiek, emotionele ontregeling of terugkerende problemen in relaties.</p><p>Mijn doel als therapeut is om je te helpen meer rust, flexibiliteit en vertrouwen in jezelf te ontwikkelen.</p>',
                'image' => '/images/ff96a9dc8ea72c2c-11062b_aa33e58c18774e7db74c68e74a6c231e-mv2.jpg',
                'stats' => [
                    ['value' => 'EMDR', 'label' => 'Gevorderd gecertificeerd'],
                    ['value' => 'MSc.', 'label' => 'Psychologiediploma'],
                    ['value' => '10+', 'label' => 'Evidence-based methoden'],
                ],
                'cta_primary_label' => 'Trauma & Mijn Aanpak',
                'cta_primary_url' => '/trauma-approach',
                'cta_secondary_label' => 'Plan een gratis kennismaking',
                'cta_secondary_url' => '/booking',
            ],
            'home_areas' => [
                'heading' => 'Waar ik je mee kan helpen',
                'body' => '',
                'image' => '/images/540a4d3e95a87201-11062b_e8771669914d4b8a949e06893dfd43a0-mv2.jpg',
                'items' => [
                    ['title' => 'Traumatisering en PTSS'],
                    ['title' => 'Angst- en panieklachten'],
                    ['title' => 'Somberheid, depressie en rouw'],
                    ['title' => 'Problemen met zelfvertrouwen en een laag zelfbeeld'],
                    ['title' => 'Perfectionisme en controledwang'],
                    ['title' => 'Moeite met emoties reguleren en boosheid'],
                    ['title' => 'Burn-out en chronische stress'],
                    ['title' => 'Relatie- en hechtingsproblemen'],
                ],
            ],
            'home_working_together' => [
                'heading' => 'Samenwerken',
                'body' => '<p>Je bent niet je klacht. Je bent ook niet je diagnose.</p><p>Veel van de patronen waar mensen vandaag tegenaan lopen, zijn ooit ontstaan als een begrijpelijke reactie op moeilijke ervaringen. Wat je vroeger hielp om te overleven, helpt je nu misschien niet meer om werkelijk te leven.</p><p>In therapie onderzoeken we samen welke patronen je niet langer helpen, waar ze vandaan komen en welke veranderingen nodig zijn om weer meer rust, vrijheid en vertrouwen te ervaren.</p><p>Mijn rol is om daarin een veilige, betrokken en doelgerichte omgeving te bieden, zodat duurzame verandering mogelijk wordt.</p>',
                'image' => '/images/1cea4c553e34803a-a3c153_bbf1019446e34069a3b96c18f172e810-mv2.jpg',
                'cta_label' => 'Plan een gratis kennismakingsgesprek',
                'cta_url' => '/booking',
            ],
            'home_cta_bottom' => [
                'heading' => 'Vrijblijvend kennismaken',
                'body' => 'Of je nu worstelt met trauma, angst, somberheid of het gevoel hebt vast te zitten, het kennismakingsgesprek biedt de gelegenheid om je hulpvraag te bespreken en te onderzoeken of mijn aanpak bij je past.',
                'additional_text' => 'Het eerste gesprek is gratis en vrijblijvend.',
                'cta_primary_label' => 'Plan een gratis kennismakingsgesprek',
                'cta_primary_url' => '/booking',
                'cta_secondary_label' => 'WhatsApp mij',
                'cta_secondary_url' => 'https://wa.me/66935309052?text=Hoi%20Lysander%2C%20ik%20wil%20graag%20meer%20weten%20over%20therapie.',
            ],

            // ── APPROACH ──────────────────────────────────────────────────
            'approach_hero' => [
                'heading' => 'Trauma & Mijn Werkwijze',
                'subheading' => 'Trauma & Mijn Werkwijze',
                'body' => 'Trauma gaat niet alleen over wat er in het verleden is gebeurd, maar ook over de manier waarop die ervaringen nu nog invloed hebben op je leven.',
            ],
            'approach_understanding' => [
                'heading' => 'Hoe trauma het heden beïnvloedt',
                'body' => '<p>Ingrijpende, pijnlijke en overweldigende ervaringen kunnen een blijvende negatieve impact hebben op het zenuwstelsel, de emotieregulatie, relaties met anderen en het beeld dat je van jezelf hebt. Trauma kan zich uiten in klachten zoals angst, paniek, emotionele afvlakking, voortdurende alertheid, opdringende herinneringen, flashbacks, schaamte, een negatief zelfbeeld of hardnekkige patronen van vermijding en controle.</p><p>Soms is de oorzaak van trauma duidelijk aanwijsbaar. In andere gevallen ontstaat de impact geleidelijk, bijvoorbeeld door jarenlang opgroeien met kritiek, emotionele verwaarlozing, instabiliteit, afwijzing of chronische stress.</p><p>Ik werk zowel met acute en duidelijk afgebakende traumatische gebeurtenissen ("grote T"-trauma\'s) als met meer subtiele, relationele of langdurig opgebouwde vormen van trauma ("kleine t"-trauma\'s). Beide typen kunnen diepe sporen nalaten, en beide zijn vaak goed behandelbaar.</p>',
                'image' => '/images/de8d235e4bd94eb8-a3c153_20122b9a32cc4e9a9faca835b9f82d14-mv2.jpg',
                'cta_label' => 'Plan een gratis kennismakingsgesprek',
                'cta_url' => '/booking',
            ],
            'approach_types' => [
                'heading' => 'De types trauma waar ik mee werk',
                'body' => 'Ik werk onder andere met cliënten die last hebben van:',
                'items' => [
                    ['title' => 'Oorlogs- en geweldservaringen'],
                    ['title' => 'Ongevallen en lichamelijk letsel'],
                    ['title' => 'Seksueel misbruik of seksueel geweld'],
                    ['title' => 'Medisch trauma'],
                    ['title' => 'Paniekaanvallen en overweldigende psychologische ervaringen'],
                    ['title' => 'Emotionele verwaarlozing of mishandeling in je jeugd'],
                    ['title' => 'Rouw en traumatisch verlies'],
                    ['title' => 'Pesten en sociale uitsluiting'],
                    ['title' => 'Relatie- of gezinsproblemen met veel conflict'],
                    ['title' => 'Problemen rondom zelfvertrouwen, identiteit en eigenwaarde die samenhangen met traumatische ervaringen'],
                ],
            ],
            'approach_treatments' => [
                'heading' => ' Traumagerichte Therapie',
                'body' => 'Mijn werkwijze combineert verschillende wetenschappelijk onderbouwde behandelmethoden, afgestemd op jouw klachten, doelen en persoonlijke situatie.',
                'cards' => [
                    ['subtitle' => 'Primaire methode', 'title' => 'EMDR', 'description' => 'Eye Movement Desensitization and Reprocessing — de gouden standaard evidence-based behandeling voor trauma en PTSS.'],
                    ['subtitle' => 'Traumaverwerking', 'title' => 'Exposuretherapie', 'description' => 'Geleidelijke, gestructureerde confrontatie met angst en trauma. Helpt vermijding te verminderen en moeilijke ervaringen te integreren.'],
                    ['subtitle' => 'Traumaverwerking', 'title' => 'Flash Technique', 'description' => 'Een zachtere ingang voor traumaverwerking wanneer herinneringen zeer belastend zijn.'],
                    ['subtitle' => 'Geheugenherwerking', 'title' => 'Imagery Rescripting', 'description' => 'Herschrijven van pijnlijke emotionele herinneringen om de emotionele lading te verminderen.'],
                    ['subtitle' => 'Lichaamsgericht', 'title' => 'Lichaamsgerichte interventies', 'description' => 'Lichaamsgerichte aanpak om te verwerken wat vastzit in het lichaam en zenuwstelsel.'],
                ],
            ],
            'approach_emdr' => [
                'heading' => 'EMDR Gaat Niet Alleen Over Het Verleden',
                'body' => '<p>Veel mensen kennen EMDR als een behandeling voor traumatische herinneringen uit het verleden. Minder bekend is dat EMDR ook zeer effectief kan zijn bij angsten die juist gericht zijn op de toekomst.</p><p>Soms zijn het niet de herinneringen aan wat er gebeurd is die iemand gevangen houden, maar de beelden van wat er mogelijk zou kunnen gebeuren. Deze zogenaamde flashforwards kunnen zo levendig en overtuigend zijn dat ze gevoelens van angst, spanning en vermijding blijven voeden.</p><p>Denk bijvoorbeeld aan angst voor een nieuwe paniekaanval, angst voor geweld of slachtofferschap, ziekteangst, faalangst, angst om de controle te verliezen, angst om alleen achter te blijven of afgewezen te worden, sociale angst en gevoeligheid voor kritiek, of catastrofale "wat als..." scenario\'s.</p><p>Wanneer dergelijke angsten een grote invloed krijgen op je dagelijks leven, kan EMDR een krachtige manier zijn om deze patronen te doorbreken.</p>',
                'image' => '/images/4e854682cd76d19d-30f861_eb190602eba243f586aac2f6026db98b-mv2.jpg',
                'cards' => [
                    ['title' => 'Angst voor paniekaanvallen', 'description' => 'De angst voor angst zelf behandelen — niet alleen de symptomen.'],
                    ['title' => 'Ziekteangst', 'description' => 'Catastrofale gezondheidsangsten en opdringende lichaamspreoccupaties.'],
                    ['title' => 'Angst om controle te verliezen', 'description' => 'Sociale angsten, schaamtespiralen en catastrofale "wat als" gedachten.'],
                    ['title' => 'Toekomstgerichte angst', 'description' => 'Emotioneel geladen flashforwards die mensen vasthouden.'],
                ],
            ],
            'approach_why' => [
                'heading' => 'Mijn Specialisatie In Traumatherapie',
                'body' => '<p>Trauma vormt vaak de onderliggende laag van klachten die soms al jarenlang aanwezig zijn, zoals angst, somberheid, perfectionisme of een negatief zelfbeeld.</p><p>Wat mij zo aanspreekt in traumatherapie is dat verandering vaak verder gaat dan alleen het verminderen van klachten. Wanneer traumatische ervaringen verwerkt worden, zie ik keer op keer dat mensen fundamenteel anders naar zichzelf en de wereld gaan kijken.</p><p>Voor mij gaat traumatherapie uiteindelijk over meer dan klachtenvermindering alleen. Het gaat over het loskomen van oude overlevingspatronen en het hervinden van veiligheid, vertrouwen en vrijheid.</p>',
                'quote' => 'Traumatherapie gaat uiteindelijk over het loskomen van oude overlevingspatronen en het hervinden van veiligheid, vertrouwen en vrijheid.',
                'image' => '/images/24946176bc4178fd-d0220c_d40feae8ad4e4961b519d527fe1eb369-mv2_d_1440_1920_s_2.jpg',
                'cta_primary_label' => 'Bekijk Opleidingen',
                'cta_primary_url' => '/clinical-training',
                'cta_secondary_label' => 'Plan een gratis kennismaking',
                'cta_secondary_url' => '/booking',
            ],
            'approach_cta' => [
                'heading' => 'Betekenisvol herstel is mogelijk',
                'body' => 'Trauma kan een diepe invloed hebben op hoe je jezelf, andere mensen en de wereld om je heen ervaart. Tegelijkertijd weet ik uit ervaring dat herstel goed mogelijk is. Met de juiste begeleiding kunnen onvewerkte ervaringen worden verwerkt, kan de grip van angst en vermijding afnemen en ontstaat er weer ruimte voor meer rust, vertrouwen, verbinding en vrijheid in het dagelijks leven.',
                'cta_primary_label' => 'Plan een gratis kennismakingsgesprek',
                'cta_primary_url' => '/booking',
                'cta_secondary_label' => 'WhatsApp mij',
                'cta_secondary_url' => 'https://wa.me/66935309052?text=Hoi%20Lysander%2C%20ik%20wil%20graag%20meer%20weten%20over%20therapie.',
            ],

            // ── TRAINING ──────────────────────────────────────────────────
            'training_hero' => [
                'heading' => 'Opleiding & Professionele Ontwikkeling',
                'subheading' => 'Professionele achtergrond',
                'body' => 'Goede therapie vraagt om blijvende ontwikkeling. Daarom investeer ik voortdurend in het verdiepen van mijn kennis en vaardigheden, zodat ik cliënten behandeling kan bieden die aansluit bij de huidige wetenschappelijke inzichten én de complexiteit van de mens achter de klacht.',
            ],
            'training_background' => [
                'heading' => 'MSc. Psychologie',
                'body' => '<p>Ik ben opgeleid als psycholoog (MSc) en heb daarnaast een academische achtergrond in de <strong>Sociale Psychologie</strong> en <strong>Neurocognitieve Wetenschappen</strong>.</p><p>In de afgelopen jaren heb ik mij aanvullend gespecialiseerd in traumabehandeling, ervaringsgerichte therapieën en integratieve psychotherapie. Deze opleidingen vormen samen de basis van mijn werkwijze en helpen mij om behandelingen af te stemmen op de unieke behoeften van iedere cliënt.</p>',
                'image' => '/images/ff96a9dc8ea72c2c-11062b_aa33e58c18774e7db74c68e74a6c231e-mv2.jpg',
                'stats' => [
                    ['value' => 'MSc.', 'label' => 'Psychologie'],
                    ['value' => 'EMDR', 'label' => 'Gevorderd gecertificeerd'],
                    ['value' => '10+', 'label' => 'Opleidingsprogramma\'s'],
                ],
            ],
            'training_categories' => [
                'heading' => 'Gespecialiseerde klinische opleidingen',
                'groups' => [
                    [
                        'title' => 'Trauma & EMDR',
                        'items' => [
                            ['title' => 'EMDR Basisopleiding'],
                            ['title' => 'EMDR Vervolgopleiding'],
                            ['title' => 'Affect-Focused EMDR'],
                            ['title' => 'Exposure Therapie'],
                            ['title' => 'Imaginaire Rescripting'],
                            ['title' => 'Flash Technique 2.0'],
                        ],
                    ],
                    [
                        'title' => 'Schema- & Ervaringsgerichte Therapie',
                        'items' => [
                            ['title' => 'Schematherapie Basisopleiding'],
                            ['title' => 'ACT & Schematherapie Integratie'],
                            ['title' => 'EMDR & Schematherapie Integratie'],
                            ['title' => 'Woede-, Wraak- en Wrokprotocol'],
                            ['title' => 'Boksgerichte psychotherapie'],
                        ],
                    ],
                    [
                        'title' => 'ACT & CGT',
                        'items' => [
                            ['title' => 'Acceptance and Commitment Therapy (ACT) — Basisopleiding'],
                            ['title' => 'Acceptance and Commitment Therapy (ACT) — Vervolgopleiding'],
                            ['title' => 'Acceptance and Commitment Therapy in Groepen'],
                            ['title' => 'Cognitieve Gedragstherapie (CGT)'],
                            ['title' => 'Beck Institute CBT Training'],
                        ],
                    ],
                    [
                        'title' => 'Professionele Achtergrond',
                        'items' => [
                            ['title' => 'Naast mijn klinische werkzaamheden heb ik ervaring met het begeleiden van een internationale cliëntengroep en met een breed spectrum aan psychologische klachten en hulpvragen.'],
                            ['title' => 'Mijn werkwijze combineert wetenschappelijk onderbouwde behandelmethoden met een integratieve, traumagerichte en persoonsgerichte benadering.'],
                        ],
                    ],
                ],
            ],
            'training_approach' => [
                'heading' => 'Integratief, trauma-geïnformeerd, geïndividualiseerd',
                'body' => '<p>Naast mijn klinische werkzaamheden heb ik ervaring met het begeleiden van een internationale cliëntengroep en met een breed spectrum aan psychologische klachten en hulpvragen.</p><p>Mijn werkwijze combineert wetenschappelijk onderbouwde behandelmethoden met een integratieve, traumagerichte en persoonsgerichte benadering. Daarbij kijk ik niet alleen naar klachten of diagnoses, maar vooral naar de mens als geheel en de factoren die bijdragen aan herstel, groei en duurzame verandering.</p>',
                'cta_primary_label' => 'Bekijk Trauma & Mijn Aanpak',
                'cta_primary_url' => '/trauma-approach',
                'cta_secondary_label' => 'Plan een gratis kennismaking',
                'cta_secondary_url' => '/booking',
            ],

            // ── TESTIMONIALS ──────────────────────────────────────────────
            'testimonials_hero' => [
                'heading' => 'Ervaringen van cliënten',
                'subheading' => 'Client ervaringen',
                'body' => 'De onderstaande ervaringen zijn afkomstig van cliënten die een deel van hun verhaal wilden delen. Hun woorden geven een indruk van hoe therapie kan bijdragen aan inzicht, herstel en persoonlijke groei.',
            ],
            'testimonials_quote' => [
                'body' => '"Als hij mij kan helpen, kan hij jou ook helpen."',
                'attribution' => '— Rut',
            ],
            'testimonials_grid' => [
                'heading' => 'Verhalen van herstel & groei',
                'subheading' => 'De ervaring van ieder mens is uniek. Deze testimonials worden gedeeld met toestemming van de cliënten en vertegenwoordigen oprechte ervaringen uit de therapie.',
            ],
            'testimonials_cta' => [
                'heading' => 'Begin je eigen reis',
                'body' => 'Elk verhaal van herstel begint met een eerste stap. Neem contact op en laten we praten over wat jou hier brengt. Het eerste gesprek is gratis en vrijblijvend.',
                'cta_primary_label' => 'Plan een gratis kennismakingsgesprek',
                'cta_primary_url' => '/booking',
                'cta_secondary_label' => 'WhatsApp mij',
                'cta_secondary_url' => 'https://wa.me/66935309052?text=Hoi%20Lysander%2C%20ik%20wil%20graag%20meer%20weten%20over%20therapie.',
            ],

            // ── FEES ──────────────────────────────────────────────────────
            'fees_hero' => [
                'heading' => 'Tarieven & Traject',
                'subheading' => 'Praktische informatie',
                'body' => 'Transparante informatie over sessietarieven, wat inbegrepen is en hoe therapie begint. Starten is altijd gratis en vrijblijvend.',
            ],
            'fees_pricing' => [
                'heading' => 'Tarief',
                'body' => '<p>Een individuele therapiesessie duurt 60 minuten en kost <strong>€110 per sessie</strong>.</p><p>Alle sessies vinden online plaats in een veilige, vertrouwelijke en professionele omgeving.</p>',
                'fee_amount' => '€110',
                'fee_duration' => 'Per sessie · 60 minuten',
                'items' => [
                    ['title' => 'Reflectie- of e-healthdocumenten na sessies'],
                    ['title' => 'Oefeningen en opdrachten voor tussen de sessies'],
                    ['title' => 'Aanvullend therapeutisch materiaal'],
                    ['title' => 'Voorbereiding en integratie van therapeutisch werk'],
                    ['title' => 'Contact tussen sessies voor praktische vragen of korte ondersteuning rondom het behandelproces'],
                ],
                'cta_label' => 'Plan een gratis kennismaking',
                'cta_url' => '/booking',
            ],
            'fees_process' => [
                'heading' => 'Hoe Ziet Een Traject Eruit?',
                'subheading' => '',
                'steps' => [
                    ['title' => 'Gratis Kennismakingsgesprek (30 minuten)', 'description' => 'We starten met een vrijblijvend online kennismakingsgesprek van ongeveer 30 minuten. Tijdens dit gesprek bespreken we jouw situatie, wat je hoopt te bereiken met therapie en of ik de juiste therapeut voor je ben. Uiteraard is er ook ruimte voor praktische vragen over de behandeling.', 'duration' => '30 minuten · Gratis', 'badge' => 'Gratis'],
                    ['title' => 'Intakegesprek (60 minuten)', 'description' => 'Wanneer we besluiten samen verder te gaan, volgt een intakegesprek van 60 minuten. Tijdens de intake brengen we jouw klachten, achtergrond, belangrijke levenservaringen en behandeldoelen in kaart. Op basis hiervan stel ik een behandelplan op waarin de belangrijkste aandachtspunten, doelen en behandelrichting worden beschreven.', 'duration' => '60 minuten', 'badge' => null],
                    ['title' => 'Individuele Therapiesessies (60 minuten)', 'description' => 'Na de intake start de behandeling. De sessies worden afgestemd op jouw hulpvraag, doelen en persoonlijke situatie. Mijn werkwijze is actief, betrokken en doelgericht. De sessies vinden plaats in jouw tempo, met aandacht voor wat jij op dat moment nodig hebt.', 'duration' => '60 minuten', 'badge' => null],
                ],
            ],
            'fees_info' => [
                'heading' => 'Beschikbaarheid',
                'cards' => [
                    ['title' => 'Online sessies', 'description' => 'Sessies vinden online plaats via een beveiligd videoplatform. Hierdoor kan ik cliënten zowel binnen Nederland als internationaal begeleiden.'],
                    ['title' => 'Persoonlijk (Amsterdam)', 'description' => 'Voornamelijk een online praktijk. Een beperkt aantal persoonlijke sessies in Amsterdam is beschikbaar op aanvraag.'],
                    ['title' => 'Sessieduur', 'description' => 'Sessies duren 60 minuten. Het gratis kennismakingsgesprek duurt 30 minuten.'],
                    ['title' => 'Talen', 'description' => 'Sessies worden gevoerd in het Nederlands of Engels. Beide talen zijn gelijk beschikbaar voor alle therapievormen.'],
                ],
            ],
            'fees_cta' => [
                'heading' => 'Om voldoende aandacht en kwaliteit van zorg te kunnen bieden, werk ik met een beperkt aantal cliënten tegelijk.',
                'subheading' => 'Hierdoor kan er soms een korte wachttijd ontstaan voordat een nieuw traject kan starten. Op dit moment bedraagt de wachttijd doorgaans ongeveer 2 tot 6 weken.',
                'cta_label' => 'Plan een gratis kennismakingsgesprek',
                'cta_url' => '/booking',
            ],

            // ── CONTACT ───────────────────────────────────────────────────
            'contact_hero' => [
                'heading' => 'Contact',
                'subheading' => 'Neem Contact Op',
                'body' => 'Ben je benieuwd wat therapie voor jou zou kunnen betekenen? Of wil je eerst onderzoeken of ik de juiste persoon ben om je daarbij te begeleiden? Dan nodig ik je graag uit voor een vrijblijvend online kennismakingsgesprek van 30 minuten.',
            ],
            'contact_info' => [
                'heading' => 'Neem contact op',
                'whatsapp_number' => '66935309052',
                'whatsapp_text' => 'Liever een snel bericht?',
                'email' => 'therapistlysander@gmail.com',
                'items' => [
                    ['label' => 'E-mail', 'value' => 'therapistlysander@gmail.com'],
                    ['label' => 'WhatsApp Business', 'value' => '+66 93 530 90 52'],
                    ['label' => 'Locatie', 'value' => 'Online / Internationaal'],
                    ['label' => 'Sessieduur', 'value' => '60 minuten · Gratis kennismakingsgesprek (30 min)'],
                    ['label' => 'Talen', 'value' => 'Nederlands & Engels'],
                ],
            ],
            'contact_booking' => [
                'heading' => 'Plan een gratis kennismakingsgesprek',
                'body' => 'Een kennismakingsgesprek is gratis en vrijblijvend. Het is simpelweg een eerste stap om te onderzoeken of we een goede match zijn en of therapie je kan bieden wat je zoekt.',
                'cta_label' => 'Start met boeken',
                'cta_url' => '/booking',
            ],

            // ── FAQ ───────────────────────────────────────────────────────
            'faq_hero' => [
                'heading' => 'Veelgestelde Vragen',
                'subheading' => 'Vragen & Antwoorden',
                'body' => 'Antwoorden op de meest gestelde vragen over therapie, EMDR, tarieven en hoe te beginnen. Als iets hier niet beantwoord wordt, neem dan gerust contact op.',
            ],
            'faq_cta' => [
                'heading' => 'Nog vragen?',
                'body' => 'Neem gerust contact op — ik beantwoord graag je vragen voordat je besluit te boeken.',
                'cta_label' => 'Plan een gratis kennismaking',
                'cta_url' => '/booking',
            ],
            'faq_categories' => [
                'categories' => [
                    ['key' => 'therapy_emdr', 'label' => 'Therapie & EMDR'],
                    ['key' => 'starting_therapy', 'label' => 'Starten met Therapie'],
                    ['key' => 'practical', 'label' => 'Praktische Informatie'],
                    ['key' => 'sessions_progress', 'label' => 'Sessies & Voortgang'],
                ],
            ],
        ];
    }

    /**
     * Dutch translations for FAQs.
     */
    private function getFaqTranslations(): array
    {
        return [
            // ── Therapie & EMDR ──────────────────────────────────────
            [
                'category' => 'therapy_emdr', 'sort_order' => 1,
                'question' => 'Met welke behandelmethoden werk je?',
                'answer' => 'Ik werk in de basis met EMDR, Acceptance & Commitment Therapy (ACT), Cognitieve Gedragstherapie (CGT), Schematherapie, en lichaamsgerichte interventies. De behandeling wordt afgestemd op de persoon en de hulpvraag.',
            ],
            [
                'category' => 'therapy_emdr', 'sort_order' => 2,
                'question' => 'Wat is EMDR?',
                'answer' => '<p>EMDR (Eye Movement Desensitization and Reprocessing) is een wetenschappelijk onderbouwde behandelmethod die oorspronkelijk werd ontwikkeld voor het verwerken van traumatische ervaringen en belastende herinneringen.</p><p>Inmiddels weten we dat EMDR veel breder toepasbaar is. Naast trauma kan het ook effectief zijn bij angstklachten, catastrofale toekomstscenario\'s, flashforwards en andere emotioneel beladen beelden die mensen gevangen houden in angst, vermijding of voortdurende waakzaamheid.</p><p>Tijdens EMDR wordt het natuurlijke verwerkingsvermogen van de hersenen geactiveerd, waardoor herinneringen, beelden en emoties vaak hun emotionele lading verliezen.</p>',
            ],
            [
                'category' => 'therapy_emdr', 'sort_order' => 3,
                'question' => 'Met welke klachten werk je?',
                'answer' => 'Ik werk onder andere met trauma en PTSS, angstklachten, paniek, somberheid, rouw, zelfwaardeproblemen, perfectionisme, emotieregulatie, burn-out, relatieproblemen en langdurige patronen die geworteld zijn in eerdere levenservaringen.',
            ],
            [
                'category' => 'therapy_emdr', 'sort_order' => 4,
                'question' => 'Is online therapie net zo effectief als face-to-face therapie?',
                'answer' => '<p>Onderzoek laat zien dat online therapie voor veel klachten net zo effectief kan zijn als face-to-face behandeling, waaronder trauma, angst, depressie en stressgerelateerde problematiek.</p><p>Verschillende meta-analyses laten bovendien zien dat de kwaliteit van de therapeutische relatie online doorgaans vergelijkbaar is met die van traditionele face-to-face therapie.</p>',
            ],

            // ── Starten met Therapie ─────────────────────────────────
            [
                'category' => 'starting_therapy', 'sort_order' => 1,
                'question' => 'Wat is psychotherapie?',
                'answer' => '<p>Psychotherapie is een samenwerkingsproces gericht op het begrijpen en veranderen van patronen die bijdragen aan emotioneel lijden.</p><p>Afhankelijk van je behoefte kan therapie het verwerken van moeilijke ervaringen, het ontwikkelen van nieuwe vaardigheden, het versterken van zelfbegrip, het verbeteren van relaties en het opbouwen van meer psychologische flexibiliteit omvatten.</p>',
            ],
            [
                'category' => 'starting_therapy', 'sort_order' => 2,
                'question' => 'Hoe weet ik of therapie iets voor mij is?',
                'answer' => '<p>Als je emotionele distress ervaart, vastzit, worstelt met terugkerende patronen, of merkt dat je moeilijk met uitdagingen kunt omgaan, kan therapie helpen.</p><p>Het kennismakingsgesprek is een goede gelegenheid om te onderzoeken of samenwerken als de juiste match voelt.</p>',
            ],
            [
                'category' => 'starting_therapy', 'sort_order' => 3,
                'question' => 'Wat gebeurt er tijdens het kennismakingsgesprek?',
                'answer' => '<p>Het kennismakingsgesprek is een korte en vrijblijvende kennismaking waarin we jouw situatie bespreken, kijken naar je hulpvraag en onderzoeken of er een goede basis is om samen te werken.</p>',
            ],
            [
                'category' => 'starting_therapy', 'sort_order' => 4,
                'question' => 'Wat is de pre-intake vragenlijst?',
                'answer' => '<p>Voorafgaand aan het intakegesprek word je gevraagd een korte vragenlijst in te vullen over je achtergrond, huidige klachten, relevante voorgeschiedenis en behandeldoelen.</p><p>Dit helpt mij om de intake voor te bereiden en stelt ons in staat om de eerste sessie effectiever te gebruiken.</p>',
            ],
            [
                'category' => 'starting_therapy', 'sort_order' => 5,
                'question' => 'Heb ik een diagnose nodig om met therapie te starten?',
                'answer' => '<p>Nee. Veel mensen zoeken hulp omdat ze vastlopen, zich overweldigd voelen, angstklachten ervaren of merken dat ze steeds tegen dezelfde patronen aanlopen. Een officiële diagnose is niet nodig om met therapie te beginnen.</p>',
            ],
            [
                'category' => 'starting_therapy', 'sort_order' => 6,
                'question' => 'Wat gebeurt er na de intake?',
                'answer' => '<p>Na de intake stel ik een behandelplan op waarin de belangrijkste klachten, doelen en voorgestelde behandelrichting worden beschreven.</p>',
            ],

            // ── Praktische Informatie ────────────────────────────────
            [
                'category' => 'practical', 'sort_order' => 1,
                'question' => 'Wat kost een sessie?',
                'answer' => '<p>Een individuele therapiesessie duurt 60 minuten en kost €110 per sessie.</p><p>Een gratis 30-minuten kennismakingsgesprek is beschikbaar voordat de therapie begint.</p>',
            ],
            [
                'category' => 'practical', 'sort_order' => 2,
                'question' => 'Wordt therapie vergoed door de zorgverzekering?',
                'answer' => '<p>Ik heb geen contracten met Nederlandse zorgverzekeraars. Daardoor wordt therapie in de meeste gevallen niet vergoed vanuit de Nederlandse basisverzekering.</p><p>Sommige internationale zorgverzekeraars bieden afhankelijk van de polisvoorwaarden wel een gedeeltelijke of volledige vergoeding. Indien gewenst kan ik facturen of aanvullende documentatie aanleveren ter ondersteuning van een declaratie.</p><p>Voor de exacte mogelijkheden raad ik aan contact op te nemen met je zorgverzekeraar.</p>',
            ],
            [
                'category' => 'practical', 'sort_order' => 3,
                'question' => 'Bied je alleen online therapie aan?',
                'answer' => '<p>Ja. Op dit moment vinden alle sessies online plaats via een beveiligd videoplatform. Hierdoor kan ik cliënten zowel binnen Nederland als internationaal begeleiden.</p>',
            ],
            [
                'category' => 'practical', 'sort_order' => 4,
                'question' => 'Werk je ook in het Nederlands en Engels?',
                'answer' => 'Ja. Ik werk regelmatig met zowel Nederlandse als internationale cliënten en bied therapie aan in het Nederlands en Engels.',
            ],
            [
                'category' => 'practical', 'sort_order' => 5,
                'question' => 'Werk je met expats en internationale cliënten?',
                'answer' => 'Ja. Ik werk regelmatig met zowel Nederlandse als internationale cliënten en bied therapie aan in het Nederlands en Engels.',
            ],
            [
                'category' => 'practical', 'sort_order' => 6,
                'question' => 'Hoe lang duurt een sessie?',
                'answer' => '<p>Sessies duren 60 minuten. Het gratis kennismakingsgesprek duurt 30 minuten.</p>',
            ],

            // ── Sessies & Voortgang ──────────────────────────────────
            [
                'category' => 'sessions_progress', 'sort_order' => 1,
                'question' => 'Hoeveel sessies heb ik nodig?',
                'answer' => '<p>Dat verschilt per persoon. Sommige mensen hebben voldoende aan een kortdurend traject, terwijl anderen kiezen voor een langere behandeling. Dit bespreken en evalueren we gedurende het traject.</p>',
            ],
            [
                'category' => 'sessions_progress', 'sort_order' => 2,
                'question' => 'Moet ik direct alles vertellen?',
                'answer' => '<p>Nee. Therapie gaat niet over jezelf dwingen om moeilijke ervaringen direct te bespreken. We werken stap voor stap en in een tempo dat voor jou veilig voelt.</p>',
            ],
            [
                'category' => 'sessions_progress', 'sort_order' => 3,
                'question' => 'Wat als ik emotioneel word tijdens een sessie?',
                'answer' => '<p>Emoties zijn een normaal onderdeel van therapie. Veel mensen dragen moeilijke gevoelens al lange tijd alleen. In therapie is er juist ruimte voor wat er op dat moment aanwezig is.</p>',
            ],
            [
                'category' => 'sessions_progress', 'sort_order' => 4,
                'question' => 'Hoe snel kan ik resultaat verwachten?',
                'answer' => '<p>Dat verschilt per persoon en hulpvraag. Sommige cliënten merken binnen enkele sessies al duidelijke veranderingen, vooral bij traumagerichte behandelingen zoals EMDR of Exposuretherapie. Meer complexe problematiek vraagt vaak meer tijd.</p>',
            ],
        ];
    }

    /**
     * Dutch translations for SEO settings.
     */
    private function getSeoTranslations(): array
    {
        return [
            'home' => [
                'meta_title' => 'Lysander Verschuur — Psycholoog & Traumatherapeut',
                'meta_description' => 'Lysander Verschuur is psycholoog en traumatherapeut en biedt online therapie in het Nederlands en Engels. EMDR, CGT en traumagerichte therapie.',
                'og_title' => 'Lysander Verschuur — Psycholoog & Traumatherapeut',
                'og_description' => 'Een veilige, betrokken omgeving om trauma, angst en de uitdagingen waar je tegenaan loopt te verwerken. Online sessies.',
            ],
            'about' => [
                'meta_title' => 'Over Lysander Verschuur — Psycholoog & Traumatherapeut',
                'meta_description' => 'Lees meer over de achtergrond, opleiding en aanpak van Lysander Verschuur als psycholoog en traumatherapeut.',
                'og_title' => 'Over Lysander Verschuur',
                'og_description' => 'Psycholoog en traumatherapeut, werkzaam met volwassenen in het Nederlands en Engels.',
            ],
            'fees-process' => [
                'meta_title' => 'Tarieven & Traject — Therapie met Lysander Verschuur',
                'meta_description' => 'Transparante informatie over sessietarieven, wat te verwachten van het therapieproces en hoe te beginnen.',
                'og_title' => 'Tarieven & Traject — Lysander Verschuur',
                'og_description' => 'Informatie over therapie tarieven, het intakeproces en hoe samenwerken eruitziet.',
            ],
            'booking' => [
                'meta_title' => 'Plan een Kennismakingsgesprek — Lysander Verschuur',
                'meta_description' => 'Plan een gratis 30-minuten kennismakingsgesprek met Lysander Verschuur om te onderzoeken of therapie de juiste stap is.',
                'og_title' => 'Plan een Gratis Kennismakingsgesprek',
                'og_description' => 'Plan een vrijblijvend gesprek met Lysander Verschuur — psycholoog en traumatherapeut.',
            ],
            'faq' => [
                'meta_title' => 'FAQ — Veelgestelde Vragen over Therapie met Lysander',
                'meta_description' => 'Antwoorden op veelgestelde vragen over starten met therapie, wat te verwachten, sessievormen en meer.',
                'og_title' => 'Veelgestelde Vragen',
                'og_description' => 'Alles wat je moet weten over starten met therapie bij Lysander Verschuur.',
            ],
            'trauma-approach' => [
                'meta_title' => 'Traumatherapie Aanpak — EMDR & Traumagerichte Therapie',
                'meta_description' => 'Lysander Verschuur gebruikt wetenschappelijk onderbouwde traumatherapie benaderingen waaronder EMDR en traumagerichte CGT.',
                'og_title' => 'Traumatherapie Aanpak',
                'og_description' => 'Lees meer over de traumatherapie methoden die Lysander Verschuur gebruikt, waaronder EMDR en CGT.',
            ],
        ];
    }
}

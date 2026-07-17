<?php

use App\Models\PageSection;
use App\Models\SeoSetting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Non-destructive: seeds CMS content + SEO for the existing Privacy and Terms
 * pages so they become editable from the admin panel. All inserts are guarded
 * by existence checks, so this migration is idempotent and never overwrites
 * content an admin may have already edited.
 */
return new class extends Migration
{
    public function up(): void
    {
        $privacyBodyEn = <<<'HTML'
<h2>1. Who is responsible for your data</h2>
<p>Lysander Verschuur, MSc. (“we”, “us” or “the practice”) is the data controller responsible for the personal data processed through this website. For any privacy-related questions, you can reach us at <a href="mailto:contact@therapistlysander.com">contact@therapistlysander.com</a>.</p>

<h2>2. What data we collect</h2>
<p>We only collect the information you choose to provide, namely:</p>
<ul>
  <li><strong>Contact form:</strong> your name, email address and the content of your message.</li>
  <li><strong>Booking request:</strong> your name, email address, chosen session type, preferred date and time, timezone and preferred language.</li>
  <li><strong>Pre-intake questionnaire:</strong> information about your reasons for seeking therapy and your background. This may include health-related information, which the GDPR treats as a special category of data and which we handle with additional care.</li>
  <li><strong>Technical data:</strong> limited, functional data needed to keep the website working securely (see the cookies section).</li>
</ul>

<h2>3. Why we process your data (legal basis)</h2>
<p>We process your data to respond to your message, to arrange appointments, and to provide careful and appropriate care. The legal bases are your consent, the performance of an agreement, and our legitimate interest in responding to your request. For health-related data, we rely on your explicit consent and on the basis of providing healthcare.</p>

<h2>4. Cookies</h2>
<p>This website uses <strong>functional (strictly necessary) cookies only</strong>: a session cookie and a security (CSRF) cookie that are required for the contact and booking forms to work safely and to remember your language preference.</p>
<p>We currently place <strong>no analytics, tracking or marketing cookies</strong>, and no third-party services such as Google Analytics are active. Strictly necessary cookies do not require a consent banner under the law. If any tracking is added in the future, this statement will be updated and consent will be requested.</p>

<h2>5. How long we keep your data</h2>
<p>We do not keep your data longer than necessary for the purposes for which it was collected. Healthcare records may be subject to a statutory retention period. <em>[Insert your specific retention periods here, in consultation with a legal professional.]</em></p>

<h2>6. Who we share data with</h2>
<p>We never sell your data. We only share data with service providers that help us operate the website and practice, such as our hosting provider, email service, and calendar service (Google Calendar) for scheduling appointments. These parties process data solely on our instructions.</p>

<h2>7. Your rights</h2>
<p>You have the right to access, correct, delete or transfer your data, and to object to or restrict its processing. To exercise these rights, contact us at <a href="mailto:contact@therapistlysander.com">contact@therapistlysander.com</a>. You also have the right to lodge a complaint with the Dutch Data Protection Authority (Autoriteit Persoonsgegevens).</p>

<h2>8. Security</h2>
<p>We take appropriate technical and organisational measures to protect your data against loss or unauthorised access, including a secure (encrypted) connection.</p>

<h2>9. Changes to this statement</h2>
<p>We may update this privacy policy from time to time. The most current version is always available on this page, with the date of the latest change shown at the top.</p>

<h2>10. Contact</h2>
<p>If you have any questions about this privacy policy or about how your data is processed, you are welcome to contact us at <a href="mailto:contact@therapistlysander.com">contact@therapistlysander.com</a>.</p>
HTML;

        $privacyBodyNl = <<<'HTML'
<h2>1. Wie is verantwoordelijk voor je gegevens</h2>
<p>Lysander Verschuur, MSc. (hierna “wij”, “ons” of “de praktijk”) is de verwerkingsverantwoordelijke voor de persoonsgegevens die via deze website worden verwerkt. Voor vragen over privacy kun je contact opnemen via <a href="mailto:contact@therapistlysander.com">contact@therapistlysander.com</a>.</p>

<h2>2. Welke gegevens we verzamelen</h2>
<p>Wij verzamelen alleen de gegevens die je zelf aan ons verstrekt, namelijk:</p>
<ul>
  <li><strong>Contactformulier:</strong> naam, e-mailadres en de inhoud van je bericht.</li>
  <li><strong>Aanvraag voor een afspraak:</strong> naam, e-mailadres, gekozen type sessie, gewenste datum en tijd, tijdzone en voorkeurstaal.</li>
  <li><strong>Vragenlijst vóór de intake:</strong> informatie over je hulpvraag en achtergrond. Dit kan gegevens over je gezondheid bevatten, die volgens de AVG als bijzondere persoonsgegevens worden beschouwd en met extra zorg worden behandeld.</li>
  <li><strong>Technische gegevens:</strong> beperkte, functionele gegevens die nodig zijn om de website veilig te laten werken (zie het onderdeel over cookies).</li>
</ul>

<h2>3. Waarom we je gegevens verwerken (grondslag)</h2>
<p>Wij verwerken je gegevens om te reageren op je bericht, om een afspraak in te plannen en om zorgvuldige zorg te kunnen bieden. De juridische grondslagen zijn je toestemming, de uitvoering van een overeenkomst en ons gerechtvaardigd belang om je aanvraag te beantwoorden. Voor gezondheidsgegevens baseren wij ons op jouw uitdrukkelijke toestemming en op de grondslag voor het verlenen van zorg.</p>

<h2>4. Cookies</h2>
<p>Deze website gebruikt uitsluitend <strong>functionele (strikt noodzakelijke) cookies</strong>: een sessiecookie en een beveiligingscookie (CSRF) die nodig zijn om de contact- en aanvraagformulieren veilig te laten werken en om je taalkeuze te onthouden.</p>
<p>Er worden op dit moment <strong>geen analytische, tracking- of marketingcookies</strong> geplaatst en er zijn geen diensten van derden zoals Google Analytics actief. Voor uitsluitend functionele cookies is volgens de wet geen toestemmingsbanner vereist. Mocht er in de toekomst wel tracking worden toegevoegd, dan wordt deze verklaring bijgewerkt en zal er om toestemming worden gevraagd.</p>

<h2>5. Hoe lang we je gegevens bewaren</h2>
<p>Wij bewaren je gegevens niet langer dan noodzakelijk voor de doeleinden waarvoor ze zijn verzameld. Voor zorgdossiers kan een wettelijke bewaartermijn gelden. <em>[Vul hier de concrete bewaartermijnen in, in overleg met een juridisch professional.]</em></p>

<h2>6. Met wie we gegevens delen</h2>
<p>Wij verkopen je gegevens nooit. We delen gegevens uitsluitend met dienstverleners die ons helpen de website en praktijk te laten functioneren, zoals onze hostingprovider, e-maildienst en agendadienst (Google Calendar) voor het inplannen van afspraken. Deze partijen verwerken gegevens uitsluitend in onze opdracht.</p>

<h2>7. Je rechten</h2>
<p>Je hebt het recht om je gegevens in te zien, te corrigeren, te verwijderen of over te dragen, en om bezwaar te maken tegen of de verwerking te beperken. Neem hiervoor contact op via <a href="mailto:contact@therapistlysander.com">contact@therapistlysander.com</a>. Je hebt ook het recht om een klacht in te dienen bij de Autoriteit Persoonsgegevens.</p>

<h2>8. Beveiliging</h2>
<p>Wij nemen passende technische en organisatorische maatregelen om je gegevens te beschermen tegen verlies of ongeoorloofde toegang, waaronder een beveiligde (versleutelde) verbinding.</p>

<h2>9. Wijzigingen in deze verklaring</h2>
<p>Wij kunnen deze privacyverklaring van tijd tot tijd aanpassen. De meest actuele versie staat altijd op deze pagina, met de datum van laatste wijziging bovenaan.</p>

<h2>10. Contact</h2>
<p>Heb je vragen over deze privacyverklaring of over de verwerking van je gegevens? Neem gerust contact op via <a href="mailto:contact@therapistlysander.com">contact@therapistlysander.com</a>.</p>
HTML;

        $termsBodyEn = <<<'HTML'
<h2>1. About these terms</h2>
<p>These terms and conditions apply to the use of the website of Lysander Verschuur, MSc. By using the website, you agree to these terms, so please read them carefully.</p>

<h2>2. Nature of the services</h2>
<p>Lysander Verschuur provides psychological and trauma-focused therapy. The website is intended to provide information and to submit a request for an appointment or introductory call. A request submitted through the website is a <strong>request</strong> and not yet a confirmed appointment; an appointment is only made after it has been explicitly confirmed.</p>

<h2>3. Not an emergency service</h2>
<p>This website and the practice are not intended for crisis or emergency situations. If you or someone else is in immediate danger, call your local emergency number right away (in the Netherlands, <strong>112</strong>). For conversations about suicidal thoughts in the Netherlands, you can call <strong>113 Suicide Prevention</strong> at 0800-0113 (free, 24/7).</p>

<h2>4. Appointments, fees and cancellation</h2>
<p>Current fees and the way of working can be found on the fees page. A cancellation policy may apply to cancelling or rescheduling appointments. <em>[Insert your specific cancellation and payment policy here.]</em></p>

<h2>5. Intellectual property</h2>
<p>All content on this website, including text, images, logos and design, is owned by Lysander Verschuur or by rightful licensors and is protected. You may not copy or reuse this content without permission.</p>

<h2>6. Disclaimer</h2>
<p>The information on this website is general in nature and does not constitute individual medical, psychological or legal advice, nor is it a substitute for a professional consultation. Using the website does not create a treatment agreement or therapeutic relationship.</p>

<h2>7. Links to third-party websites</h2>
<p>This website may contain links to third-party websites. We are not responsible for the content, privacy practices, or conduct of those websites.</p>

<h2>8. Governing law</h2>
<p>These terms are governed by the laws of the Netherlands. Any disputes will be submitted to the competent court in the Netherlands.</p>

<h2>9. Changes</h2>
<p>We may update these terms from time to time. The most current version is always available on this page, with the date of the latest change shown at the top.</p>

<h2>10. Contact</h2>
<p>If you have any questions about these terms, you are welcome to contact us at <a href="mailto:contact@therapistlysander.com">contact@therapistlysander.com</a>.</p>
HTML;

        $termsBodyNl = <<<'HTML'
<h2>1. Over deze voorwaarden</h2>
<p>Deze algemene voorwaarden zijn van toepassing op het gebruik van de website van Lysander Verschuur, MSc. Door de website te gebruiken, ga je akkoord met deze voorwaarden. Lees ze daarom aandachtig door.</p>

<h2>2. Aard van de dienstverlening</h2>
<p>Lysander Verschuur biedt psychologische en op trauma gerichte therapie. De website is bedoeld om informatie te geven en om een aanvraag voor een afspraak of een kennismakingsgesprek in te dienen. Een via de website ingediende aanvraag is een <strong>verzoek</strong> en nog geen bevestigde afspraak; een afspraak komt pas tot stand na uitdrukkelijke bevestiging.</p>

<h2>3. Geen nooddienst</h2>
<p>Deze website en de praktijk zijn niet bedoeld voor crisis- of noodsituaties. Bevind je jezelf of iemand anders in acuut gevaar, bel dan direct <strong>112</strong>. Voor gesprekken over suïcidale gedachten kun je bellen met <strong>113 Zelfmoordpreventie</strong> via 0800-0113 (gratis, 24/7).</p>

<h2>4. Afspraken, tarieven en annulering</h2>
<p>Actuele tarieven en de werkwijze vind je op de pagina met tarieven. Voor het annuleren of verzetten van afspraken kan een annuleringsbeleid gelden. <em>[Vul hier het concrete annulerings- en betalingsbeleid in.]</em></p>

<h2>5. Intellectueel eigendom</h2>
<p>Alle inhoud op deze website, waaronder teksten, afbeeldingen, logo’s en vormgeving, is eigendom van Lysander Verschuur of van de rechthebbende licentiegevers en is beschermd. Je mag deze inhoud niet zonder toestemming kopiëren of hergebruiken.</p>

<h2>6. Disclaimer</h2>
<p>De informatie op deze website is algemeen van aard en vormt geen individueel medisch, psychologisch of juridisch advies, en is geen vervanging van een professioneel consult. Door de website te gebruiken ontstaat geen behandelovereenkomst of therapeutische relatie.</p>

<h2>7. Links naar websites van derden</h2>
<p>Deze website kan links naar websites van derden bevatten. Wij zijn niet verantwoordelijk voor de inhoud, het privacybeleid of de werkwijze van die websites.</p>

<h2>8. Toepasselijk recht</h2>
<p>Op deze voorwaarden is Nederlands recht van toepassing. Geschillen worden voorgelegd aan de bevoegde rechter in Nederland.</p>

<h2>9. Wijzigingen</h2>
<p>Wij kunnen deze voorwaarden van tijd tot tijd aanpassen. De meest actuele versie staat altijd op deze pagina, met de datum van laatste wijziging bovenaan.</p>

<h2>10. Contact</h2>
<p>Heb je vragen over deze voorwaarden? Neem gerust contact op via <a href="mailto:contact@therapistlysander.com">contact@therapistlysander.com</a>.</p>
HTML;

        // ── Privacy page content ──
        if (Schema::hasTable('page_sections') && ! PageSection::where('page', 'privacy')->exists()) {
            $s = new PageSection();
            $s->page = 'privacy';
            $s->section_key = 'privacy_main';
            $s->label = 'Privacy Content';
            $s->is_active = true;
            $s->sort_order = 1;
            $s->setTranslation('content', 'en', [
                'title'    => 'Privacy Policy',
                'subtitle' => 'This statement explains what personal data is collected through this website and how it is used and protected.',
                'body'     => $privacyBodyEn,
            ]);
            $s->setTranslation('content', 'nl', [
                'title'    => 'Privacybeleid',
                'subtitle' => 'Deze verklaring legt uit welke persoonsgegevens worden verzameld via deze website en hoe deze worden gebruikt en beschermd.',
                'body'     => $privacyBodyNl,
            ]);
            $s->save();
        }

        // ── Terms page content ──
        if (Schema::hasTable('page_sections') && ! PageSection::where('page', 'terms')->exists()) {
            $s = new PageSection();
            $s->page = 'terms';
            $s->section_key = 'terms_main';
            $s->label = 'Terms Content';
            $s->is_active = true;
            $s->sort_order = 1;
            $s->setTranslation('content', 'en', [
                'title'    => 'Terms & Conditions',
                'subtitle' => 'These terms apply to the use of this website and the information and services offered on it.',
                'body'     => $termsBodyEn,
            ]);
            $s->setTranslation('content', 'nl', [
                'title'    => 'Algemene voorwaarden',
                'subtitle' => 'Deze voorwaarden zijn van toepassing op het gebruik van deze website en de daarop aangeboden informatie en diensten.',
                'body'     => $termsBodyNl,
            ]);
            $s->save();
        }

        // ── Privacy SEO ──
        if (Schema::hasTable('seo_settings') && ! SeoSetting::where('page_key', 'privacy')->exists()) {
            $seo = new SeoSetting();
            $seo->page_key = 'privacy';
            $seo->setTranslation('meta_title', 'en', 'Privacy Policy | Therapist Lysander');
            $seo->setTranslation('meta_title', 'nl', 'Privacybeleid | Therapist Lysander');
            $seo->setTranslation('meta_description', 'en', 'How Therapist Lysander collects, uses and protects your personal data, in line with the GDPR.');
            $seo->setTranslation('meta_description', 'nl', 'Hoe Therapist Lysander je persoonsgegevens verzamelt, gebruikt en beschermt, in lijn met de AVG.');
            $seo->setTranslation('og_title', 'en', 'Privacy Policy | Therapist Lysander');
            $seo->setTranslation('og_title', 'nl', 'Privacybeleid | Therapist Lysander');
            $seo->setTranslation('og_description', 'en', 'How Therapist Lysander collects, uses and protects your personal data, in line with the GDPR.');
            $seo->setTranslation('og_description', 'nl', 'Hoe Therapist Lysander je persoonsgegevens verzamelt, gebruikt en beschermt, in lijn met de AVG.');
            $seo->save();
        }

        // ── Terms SEO ──
        if (Schema::hasTable('seo_settings') && ! SeoSetting::where('page_key', 'terms')->exists()) {
            $seo = new SeoSetting();
            $seo->page_key = 'terms';
            $seo->setTranslation('meta_title', 'en', 'Terms & Conditions | Therapist Lysander');
            $seo->setTranslation('meta_title', 'nl', 'Algemene voorwaarden | Therapist Lysander');
            $seo->setTranslation('meta_description', 'en', 'The terms that apply to the use of this website and to booking sessions with Therapist Lysander.');
            $seo->setTranslation('meta_description', 'nl', 'De voorwaarden die gelden voor het gebruik van deze website en voor het boeken van sessies bij Therapist Lysander.');
            $seo->setTranslation('og_title', 'en', 'Terms & Conditions | Therapist Lysander');
            $seo->setTranslation('og_title', 'nl', 'Algemene voorwaarden | Therapist Lysander');
            $seo->setTranslation('og_description', 'en', 'The terms that apply to the use of this website and to booking sessions with Therapist Lysander.');
            $seo->setTranslation('og_description', 'nl', 'De voorwaarden die gelden voor het gebruik van deze website en voor het boeken van sessies bij Therapist Lysander.');
            $seo->save();
        }

        // ── Keep any DB translation override in sync with the footer label change ──
        if (Schema::hasTable('ui_translations')) {
            DB::table('ui_translations')
                ->where('locale', 'nl')->where('group', 'footer')->where('key', 'privacy')
                ->where('value', 'Privacyverklaring')
                ->update(['value' => 'Privacybeleid']);
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('page_sections')) {
            PageSection::whereIn('page', ['privacy', 'terms'])->whereIn('section_key', ['privacy_main', 'terms_main'])->delete();
        }

        if (Schema::hasTable('seo_settings')) {
            SeoSetting::whereIn('page_key', ['privacy', 'terms'])->delete();
        }

        if (Schema::hasTable('ui_translations')) {
            DB::table('ui_translations')
                ->where('locale', 'nl')->where('group', 'footer')->where('key', 'privacy')
                ->where('value', 'Privacybeleid')
                ->update(['value' => 'Privacyverklaring']);
        }
    }
};

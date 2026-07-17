@extends('layouts.app')

@php $isNl = app()->getLocale() === 'nl'; @endphp

@section('title', $isNl ? 'Privacyverklaring' : 'Privacy Policy')
@section('meta_description', $isNl
  ? 'Privacyverklaring van Lysander Verschuur — hoe persoonsgegevens worden verwerkt, welke cookies worden gebruikt en welke rechten je hebt.'
  : 'Privacy Policy for Lysander Verschuur — how personal data is processed, which cookies are used, and your rights.')

@section('page_styles')
<style>
  .legal { padding: var(--space-10) 0 var(--space-16); max-width: 44rem; }
  .legal__notice {
    background: var(--color-teal-light);
    border: 1px solid var(--color-teal);
    border-left-width: 4px;
    border-radius: var(--radius-md);
    padding: var(--space-4) var(--space-5);
    margin-bottom: var(--space-8);
    font-size: var(--size-sm);
    color: var(--color-text);
    line-height: 1.7;
  }
  .legal__notice strong { color: var(--color-teal); }
  .legal__updated { font-size: var(--size-sm); color: var(--color-text-light); margin-bottom: var(--space-8); }
  .legal h2 {
    font-family: var(--font-heading);
    font-size: var(--size-xl);
    color: var(--color-text);
    margin: var(--space-8) 0 var(--space-3);
  }
  .legal h2:first-of-type { margin-top: 0; }
  .legal p { font-size: var(--size-base); color: var(--color-text-muted); line-height: 1.8; margin-bottom: var(--space-4); }
  .legal ul { list-style: disc; padding-left: var(--space-6); margin-bottom: var(--space-4); }
  .legal li { font-size: var(--size-base); color: var(--color-text-muted); line-height: 1.8; padding: var(--space-1) 0; }
  .legal a { color: var(--color-teal); text-decoration: underline; }
</style>
@endsection

@section('content')

<div class="page-hero">
  <div class="container--narrow">
    <span class="page-hero__eyebrow">{{ $isNl ? 'Juridisch' : 'Legal' }}</span>
    <h1 class="page-hero__title">{{ $isNl ? 'Privacyverklaring' : 'Privacy Policy' }}</h1>
    <div class="page-hero__text">
      <p>{{ $isNl
        ? 'Deze verklaring legt uit welke persoonsgegevens worden verzameld via deze website en hoe deze worden gebruikt en beschermd.'
        : 'This statement explains what personal data is collected through this website and how it is used and protected.' }}</p>
    </div>
  </div>
</div>

<section class="section section--white">
  <div class="container--narrow legal">

    <div class="legal__notice">
      @if($isNl)
        <strong>Let op:</strong> Dit is een concepttekst als startpunt. Laat deze verklaring vóór de definitieve publicatie controleren door een juridisch professional, zodat deze volledig aansluit op jouw praktijk en voldoet aan de AVG.
      @else
        <strong>Please note:</strong> This is a starter draft intended as a starting point. Have it reviewed by a legal professional before you go live, so it accurately reflects your practice and fully complies with the GDPR.
      @endif
    </div>

    <p class="legal__updated">{{ $isNl ? 'Laatst bijgewerkt' : 'Last updated' }}: {{ now()->translatedFormat($isNl ? 'j F Y' : 'F j, Y') }}</p>

    @if($isNl)
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
    @else
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
    @endif

  </div>
</section>

@endsection

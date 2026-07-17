@extends('layouts.app')

@php $isNl = app()->getLocale() === 'nl'; @endphp

@section('title', $isNl ? 'Algemene voorwaarden' : 'Terms & Conditions')
@section('meta_description', $isNl
  ? 'Algemene voorwaarden voor het gebruik van de website van Lysander Verschuur en de aangeboden diensten.'
  : 'Terms & Conditions for the use of the Lysander Verschuur website and the services offered.')

@section('page_styles')
<style>
  .legal { padding: var(--space-10) var(--space-6) var(--space-16); max-width: 44rem; }
  .legal__notice {
    background: var(--color-teal-light);
    border: 1px solid var(--color-teal);
    border-left-width: 4px;
    border-radius: var(--radius-md);
    padding: var(--space-5) var(--space-6);
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
    <h1 class="page-hero__title">{{ $isNl ? 'Algemene voorwaarden' : 'Terms & Conditions' }}</h1>
    <div class="page-hero__text">
      <p>{{ $isNl
        ? 'Deze voorwaarden zijn van toepassing op het gebruik van deze website en de daarop aangeboden informatie en diensten.'
        : 'These terms apply to the use of this website and the information and services offered on it.' }}</p>
    </div>
  </div>
</div>

<section class="section section--white">
  <div class="container--narrow legal">

    <div class="legal__notice">
      @if($isNl)
        <strong>Let op:</strong> Dit is een concepttekst als startpunt. Laat deze voorwaarden vóór de definitieve publicatie controleren door een juridisch professional, zodat ze aansluiten op jouw praktijk en de geldende regelgeving.
      @else
        <strong>Please note:</strong> This is a starter draft intended as a starting point. Have these terms reviewed by a legal professional before you go live, so they fit your practice and applicable regulations.
      @endif
    </div>

    <p class="legal__updated">{{ $isNl ? 'Laatst bijgewerkt' : 'Last updated' }}: {{ now()->translatedFormat($isNl ? 'j F Y' : 'F j, Y') }}</p>

    @if($isNl)
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
    @else
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
    @endif

  </div>
</section>

@endsection
